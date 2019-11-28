<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-22
 * Time: 19:13
 */

namespace app\controllers\traits;

use common\helpers\StringsHelper;
use Yii;
use common\helpers\SessionHelper;

trait appTrait
{

    private $session = null;

    public function getCache()
    {
        if(! $this->session ){
            $this->session = new SessionHelper();
        }
        return $this->session;
    }


    /**
     * 创建用户session信息
     * @param integer $shop_id 用户id
     */
    public function setLoginShopInfo($shop_id)
    {
        $session_id = $this->getCache()->hset('shop_id' ,$shop_id, Yii::$app->params['redis_session_timeout']);

        //设置 cookie
        $this->setCookieSessionId($session_id);
    }


    /**
     * 获取session 中指定用户信息
     * @param string ...$name eg: shop_id
     * @return null
     */
    public function getLoginShopInfo(...$name)
    {
        $sessionId = $this->getCookiesSessionId();
        $shopInfo = $this->getCache()->hget($sessionId, ...$name);

        return $shopInfo;
    }

    /**
     * 设置 sessionId 到 cookie
     * @param $session_id
     */
    private function setCookieSessionId($session_id)
    {
        $cookie = new \yii\web\Cookie([
            'name' => Yii::$app->params['sessionName'],
            'value' => $session_id,
            'expire' => time() + Yii::$app->params['redis_session_timeout'],
            'httpOnly' => true
        ]);
        Yii::$app->response->getCookies()->add($cookie);
    }

    /**
     * @return mixed
     */
    public function getCookiesSessionId()
    {
        return Yii::$app->request->cookies->getValue(Yii::$app->params['sessionName']);
    }

    /**
     * 删除 cookie-session
     */
    public function delCookiesSessionId()
    {
        Yii::$app->response->getCookies()->remove(Yii::$app->params['sessionName']);
    }

    /**
     * 判断 sessionId 是否存在 redis中
     * @param $sessionId
     */
    public function hasSessionId()
    {
        $sessionId = $this->getCookiesSessionId();
        if(!$sessionId){
            return false;
        }

        $ret = $this->getCache()->exists($sessionId);

        if($ret){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 删除用户 redis-session和 cookie-session
     */
    public function delSessionId()
    {
        $sessionId = $this->getCookiesSessionId();
        if(!$sessionId){
            return false;
        }
        $ret = $this->getCache()->destory($sessionId);
        if($ret){
            $this->delCookiesSessionId();
            return true;
        }else{
            return false;
        }
    }


}