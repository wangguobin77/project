<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-11-01
 * Time: 12:46
 */

namespace app\console\jobs;


use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * 队列的任务的基础类
 *
 * @author qijun jiang <jqjtqq@163.com>
 */
class Job extends BaseObject implements JobInterface
{
    public $batch_id;  //批次 id
    public $batch_count;    //该批次生产 leapid 数量
    public $key_update;    //该批次的 key_update

    const TYPE_MYSQL = 1;
    const TYPE_REDIS = 2;
    const TYPE_BIN = 3;

    const TYPE_LABELS = [
        self::TYPE_MYSQL => 'MYSQL',
        self::TYPE_REDIS => 'REDIS',
        self::TYPE_BIN => 'BIN'
    ];

    public $errorFile ='console/runtime/jobs/jobError_%s.log'; //这里最好使用绝对路径,没搞懂相对路径的工作目录是哪里
    public $successFile ='console/runtime/jobs/jobSuccess_%s.log'; //这里最好使用绝对路径,没搞懂相对路径的工作目录是哪里

    public function execute($queue)
    {
        // TODO: 在子类中重写或者做一些公共任务
    }

    //工作成功日志
    protected function succLog($type, $info, $result, $dbError = '', $debug = '')
    {
        file_put_contents(
            sprintf(ROOT_PATH . $this->successFile, date('Ymd')),
            "============================== ".self::TYPE_LABELS[$type]." JOB ============================== ".PHP_EOL.
            "time: " .date("Y-m-d H:i:s").PHP_EOL.
            "info: {$info}".PHP_EOL.
            "result: {$result}" .PHP_EOL.
            "dbError: {$dbError}". PHP_EOL.
            "debug: {$debug}". PHP_EOL.
            "============================== ".self::TYPE_LABELS[$type]." JOB END =============================".PHP_EOL.PHP_EOL,
            FILE_APPEND
        );
    }

    //工作失败日志
    protected function failLog($type, $info, $result, $dbError = '', $debug = '')
    {
        file_put_contents(
            sprintf(ROOT_PATH . $this->errorFile, date('Ymd')) ,
            "============================== ".self::TYPE_LABELS[$type]." JOB ============================== ".PHP_EOL.
            "time: " .date("Y-m-d H:i:s").PHP_EOL.
            "info: {$info}".PHP_EOL.
            "result: {$result}" .PHP_EOL.
            "dbError: {$dbError}". PHP_EOL.
            "debug: {$debug}". PHP_EOL.
            "============================== ".self::TYPE_LABELS[$type]." JOB END =============================".PHP_EOL.PHP_EOL,
            FILE_APPEND
        );
    }
}