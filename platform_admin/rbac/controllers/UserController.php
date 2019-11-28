<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/8/15
 * Time: 下午7:37
 */

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\AdminUser;
use common\library\SmsService;
use yii\helpers\Url;
use app\models\AdminUserProfile;
class UserController extends Controller
{
    protected $userData;

    public function init()
    {
        session_to();

        $this->layout = false;


    }

    public function actionTest()
    {
        $redis=Yii::$app->redis;
    }


    /**
     * 在登陆情况下获取用户信息
     * @return mixed
     */
    protected function getUserInfo()
    {
        $url = Url::toRoute('index/index');
        if(!isset($_SESSION['uid']) || !$_SESSION['uid']['username'])  header("Location:$url");;//session
       // if(!isset($_SESSION['uid']) || !$_SESSION['uid']['username']) show_json(100000,'未登陆，无权限操作该功能');


        return $_SESSION['uid'];//返回用户的信息
    }
    /**
     * 找回密码
     * @return string
     */
    public function actionFindpwd()
    {
        return $this->render('findpwd');
    }

    /**
     * 根据手机号码 判断用户是否存在
     */
    public function actionIsCheckMobile()
    {
        $mobile = Yii::$app->request->post('mobile');

        if(checkmobile($mobile)){
            $userinfo = AdminUser::find()->where(['username'=>$mobile])->asArray()->one();

            if(!$userinfo){
                show_json(100000,'user info no have');
            }

            show_json(0,'success');
        }

        show_json(100000,'params error');

    }

    /**
     * 获取手机验证码
     */
    public function actionGetMobileCode()
    {

        $mobile = Yii::$app->request->post('mobile');

        if(checkmobile($mobile)){
            if((new SmsService)->sendAuthCodeToQueue2($mobile)){
                show_json(0,'get mobile code success');
            }
        }

        show_json(100000,'params error');

    }

    /**
     * 验证手机的验证码
     */
    public function actionReg_phone_2()
    {

        if(Yii::$app->request->post()){
            $mobile = Yii::$app->request->post('mobile');//手机号码

            $authCode = Yii::$app->request->post('authcode');//获取手机验证码

            if(checkmobile($mobile)) {//验证手机号码

                $SmsService = new SmsService;

                $SmsService->disUsermobileAuthcode($mobile,$authCode);//处理手机验证码验证

                show_json(0,'success',['mobile'=>$mobile]);
            }

        }

        show_json(100000,'params error');


    }

    /**
     * 更新用户手机号码
     */
    public function actionUphone()
    {

        $this->userData = $this->getUserInfo();
        if(Yii::$app->request->post()){
            $mobile = Yii::$app->request->post('mobile');//手机号码

            $authCode = Yii::$app->request->post('authcode');//获取手机验证码

            if(checkmobile($mobile)) {//验证手机号码

                $SmsService = new SmsService;

                $SmsService->disUsermobileAuthcode($mobile,$authCode);//处理手机验证码验证


                $AdminUser = AdminUser::find()->where(['username'=>$mobile])->one();//判断修改的号码是否已经存在

                if($AdminUser){
                    show_json(100000,'User information already exists');//用户信息已经存在
                }

                $userInfo = AdminUser::find()->where(['username'=>$_SESSION['uid']['username']])->one();

                if(!$userInfo){
                    show_json(100000,'User information does not exist');//用户信息不存在
                }

                $userInfo2 = AdminUserProfile::find()->where(['uid'=>$userInfo['id']])->one();//用户扩展信息表

                $model = AdminUser::findOne($userInfo['id']);

                $model2 = AdminUserProfile::findOne($userInfo2['id']);

                $model->username = $mobile;
                $model2->mobile = $mobile;

                if($model->save() && $model2->save()){

                    $this->unsetUSerInfo();
                    //Yii::$app->user->logout();

                    show_json(0,'success',['mobile'=>$mobile]);
                }


            }

        }

        show_json(100000,'params error');


    }

