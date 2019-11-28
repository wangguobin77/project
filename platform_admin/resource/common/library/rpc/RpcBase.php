<?php
namespace common\library\rpc;
/**
 * rpc 核心类
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/11
 * Time: 下午4:40
 */
class RpcBase
{
    private static $secretKey = 'senseplay';//rpc 请求加密 揭秘的key值

    public static $timeStampOut = 300;

    public static function encodeDataStr ( array $data)
    {
        return base64_encode(json_encode($data));
    }
    public static function decodeDataStr ($data)
    {
        return json_decode(base64_decode($data),true);
    }
    public static function createSign(array $data)
    {
        $stringA=implode('&',$data);
        $stringSignTemp=$stringA."&secretKey=".self::$secretKey;
        $sign=strtoupper(hash_hmac("sha256",$stringSignTemp,self::$secretKey));
        return $sign;
    }
    public static function verifySign(array $data)
    {
        $srcData = $data;
        unset($srcData['sign']);
        $srcData = [
            'dataStr='.$srcData['dataStr'],
            'timeStamp='.$srcData['timeStamp'],
            'nonceStr='.$srcData['nonceStr'],
        ];
        $sign = self::createSign($srcData);
        return ($sign==$data['sign'])?true:false;
    }
    public static function getRandStr()
    {
        $str="QWERTYUIOPASDFGHJKLZXCVBNM1234567890qwertyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        return substr(str_shuffle($str),10,16);
    }
}