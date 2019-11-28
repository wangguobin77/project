<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/11/6
 * Time: 上午11:51
 */

namespace app\models\keymap;

use Yii;

class Keymap extends \yii\db\ActiveRecord
{
    /**
     * 调用终端大类
     */
    public function getSelectDeviceCategory()
    {
        $spname = 'sp_category_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('','','','0')")->queryAll();
        return $res;
    }

    /**
     * 获取所有command命令
     */
    public function getSelectCommandAll()
    {
        /*$spname = 'sp_km_command_select_all';*/
        $spname = 'sp_km_command_select_all_new';
        $res = Yii::$app->db->createCommand("call ".$spname."('','','')")->queryAll();
        return $res;
    }

    /**
     * 获取rc 所支持的键盘操作类型 如：按键 滚轮 摇杆等
     * @param $remote_type_id
     * @param int $type_group
     * @return array
     */
    public function getSelectRcEventType($remote_type_id,$type_group=1)
    {
        $spname = 'sp_remote_keyset_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$remote_type_id."','".$type_group."')")->queryAll();
        return $res;
    }

    /**
     * 获取rc 的所有按键或者摇杆等
     * @param $remote_type_id
     * @return array
     */
    public function getSelectRcAllKey($remote_type_id)
    {
        $spname = 'sp_remote_keyset_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$remote_type_id."','')")->queryAll();
        return $res;
    }

    /**
     * 获取获取所有的键下面的事件
     * @param $start_number 分页
     * @param $page_size
     * @return array
     */
    public function getSelectKeycodeAll($start_number='',$page_size='')
    {
        $spname = 'sp_km_keycode_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$start_number."','".$page_size."')")->queryAll();
        return $res;
    }

    /**
     * @param $start_number 分页
     * @param $page_size
     * @return array
     */
    public function getSelectJudgeTypeAll($start_number='',$page_size='')
    {
        $spname = 'sp_km_condition_judge_type_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('".$start_number."','".$page_size."')")->queryAll();
        return $res;
    }

    /**
     * 根据category_id 获取获取所有对等条件
     * @param $start_number 分页
     * @param $page_size
     * @param $category_id 大类id
     * @return array
     */
    public function getSelectConditionTypeAll($start_number='',$page_size='',$category_id)
    {
        $spname = 'sp_km_condition_type_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$start_number."','"
            .$page_size."','"
            .$category_id."')")->queryAll();
        return $res;
    }

    /**
     * 获取 condition value
     * @param $start_number 分页
     * @param $page_size
     * @return array
     */
    public function getSelectConditionValueAll($start_number='',$page_size='')
    {
        $spname = 'sp_km_condition_value_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$start_number."','"
            .$page_size."')")->queryAll();
        return $res;
    }

    /**
     * 获取 rc 的偏移量值
     * @param $start_number 分页
     * @param $page_size
     * @param $remote_type_id
     * @return array
     */
    public function getSelectRemoteAnalogAll($start_number='',$page_size='',$remote_type_id)
    {
        $spname = 'sp_remote_analog_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$start_number."','"
            .$page_size."','"
            .$remote_type_id."')")->queryAll();
        return $res;
    }

    /**
     * 根据条件参数获取当前keymap版本相关信息 不存在则添加版本 存在直接返回
     * @param $keymap_id
     * @param $remote_type_id
     * @param $category_id
     * @param $device_type_id
     * @param $is_offical
     * @param $version
     * @return bool
     */
   /* public function addKmKeymap($keymap_id,$remote_type_id,$category_id,$device_type_id,$is_offical,$version,$keymap_name)
    {
        $spname = 'sp_km_keymap_add';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$keymap_id."','"
            .$remote_type_id."','"
            .$category_id."','"
            .$device_type_id."','"
            .$is_offical."','"
            .$version."','"
            .$keymap_name."',@ret)")->query();

        //$res = Yii::$app->db->createCommand("select @ret")->queryOne();

        $ret = yii::$app->db->createCommand("select @ret,@id_,@ver")->queryAll();

        if(!array_key_exists($ret[0]['@ret'],$ret[0])){
            return ['id'=>$ret[0]['@id_'],'version'=>$ret[0]['@ver'],'status'=>0];
        }else{
            //var_log(['code'=>$ret['@ret'],'status'=>1],'addkeymap');
            return ['code'=>$ret[0]['@ret'],'status'=>1];
        }

    }*/
    public function addKmKeymap($keymap_id,$remote_type_id,$category_id,$device_type_id,$is_offical,$version,$keymap_name,$manufacture_id,$userid)
    {
        $spname = 'sp_km_keymap_add_new';

        $ret = Yii::$app->db->createCommand("call ".$spname."('"
            .$keymap_id."','"
            .$remote_type_id."','"
            .$category_id."','"
            .$device_type_id."','"
            .$is_offical."','"
            .$version."','"
            .$keymap_name."','"
            .$manufacture_id."','"
            .$userid."')")->queryOne();

        if($ret){
            return $ret;
        }
        return false;
    }

    /**
     * 获取 某个设备版本下的完整keymap配置
     * @param $start_number 分页
     * @param $page_size
     * @param $km_id
     * @return array
     */
    public function getSelectKeymapDataAll($start_number='',$page_size='',$km_id)
    {
        $spname = 'sp_km_keymap_data_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$start_number."','"
            .$page_size."','"
            .$km_id."')")->queryAll();
        return $res;
    }

    /**
     * 根据条件参数获取当前keymap版本相关信息 不存在则添加版本 存在直接返回
     *  @params id varchar(32) 主键id，程序生成的guid
     *  @params remote_type_id varchar(32) 遥控器系列的id
     * @param $category_id 大类id
     *  @params command varchar(32) 命令
     *  @params km_data text 命令详情，json数据
     *  @params km_id varchar(32) keymap的id
     *  @params km_name varchar(32) name
     *  @params ret int 返回值
     */
    public function addKmKeymapData($id,$remote_type_id,$category_id,$command,$km_data,$km_id,$km_name)
    {
        $spname = 'sp_km_keymap_data_add';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$id."','"
            .$remote_type_id."','"
            .$category_id."','"
            .$command."','"
            .$km_data."','"
            .$km_id."','"
            .$km_name."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     *
     *0.获取所有的遥控器系列
     *  @params start_number  从第几条开始取
     *  @params page_size 取几条
     *  @params manufacture_id_val 厂商的id（如果需要取到某厂商名下的remote type许传递厂商id，否则传空）
     *  @params name_en_val 模糊查询name_en字段时要用到的值，不用传空
     *  @params is_deleted_val 是否删除 值为字符串1，0或者空
     *  添加keymap的时候前三个参数传空，is_deleted_val传‘0’,即只去没有被删除的category。
     *  例如：sp_remote_type_select_all('','','','','0')
     */
/*    public function getSelectRemoteTypeAll($start_number,$page_size,$manufacture_id_val,$name_en_val,$is_deleted_val)
    {
        $spname = 'sp_remote_type_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$start_number."','"
            .$page_size."','"
            .$manufacture_id_val."','"
            .$name_en_val."','"
            .$is_deleted_val."')")->queryAll();
        return $res;
    }*/
    /**
     * @param int $offset 从第几条开始取
     * @param int $limit 每次取多少条
     * @param int $state 0研发中|1线上|2下架|-1全部
     * @param int $delete 0未删除|1已删除|-1全部
     * @param string $manufacture_id 厂商(为空不筛选)
     * @param string $keyword 关键词(名字或者应为名字模糊匹配，为空不匹配)
     * @return array
     */
    public function getSelectRemoteTypeAll($offset=0, $limit=0, $state=-1, $delete=0, $manufacture_id='', $keyword='')
    {
        $spname = 'sp_remote_type_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$offset."','"
            .$limit."','"
            .$state."','"
            .$delete."','"
            .$manufacture_id."','"
            .$keyword."')")->queryAll();
        return $res;
    }

    //2018-12-26
    public function getSelectRemoteTypeAllNew($offset=0, $limit=0, $state=-1, $delete=0, $manufacture_id='', $keyword='')
    {
       /* $spname = 'sp_remote_type_select_all_new';*/
        $spname = 'sp_remote_type_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$offset."','"
            .$limit."','"
            .$state."','"
            .$delete."','"
            .$manufacture_id."','"
            .$keyword."')")->queryAll();
        return $res;
    }


    /**
     *
     * 根据keymap_data_id 查询单条配置信息
     */
    public function getKeymapDataSelectOne($km_data_id)
    {
       /* $spname = 'sp_km_keymap_data_select_one';*/
        $spname = 'sp_km_keymap_data_select_one_new';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$km_data_id.
           "')")->queryOne();
        return $res;
    }

    /**
     * 删除keymap_data。。
     * @param $km_data_id
     * @return array
     */
    public function setDelKeymapData($km_data_id)
    {
        $spname = 'sp_km_keymap_data_delete';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$km_data_id."',".
             "@ret)")->query();
        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();


        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     *16.修改keymap_data。。
     *  @params id varchar(32) 要修改的命令id
     *  @params remote_type_id varchar(32) 遥控器系列的id
     *  @params category_id varchar(32) device大类的id
     *  @params command varchar(32) 命令
     *  @params km_data text 命令详情，json数据
     *  @params km_id varchar(32) keymap的id
     *  @params km_name varchar(32) name
     *  @params ret int 返回值
     *  添加keymap的时候两个参数都传空
     */
    public function setUpKeymapDataEdit($id,$remote_type_id,$category_id,$command,$km_data,$km_id,$km_name)
    {
        $spname = 'sp_km_keymap_data_edit';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$id."','"
            .$remote_type_id."','"
            .$category_id."','"
            .$command."','"
            .$km_data."','"
            .$km_id."','"
            .$km_name."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }


    /**
     * 获取keymap全部数据 但每个版本配置只留最新的一条
     * @return array
     *
     */
    public function getKeymapSelectsAll()
    {
        $spname = 'sp_km_keymap_selects_all';
        $res = Yii::$app->db->createCommand("call ".$spname."()")->queryAll();
        return $res;
    }

    /**
     * 根据keycode 表的key值获取对应的parent与type
     */
    public function getKeycodeSelectByKeyOne($key)
    {
        $spname = 'sp_km_keycode_select_by_key';
        $ret = Yii::$app->db->createCommand("call " . $spname . "('"
            . $key . "')")->queryOne();


        if ($ret) {
            return $ret;
        }
        return false;
    }

    /**
     * 根据keymapid 更新版本状态
     * @param $keymap_id
     * @return bool
     */
    public function setUpKeymapRelease($keymap_id)
    {
        $spname = 'sp_km_keymap_release';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$keymap_id."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据id 查询keymap表的对应数据
     *
     */
    public function getKeymapSelectOne($keymap_id)
    {
        /*$spname = 'sp_km_keymap_select_one';*/
        $spname = 'sp_km_keymap_select_one_new';
        $ret = Yii::$app->db->createCommand("call " . $spname . "('"
            . $keymap_id . "')")->queryOne();

        if ($ret) {
            return $ret;
        }
        return false;
    }


    /**
     * 根据category_id remote_type_id 获取keymap正式版本的最新一条信息 做copy使用
     *
     */
    public function getNewRKeymapInfoOne($category_id,$remote_type_id)
    {
        $spname = 'sp_km_keymap_r_info_select_one';
        $ret = Yii::$app->db->createCommand("call " . $spname . "('"
            . $remote_type_id . "','"
             . $category_id .
            "')")->queryOne();

        if ($ret) {
            return $ret;
        }
        return false;
    }

    /**
     * 根据keymap_id command 获取该命令已添加次数及 允许添加次数
     */
    public function getCommandAndKeymapdataCountone($keymap_id,$command)
    {
        $spname = 'sp_km_keymap_data_and_km_command_select_count_one';
        $ret = Yii::$app->db->createCommand("call " . $spname . "('"
            . $keymap_id . "','"
            . $command .
            "')")->queryOne();

        if ($ret) {
            return $ret;
        }
        return false;
    }

    /**
     * 根据keymap_id command 获取该命令已添加次数及 允许添加次数
     */
    public function getRcinfoAndCategoryinfo($remote_type_id,$category_id)
    {
        $spname = 'sp_keymap_select_category_and_rc';
        $ret = Yii::$app->db->createCommand("call " . $spname . "('"
            . $remote_type_id . "','"
            . $category_id .
            "')")->queryOne();

        if ($ret) {
            return $ret;
        }
        return false;
    }

    /**
     * 查询所有的remote大类相关信息
     * @return array
     */
    public function getRemoteCategorySelectAll()
    {
        $spname = 'sp_km_remote_category_select_all';
        $res = Yii::$app->db->createCommand("call ".$spname."()")->queryAll();
        return $res;
    }

    /**
     * 查询所有的KeymapType
     * @return array
     */
    public function getKeymapTypeSelectAll($start_number,$page_size)
    {
        $spname = 'sp_km_keymap_type_select_all';
        $res = Yii::$app->db->createCommand("call " . $spname . "('"
            . $start_number . "','"
            . $page_size .
            "')")->queryAll();
        return $res;
    }

    /**
     * 查询所有的KeymapType
     * @return array
     */
    public function getCommandTypeSelectAll($start_number,$page_size)
    {
        $spname = 'sp_km_command_type_select_all';
        $res = Yii::$app->db->createCommand("call " . $spname . "('"
            . $start_number . "','"
            . $page_size .
            "')")->queryAll();
        return $res;
    }

    /**
     * 查询所有的km_opstyle
     * @return array
     */
    public function getOpStyleSelectAll($start_number,$page_size)
    {
        $spname = 'sp_km_op_style_select_all';
        $res = Yii::$app->db->createCommand("call " . $spname . "('"
            . $start_number . "','"
            . $page_size .
            "')")->queryAll();
        return $res;
    }


    /**
     * 厂商添加的keymap 查看
     * @param $start_number
     * @param $page_size
     * @param int $B_status
     * @param int $is_official 默认为1 支持厂商list
     */
    public function getKeymapManufactureSelectAll($start_number=0,$page_size=0,$B_status=0,$is_official=1)
    {
        $spname = 'sp_km_keymap_manufacture_select_all_new';
        $res = Yii::$app->db->createCommand("call " . $spname . "("
            . $start_number . ","
            . $page_size . ","
            . $B_status . ","
            . $is_official .
            ")")->queryAll();
        return $res;
    }

    /**
     * 取device type 详情
     * @param $device_id
     * @return bool
     */
    public function getdeviceTypeSelectOne($device_id)
    {
        $spname = 'sp_device_type_select_by_device_id';
        $ret = Yii::$app->db->createCommand("call ".$spname."('"
            .$device_id."')")->queryOne();


        if ($ret) {
            return $ret;
        }
        return false;
    }


    /**
     * 根据keymapid 更新版本状态
     */
    public function setChangeBStatus($keymap_id,$status)
    {
        $spname = 'sp_km_keymap_change_B_status';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$keymap_id."','"
            .$status.
            "',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }


    /**
     * 添加审核厂商keymap log 或者未通过描述
     * @param $keymap_id
     * @return bool
     */
    public function setKeymapDescribeCheckLogAdd($keymap_id,$msg,$B_status,$ip,$created_ts)
    {
        $spname = 'sp_km_keymap_describe_check_log_add';
        Yii::$app->db->createCommand("call ".$spname."('"
            .$keymap_id."','"
            .$msg."',"
            .$B_status.",'"
            .$ip."',"
            .$created_ts
            .",@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据遥控器获取对应的厂商信息
     * @return array
     */
    public function getManufactureToRemoteAll()
    {
        $spname = 'sp_manufacture_get_remote_select_all';
        $res = Yii::$app->db->createCommand("call " . $spname . "()")->queryAll();
        return $res;
    }
}