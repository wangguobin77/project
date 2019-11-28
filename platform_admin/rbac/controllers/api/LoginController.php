<?php
/**
 * 用户登陆 移动端
 */
namespace backend\controllers\api;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/18
 * Time: 上午10:57
 */
use backend\models\AdminUserBranch;
use backend\models\AdminUser;
use yii;
use yii\web\Controller;
use backend\library\oauth\accesstoken;
class LoginController extends Controller
{

    public function actionAuthorize()
    {

        if(Yii::$app->request->isPost){//验证是post提交 并且确定授权
            try{
                $username = filterData(Yii::$app->request->post('username'),'string',11);

                $pwd = filterData(Yii::$app->request->post('pwd'),'string',20);

                //验证提交的用户信息是否存在
                $userInfo = (new AdminUser)->verifyUserinfo($username,$pwd);//验证用户是否存在 不存在抛出错误

                $token = (new accesstoken)->createAccessToken($userInfo['uid']);

                $this->setUserInfoFromSession($token,$userInfo);//存储session

                $this->setCookieToken($token['access_token']);//将token 存储cookie

                $this->returnUserInfo($userInfo,$token);//接口返回数据
            }catch (\Exception $e){
                show_json(100000,'Log in interface error');
            }


        }else{

            // $view = $this->getViewTypeString($client_id);//获取使用的模版

            return $this->render('login');
        }
    }

    /**
     * session 存储最基本的数据信息
     * @param $uid
     * @param $userInfo
     */
    protected function setUserInfoFromSession($uid,$userInfo)
    {
        $data = [
            'uid' => $uid,
            'username' => $userInfo['username'],
            'real_name' => $userInfo['real_name']
        ];

        $_SESSION['info'] = $data;
    }


    /**
     * 设置全局cookie 维持客户端用户登陆状态
     */
    public function setCookieToken($access_token)
    {
        setcookie(Yii::$app->params['global_cookie_token'],$access_token,time()+3600*24*30,'/');
    }

    /**
     * 返回用户相关的数据信息
     * @param $userInfo
     * @param $token_info
     */
    protected function returnUserInfo($userInfo,$token_info)
    {
        $data = [
            'username' => $userInfo['username'],//登陆的用户名 默认是手机号码
            'real_name' => $userInfo['real_name'],//用户真实姓名
            'status' => $userInfo['status'],//用户在职状态，0 代表员工离职，账号被禁用
            'first_login_time' => $userInfo['status'],//用户第一次登陆的时间
            'work_number' => $userInfo['work_number'],//员工工号 唯一
            'position_id' => $userInfo['position_id'],//职位id
            'branch_id' => (new AdminUserBranch)->getBranchIdList(),//所属部门id 一个用户可以从属多个部门与组织
            'token_info' =>  $token_info,//有关token 的信息 包括token有效期
        ];


        show_json(0,'success',$data);//返回数据
    }

}