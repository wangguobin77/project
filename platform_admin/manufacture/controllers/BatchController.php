<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-22
 * Time: 10:23
 */

namespace app\controllers;

use app\controllers\bis\batchBis;
use app\controllers\bis\snBis;
use common\ErrorCode;
use common\helpers\Exception;
use common\helpers\Utils;
use common\helpers\ValidateHelper;
use common\MvcResult;
use common\Result;
use Yii;
use common\library\exportCsv;
use app\controllers\base\BaseController;

class BatchController extends BaseController
{
    public $layout = false;
    public $enableCsrfValidation = false;

    //csv下载
    public function actionExport_csv()
    {
        $bid = filterData(Yii::$app->request->get('batch_id',''),'integer',1000);//批次号id

        if(!$bid) show_json(100000,'batch error');
        (new exportCsv)->rankedexport($bid);
    }

    /**
     * 申请批次号页面
     */
    public function actionApply_view()
    {
        $params = Utils::getHttpInput();
        try{
            $ret = MvcResult::getInstance($this);
            $ret->view = 'batch_add';
            $ret->mid = filterData(Yii::$app->request->get('mid',''),'string',32);//获取厂商id

            $rules = [
                [['mid'], 'string', 'length' => 32],
            ];
            $ret = ValidateHelper::mvcValidate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                $bis = new batchBis();
                $ret = $bis->applyView();
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 申请批次号
     */
    public function actionCheck_number()
    {
        $params = Utils::getHttpInput();
        try{
            $ret = new Result();
            $ret->mid = filterData(Yii::$app->request->get('mid',''),'string',32);//获取厂商id
//        $upc_code = filterData(Yii::$app->request->post('upc_code'),'string',13);//upc码,去除upc码，由于数据设置了not null，暂时设置为空
            $params['upc_code'] = '';

            $rules = [
                [['mid', 'h_type', 'h_id', 'batch_count'], 'required'],
                [['mid', 'h_id'], 'string', 'length' => 32],
                [['h_type', 'batch_count'], 'integer'],
            ];
            $ret = ValidateHelper::validate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                $bis = new batchBis();
                $ret = $bis->applyBatch($params);
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 厂商展示batch 列表 注意 厂商只允许查看展示 不能具有任何操作权限
     * @return string
     */
    public function actionBatch_info_list()
    {
        $params = Utils::getHttpInput();
        try{
            $ret = MvcResult::getInstance($this);
            $ret->view = 'batch_list';
            $ret->mid = $params['mid'];

            $rules = [
                [['mid'], 'required',],
            ];
            $ret = ValidateHelper::validate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                $bis = new batchBis();
                $ret = $bis->getBatchInfoList();
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 根据批次号id 获取当前下所有sn
     * sn 列表
     * @return string
     */
    public function actionGet_batch_detail()
    {
        $params = [
            'batch_id' => (int)Yii::$app->request->get('batch_id',''),
            'check_status' => (int)Yii::$app->request->get('check_status',''),
            'sn' => Yii::$app->request->get('sn',''),
            'mid' => Yii::$app->request->get('mid',''),
        ];
        try{
            $ret = MvcResult::getInstance($this);
            $ret->view = 'sn_info_list';
            $params['check_status'] = isset($params['check_status'])?$params['check_status']:0;
            $params['sn'] = isset($params['sn'])?$params['sn']:'';

            $ret->params = $params;

            $rules = [
                [['batch_id'], 'required'],
                [['sn'], 'string'],
                [['batch_id', 'check_status'], 'integer'],
            ];
            $ret = ValidateHelper::validate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                $bis = new batchBis();
                $ret = $bis->getSnList();
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 基于某个批次号下，增加一定数量的sn号
     */
    public function actionAdd_batch_sn()
    {
        try{
            $ret = new Result();
            $params['count'] = filterData(Yii::$app->request->post('count',''),'integer',5);//增加的数量
            $params['bid'] = filterData(Yii::$app->request->post('batch_id'),'integer',11);//批次号id

            $rules = [
                [['count', 'bid'], 'required'],
                [['count', 'bid'], 'integer'],
            ];
            $ret = ValidateHelper::validate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                snBis::addBatchSn($params);
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;

    }
}