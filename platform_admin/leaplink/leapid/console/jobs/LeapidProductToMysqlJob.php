<?php
/**
 * Created by PhpStorm.
 * Author: qijun jiang <jqjtqq@163.com>
 * Date: 2019-11-01
 * Time: 13:37
 */

namespace app\console\jobs;

use common\helpers\RedisHelper;
use Yii;
use app\models\batch\ARBatch;
use app\models\batch\ARLeapid;
use yii\db\Exception;

/**
 * 一个批次的生产任务
 * 插入数据库
 * 为了保持数据一致,先插入数据库
 *
 * @package app\console\jobs
 */
class LeapidProductToMysqlJob extends Job
{
    public function execute($queue)
    {
        echo "开始运行mysql批次创建任务: 批次 id ==== ". $this->batch_id . PHP_EOL;
        try {
            $leapid = new ARLeapid();
            $ret = $leapid->produceLeapid($this->batch_id, $this->batch_count);

            if($ret['@return_code'] == "1") {
                //修改审核状态
                $batch = ARBatch::findOne(['id' => $this->batch_id]);
                $batch->updateCounters(['check_status' => ARBatch::CHECK_STATUS4]);

                $this->succLog(
                    self::TYPE_MYSQL,
                    "batch_id::{$this->batch_id}",
                    "success  return_message::{$ret['@return_message']}"
                    );

                //完成 mysql 任务后开启将redis 和 bin 任务推送到queue
                //缓存刷新中或已推送到 queue,记录 key,防止任务重复堆积
                RedisHelper::getRedis()->set(Yii::$app->params['queue_cache_redis'] . $this->batch_id, 1);
                RedisHelper::getRedis()->set(Yii::$app->params['queue_cache_bin'] . $this->batch_id, 1);
                Yii::$app->leapidProductQueue->ttr(Yii::$app->params['queue_ttr'])->push(new LeapidProduceToRedisJob([
                    'batch_id' => $this->batch_id,
                    'batch_count' => $this->batch_count,
                ]));
                Yii::$app->leapidProductQueue->ttr(Yii::$app->params['queue_ttr'])->push(new LeapidProduceToBinJob([
                    'batch_id' => $this->batch_id,
                    'batch_count' => $this->batch_count,
                ]));

            } else {
                $this->failLog(
                    self::TYPE_MYSQL,
                    "batch_id::{$this->batch_id}",
                    "fail",
                    "code::{$ret['@return_code']}, message::{$ret['@return_message']}"
                    );
            }
        } catch (Exception $e) {
            $this->failLog(
                self::TYPE_MYSQL,
                "batch_id::{$this->batch_id}",
                "fail",
                "code::{$e->getCode()}, message::{$e->getMessage()}"
            );
        } catch (\Exception $e) {
            $this->failLog(
                self::TYPE_MYSQL,
                "batch_id::{$this->batch_id}",
                "fail",
                "",
                "code::{$e->getCode()}, message::{$e->getMessage()}"
            );
        }

        echo "任务完成::mysql批次创建任务: 批次 id ==== ". $this->batch_id . PHP_EOL;
    }
}