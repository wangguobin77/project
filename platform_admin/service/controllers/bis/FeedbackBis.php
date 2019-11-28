<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-27
 * Time: 14:14
 */
namespace app\controllers\bis;


use app\models\Feedback;
use common\helpers\Exception;

class FeedbackBis
{
    /**
     * 获取符合条件的列表数量
     * @param array $where
     * @return integer
     */
    public function getCountFeedback($where = []){
        $total = Feedback::find()->count();
        return $total;
    }

    /*
     * 获取分页数据
     */
    public function getData($pages){
        $data = Feedback::find()->alias('f')
            ->select('f.*,d.phone,d.email,d.logistics,d.trackingnumber,d.productmodel,d.sn,d.purchasedate')
            ->leftJoin('feedback_data as d', 'd.feedback_data_id=f.feedback_data_id')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return $data;
    }

    public function getByFeedbackId($id){
        $data = Feedback::find()->alias('f')
            ->select('f.id,f.company,f.account,f.created_at,f.contact_status,f.contact_status_time,d.*')
            ->leftJoin('feedback_data as d', 'd.feedback_data_id=f.feedback_data_id')
            ->where(['f.id' => $id])
            ->asArray()
            ->One();
        return $data;
    }

    /**
     * @param integer $id 主表id
     * @param integer $new_status 将要更新为的状态
     * @throws Exception
     */
    public static function updateStatus($id, $new_status){
        $data = Feedback::findOne(['id' => $id]);

        if(!$data || $data->contact_status !== Feedback::STATUS_DEAL){
            throw new Exception('只有待沟通状态才能操作', 900030);
        }

        $data->contact_status = $new_status;
        $data->contact_status_time = time();
        if($data->save()){
            return true;
        }else{
            return false;
        }
    }
}