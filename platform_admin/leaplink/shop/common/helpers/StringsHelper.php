<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 18:14
 */

namespace common\helpers;


/*
 * 字符串助手类
 */
class StringsHelper
{
    /*
     * 产生随机数字验证码
     */
    public static function randInt($length = 6)
    {
        $key = '';
        $pattern = '1234567890';
        for ($i = 0; $i < $length; $i++){
            $key .= $pattern[mt_rand(0,9)];
        }
        return $key;
    }

    /**
     * 生产 id (13位毫秒时间戳加 5 位随机整数)
     */
    public static function createId()
    {
        return (int)((int)(microtime(true)*1000) . self::randInt(5));
    }

    /*
     * 产生随机数字验证码
     */
    public static function randString($length = 4)
    {
        $key = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $count = strlen($pattern)-1;
        for ($i = 0; $i < $length; $i++){
            $key .= $pattern[mt_rand(0,$count)];
        }
        return $key;
    }

    /**
     * 密码+salt md5
     * @param $password
     * @param $salt
     * @return array
     */
    public static function hashPwd($password, $salt = null)
    {
        if(is_null($salt)){
            $salt = self::randString(4);
        }
        $pwd = md5($password . $salt);

        return [$pwd, $salt];
    }

    //ip 转长整型
    public static function ip2long($ip)
    {
        return sprintf("%u",ip2long($ip));
    }

    //整形转 ip
    public static function long2ip($long)
    {
        return long2ip($long);
    }

    //创建 token
    public static function createToken()
    {
        //随机字符串再 md5
        return md5(self::randString(32));
    }

    /**
     * 生成GUID（UUID）
     * @access public
     * @return string
     * @author knight
     */
    public static function createGuid()
    {

        mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
        $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
//    $hyphen = chr ( 45 ); // "-"
        $uuid = '' . //chr(123)// "{"
            substr ( $charid, 0, 8 )  . substr ( $charid, 8, 4 )  . substr ( $charid, 12, 4 )  . substr ( $charid, 16, 4 )  . substr ( $charid, 20, 12 );
        //.chr(125);// "}"
        return $uuid;

    }
}