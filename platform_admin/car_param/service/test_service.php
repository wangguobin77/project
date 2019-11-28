<?php
namespace app\service;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/21
 * Time: 下午5:44
 */

use Yii;
use app\models\test;

class test_service
{
    public static $_instance;

    //初始化该类
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();//当前配置文件
        }
        return self::$_instance;
    }


    /**
     * 测试用例
     * @param $access_token
     * @param $client_id
     * @return bool
     */
    public function tt(){

        $openid = 'mkmkmkm';

       /* $data = (new test)->setRedisOpenid($openid);*/ //redis
        $data = (new test)->setOpenid(); //db

        return $data;
    }

}