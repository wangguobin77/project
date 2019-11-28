<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/17
 * Time: 下午2:46
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use common\library\rpc\RpcClient;

use yii\helpers\Url;
class ClientController extends Controller
{

    /**
     * sdk
     * 安卓 ios 封装回掉地址
     * 所有使用第三方登陆接口的回掉地址
     * 本接口只对自平台开放
     */
    public function actionOauth()
    {
        //todo 请求此接口会过滤ip 只会对本公司所有子平台开放
        if($_GET['code']){

            try{
                //1.得到授权码(Authorization Code)
                $code = $_GET['code'];

                //2.通过授权码得到令牌(Access_token)
                $post_data = array(
                    'grant_type'=>'authorization_code',
                    'client_id'=> Yii::$app->params['app_id'],
                    'client_secret'=> Yii::$app->params['app_key'],
                    'redirect_uri'=>Yii::$app->params['OAUTH_REDIRECT_URI'],
                    'code'=>$code
                );

                $result = $this->post(Yii::$app->params['UCENTER_OAUTH_URL'],$post_data);//获取access_token

                $result = json_decode($result,true);

                if($result && isset($result['access_token'])){

                    //$openid = $this->getOpenid($result['access_token']);//获取openid

                    //$userinfo = $this->getUserinfo($openid,$result['access_token']);//获取用户信息


                    $_SESSION['oauth']['access_token'] = $result['access_token'];
                    setCookie_token($result['access_token']);//cookie 存储

                    $loginUrl = Url::toRoute('index/index'); //跳转到首页
                    header("Location: $loginUrl");exit;

                    //挂起该平台下用户登陆的状态
                }else{
                    throw new \Exception('access_token gain be defeated');//access_token 获取失败
                }

            }catch (\Exception $e){

                show_json(100006,$e->getMessage());
            }

        }else{
            //code 丢失
            show_json(100005,Yii::$app->params['errorCode'][100005]);
        }

    }

    /**
     *
     * @param url 推送数据的地址
     * @return array  服务器返回的结果
     * @descripe  使用post方式 发送数据到指定的url下  采集数据
     */
    public function post($url, $post_data){
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}