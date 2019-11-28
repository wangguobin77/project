<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/16
 * Time: 下午2:00
 */

namespace app\models;



use Yii;
class Batch extends BaseModel
{
    /** @var int 审核状态 */
    const CHECK_STATUS_DEAL = 1; //待审核
    const CHECK_STATUS_OK = 2; //审核通过
    const CHECK_STATUS_DELETE = 3; //废弃

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sn_batch_info';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * 厂商申请添加批次号
     * @param $mid
     * @param $h_id
     * @param $h_type
     * @param int $is_delete
     * @param $batch_year
     * @param $batch_no
     * @param $batch_count
     * @param $check_status
     * @param $check_ts
     * @param $created_ts
     * @return bool
     */
    public function addSnBatchInfo($m_id,$h_id,$h_type,$is_delete=0,$batch_year,$batch_no,$batch_count,$upc_code,$check_status,$check_ts,$created_ts)
    {
        $spname = 'sp_sn_batch_info_add';

        Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id."','"
            .$h_id."',"
            .$h_type.","
            .$is_delete.",'"
            .$batch_year."',"
            .$batch_no.","
            .$batch_count.",'"
            .$upc_code."',"
            .$check_status.","
            .$check_ts.","
            .$created_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /*
     * 添加批次
     */
    public function addSnBatch($data)
    {
        $spname = 'sp_add_sn_batch_info';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$data['mid']."','"
            .$data['h_id']."',"
            .$data['h_type'].",'"
            .$data['batch_year']."',"
            .$data['batch_no'].","
            .$data['batch_count'].",'"
            .$data['upc_code']."','"
            .$data['comment']."'"
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) === 1){
            return true;
        }
        return false;
    }


    /**
     * 根据条件逆序查找一条
     * @param $m_id
     * @param $h_id
     * @param $h_type
     * @return array|false
     */
    public function getBatchInfoDescSelectOne($m_id,$h_id,$h_type, $batch_year)
    {

        $spname = 'sp_sn_batch_info_desc_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id."','"
            .$h_id."',"
            .$h_type.",'"
            .$batch_year
            ."')")->queryOne();
        return $res;
    }

    /**
     * 根据厂商id 查询所有的申请的批次号列表
     * @param $m_id
     * @param $is_delete
     * @return array|false
     */
    public function getSnManufactureBatchInfoSelectAll($start_number,$page_size,$m_id,$is_delete)
    {

        $spname = 'sp_sn_manufacture_batch_info_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."("
            .$start_number.','
            .$page_size.",'"
            .$m_id."',"
            .$is_delete
            .")")->queryAll();

        return $res;
    }


    /**
     * 获取厂商下面所有的device type
     * @param $m_id
     * @return array
     */
    public function getSnDeviceTypeSelectAll($m_id)
    {

        $spname = 'sp_sn_device_type_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id
            ."')")->queryAll();
        return $res;
    }


    /**
     * 根据厂商id 获取所有的遥控器
     * @param $m_id
     * @return array
     */
    public function getSnRemoteTypeSelectAll($m_id)
    {

        $spname = 'sp_sn_remote_type_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id
            ."')")->queryAll();
        return $res;
    }

    /**
     * 查询大类所有信息
     * @return array
     */
    public function getCategoryInfoSelectAll()
    {

        $spname = 'sp_category_info_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."()")->queryAll();
        return $res;
    }


    /**
     * 获取device type 所有信息 此信息跟父表关联 父类不存在数据不会出现
     * @param $m_id
     * @return array
     */
    public function getDeviceTypeSelectAll($m_id,$is_delete=0)
    {

        $spname = 'sp_device_type_select_by_manufacture_id';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id
            ."',".$is_delete.")")->queryAll();
        return $res;
    }

    /**
     * 获取rc type 所有信息 此信息跟父表关联 父类不存在数据不会出现
     * @param $m_id
     * @return array
     */
    public function getRemoteTypeSelectAll($m_id)
    {

        $spname = 'sp_remote_type_select_by_manufacture_id';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id
            ."')")->queryAll();
        return $res;
    }

    public function getDeviceTypeByManufacture($manufacture_id)
    {
        $sql = 'select d.id,d.name from device_type d ';
        $sql .= ' left join category c on c.id=d.category_id ';
        $sql .= ' where d.manufacture_id=' . $manufacture_id;
        $sql .= ' and c.is_deleted = '. Category::UN_DELETED;
        $sql .= '';
        Yii::$app->db->createCommand($sql)->queryAll();
    }

    /**
     * 根据登陆厂商id 获取所有工厂
     * @param $m_id
     * @return array
     */
    public function getFactoryInfoSelectAll($m_id)
    {

        $spname = 'sp_factory_info_select_by_manufacture_id';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id
            ."')")->queryAll();
        return $res;
    }

    /**
     * 获取指定厂商的批次列表
     * @param $mid string 厂商id
     */
    public function getBatchInfoList($start_number,$page_size,$mid){
        $spname = 'sp_get_batch_info_list';
        $res['data'] =  Yii::$app->db->createCommand("call ".$spname."("
            .$start_number.","
            .$page_size.",'"
            .$mid."',"
            ."@totalCount)")->queryAll();

        $totalCount = Yii::$app->db->createCommand("select @totalCount")->queryOne();
        $res['totalCount'] = $totalCount['@totalCount'];
        return $res;
    }

}