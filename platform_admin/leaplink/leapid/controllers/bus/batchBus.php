<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-10-30
 * Time: 16:37
 */

namespace app\controllers\bus;

use app\console\jobs\LeapidProduceToBinJob;
use app\console\jobs\LeapidProduceToRedisJob;
use app\console\jobs\LeapidProductToMysqlJob;
use app\models\batch\ARLeapid;
use common\helpers\RedisHelper;
use Yii;
use app\models\batch\ARBatch;
use common\consequence\ErrorCode;
use common\consequence\Result;
use common\exception\GanWuException;
use common\helpers\ArrayHelper;
use yii\data\Pagination;

class batchBus
{
    public function getList($params)
    {
        $query = ARBatch::find();

        $queryCount = clone $query;
        $count = $queryCount->count();

        $pages = new Pagination(['defaultPageSize'=>10, 'totalCount' => $count]);

        $datas = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_ts desc')
            ->asArray()
            ->all();

        return ['datas' => $datas, 'pages' => $pages];
    }

    public function getInfoById($id)
    {
        return ARBatch::findOne(['id' => $id]);
    }

    public function add($datas)
    {
        $ret = new Result();
        if(!empty($datas)){
            $argv = [];
            foreach ($datas as $item){
                $tmp = [];
                $tmp['chip_type'] = trim(ArrayHelper::getNoEmpty($item, 'chip_type', '0000'));
                $tmp['key_update'] = trim(ArrayHelper::getNoEmpty($item, 'key_update', '0'));
                $tmp['batch_date'] = str_replace('-', '', $item['batch_date']);
                $tmp['batch_no'] = (int)$item['batch_no'];
                $tmp['batch_count'] = (int)$item['batch_count'];
                $tmp['info'] = trim($item['info']);
                $tmp['created_ts'] = time();
                $argv[] = $tmp;
            }

            try{
                $tran = ARBatch::getDb()->beginTransaction();

                ARBatch::saveBatchs($argv);

                $tran->commit();

            }catch (\Exception $e){
                $tran->rollback();
                throw new GanWuException($e->getMessage(), ErrorCode::ERROR);
//                throw new GanWuException('新增失败', ErrorCode::ERROR);
            }
        }
        return $ret;
    }

    /**
     * @param $id
     * @param $status
     * @return array
     * @throws GanWuException
     */
    public function checkPass($id, $status)
    {
        $ret = new Result();

        $batch = ARBatch::find()
            ->where(['id' => $id])
            ->one();
        if(!$batch){
            throw new GanWuException('找不到数据副本', ErrorCode::ERROR);
        }
        if (ARBatch::CHECK_STATUS1 != $batch->check_status){
            throw new GanWuException('不是可以审核的状态', ErrorCode::ERROR);
        }
        if(!array_key_exists($status, ARBatch::CHECK_STATUS_LABLES)){
            throw new GanWuException('不能审核', ErrorCode::ERROR);
        }

        $ts = time();

        $batch->check_status = $status;
        $batch->check_ts = $ts;

        if(!$batch->save()){
            throw new GanWuException('审核出现错误,请重试', ErrorCode::ERROR);
        }

        //审核通过-非作废审核
        if (ARBatch::CHECK_STATUS3 == $status) { //添加生产 leapid 任务
            $this->addLeapidProductJob($batch);
        }

        return ['check_ts' => date('Y-m-d H:i:s')];
    }

    /**
     * @param object $batch 批次信息
     * @info 老大说分开做,三个插入互相不影响(需要先插入mysql,然后 redis 和 bin 已 mysql 做数据源)
     */
    private function addLeapidProductJob($batch)
    {
        Yii::$app->leapidProductQueue->ttr(Yii::$app->params['queue_ttr'])->push(new LeapidProductToMysqlJob([
            'batch_id' => $batch->id,
            'batch_count' => $batch->batch_count,
            ]));
    }

    /**
     * @param $batch
     * @throws GanWuException
     */
    private function addLeapidRedisJob($batch)
    {
        $key = Yii::$app->params['queue_cache_redis'] . $batch->id;
        //查看缓存是否正在刷新或者排队
        if (RedisHelper::getRedis()->exists($key)){
            throw new GanWuException('缓存正在刷新或者排队中', ErrorCode::ERROR);
        }

        //缓存刷新中或已推送到 queue,记录 key,防止任务重复堆积
        RedisHelper::getRedis()->set($key, 1);
        //推送到 queue
        Yii::$app->leapidProductQueue->ttr(Yii::$app->params['queue_ttr'])->push(new LeapidProduceToRedisJob([
            'batch_id' => $batch->id,
            'batch_count' => $batch->batch_count,
        ]));
    }

    /**
     * @param $batch
     * @throws GanWuException
     */
    private function addLeapidBinJob($batch)
    {
        $key = Yii::$app->params['queue_cache_bin'] . $batch->id;
        //查看缓存是否正在刷新或者排队
        if (RedisHelper::getRedis()->exists($key)){
            throw new GanWuException('缓存正在刷新或者排队中', ErrorCode::ERROR);
        }

        //缓存刷新中或已推送到 queue,记录 key,防止任务重复堆积
        RedisHelper::getRedis()->set($key, 1);
        //推送到 queue
        Yii::$app->leapidProductQueue->ttr(Yii::$app->params['queue_ttr'])->push(new LeapidProduceToBinJob([
            'batch_id' => $batch->id,
            'batch_count' => $batch->batch_count,
        ]));
    }

    /**
     * 刷新缓存或 bin
     *
     * @param $id
     * @param $type
     * @throws GanWuException
     */
    public function frushCache($id, $type)
    {
        $batch = ARBatch::find()
            ->where(['id' => $id])
            ->one();

        //mysql完成才能开始 redis
        if (!(ARBatch::CHECK_STATUS4 & $batch->check_status)) {
            throw new GanWuException('mysql数据源正在生产中,不能刷新缓存', ErrorCode::ERROR);
        }
        if (ARBatch::TYPE_CACHE1 == $type){
            $this->addLeapidRedisJob($batch);

        }elseif(ARBatch::TYPE_CACHE2 == $type) {
            $this->addLeapidBinJob($batch);

        } else{
            throw new GanWuException('参数错误', ErrorCode::ERROR);
        }

    }
}