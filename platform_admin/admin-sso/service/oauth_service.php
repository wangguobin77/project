<?php
namespace app\service;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/28
 * Time: 上午10:21
 */
use app\api_interface\oauth\oauth_interface;
use app\models\AdminUserProfile;
use OAuth2\Storage\Redis;
use yii;
use OAuth2\Server;
use app\models\Oauth2;
use app\models\AdminUser;
use app\models\LoginForm;

use common\util\IFilter;
use common\util\IReq;
class oauth_service implements oauth_interface
{

    public static $_instance;

    public $storage_redis;//redis 句柄

    public $server_redis;

    protected static $appInfo_key = 'oauth_clients:';//appinfo 存储redis的键值

    public function __construct()
    {
        _initSess();//开启session

        $this->storage_redis = new Redis(Yii::$app->redis);
        $this->server_redis = new Server($this->storage_redis);
    }

    //初始化该类
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();//当前配置文件
        }
        return self::$_instance;
    }

    /**
     * 获取openid
     * @param 用户登陆系统办法的唯一凭证 $access_token
     * @return bool|string
     */
    public function getOpenid($access_token)
    {
        $act = $this->storage_redis->getAccessToken($access_token);

        if ($act) {
            $Oauth2 = new Oauth2();
            //目前直接在db获取 后面会增加缓存

            $OauthUserinfo = $Oauth2->getUserinfoToRedis($act['user_id']);

            if($OauthUserinfo){
                $openid = json_decode($OauthUserinfo,true)['openid'];
            }else{
                $openid = $Oauth2->getOpenidFromDb_userid($act['user_id'],$act['client_id']);//mysql_list_dbs resource mysql_list_dbs openid
            }

            if ($openid) {
                return $openid;
            }
        }

        return false;
    }

    /**
     * 检测用户是否在登陆状态 access_token是否失效
     * @param $access_token
     * @param $client_id
     * @return bool
     */
    public function isCheckLogin($access_token,$client_id){

        $act = json_decode($this->storage_redis->getAccessToken($access_token),true);

        if($act && $act['client_id'] == $client_id){
            return true;
        }

        return false;
    }

    /**
     * 根据appid 获取对应的模版
     * @param $appid
     * @return mixed
     */
    public function getViewTypeString($appid)
    {
        $data = [];
        if(!$appid){
            show_json(100006,Yii::$app->params['errorCode'][100006]);
        }

        $data['APP_ID'] = $appid;
        $appinfo = $this->initThirdLoginApps($data);

        return isset($appinfo['viewType'])?Yii::$app->params['viewType'][$appinfo['viewType']]:Yii::$app->params['viewType'][1];//默认使用官方

    }

    /**
     * 根据用户的openid 获取用户信息
     * @param 用户登陆的唯一凭证 $access_token
     * @param 用户的openid $openid
     * @return bool|mixed
     */
    //oauth sdk 集成登陆对外所有获取的用户信息不一致
   /* public function getUserInfo($access_token,$openid){

        $act = $this->storage_redis->getAccessToken($access_token);

        if(!$act){
            show_json(100000,'access_token invalid');//access_token 无效
        }

        //获取redis用户缓存信息
        $userinfo = $this->getUserInfoFromRedis($act['user_id']);

        if(!$userinfo){
             return false;
        }

        return $userinfo;
    }*/

    public function getUserInfo($access_token)
    {
        $act = $this->storage_redis->getAccessToken($access_token);

        if(!$act){
            show_json(100000,'access_token invalid');//access_token 无效
        }

        $key = 'pst_form_userid_userinfo:'.$act['user_id'];//键

        $redis = Yii::$app->redis;

        return $redis->get($key);//缓存redis
    }

    /**
     * 根据用户名识别是邮箱还是手机登陆
     * @param $username
     * @return int
     */
    protected function getRegtypeInt($username)
    {
        $pattern = Yii::$app->params['preg_match']['email'];//邮箱
        $pattern1 = Yii::$app->params['preg_match']['mobile'];//手机

        if(preg_match( $pattern, $username )){

            return 2;

        }elseif(preg_match( $pattern1, $username )){

            return 3;

        }else{
            //默认用户名
            return 1;
        }
    }


    /**
     * 验证用户是否正确
     * @param $username 目前账户名只能是手机登陆
     * @param $password &response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>
     */
    public function verifyUserinfo()
    {
        $postInfo = IFilter::act(IReq::get('LoginForm','post'));//登陆用户名 后台登陆统一是手机登陆

        //验证提交的用户信息是否存在
        $client_id = IFilter::act(IReq::get('client_id'),'string',32);//client_id

        $pattern1 = Yii::$app->params['preg_match']['mobile'];//手机

        //todo 需要加登陆次数过多的 限制ip操作 防止暴力破解
        if (isset($postInfo['username']) && preg_match($pattern1, $postInfo['username'])) {
            //成功处理
            //判断用户是否存在
            $model = new LoginForm();

            if ($model->load(Yii::$app->request->post()) && $model->login()){
                $u1 = [
                    'uid' => Yii::$app->user->identity->id,
                    'username' => Yii::$app->user->identity->username,//登陆用户名
                    'client_id' => $client_id,
                    'real_name' => Yii::$app->user->identity->real_name,//真实名称
                ];

                if($this->setUserinfoFromSession( Yii::$app->user->identity->id, $u1)){
                    return true;
                }//save session  basic information
            }

        }
        //错误时回跳到登陆也
        Yii::$app->getSession()->setFlash('error-login','Username or password is not legal'); //错误提示信息
        $params = [
            'response_type'=>'code',
            'client_id'=>Yii::$app->request->get('client_id', ''),
            'state' => Yii::$app->request->get('state', ''),
            'redirect_uri' => Yii::$app->request->get('redirect_uri', ''),
            'language' => Yii::$app->language
        ];//参数

        $url = combineURL(Yii::$app->params['oauth_login_url'],$params);
        header("Location:$url");
        exit;
    }

    /**
     * oauth退出操作
     * @param $access_token
     * @param 回跳的地址 $callback
     */
    public function loginOut($access_token,$callback)
    {
        $act = $this->storage_redis->getAccessToken($access_token);

        if(!$act){
            show_json(0,'success');//如果不存在access_token时候 默认已退出
        }

        var_log(['access_token'=>$access_token,'callback'=>$callback],'logout');

        //删除redis accesstoken
        if($this->storage_redis->unsetAccessToken($access_token)){
            if($callback){
                header("Location:$callback");
                exit;
            }
            show_json(0,'success');
        }
        exit;
    }

    /**
     * 根据用户user_id 获取redis用户信息
     * @param $user_id
     * @return mixed
     */
    protected function getUserInfoFromRedis($user_id)
    {
        //获取redis用户缓存信息
        $redis = Yii::$app->redis;

        $u_key = 'oauth_login_userinfo:'.$user_id;

        return json_decode($redis->get($u_key),true);//openid get userinfo

    }

    /**
     * 供 sdk 使用 必须判断 appkey 与 appsecret 合法性
     * 根据appid 与 appsecret 判断是否合法 合法返回组合信息
     * @param $config
     * @throws \Exception array('APP_KEY'=>'xx','APP_SECRET'=>'vfvf')
     */
    protected function initThirdLoginApps($config)
    {

        $APP_ID    = $config['APP_ID'];

        //先判断redis 是否存在数据 不存在 在判断db是否存在 都不存在 就是无效的key
        $redis = Yii::$app->redis;

        $appinfo = json_decode($redis->get(self::$appInfo_key.$APP_ID),true);

        if(!$appinfo){

            /*//todo db redis不存在判断db数据是否存在合法数据 都不合法则不能完成授权
            $Oauth2 = new Oauth2;

            $appinfo = $Oauth2->getAppInfoConfig($APP_ID);

            if(!$appinfo){
                var_log([$config,'ip'=>getIp()],'app-id-secret-config');//信息错误 记录带的参数信息和ip地址 防止数据强刷

                show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
            }*/

            show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法

        }

        if($appinfo['grant_types'] != 'authorization_code'){
            show_json(100000,'at present client_id not code the authorization model');//当前client id 不是code授权模式
        }
        if($APP_ID != $appinfo['client_id']){
            show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
        }else{
            return $appinfo;
        }
    }



    /**
     * 设置redis缓存用户信息
     * 合并用户基础信息与扩展信息
     * @param $userid
     * @param $u1
     */
    protected function setUserinfoFromSession($userid,$u1)
    {

        $u2 = AdminUserProfile::find()->where(['uid'=>$userid])->asArray()->one();

        if(!$u2) return false;

        /*$_SESSION['userinfo'] = array_merge($u1,$u2);//存储session*/
        $userinfo =  array_merge($u1,$u2);

        //为了获取token时创建用户的对应关系 当前用户信息需要存储redis 便于后面处理
        //redis 存储
        $key = 'pst_form_userid_userinfo:'.$userid;//键

        $redis = Yii::$app->redis;

        $redis->set($key,json_encode($userinfo));//缓存redis

        $_SESSION['userinfo'] = $u1;//目前授权session 无需存储太多信息  只需记录 u1 信息

        return true;

    }

}