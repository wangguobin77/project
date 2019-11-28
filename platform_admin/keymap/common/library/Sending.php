<?php

namespace common\library;
use devmustafa\amqp\PhpAmqpLib\Connection\common;
use Yii;
use common\library\CommonCore;
class Sending
{
    private static $manufacture_email_redis_list_key = 'global_manufacture_email_redis_list_queue';//邮箱redis 消息队列
    private static $manufacture_mobile_redis_list_key = 'global_manufacture_mobile_redis_list_queue';//手机redis 消息队列

    const USEREMAILTYPE = 1;//邮箱注册类型
    const USERMOBILETYPE = 2;//手机注册类型

    /**
     * 推送手机注册相关信息 到rabbitmq
     * @param $data
     * @return int
     */
    public static function send_mobile_to_redis($data)
    {
        $com = new CommonCore();
        $redis =$com::getConnectionRedis();

        $info = [
            'usermobile'=>$data['usermobile'],
            'regtype'=>$data['regtype'],//注册来源
            'content'=>$data['authcode'],//发送类容 验证码
            'time'=>$data['time'],
            'typeid'=>$data['typeid'] //默认手机为 1 为手机验证码
        ];//临时数据

        if($redis->lpush(self::$manufacture_mobile_redis_list_key,json_encode($info))){
            return 1;
        }else{
            //添加队列失败 记录log
            var_log($info,'send_mobile_to_redis_queue');
            return 0;
        }
    }


    /**
     * 推送邮箱注册信息到 redis
     * @param $data
     * @return int
     */
    public static function send_email_to_redis($data)
    {
        $com = new CommonCore();
        $redis =$com::getConnectionRedis();

        $info = [
            'mid'=>$data['mid'],//用户唯一userid
            'memail'=>$data['memail'],//邮箱
            'regtype'=>$data['regtype'], //注册来源
            'content'=>$data['content'],//推送的内容 为激活链接
            'time'=>$data['time'],
            'typeid'=>$data['typeid'] //默认为 1
        ];//临时数据

        if($redis->lpush(self::$manufacture_email_redis_list_key,json_encode($info))){

            return 1;
        }else{
            //添加队列失败 记录log
            var_log($info,'send_email_to_redis_queue');
            return 0;
        }


    }
}
