<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/4/9
 * Time: 下午3:33
 */

namespace backend\controllers\api;

use yii;
use yii\web\Controller;
class LogoutController extends Controller
{
    public function actionIndex()
    {
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

        if($data && $data['code'] == 0) show_json(0,'logout success');

        show_json(100000,'logout errors');
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