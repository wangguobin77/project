<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/12/11
 * Time: 上午11:49
 */

namespace app\controllers\base;

use Yii;
use app\models\Sn;
class SnBaseController extends BaseController
{
    /**
     * 根据厂商id 获取所有的工厂
     * @param $mid
     * @return array
     */
    protected function getFactoryInfoAll($mid)
    {
        $Sn = new Sn;

        $res = $Sn->getFactoryInfoAndShortInfoSelectAll($mid,0);//获取当前厂商下所有的厂商信息与当前缩写信息

        if($res){
            return $res;
        }

        return [];
    }

    /**
     * 获取device type short相关信息
     * @param $mid
     * @return array
     */
    protected function getDeviceTypeAndCategoryShortInfoAll($mid)
    {
        $Sn = new Sn;
        $data = $Sn->getdeviceTypeAndCategoryShortInfoSelectAll($mid);

        if($data){
            return $data;
        }

        return [];
    }

    /**
     * 获取remote type  short相关信息
     * @param $mid
     * @return array
     */
    protected function getRemoteTypeShortInfoAll($mid)
    {
        $Sn = new Sn;
        $data = $Sn->getRemoteTypeShortInfoSelectAll($mid);

        if($data){
            return $data;
        }

        return [];
    }

    /**
     * 厂商后台 批次号列表展示
     * @param $data
     * @param $device_type_info
     * @param $remote_type_info
     * @param $m_id
     * @return mixed
     */
    /*protected function disBatchInfoList($data,$device_type_info,$remote_type_info)
    {

        foreach ($data as $key=>$val){
            if($val['h_type'] == 1){//终端设备
                foreach ($device_type_info as $k=>$kv){
                    if($val['h_id'] == $kv['id']){
                        $data[$key]['facility_name'] = $kv['type'];
                    }else{
                        $data[$key]['facility_name'] = '';
                    }
                }
            }else if($val['h_type'] == 2){//遥控器
                foreach ($remote_type_info as $j=>$jv){
                    if($val['h_id'] == $jv['id']){
                        $data[$key]['facility_name'] = $jv['type'];
                    }else{
                        $data[$key]['facility_name'] = '';
                    }
                }
            }else{
                continue;
            }
        }

        return $data;
    }*/
    protected function disBatchInfoList($data,$device_type_info,$remote_type_info)
    {

        foreach ($data as $key=>$val){
            if($val['h_type'] == 1){//终端设备
                foreach ($device_type_info as $k=>$kv){
                    if($val['h_id'] == $kv['id']){
                        $data[$key]['facility_name'] = $kv['type'];
                    }
                }
            }else if($val['h_type'] == 2){//遥控器
                foreach ($remote_type_info as $j=>$jv){
                    if($val['h_id'] == $jv['id']){
                        $data[$key]['facility_name'] = $jv['type'];
                    }
                }
            }else{
                continue;
            }
        }

        return $data;
    }
}