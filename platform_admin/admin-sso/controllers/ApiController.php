<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/17
 * Time: 下午2:47
 */

namespace app\controllers;

use Yii;
use app\service\oauth_service;
use yii\web\Controller;
class ApiController extends Controller
{
    public function init()
    {
        $this->enableCsrfValidation = false;

    }

    /**
     * 供所有第三方 与 子系统使用
     * 获取当前授权应用的openid 通过openid可以得到用户信息
     * curl post 请求方式
     * 请求参数 ['access_token'=>$access_token]
     * @return  openid
     * @error  提示错误
     */
    public function actionGetOpenid()
    {
        if(!Yii::$app->request->isPost) return false;

        $access_token = Yii::$app->request->post('access_token');

        if(!$access_token || strlen($access_token) > 50){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        $openid = oauth_service::getInstance()->getOpenid($access_token);

        if($openid)  return $openid;

        return false;
    }


    /**
     * 是否有登入操作
     * 检测access_token是否有效
     * @return bool
     */
    public function isCheckLogin($access_token,$client_id)
    {
        if(!$client_id)  show_json(100006,Yii::$app->params['errorCode'][100006]);

        if(!$access_token) show_json(100011,Yii::$app->params['errorCode'][100011]);

         oauth_service::getInstance()->isCheckLogin($access_token,$client_id);

    }


    /**
     * 获取用户信息与oauth sdk不一样 后台拿token直接可以获取用户信息
     * 供第三方与子系统调用
     * 通过openid 获取用户基本信息
     * 请求方式 curl post方式
     * 参数 ['openid' => $openid,'access_token'=>]
     * success 返回用户信息
     * @return mixed
     */
    public function actionGetUserinfo()
    {

        if(!Yii::$app->request->isPost) return false;

        $access_token = Yii::$app->request->post('access_token');

        if(!$access_token){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        $userInfo = oauth_service::getInstance()->getUserInfo($access_token);

        if(!$userInfo){

            return false;

        }

        return $userInfo;

    }

    /**
     * 退出操作
     */
    public function Loginout($access_token,$callback_url='')
    {

        if(!$access_token){
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

        oauth_service::getInstance()->loginOut($access_token,$callback_url);
    }

    /**
     * 根据client_id 验证是否合法 是否存在
     * @return bool
     */
    public function actionCheck_is_client_info()
    {
        $client_id = Yii::$app->request->post()['client_id'];

        if(!$client_id){
            show_json(100006,Yii::$app->params['errorCode'][100006]);
        }
        //先判断redis 是否存在数据 不存在 在判断db是否存在 都不存在 就是无效的key
        $redis = Yii::$app->redis;

        $appinfo = json_decode($redis->get(self::$appInfo_key.$client_id),true);

        if(!$appinfo){

            /*//todo db redis不存在判断db数据是否存在合法数据 都不合法则不能完成授权
            $Oauth2 = new Oauth2;

            $appinfo = $Oauth2->getAppInfoConfig($APP_ID);

            if(!$appinfo){
                var_log([$config,'ip'=>getIp()],'app-id-secret-config');//信息错误 记录带的参数信息和ip地址 防止数据强刷

                show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
            }*/

            /* show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法*/
            show_json(100000,'error');

        }

        show_json(0,'success',$appinfo);

    }
}