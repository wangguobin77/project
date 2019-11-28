<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-22
 * Time: 18:52
 */

namespace common\helpers;

use Yii;
/**
 * 会话操作工具类
 * 该类帮助实现了 redis 会话
 * Class SessionHelper
 * @package common\helpers
 */
class SessionHelper
{
    /** session 过期时间 */
    const REDIS_SESSION_TIMEOUT = 604800; //7*24*3600;

    /** 缓存实例对象 */
    private $cache;

    public function getCache()
    {
        if ( !$this->cache ) {
            $this->cache = RedisHelper::getRedis();
        }
        return $this->cache;
    }


    /**
     * hash存储
     * 单个存储(更新)会话中的域值
     * 保存key-value到会话中，如果没有传入会话ID，则会创建一个新的会话。
     * 每次成功执行该函数，则该会话的时间都将会得到延续。
     * @param string $name                  域
     * @param string $value                 值
     * @param integer $timeout              过期时间
     * @param string $session_id            会话ID (默认NULL), 如果该参数为NULL的情况下 会新建一个会话
     * @return mixed
     */
    public function hset($name, $value, $timeout = self::REDIS_SESSION_TIMEOUT, $session_id = null)
    {
        if ( $session_id == null ) {
            $session_id = $this->createSessionID();
        }
        $key = $this->getSessionKey( $session_id );
print_r($key);
print_r($name);
print_r($value);
        $ret = $this->getCache()->hset($key, $name, $value);

        $this->getCache()->expire($key, $timeout); //设置/更新 会话过期时间
        print_r($ret);
        return $session_id;
    }

    /**
     * 批量更新
     * @param $map                     ['shop_id' => 1, 'phone' => 18721817517, 'username' => 'qijun.jiang']
     * @param int $timeout             过期时间
     * @param string $session_id          会话ID (默认NULL), 如果该参数为NULL的情况下 会新建一个会话
     * @return string
     */
    public function hmset($map, $timeout = self::REDIS_SESSION_TIMEOUT, $session_id = null)
    {
        if ( $session_id == null ) {
            $session_id = $this->createSessionID();
        }
        $key = $this->getSessionKey( $session_id );

        $command[] = $key;
        foreach ($map as $field => $item){
            $command[] = $field;
            $command[] = $item;
        }

        $this->getCache()->executeCommand('hmset', $command);

        $this->getCache()->expire($key, $timeout); //设置/更新 会话过期时间
        return $session_id;
    }

    /**
     * 获取会话中的值
     * @param string $session_id        会话 id
     * @param null $name                可变参数-域,如果该参数不为 null,则取出该会话中域{$name}的值,否则取出所有
     */
    public function hget($session_id, ...$name)
    {
        $returnVal = null;
        $key = $this->getSessionKey( $session_id );
        if([] !== $name) {
            $resVal = $this->getCache()->hmget($key, ...$name);
            if(!empty($resVal)){
                foreach ($name as $key => $item){
                    $returnVal[$item] = $resVal[$key];
                }
            }

        }else{
            $resVal = $this->getCache()->hgetall($key);
            $c = count($resVal);
            for ($i = 0; $i < $c; $i++){
                $returnVal[$resVal[$i]] = $resVal[$i+1];
                $i++;
            }

        }
        return $returnVal;
    }

    public function flushValues()
    {
        $this->getCache()->flushValues();
    }

    /**
     * 移除会话
     * @param string $session_id           会话 id
     */
    public function destory($session_id)
    {
        $key = $this->getSessionKey( $session_id );
        return $this->getCache()->del( $key );
    }

    public function exists($session_id)
    {
        $key = $this->getSessionKey( $session_id );
        return $this->getCache()->exists($key);
    }

    /**
     * 获取会话在缓存中的KEY
     * @param string $session_id						会话ID
     * @return string
     */
    private function getSessionKey( $session_id ) {
        $key_prefix = Yii::$app->params['cache_key_prefix']['__session__'];

        return $key_prefix . $session_id;
    }

    /**
     * 创建SESSION ID
     * @return string
     */
    private function createSessionID() {
        $str = md5( uniqid(mt_rand(), true) );
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);

        return $uuid;
    }
}