<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 17:59
 */

namespace common\helpers;

use Yii;
/*
 * 实用工具助手类
 */
class UtilsHelper
{
    /**
     * @param string $phone         手机号
     * @param string $code          验证码
     * @param string $templateIdx   模板索引
     * @return boolean              发送结果 true:成功,false:失败
     */
    public static function sendPhoneCode($phone, $code, $templateIdx = '1'){
        //todo 发送手机验证码

        return true;
    }

    /**
     * 获取http请求参数
     */
    public static function getHttpInput()
    {
        if ( Yii::$app->request->getIsPost() ) {
            $argv = Yii::$app->request->post();
        } else {
            $argv = Yii::$app->request->get();
        }
        return $argv;
    }

    //获取整形客户端 ip
    public static function getUserLongIp()
    {
        return StringsHelper::ip2long(Yii::$app->request->getUserIP());
    }

    /**
     * 分转换为元
     * @param float|int $value      值
     * @param int $hold             保留小数位
     * @return string
     */
    public static function fen2yuan($value, $hold = 2)
    {
        return sprintf("%.".$hold."f", $value/100);
    }

    /**
     * @param float|int $value      值
     * @param int $hold             保留小数位
     * @return string
     */
    public static function yuan2fen($value, $hold = 2)
    {
        return sprintf("%.".$hold."f", $value*100);
    }

    /**
     * 时间戳转日期
     * @param $time
     * @param string $format
     * @return false|string
     */
    public static function int2date($time, $format = 'Y-m-d H:i')
    {
        return date($format, $time);
    }

    /**
     * curl post 第1种方式
     * @param url 推送数据的地址
     * @return array  服务器返回的结果
     * @descripe  使用post方式 发送数据到指定的url下  采集数据
     */
    public static function postCurl ($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $back_info = curl_exec($ch);
        if (curl_errno($ch)) {
            $back_info = curl_error($ch);
            writeLog($back_info);
            $back_info = json_encode(array('status' => false, 'msg' => $back_info));
        }
        curl_close($ch);
        return $back_info;
    }

    /**
     * curl post 第2种方式
     * @param $url
     * @param $post_data
     * @return mixed
     */
    public static function postCurl_1($url, $post_data){
        $o="";
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".urlencode($v)."&";
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}