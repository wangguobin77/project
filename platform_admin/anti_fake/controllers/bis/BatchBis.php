<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-26
 * Time: 10:44
 */

/**
 * 批次业务层
 */
namespace app\controllers\bis;

use Yii;
use app\models\Batch;
use common\helpers\Exception;

class BatchBis
{
    /**
     * csv导入批次
     * @param $category_id
     * @param $file
     * @throws Exception
     */
    public function load($category_id, $file){
        $file = fopen($file, 'r');
        $num = 0; //数量
        $sn_str = '('; //sn
        $sns = []; //sn

        while (!feof($file)) {
            $data = fgetcsv($file);
            if(!empty($data)){
                $num++;
                $sn_str .= '"' . $data[0] . '",';
                $sns[] = $data[0];
            }
        }
        $sn_str = substr($sn_str, 0, -1) . ')';
        fclose($file);

        // 查询该批sn在数据库中是否有重复的
        $sql = 'select sn from `sn` where sn in' . $sn_str . 'limit 1';
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        if(!empty($res)){
            throw new Exception("sn:{$res['sn']}已存在，不能重复录入!", 100000);
        }

        //批次号
        $batch_num = $this->getBatchNum($category_id);

        $tran = Yii::$app->db->beginTransaction();

        try{
            $ts = time();
            //插入批次
            $sql = "insert into `batch`(category_id, batch_num, quantity, status, create_ts) VALUES ('{$category_id}','{$batch_num}', {$num},".Batch::STATUS1.",{$ts})";
            if(Yii::$app->db->createCommand($sql)->execute()){
                $batch_id = Yii::$app->db->getLastInsertID();

                //组装sn插入sql语句
                $val = '';
                foreach ($sns as $k => $v){
                    $batch_serial = $k+1;
                    $val .= "('{$v}', '". str_rand(32) ."','{$batch_id}',{$batch_serial}),";
                }
                $val = substr($val, 0, -1);

                $sql = 'insert into `sn` (`sn`,`key`,`batch_id`,`batch_serial`) values '.$val;
                Yii::$app->db->createCommand($sql)->execute();

                $tran->commit();
            }else{
                $tran->rollBack();
                throw new Exception('批量导入失败', 100000);
            }

        }catch (\yii\db\Exception $e){
            $tran->rollBack();
            throw new Exception('批量导入失败', 100000);

        }



    }

    /**
     * 生产批次号
     * @return string
     * @throws Exception
     */
    private function getBatchNum($category_id) {
        $batch_num = substr(date('Y', time()), 2) . $this->IntToChr(date('m', time()));
        $num = '00';
        $batch = Batch::find()->select('batch_num')->where(['category_id'=>$category_id])->orderBy('id desc')->asArray()->one();
        if ($batch) {
            if (substr($batch_num,0,3) == $batch_num) {
                $num = substr($batch['batch_num'],3) + 1;
                if ($num > 99) {
                    throw new Exception('Apply up to 100 times a month.', 100000);
                }
                $num = str_pad($num,2,"0",STR_PAD_LEFT);
            }
        }
        $batch_num .= $num;

        return $batch_num;
    }

    private function IntToChr($index, $start = 64) {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= $this->IntToChr(floor($index / 26)-1);
        }
        return $str . chr($index % 26 + $start);
    }
}