<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/26
 * Time: 下午3:51
 */

namespace app\common\core;


use yii;
class doHanderDb extends \yii\db\ActiveRecord
{
    protected static $db = null;//当前对象

    protected static $_instance = null;//当前对象 键值判断

    public function __construct($db_config)
    {
        self::getInstance($db_config);
    }

    public static function getInstance($db_config){

        if(!isset(self::$_instance[$db_config])) {
            self::$db = self::$_instance[$db_config] = Yii::$app->$db_config;//当前配置文件
        }
        return self::$db;
    }

}