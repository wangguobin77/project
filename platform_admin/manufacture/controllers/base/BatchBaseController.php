<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/16
 * Time: 下午1:57
 */

namespace app\controllers\base;

use Yii;
use app\models\Batch;
use app\models\Sn;
class BatchBaseController extends BaseController
{
    /**
     * 根据厂商id+[分类编码]-[型号编码]-[工厂编码]-生产年月 获取当前批次号
     * 批次号基于最后一次添加 递增
     * @param $m_id
     */
    protected function getBatchNo($m_id,$h_id,$h_type, $batch_year)
    {
        $Batch = new Batch;

        $data = $Batch->getBatchInfoDescSelectOne($m_id,$h_id,$h_type,$batch_year);

        if($data){

            $batch_number = intval($data['batch_no'])+1;

            if($batch_number > 99){ //安每个月最大号只能到99
                show_json(100000,'The application number has been capped');//申请批次号已达上线 最大999
            }

            return $batch_number;
        }

        /* return 0;*/
        return 1;
    }

    /**
     * 处理生产数量 不能超过最大数 五个字节
     * @param $batch_count
     * @return mixed
     */
    protected function disBatch_count($batch_count)
    {
        if(!intval($batch_count)){
            show_json(100000,'the number is wrong');
        }

        if($batch_count > 99999){
            show_json(100000,'outsize 99999');
        }

        return $batch_count;
    }


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

    /**
     * 根据批次id 返回批次序列号
     * @param $bid
     * @return string
     */
    public function getBatchNumber($bid)
    {
        $Sn = new Sn;
        $data = $Sn->getSnBatchInfoSelectOne($bid);//批次号信息


        //验证 查找缩写
        if(!$data){
            show_json(100000,'Data is not legal cannot be audited');
        }

        //只有被审核通过的文件才能下载
        if($data['check_status'] != 3){
            show_json(100000,'Only approved files can be downloaded');
        }

        $sn_manufacture_category_short_info = $Sn->getSnSFRSelectAll($data['m_id']);

        if(!$sn_manufacture_category_short_info){
            show_json(100000,'lack of specific information audit failed');//缺少具体信息 不能通过审核
        }

        if($data['h_type'] == 1){//终端
            $sn_category_device_short_info = $Sn->getSnDeviceAndCategoryShortInfo($data['h_id']);

            if(!$sn_category_device_short_info){
                show_json(100000,'lack of hid specific information audit failed');//缺少具体信息 不能通过审核
            }
            $c_short = $sn_category_device_short_info['category_short'];//大类缩写

            $d_short = $sn_category_device_short_info['device_type_short'];//终端缩写
        }else if($data['h_type'] == 2){//遥控器
            /* $sn_remote_short_info = $Sn->getSnRemoteShortInfo($data['r_id']);*/
            $sn_remote_short_info = $Sn->getSnRemoteShortInfo($data['h_id']);

            if(!$sn_remote_short_info){
                show_json(100000,'lack of rid specific information audit failed');//缺少具体信息 不能通过审核
            }
            $c_short = 'RC';
            $d_short = $sn_remote_short_info['remote_type_short'];//大类缩写
        }else{
            show_json(100000,'device type unknown');//设备类型不详 不能通过审批
        }
        //end

        $batch_number_str = $sn_manufacture_category_short_info['manufacture_short'].$c_short.$d_short.$data['batch_year'].$data['batch_no'];//批次序列号

        return $batch_number_str;
        //根据批次id 获取所有sn相关数据 getSnInfoFromBid
       // $sn_info_list = $Sn->getSnInfoFromBid($batch_number_str);//sninfo信息
    }
}