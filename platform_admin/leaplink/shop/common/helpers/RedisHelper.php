<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 16:51
 */

namespace common\helpers;

use Yii;

/*
 * Redis 助手类
 */
class RedisHelper
{
    /** 单例模式  */
    const SINGLETON_MODE = 1;

    /** 哨兵模式  */
    const SENTINEL_MODE = 2;

    /** 集群模式  */
    const CLUSTER_MODE = 3;


    /**
     * 获取 redis 实例
     */
    public static function getRedis()
    {
        $redis = null;

        if(self::SINGLETON_MODE === Yii::$app->params['redis_mod'] ){
            $host = Yii::$app->params['singleton_redis']['host'];
            $port = Yii::$app->params['singleton_redis']['port'];
            $db = Yii::$app->params['singleton_redis']['db'];
            $pwd = Yii::$app->params['singleton_redis']['pwd'];

            $redis = self::_get_redis_instance( $host, $port, $db, $pwd );
        }elseif (self::SENTINEL_MODE === Yii::$app->params['redis_mod']){
            //todo 连接 redis 哨兵获取 master 信息
        }

        return $redis;
    }

    /**
     * 通过配置获取 redis 实例
     * @param $config
     * e.g. $config => ['host' => '127.0.0.1', 'port' => 6379, 'db' => 15, 'pwd' => '123456']
     */
    public static function getRedisByConfig($config)
    {
        //todo 直接传入配置连接 redis
        $redis = null;



        return $redis;
    }

    /**
     * 获取Redis实例
     * @param string    $host							redis服务器host
     * @param integer   $port							redis服务器端口
     * @param integer   $dbIdx						    库索引
     * @param string    $pwd							密码
     * @return object   $redis
     */
    private static function _get_redis_instance( $host, $port, $dbIdx, $pwd = null ) {
        $redis = Yii::$app->redis;
        if ( $redis->getIsActive() ) {
            if ( $redis->hostname != $host || $redis->port != $port ) {
                $redis->close();
                $redis = self::_open_redis_connection( $redis, $host, $port, $pwd, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT );
            }
        } else {
            $redis = self::_open_redis_connection( $redis, $host, $port, $pwd, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT );
        }

        $redis->select( $dbIdx );

        return $redis;
    }


    private static function _open_redis_connection( $redis, $host, $port, $pwd, $socketClientFlags ) {
        $redis->hostname = $host;
        $redis->port = $port;
        if ( $pwd ) {
            $redis->password = $pwd;
        }
        $redis->socketClientFlags = $socketClientFlags;
        $redis->open();

        return $redis;
    }
}