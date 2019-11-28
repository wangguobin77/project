<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/28
 * Time: 下午2:08
 */

namespace app\controllers\base;

use yii;
use yii\web\Controller;
use app\service\oauth_service;
class OauthBaseController extends Controller
{

    /**
     * 退出操作
     * @param $access_token
     * @param $callback
     */
    public function loginOut($access_token,$callback)
    {
        oauth_service::getInstance()->loginOut($access_token,$callback);
    }

    /**
     * 根据appid 获取对应的模版
     * @param $appid
     * @return mixed
     */
    public function getViewTypeString($appid)
    {
        return oauth_service::getInstance()->getViewTypeString($appid);
    }


    /**
     * 验证用户是否正确
     * @param $username
     * @param $password &response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>
     */
    protected function verifyUserinfo()
    {
         oauth_service::getInstance()->verifyUserinfo();
    }

    /**
     * 返回session存储的用户信息
     * @return string
     */
    protected function getUserinfoFromSession()
    {
        return isset($_SESSION['userinfo'])?$_SESSION['userinfo']:show_json(100010,Yii::$app->params['errorCode'][100010]);
    }
}