<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-21
 * Time: 12:01
 */

namespace app\controllers\bis;


use app\models\Batch;
use app\models\Sn;
use common\ErrorCode;
use common\helpers\Exception;
use common\MvcResult;
use common\Result;
use Yii;
use yii\data\Pagination;

class batchBis
{
    /**
     * 获取指定厂商的批次列表
     * @return MvcResult
     */
    public function getBatchInfoList(){
        $ret = MvcResult::getInstance();

        $Batch = new Batch();
        $ret->pages = new Pagination(['defaultPageSize'=>10, 'validatePage'=>false]);
        $data = $Batch->getBatchInfoList($ret->pages->offset,$ret->pages->limit,$ret->mid);
        $ret->pages->totalCount = $data['totalCount'];
        $ret->data = $data['data'];

        return $ret;
    }

    /**
     * 获取申请批次号页面所需信息
     * @return MvcResult
     * @throws Exception
     */
    public function applyView(){
        $ret = MvcResult::getInstance();

        $Batch = new Batch();
        $ret->device_list = $Batch->getDeviceTypeSelectAll($ret->mid);//终端
        $ret->remote_list = $Batch->getRemoteTypeSelectAll($ret->mid);//rc

        return $ret;
    }

    /**
     * 添加批次
     * @param $data
     * @return Result
     * @throws Exception
     */
    public function applyBatch($data){
        $ret = new Result();

        $batch_year = substr(date('Y'),2);//年份

        //todo 这里需要年份拼接月份
        $data['batch_year'] = $batch_year.$this->getMonth();//年月

        $data['batch_no'] = $this->getBatchNo($data['mid'],$data['h_id'],$data['h_type'], $data['batch_year']);//批次号 todo

        $data['batch_count'] = $this->disBatch_count($data['batch_count']);//生产数量

        $Batch = new Batch;
        $res = $Batch->addSnBatch($data);

        if(!$res){
            throw new Exception('添加失败，请稍后重试', ErrorCode::BAD_DB_EXEC);
        }
        insert_db_log('insert', "添加批次:厂商-{$data['mid']} 设备-{$data['h_id']} 数量-{$data['batch_count']} 批次号-{$data['batch_no']}");
        return $ret;

    }

    /**
     * 根据厂商id+[分类编码]-[型号编码]-[工厂编码]-生产年月 获取当前批次号
     * 批次号基于最后一次添加 递增
     * @param $m_id
     * @param $h_id
     * @param $h_type
     * @param $batch_year
     * @return int
     * @throws Exception
     */
    protected function getBatchNo($m_id,$h_id,$h_type, $batch_year)
    {
        $Batch = new Batch;

        $data = $Batch->getBatchInfoDescSelectOne($m_id,$h_id,$h_type,$batch_year);

        if($data){

            $batch_number = intval($data['batch_no'])+1;

            if($batch_number > 99){ //安每个月最大号只能到99
                throw new Exception('申请批次已达本月上限');//申请批次号已达上线 最大99
            }

            return $batch_number;
        }
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

    /**
     * 获取月份 用于凭借批次号
     */
    protected function getMonth()
    {
        $month = intval(date('m'));//截取月份

        if($month == 10){
            return 'A';
        }else if($month == 11){
            return 'B';
        }else if($month == 12){
            return 'C';
        }else{
            return $month;
        }
    }

    /**
     * 获取sn列表
     * @return MvcResult
     */
    public function getSnList(){
        $ret = MvcResult::getInstance();

        $Sn = new Sn();
        $res = $Sn->getSnInfoSelectParamCount(0, 0, $ret->params['batch_id'], $ret->params['check_status'], $ret->params['sn']);


        $ret->pages = new Pagination(['totalCount'=>isset($res['total'])?$res['total']:0,'defaultPageSize'=>10] );  //传入页面的总页数格式


        $ret->data = $Sn->getSnInfoFromConditionSelectAll($ret->pages->offset,$ret->pages->limit,$ret->params['batch_id'], $ret->params['check_status'], $ret->params['sn']);

        return $ret;
    }
}

