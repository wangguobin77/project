<?php
namespace app\models;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/12/12
 * Time: 上午10:37
 */
use Yii;
class Sn extends BaseModel
{
    /** 批次号审核状态 */
    const CHECK_STATUS1 = 1;  //待审批
    const CHECK_STATUS2 = 2;  //审核不通过
    const CHECK_STATUS3 = 3;  //通过审批

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sn_info';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * 根据工厂id 查询单条记录
     * @param $fid
     * @param $is_delete
     * @return array|false
     */
    public function getFactoryInfoSelectOne($fid,$is_delete)
    {

        $spname = 'sp_factory_info_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$fid."',".$is_delete.")")->queryOne();
        return $res;
    }

    /**
     * 根据厂商id 查询下面所有工厂
     * @param $mid
     * @param $is_delete
     * @return array
     */
    public function getFactoryInfoSelectAll($mid,$is_delete)
    {

        $spname = 'sp_factory_info_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$mid."',".$is_delete.")")->queryAll();
        return $res;
    }

    /**
     * 根据厂商id 查询下面所有工厂与工厂short缩写的关联信息
     * @param $mid
     * @param $is_delete
     * @return array
     */
    public function getFactoryInfoAndShortInfoSelectAll($mid,$is_delete)
    {

        $spname = 'sp_factory_info_and_short_info_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$mid."',".$is_delete.")")->queryAll();
        return $res;
    }

    /**
     * 添加厂商缩写
     * @param $mid
     * @param $short
     * @param $created_ts
     * @param $updated_ts
     * @return bool
     */
    public function addSnManufactureShortInfo($mid,$short,$created_ts,$updated_ts)
    {
        $spname = 'sp_sn_manufacture_short_info_add';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$mid."','"
            .$short."',"
            .$created_ts.","
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 修改厂商的short 缩写
     * @param $id
     * @param $short
     * @param $updated_ts
     * @return bool
     */
    public function upSnManufactureShortInfo($id,$short,$updated_ts)
    {
        $spname = 'sp_sn_manufacture_short_info_up';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id.",'"
            .$short."',"
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 修改厂商的short 缩写
     * @param $id
     * @param $short
     * @param $updated_ts
     * @return bool
     */
    public function delSnManufactureShortInfo($id)
    {
        $spname = 'sp_sn_manufacture_short_info_del';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }else if(intval($ret['@ret']) == 200000){
            show_json(100000,'no data exist');
        }
        return false;
    }


    /**
     * 根据id 查询单条记录
     * @param $id
     * @return array|false
     */
    public function getManufactureShortSelectOne($id)
    {

        $spname = 'sp_sn_manufacture_short_info_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$id.")")->queryOne();
        return $res;
    }


    /*******************工厂short*************/
    /**
     * 添加工厂short
     * @param $factory_id
     * @param $short
     * @param $created_ts
     * @param $updated_ts
     * @return bool
     */
    public function addSnFactoryShortInfo($factory_id,$short,$created_ts,$updated_ts)
    {
        $spname = 'sp_sn_factory_short_info_add';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$factory_id."','"
            .$short."',"
            .$created_ts.","
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 修改工厂short
     * @param $id
     * @param $short
     * @param $updated_ts
     * @return bool
     */
    public function upSnFactoryShortInfo($id,$short,$updated_ts)
    {
        $spname = 'sp_sn_factory_short_info_up';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id.",'"
            .$short."',"
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据工厂short表id 删除
     * @param $id
     * @return bool
     */
    public function delSnFactoryShortInfo($id)
    {
        $spname = 'sp_sn_factory_short_info_del';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据工厂short id 查询单条记录
     * @param $id
     * @return array|false
     */
    public function getFactoryShortSelectOne($id)
    {

        $spname = 'sp_sn_factory_short_info_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$id.")")->queryOne();
        return $res;
    }

    /*****************device type short 相关*************************/

    /**
     * 根据厂商id 获取终端相关short信息
     * @param $mid
     * @return array
     */
    public function getdeviceTypeAndCategoryShortInfoSelectAll($mid)
    {

        $spname = 'sp_sn_device_type_short_and_category_short_info_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$mid."')")->queryAll();
        return $res;
    }

    /**
     * 添加device type short缩写
     * @param $device_type_id
     * @param $short
     * @param $created_ts
     * @param $updated_ts
     * @return bool
     */
    public function addSnDeviceTypeShortInfo($device_type_id,$short,$created_ts,$updated_ts)
    {
        $spname = 'sp_sn_device_type_short_info_add';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$device_type_id."','"
            .$short."',"
            .$created_ts.","
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }


    /**
     * @param $short string 缩写
     * @return bool
     */
    public function getSnDeviceShortExist($short)
    {
        $sql = 'select exists(select id from sn_device_type_short_info where short = "'.$short.'" limit 1) as ob';
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['ob'];
    }

    /**
     * @param $id
     * @param $short
     * @param $updated_ts
     * @return bool
     */
    public function upSnDeviceTypeShortInfo($id,$short,$updated_ts)
    {
        $spname = 'sp_sn_device_type_short_info_up';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id.",'"
            .$short."',"
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * s删除操作
     * @param $id
     * @return bool
     */
    public function delSnDeviceTypeShortInfo($id)
    {
        $spname = 'sp_sn_device_type_short_info_del';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据device type short id 查询单条记录
     * @param $id
     * @return array|false
     */
    public function getDeviceTypeShortSelectOne($id)
    {

        $spname = 'sp_sn_device_type_short_info_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$id.")")->queryOne();
        return $res;
    }

    /*****************************remote short 相关***********************************/
    /**
     * 根据厂商id 获取遥控器相关short信息
     * @param $mid
     * @return array
     */
    public function getRemoteTypeShortInfoSelectAll($mid)
    {

        $spname = 'sp_remote_type_short_info_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$mid."')")->queryAll();
        return $res;
    }

    /**
     * 添加remote short相关
     * @param $remote_type_id
     * @param $short
     * @param $rc_category_short
     * @param $created_ts
     * @param $updated_ts
     * @return bool
     */
    public function addSnRemoteShortInfo($remote_type_id,$short,$rc_category_short,$created_ts,$updated_ts)
    {
        $spname = 'sp_sn_remote_short_info_add';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$remote_type_id."','"
            .$short."','"
            .$rc_category_short."',"
            .$created_ts.","
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * @param $short string 缩写
     * @return bool
     */
    public function getSnRemoteShortExist($short)
    {
        $sql = 'select exists(select id from sn_remote_short_info where short = "'.$short.'" limit 1) as ob';
        $res = Yii::$app->db->createCommand($sql)->queryOne();
        return $res['ob'];
    }

    /**
     * 修改remote type short 单条记录
     * @param $id
     * @param $short
     * @param $updated_ts
     * @return bool
     */
    public function upSnRemoteShortInfo($id,$short,$updated_ts)
    {
        $spname = 'sp_sn_remote_short_info_up';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id.",'"
            .$short."',"
            .$updated_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 删除remote type short info
     * @param $id
     * @return bool
     */
    public function delSnRemoteShortInfo($id)
    {
        $spname = 'sp_sn_remote_short_info_del';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 查询remote type short 单条记录
     * @param $id
     * @return array|false
     */
    public function getSnRemoteShortInfoSelectOne($id)
    {

        $spname = 'sp_sn_remote_short_info_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$id.")")->queryOne();
        return $res;
    }

    /**
     * @param $id
     * @param $short
     * @param $updated_ts
     * @return bool
     */
    public function upSnBatchInfo($id,$check_status,$check_ts)
    {
        $spname = 'sp_sn_batch_info_up';
        Yii::$app->db->createCommand("call ".$spname."("
            .$id.","
            .$check_status.","
            .$check_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据厂商id 查询厂商与short相关信息
     * @param $manufacture_id
     * @return array|false
     */
    public function selectSnManufactureShortOne($manufacture_id)
    {
        $res = Yii::$app->db->createCommand("call sp_sn_manufacture_select_one('"
            . $manufacture_id ."'
            );")->queryOne();
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
     * 根据ID 获取batch 单条记录
     * @param $id
     * @return array|false
     */
    public function getSnBatchInfoSelectOne($id)
    {

        $spname = 'sp_sn_batch_info_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."("
            .$id
            .")")->queryOne();
        return $res;
    }

    /**
     * 获取 厂商与工厂short信息
     * @param $id
     * @return array|false
     */
    public function getSnSFRSelectAll($m_id)
    {

        $spname = 'sp_sn_m_f_r_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$m_id
            ."')")->queryOne();
        return $res;
    }

    /**
     * 根据device type id 获取device short 相关信息
     * @param $did
     * @return array|false
     */
    public function getSnDeviceAndCategoryShortInfo($did)
    {
        $spname = 'sp_sn_device_category_short_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$did
            ."')")->queryOne();
        return $res;
    }

    /**
     * 根据遥控器id 获取remote short相关信息
     * @param $rid
     * @return array|false
     */
    public function getSnRemoteShortInfo($rid)
    {

        $spname = 'sp_sn_remote_short_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."('"
            .$rid
            ."')")->queryOne();
        return $res;
    }

    /**
     * 审核通过需要添加sn相关流水号
     * @param $id
     * @param $m_short
     * @param $c_short
     * @param $d_short
     * @param $batch_no
     * @param $batch_count
     * @return bool
     */
    public function addSnInfo($id,$m_short,$c_short,$d_short,$batch_no,$batch_count)
    {
         $spname = 'sp_sn_info_add';

        Yii::$app->db->createCommand("call ".$spname."('"
            .$id."','"
            .$m_short."','"
            .$c_short."','"
            .$d_short."','"
            .$batch_no."',"
            .$batch_count
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 获取sninfo所有
     * @param $start_number
     * @param $page_size
     * @param $sn
     * @return array
     */
    public function getSnInfoSelectAll($start_number,$page_size,$sn)
    {
        $spname = 'sp_sn_info_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."("
            .$start_number.","
            .$page_size.",'"
            .$sn
            ."')")->queryAll();

        $total = Yii::$app->db->createCommand("select @total")->queryOne();

        return ['data'=>$res,'total'=>$total['@total']];
    }

    /**
     * 根据批次号获取当前下所有sn
     * @param $start_number
     * @param $page_size
     * @param $bid
     * @return array
     */
    public function getSnInfoFromConditionSelectAll($start_number, $page_size, $bid, $check_status, $sn)
    {
        $spname = 'sp_sn_info_from_params_condition_select';
        $res =  Yii::$app->db->createCommand("call ".$spname."("
            . $start_number . ","
            . $page_size . ",'"
            . $bid . "',"
            . $check_status . ",'"
            . $sn
            . "')")->queryAll();

        return $res;
    }


    /**
     * 按条件获取总数
     * @param $start_number
     * @param $page_size
     * @param $bid
     * @param $check_status
     * @param $sn
     * @return array
     */
    public function getSnInfoSelectParamCount($start_number, $page_size, $bid, $check_status, $sn)
    {
        $spname = 'sp_sn_info_from_params_condition_select_count';
        $total =  Yii::$app->db->createCommand("call ".$spname."("
            . $start_number . ","
            . $page_size . ",'"
            . $bid . "',"
            . $check_status . ",'"
            . $sn
            . "')")->queryOne();

        return ['total'=>$total['total']];
    }

    /**
     * 获取总数 snifo
     * @return array|false
     */
    public function getSnInfoSelectCount()
    {
        $spname = 'sp_sn_info_select_count';
        $res =  Yii::$app->db->createCommand("call ".$spname."()")->queryOne();
        return $res;
    }

    /**
     * 根据bid 获取所属厂商名称
     * @return array|false
     */
    public function getSnManufactureNameBid()
    {
        $spname = 'sp_sn_manufacture_info_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."()")->queryAll();
        return $res;
    }

    /**
     * 根据批次号id 获取所有的sn信息
     * @return array|false
     */
    public function getSnInfoFromBid($bid)
    {
        $spname = 'sp_sn_info_from_bid_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."('".$bid."')")->queryAll();
        return $res;
    }


    /**
     * 根据厂商的名字 获取厂商相关的信息
     * @param $m_name
     * @return array
     */
    public function getManufactureFromNameInfo($m_name)
    {
        $spname = 'sp_manufacture_from_mname_select_one';
        $res =  Yii::$app->db->createCommand("call ".$spname."('".$m_name."')")->queryAll();
        return $res;

    }


    /**
     * 根据条件查询batchinfo
     * @param $start_number
     * @param $page_size
     * @param $check_status
     * @param $mid
     * @return array
     */
    public function getBatchInfoFromCondition($start_number,$page_size,$check_status=0,$mid='')
    {
        $spname = 'sp_batch_conditions_select_all';

        $res =  Yii::$app->db->createCommand("call ".$spname."("
            .$start_number.","
            .$page_size.","
            .$check_status.",'"
            .$mid
            ."')")->queryAll();

        return $res;

    }


    /**
     * 基于某批次号下增加sn数量
     * 审核通过需要添加sn相关流水号
     * @param $batch_id 批次号
     * @param $bid_str  sn 号前缀 作为同一批次下的sn号唯一标时
     * @param $m_short
     * @param $c_short
     * @param $d_short
     * @param $batch_no
     * @param $batch_count
     * @return bool
     */
    public function addAgainSnInfo($batch_id,$bid_str,$m_short,$c_short,$d_short,$batch_no,$batch_count,$current_count)
    {
        $spname = 'sp_sn_info_again_add';

        Yii::$app->db->createCommand("call ".$spname."("
            .$batch_id.",'"
            .$bid_str."','"
            .$m_short."','"
            .$c_short."','"
            .$d_short."','"
            .$batch_no."',"
            .$batch_count.","
            .$current_count
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**********************************************************添加用户绑定解绑sn相关的信息 start*****************************************/
    /**
     * 获取sninfo所有
     * @param $start_number
     * @param $page_size
     * @return array
     */
    public function getNewSnInfoSelectAll($start_number,$page_size)
    {
        $spname = 'sp_new_sn_info_select_all';
        $res =  Yii::$app->db->createCommand("call ".$spname."("
            .$start_number.","
            .$page_size
            .")")->queryAll();

        return $res;
    }



    /**
     * 删除device与user的绑定关系
     * @param $openid 用户openid
     * @param $deviceid 终端设备id
     * @return bool
     */
    public function unbindDeviceToOpenid($openid,$sn,$deviceid)
    {
        $spname = 'sp_unbind_device_to_openid_del';
        Yii::$app->db->createCommand("call ".$spname."('"
            . $openid . "','"
            . $sn . "','"
            . $deviceid . "',"
            ."@ret)")->query();

        $res = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($res['@ret']) == 1){
            return true;
        }
        return false;
    }


    /**
     * 添加device与user的绑定关系
     * @param $openid
     * @param $sn
     * @param $sn_id
     * @param $deviceid
     * @param $accessCode
     * @param $up_sninfo_bind_time
     * @return bool
     */
    public function addDeviceUserSp($openid,$sn,$sn_id,$deviceid,$accessCode,$up_sninfo_bind_time)
    {
        $create_time = time();
        $spname = 'sp_by_device_bind_openid_add';

        Yii::$app->db->createCommand("call ".$spname."('"
            . $openid . "','"
            . $sn . "',"
            . $sn_id . ",'"
            . $deviceid . "','"
            . $accessCode . "',"
            . $up_sninfo_bind_time . ","
            . $create_time . ","
            ."@ret)")->query();

        $res = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($res['@ret']) == 200100){
            show_json(100101,'The device has been bound');//该设备已被绑定 不能重复绑定
        }else if(intval($res['@ret']) == 1){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 根据sn 查询sn_info表信息是否存在
     * @param $sn
     * @return array|bool|false
     */
    public function getBySnToSninfoSelectOne($sn)
    {
        $spname = 'sp_by_sn_to_sninfo_select_one';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$sn."');")->queryOne();

        if($res){
            return $res;
        }
        return false;
    }

    /**
     * 跟据sn 查询 device 表是否有信息存在
     * @param $sn
     * @return array|bool
     */
    public function getBySnToDeviceInfo($sn_id)
    {
        $spname = 'sp_by_sn_to_device_one_select';
        $res = Yii::$app->db->createCommand("call ".$spname."(".$sn_id.");")->queryOne();

        if($res){
            return $res;
        }
        return false;
    }


    /**
     * 根据uuid 判断uuid是否存在
     * @param $uuid
     * @return array|bool|false
     */
    public function getBySpPstUuidInfo($uuid)
    {
        $spname = 'sp_sp_pst_uuid_select_one';
        $res = Yii::$app->db_sp_pst->createCommand("call ".$spname."('".$uuid."');")->queryOne();

        if($res){
            return $res;
        }
        return false;
    }

    /**********************************************************添加用户绑定解绑sn相关的信息 end*****************************************/

    /**
     * @param $batch_id integer 批次id
     * @param $new_status integer 改动的状态
     */
    public function checkStatus($batch_id, $new_status)
    {
        $spname = 'sp_batch_check_status';
        $res = Yii::$app->db->createCommand("call ".$spname."({$batch_id}, {$new_status});")->queryOne();
        return $res['_return_code'];
    }

    /**
     * @param $batch_id integer 批次id
     * @param $new_status integer 改动的状态
     */
    public function addBatchSn($batch_id, $count)
    {
        $spname = 'sp_add_batch_sn_two';
        $res = Yii::$app->db->createCommand("call ".$spname."({$batch_id}, {$count});")->queryOne();
        return $res['_return_code'];
    }

    /**
     * 获取sn列表
     */
    public function getSnList($start_number, $page_size, $bid, $status, $sn){
        $spname = 'sp_get_sn_list';
        $res['data'] =  Yii::$app->db->createCommand("call ".$spname."("
            .$start_number.","
            .$page_size.","
            .$bid.","
            .$status.",'"
            .$sn."',"
            ."@totalCount)")->queryAll();

        $totalCount = Yii::$app->db->createCommand("select @totalCount")->queryOne();
        $res['totalCount'] = $totalCount['@totalCount'];
        return $res;
    }
}