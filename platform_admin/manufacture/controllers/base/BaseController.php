<?php
// +----------------------------------------------------------------------
// | TITLE:基础类
// +----------------------------------------------------------------------

namespace app\controllers\base;

/**
 * Class BaseController
 * @package rbac\controllers
 */
use Yii;
use common\library\rpc\RpcClient;
class BaseController extends BaseCore
{

    public function beforeAction($action)
    {

        $this->isCheckLogin();//验证是否登陆过

        parent::beforeAction($action);//

        $role_list = $this->getRoleIdList($_SESSION['uid']['uid']);

        $this->menu = $this->disUnique($role_list);

        $menu_data = $this->orderData($this->menu);//重新排序 把id作为每个数组的索引 当前拥有的菜单

        if (!$this->verifyRule($menu_data,$_SESSION['uid']['uid'])) {
            //todo 没有权限处理
            // die('你没有权限');
        } else {
            $index_v = $this->getMenuId($menu_data);//判断直接输入访问的路由是否有权限

            self::$_menuCurrentId = array_merge($this->getPidArr($this->getAllMenuRule(),$index_v),[$index_v]);


            self::$rule_list = $this->getRuleList();//角色对应路由

            self::$role_list = $role_list;

            self::$menu_key = array_keys($menu_data);

        }

        return true;
    }

    protected function disUnique($role_list)
    {
        /* $condition = ['class'=>'\app\controllers\RbacController'];
         $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
         return $ApiController->disUnique($role_list);*/
        $post_data = [
            'role_list' => $role_list,
        ];

        $c_a = 'rbac/dis-unique';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return json_decode(postCurl_1($url,$post_data),true);

    }

    /**
     * 获取角色对应的路由
     * @return mixed
     */
    protected function getRuleList()
    {
        /*$condition = ['class'=>'\app\controllers\RbacController'];
        $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
        return $ApiController->getRuleList();*/

        $c_a = 'rbac/get-rule-list';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return json_decode(getCurl($url),true);
    }

    /**
     * 根据用户id 获取所有角色
     * @param $access_token
     * @return mixed
     */
    protected function getRoleIdList($uid)
    {
        /* $condition = ['class'=>'\app\controllers\RbacController'];
         $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
         return $ApiController->getRoleIdList($uid);*/

        $post_data = [
            'uid' => $uid,
        ];

        $c_a = 'rbac/get-role-id-list';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return postCurl_1($url,$post_data);

    }

    /**
     * 获取所有的菜单 每条记录的索引时自己的id
     * @return mixed
     */
    protected function getAllMenuRule()
    {
        /*$condition = ['class'=>'\app\controllers\RbacController'];
        $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
        return $ApiController->getAllMenuRule();*/


        $c_a = 'rbac/get-all-menu-rule';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return json_decode(getCurl($url),true);

    }


    /**
     * 根据用户id 获取所有角色
     * @param $access_token
     * @return mixed
     */
    protected function verifyRule($rule_list,$uid)
    {
        /* $condition = ['class'=>'\app\controllers\RbacController'];
         $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
         return $ApiController->verifyRule($rule_list,$uid,$_SERVER['HTTP_HOST'],$_SERVER['REQUEST_URI']);*/
        $post_data = [
            'uid' => $uid,
            'role_list' => json_encode($rule_list),
            'http_host' => $_SERVER['HTTP_HOST'],
            'request_uri' => $_SERVER['REQUEST_URI']
        ];

        $c_a = 'rbac/verify-rule';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return json_decode(postCurl_1($url,$post_data),true);

    }

    /**
     * 获取token  如果不存在 需要从新登陆
     * @return bool|string
     */
    protected function getAccessToken()
    {
        if(isset($_SESSION['oauth']['access_token'])) return  $_SESSION['oauth']['access_token'];//session

        if(getCookie_token()) return getCookie_token();//cookie 缓存

        return false;
    }

    /**
     * token 换取openid
     * @param $access_token
     * @return mixed
     */
    protected function getOpenid($access_token)
    {
        /*  $condition = ['class'=>'\app\controllers\ApiController'];
          $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
          $openid = $ApiController->getOpenid($access_token);

          if(!$openid){
              show_json(100000,'openid fail to get');
          }

          return $openid;*/

        $post_data = [
            'access_token' => $access_token,
        ];

        $c_a = 'api/get-openid';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return json_decode(postCurl_1($url,$post_data),true);

    }

    /**
     * 获取用户信息
     * @param $openid
     * @param $access_token
     */
    protected function getUserinfo($access_token)
    {
        /* $condition = ['class'=>'\app\controllers\ApiController'];
         $ApiController = RpcClient::getClient($condition,Yii::$app->params['yar_server_address']);
         $userInfo = $ApiController->getUserinfo($access_token);

         if(!$userInfo) show_json(100000,'User information does not exist');

         return $userInfo;*/

        $post_data = [
            'access_token' => $access_token,
        ];

        $c_a = 'api/get-userinfo';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return postCurl_1($url,$post_data);
    }

    /**
     * 验证是否登陆 登陆过跳出
     */
    public function isCheckLogin()
    {
        $access_token = $this->getAccessToken();
        if($access_token){
            //判断session 是否缓存用户具体信息

            if(isset($_SESSION['uid']['username'])) return;

            $userInfo = json_decode($this->getUserinfo($access_token),true);

            $_SESSION['uid'] = $userInfo;//用户基本数据缓存session
            return true;
        }else{
            //跳转登陆
            $this->login();
            exit;
        }
    }

    /**
     * 登陆
     * @return bool
     */
    protected function login()
    {
        $params = [
            'response_type'=>'code',
            'client_id'=>Yii::$app->params['app_id'],
            'state' => 'senseplay_admin',//默认的 可以随便写
            'redirect_uri' => Yii::$app->params['OAUTH_REDIRECT_URI'],//回掉地址
            'language' => Yii::$app->language
        ];//参数

        $url = combineURL(Yii::$app->params['oauth_login_url'],$params);
        header("Location:$url");
    }

    /**
     * 获取当前route 的id
     * @param $data
     * @return bool
     */
    private function getMenuId($data)
    {

        $url_arr = [];//url 数组
        $url_arr[0] =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $route = Yii::$app->request->getQueryParam('r');//当前路由模块 不同项目里可能访问形式不一样 所以倒是这里需要更改
        $url_arr[1] = $route;
        if(!$route){
            return 0;//当无路由时直接输出0 让他跳转首页
        }
        foreach ($data as $key=>$val){

            /* if($val['route'] === $route || in_array($route,$this->allowUrl)){*/
            if(in_array($val['route'],$url_arr) || in_array($route,$this->allowUrl)){
                return $val['id'];
            }

        }
        show_json(100000,'暂无权限');//如果返回为false 直接跳转首页

    }

    /**
     * 处理数组 把每个数字字段的id作为当前索引
     * @param $data
     */
    protected function orderData($data){
        $list = [];
        foreach ($data as $key=>$val){
            $list[$val['id']] = $val;
        }

        return $list;
    }


    /**
     * 获取当前子类上面的每一层父类id [1,3,4]
     * @param $data
     * @param int $index
     */
    protected function getPidArr($data,$index=0,$arr=[])
    {

        if(isset($data[$index]['pid']) && $data[$index]['pid'] != 0){
            array_push($arr,$data[$index]['pid']);
            return $this->getPidArr($data,$data[$index]['pid'],$arr);

        }else{

            return $arr;
        }

    }

    protected function getErrMessage($code)
    {
        return Yii::t('app', Yii::$app->params['errorCode'][$code]);
    }
}
