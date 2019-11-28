<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/26
 * Time: 下午3:36
 */

namespace app\common\core;

use Yii;
use app\models\redisdb\testRedisDb;
class Model_core
{
    protected $model = null;//当前对象


    /**
     * db 操作实力
     * @param $db_conf db连接配置
     * @param $con_file 加载db文件
     * @return mixed
     */
    public function dbreader($db_conf,$con_file)
    {
        $class_name = $con_file .'Db';//类名

        if(!$this->loadDbFile($class_name)) die('db File does not exist！');

       return new $class_name($db_conf);
    }

    /**
     * redis 操作
     * @param $redis_conf redis连接配置
     * @param $con_file 加载redis 操作文件
     * @return mixed
     */
    public function redisreader($redis_conf,$con_file)
    {
        $class_name = $con_file .'RedisDb';//类名

        if(!$this->loadRedisFile($class_name)) die('redis File does not exist！');

        return new $class_name($redis_conf);

    }

    /**
     * 加载db文件
     * @param $con_file
     * @return bool
     */
    public function loadDbFile($class_name)
    {
        $file_path = APP_MODEL . DS .'db' . DS . $class_name .'.php';

        if(file_exists($file_path)){

            include_once ($file_path);
            return true;
        }

        return false;
    }

    /**
     * 加载redis文件
     * @param $con_file
     * @return bool
     */
    public function loadRedisFile($class_name)
    {
        $file_path = APP_MODEL . DS .'redisdb' . DS . $class_name .'.php';

        if(file_exists($file_path)){

            include_once ($file_path);

            return true;
        }

        return false;
    }
}