    /**
     * 根据手机号码 更新密码
     */
    public function actionDisPwd()
    {

        $mobile = Yii::$app->request->post('mobile');

        if(!checkmobile($mobile)){
            show_json(100000,'params error');
        }

        /*判断两次密码是否相同*/
        if(checkLengthParams(Yii::$app->request->post('pwd'),20,6) !== checkLengthParams(Yii::$app->request->post('rep_pwd'),20,6)){
            show_json(100025, Yii::$app->params['errorCode'][100025]);
        }

        $userinfo = AdminUser::find()->where(['username'=>$mobile])->asArray()->one();

        if(!$userinfo){
            show_json(100000,'user info nonentity');//用户信息不粗在
        }

        $model = AdminUser::findOne($userinfo['id']);

        $model->password_hash = $model->setPassword(Yii::$app->request->post('pwd'));//加密后的密码

        if($model->save()){
            show_json(0,'reset pwd success');
        }
        //如果不一致说明更新了手机账号，所以需要重置手机密码
        show_json(100000,'reset pwd error');
    }


    /**
     * @return string
     */
    public function actionRphone()
    {
        $this->userData = $this->getUserInfo();
        return $this->render('resetphone');
    }

    /**
     * @return string
     */
    public function actionFail()
    {
        return $this->render('fail');
    }

    /**
     * 重置密码
     * @return string
     */
    public function actionRset_pwd()
    {
        $this->userData = $this->getUserInfo();
        $mobile = $this->userData['username'];

        if(!$mobile){
            Yii::$app->getSession()->setFlash('error','Please login again'); //错误提示信息
            return $this->goHome();
            // show_json(100000,'Please login again');
        }

        if(!is_numeric($mobile)){
            Yii::$app->getSession()->setFlash('error','The current account cannot be changed'); //错误提示信息
            return $this->goHome();
            //show_json(100000,'The current account cannot be changed');
        }
        return $this->render('rsetpwd',['mobile'=>$mobile,'yc_mobile'=>substr($mobile,0,3).'*****'.substr($mobile,7,4)]);
    }


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $this->userData = $this->getUserInfo();
        /*Yii::$app->user->logout();
        $_SESSION = null;
        unset($_COOKIE);*/
        /* $url = Yii::$app->request->hostInfo.'/rbac/web/index.php?r=site/login';

         $this->redirect($url,301);*/
       /* return $this->goHome();*/

        $access_token = $this->getAccessToken();

        if(!$access_token){
            show_json(100000,'未登陆,退出无效');
        }
        $_SESSION = array(); //清除SESSION值.
        if(isset($_COOKIE[Yii::$app->params['access_token_name']])){  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.
            setcookie(Yii::$app->params['access_token_name'],'',time()-1,'/');
        }
        session_destroy();  //清除服务器的sesion文件

        //清除oauth是授权的access_token
        $data = $this->logout($access_token);
        $url = Yii::$app->request->hostInfo;

        $this->redirect($url,301);

        //return $this->goHome();

      /*  if($data && $data['code'] == 0) show_json(0,'logout success');

        show_json(100000,'logout errors');*/
    }

    /**
     * 释放缓存 用户信息 从新登陆
     * @return yii\web\Response
     */
    protected function unsetUSerInfo()
    {
        $access_token = $this->getAccessToken();
        if(!$access_token){
            return false;
           /* $url = Yii::$app->request->hostInfo;

            $this->redirect($url,301);*/
        }

        $_SESSION = array(); //清除SESSION值.
        if(isset($_COOKIE[Yii::$app->params['access_token_name']])){  //判断客户端的cookie文件是否存在,存在的话将其设置为过期.
            setcookie(Yii::$app->params['access_token_name'],'',time()-1,'/');
        }
        session_destroy();  //清除服务器的sesion文件

        //清除oauth是授权的access_token
        $data = $this->logout($access_token);

        /*return $this->goHome();*/
       /*  $url = Yii::$app->request->hostInfo;

        $this->redirect($url,301);*/
    }

    /**
     * 获取用户登陆的token
     * @return bool|string
     */
    protected function getAccessToken()
    {
        if(isset($_SESSION['oauth']['access_token'])) return  $_SESSION['oauth']['access_token'];//session

        if(getCookie_token()) return getCookie_token();//cookie 缓存

        return false;

    }

    protected function logout($access_token)
    {
        $post_data = [
            'access_token' => $access_token,
            'callback' => ''
        ];

        $c_a = 'oauth/logout';

        $url = Yii::$app->params['yar_server_address'].$c_a;

        return json_decode(postCurl_1($url,$post_data),true);
    }
}