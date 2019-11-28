<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/26
 * Time: 下午3:52
 */

namespace app\common\core;

use Yii;
class doHanderRedis
{
    protected static $redis = null;//当前对象

    protected static $_instance = null;//当前对象 键值判断

    public function __construct($redis_config)
    {
        self::getInstance($redis_config);
    }

    public static function getInstance($redis_config){

        if(!isset(self::$_instance[$redis_config])) {
            self::$redis = self::$_instance[$redis_config] = Yii::$app->$redis_config;//当前配置文件
        }
        return self::$redis;
    }
}