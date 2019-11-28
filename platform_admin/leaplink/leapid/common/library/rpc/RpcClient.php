<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/11
 * Time: 下午4:43
 */

namespace common\library\rpc;


class RpcClient extends RpcBase
{
    /**
     * 客户端组装请求数据
     * @param array $condition 条件 参数 值
     * @param $url 请求地址的服务器
     * @return string|\Yar_Client
     */
    public static function getClient(array $condition,$url)
    {
        if(!$url){
            return 'please set params rpc_host';
        }
        //error_reporting(E_ERROR);
        //ini_set("yar.debug",'on');
        ini_set("yar.timeout",60000);
        $defult = [
            'url'   => $url, //服务器URL
            'class' => '', //class名称
        ];
        $condition = array_merge($defult,$condition);
        $data = [];
        $data['class'] = $condition['class'];
        $dataStr = self::encodeDataStr($data);
        $urlData = $tmpData = [
            'dataStr='.$dataStr,
            'timeStamp='.time(),
            'nonceStr='.self::getRandStr(),
        ];
        $urlData[] = 'sign='.self::createSign($tmpData);
        $url = "{$condition['url']}?".implode('&',$urlData);
        try{
            $object =  new \Yar_Client($url);
            $object->SetOpt(YAR_OPT_CONNECT_TIMEOUT, 1000);
            $object->SetOpt(YAR_OPT_TIMEOUT, 1000);
        }catch (\Exception $e){
            error_log($e->getMessage());
        }
        return $object;
    }
}