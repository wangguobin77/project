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
class LeapidProduceToBinJob extends Job
{
    public $limit = 2000; //为避免一次查询太多导致内存占用,每次查出指定条数
    public $bin_root = '/leapid/%s/'; //bin 文件路径
    public $bin = 'bin';
    public $bin_path;
    public $zip_path;

    public function execute($queue)
    {
        echo "开始运行bin批次同步任务: 批次 id ==== ". $this->batch_id . PHP_EOL;
        try {
            $batch = ARBatch::findOne(['id' => $this->batch_id]);

            $this->bin_root = Yii::$app->params['bin_root'].sprintf($this->bin_root, $batch->batch_date . str_pad($batch->batch_no, 2, 0, STR_PAD_LEFT));
            $this->bin_path = $this->bin_root . $this->bin;
            $this->zip_path = $this->bin_root;

            if(!is_dir($this->bin_root)){
                mkdir($this->bin_root, 0777, true);
            }
            if(!is_dir($this->bin_path)){
                mkdir($this->bin_path, 0777, true);
            }
            if(!is_dir($this->zip_path)){
                mkdir($this->zip_path, 0777, true);
            }


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

                $this->dataToBin($leapid_list, $batch->chip_type, $batch->key_update);
            }

            exec("cd  {$this->zip_path} && zip -q -r {$batch->batch_date}".str_pad($batch->batch_no, 2, 0, STR_PAD_LEFT).".zip ./bin");

            //打包
//            $zip = new \ZipArchive();
//            if($zip->open($this->zip_path. $this->batch_id . '.zip', \ZipArchive::OVERWRITE)=== TRUE){
//                $this->addFileToZip($this->bin_path . '/', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
//                $zip->close(); //关闭处理的zip文件
//            } else {
//                throw new \Exception('无法打开文件，或者文件创建失败', -10);
//            }


            //修改审核状态
            $batch = ARBatch::findOne(['id' => $this->batch_id]);
            if(!($batch->check_status & ARBatch::CHECK_STATUS6)){
                $batch->updateCounters(['check_status' => ARBatch::CHECK_STATUS6]);
            }

            $this->succLog(
                self::TYPE_BIN,
                "batch_id::{$this->batch_id}",
                "success"
            );

        } catch (Exception $e) {
            $this->failLog(
                self::TYPE_BIN,
                "batch_id::{$this->batch_id}",
                "fail",
                "code::{$e->getCode()}, message::{$e->getMessage()}"
            );
        } catch (\Exception $e) {
            $this->failLog(
                self::TYPE_BIN,
                "batch_id::{$this->batch_id}",
                "fail",
                "",
                "code::{$e->getCode()}, message::{$e->getMessage()}"
            );
        }

        //删除 key
        $key = Yii::$app->params['queue_cache_bin'] . $this->batch_id;
        RedisHelper::getRedis()->del($key);

        echo "任务完成::bin批次同步任务: 批次 id ==== ". $this->batch_id . PHP_EOL;
    }

    public function dataToBin($leapid_list, $chip_type, $key_update)
    {
        foreach ($leapid_list as $leapid) {
            $this->createBinContent($chip_type, $key_update, $leapid);
        }
    }

    public function createBinContent($chip_type, $key_update, $leapid)
    {
        $bin_content = pack("H*", bin2hex($chip_type) . sprintf("%08X",$leapid['id']) . $leapid['reserved'] . $key_update . $leapid['key_main'].$leapid['key_ext']);

        $filename = $this->bin_path. DIRECTORY_SEPARATOR . $leapid['id'] .".bin";
        $fp = fopen($filename, 'wb');
        fwrite($fp, $bin_content);
        fclose($fp);
    }

    function addFileToZip($path,$zip){
        $handler=opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){
            if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip);
                }else{ //将文件加入zip对象
                    $zip->addFile($path."/".$filename);
                }
            }
        }
        @closedir($path);
    }
}