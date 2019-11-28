<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-21
 * Time: 12:01
 */

namespace app\controllers\bis;


use common\ErrorCode;
use common\helpers\Exception;
use app\models\Sn;
use common\Result;
use app\models\Manufacture;
use Yii;

class snBis
{
    /**
     * 获取sn绑定日志
     * @param $sn
     * @return array
     * @throws Exception
     */
    public function getSnBindLog($sn)
    {
        $sql = 'select uuid,sn,bind_type,created_ts from sn_bind_or_unbind_openid_log where sn="'.$sn . '" order by created_ts desc';
        $res = Yii::$app->db->createCommand($sql)->queryAll();
        if(!empty($res)){
            array_walk($res, function (&$item){
                $item['created_ts'] = date('Y-m-d H:i:s', $item['created_ts']);
            });
        }
        return $res;
    }

    /**
     * 审核
     * @param $batch_id
     * @param $new_status
     * @return int
     * @throws Exception
     */
    public static function checkStatus($batch_id, $new_status)
    {
        $sn = new Sn();

        try{
            $return_code = $sn->checkStatus($batch_id, $new_status);
        }catch (\yii\db\Exception $e){
            throw new Exception('数据库运行错误', ErrorCode::ERROR);
        }

        if($return_code === '0'){
            insert_db_log('update', "批次审批:批次id-{$batch_id} 新状态-{$new_status}");
            return ErrorCode::SUCCEED;
        } elseif ($return_code === '90050'){
            throw new Exception('不是可以审核的状态.', ErrorCode::BAD_STATUS);
        }elseif ($return_code === '90051'){
            throw new Exception('厂商缩写未设置.', ErrorCode::INVALID_MANUFACTURE_SHORT);
        }elseif ($return_code === '90052'){
            throw new Exception('大类缩写未设置.', ErrorCode::INVALID_CATEGORY_SHORT);
        }elseif ($return_code === '90053'){
            throw new Exception('类型缩写未设置.', ErrorCode::INVALID_TYPE_SHORT);
        }else{
            throw new Exception('未捕获的错误（未知情况）', ErrorCode::ERROR);
        }
    }


    /**
     * 添加厂商缩写
     * @param $params
     * @return Result
     * @throws Exception
     */
    public function addManufactureShort($params){
        $ret = new Result();
        $manufactureModel = new Manufacture();
        $res = $manufactureModel->addShort($params['mid'], $params['short']);

        if($res === false){
            throw new Exception('数据库运行错误', ErrorCode::BAD_DB_EXEC);
        }
        if($res['_return_code'] == ErrorCode::REPEAT_SHORT){
            throw new Exception('缩写重复', ErrorCode::REPEAT_SHORT);
        }

        $ret->code = $res['_return_code'];
        insert_db_log('insert', "添加厂商缩写:厂商id-{$params['mid']} 缩写-{$params['short']}");
        return $ret;
    }


    /**
     * 在指定批次上添加更多的sn
     * @param $params
     * @return int
     * @throws Exception
     */
    public static function addBatchSn($params){
        $sn = new Sn();

        try{
            $return_code = $sn->addBatchSn($params['bid'], $params['count']);
        }catch (\yii\db\Exception $e){
            throw new Exception('数据库运行错误', ErrorCode::ERROR);
        }


        if($return_code === '0'){
            insert_db_log('insert', "批次内添加:批次id-{$params['bid']} 数量-{$params['count']}");
            return ErrorCode::SUCCEED;
        } elseif ($return_code === '900050'){
            throw new Exception('滚粗！不是可以添加的状态.', ErrorCode::BAD_STATUS);
        }elseif ($return_code === '900051'){
            throw new Exception('厂商缩写未设置.', ErrorCode::INVALID_MANUFACTURE_SHORT);
        }elseif ($return_code === '900052'){
            throw new Exception('大类缩写未设置.', ErrorCode::INVALID_CATEGORY_SHORT);
        }elseif ($return_code === '900053'){
            throw new Exception('类型缩写未设置.', ErrorCode::INVALID_TYPE_SHORT);
        }elseif ($return_code === '900057'){
            throw new Exception('超出99999的数量限制.', ErrorCode::MORE_THAN_LIMIT);
        }else{
            throw new Exception('未捕获的错误（未知情况）', ErrorCode::ERROR);
        }
    }



}