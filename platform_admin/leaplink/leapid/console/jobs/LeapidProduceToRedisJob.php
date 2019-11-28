<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-11-04
 * Time: 15:22
 */

namespace app\console\jobs;

use Yii;
use app\models\batch\ARBatch;
use app\models\batch\ARLeapid;
use common\helpers\RedisHelper;
use yii\db\Exception;

/**
 * redis 生产任务
 * 使用 mysql 生成的数据保存到 redis 中
 *
 * @package app\console\jobs
 */
class LeapidProduceToRedisJob extends Job
{
    public $limit = 2000; //为避免一次查询太多导致内存占用,每次查出指定条数
    public $leap_key = 'datas:leapid:%s'; //存 redis hash 的 key 格式,用 id 替换%s

    public function execute($queue)
    {
        echo "开始运行redis批次同步任务: 批次 id ==== ". $this->batch_id . PHP_EOL;
        try {
            $pages = ceil($this->batch_count/$this->limit);
            for ($p = 0; $p < $pages; $p++) {
                $leapid_list = ARLeapid::find()
                    ->where(['batch_id' => $this->batch_id])
                    ->orderBy('id asc')
                    ->offset($this->limit*$p)
                    ->limit($this->limit)
                    ->asArray()
                    ->all();

                if(!$leapid_list){
                    throw new \Exception('请先在 完成 mysql 数据生产工作', 100001);
                }

                $this->dataToRedis($leapid_list);
            }

            //修改审核状态
            $batch = ARBatch::findOne(['id' => $this->batch_id]);
            if(!($batch->check_status & ARBatch::CHECK_STATUS5)){
                $batch->updateCounters(['check_status' => ARBatch::CHECK_STATUS5]);
            }

            $this->succLog(
                self::TYPE_REDIS,
                "batch_id::{$this->batch_id}",
                "success"
            );

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

        $key = Yii::$app->params['queue_cache_redis'] . $this->batch_id;
        RedisHelper::getRedis()->del($key);

        echo "任务完成::redis批次同步任务: 批次 id ==== ". $this->batch_id . PHP_EOL;
    }

    public function dataToRedis($leapid_list)
    {
        $redis = RedisHelper::getRedis();

        foreach ($leapid_list as $leapid) {
            $command = [];
            $key = sprintf($this->leap_key, $leapid['id']);
            $command[] = $key;
            foreach ($leapid as $field => $item) {
                $command[] = $field;
                $command[] = $item;
                $redis->executeCommand('hmset', $command);
            }
        }
    }
}