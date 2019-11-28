<?php
/**
 * 移动端登陆 退出接口
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/19
 * Time: 上午9:53
 */

namespace backend\controllers\api;

use yii;
use  backend\controllers\base\BaseCoreController;
use backend\library\oauth\accesstoken;
class OauthController extends BaseCoreController
{
    /**
     * 用户退出
     */
    public function actionLogout()
    {
        $access_token = BaseCoreController::getLocalCookieToken();//获取本地cookie token数据

        if(!$access_token){
            show_json(100000,'You have quit');
        }

        (new accesstoken)->delUserAccessToken($access_token);//清除redis 用户token数据

        setcookie(Yii::$app->params['global_cookie_token'],'',time()+3600*24*30,'/');//清除本地cookie

        session_destroy();//销毁session
    }
}