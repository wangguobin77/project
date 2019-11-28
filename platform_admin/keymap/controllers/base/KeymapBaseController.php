<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/11/4
 * Time: 下午4:16
 */

namespace app\controllers\base;

use Yii;
use yii\web\Controller;
use app\models\keymap\Keymap;
use app\controllers\base\BaseController;
class KeymapBaseController extends BaseController
{
    /**
     * 获取厂商id
     */
    protected function getMid($keymap_list)
    {
        if(!$keymap_list) return 0;
        foreach ($keymap_list as $k=>$v){
            return $keymap_list[$k]['manufacture_id'];
            die;
        }
    }

    /**
     * 获取遥控器id
     * @param $manufacture_remote
     * @return array
     */
    protected function getRid($manufacture_remote)
    {
        if(!$manufacture_remote) return [];
        foreach ($manufacture_remote as $k=>$v){
            return $v[0]['r_id'];
        }
    }

    /**
     * /
     * 处理组装遥控器与厂商的对应关心
     * @param $data
     * @return array
     */
    protected function disManufactureToRemoteAll($data)
    {
        $arr = [];

        if(!$data) return $arr;

        foreach ($data as $key=>$val){
            $t = [];
            $t['manufacture_id'] = $val['id'];
            $t['manufacture_name'] = $val['name'];
            $t['r_id'] = $val['r_id'];
            $t['r_name'] = $val['r_name'];
            $t['r_type'] = $val['r_type'];
            $t['r_key'] = $val['r_key'];
            if(isset($arr[$val['id']])){
                array_push($arr[$val['id']],$t);
            }else{
                $arr[$val['id']] = [$t];
            }
        }

        return $arr;
    }


    /**
     * 处理列表展示
     * @return array
     */
    protected function disKeymapListData()
    {
        $Keymap = new Keymap();
        $keymap_data = $Keymap->getKeymapSelectsAll();//获取所有版本信息
//        $remote_type_list = $Keymap->getSelectRemoteTypeAll('','','','','0');//获取遥控器系列大类
        $remote_type_list = $Keymap->getSelectRemoteTypeAll();//获取遥控器系列大类

        $category_list = $Keymap->getSelectDeviceCategory();//获取终端大类

        $keymap_list = [];
        foreach ($remote_type_list as $key=>$val){
            foreach ($category_list as $k=>$v){
                if(is_array($v)){
                    $v['r_name'] = $val['name'];//遥控器名称
                    $v['r_en_name'] = $val['name_en'];//遥控器名称 英文
                    $v['r_type'] = $val['type'];//遥控器型号

                    $v['remote_type_id'] = $val['id'];//类型id
                   /* $v['remote_type'] = $val['key'];//型号*/
                    $v['remote_type'] = $val['type'];//型号//2019-03-28
                    $v['B'] = '--';
                    $v['R'] = '--';

                    $v['manufacture_name'] = $val['m_name'];//厂商名称
                    $keymap_list[$val['id'].$v['id']] = $v;
                }

            }
        }

        foreach ($keymap_list as $ks=>$vs){
            foreach ($keymap_data as $kk=>$vv){
                //判断组合键队是否存在
                if($ks == $vv['remote_type_id'].$vv['category_id']){

                    if($vv['status'] == 'R'){
                        $keymap_list[$ks]['R'] = $vv['ver'];
                        $keymap_list[$ks]['keymap_id_R'] = $vv['id'];//keymap版本的唯一id
                        $keymap_list[$ks]['release_time'] = $vv['release_time'];//发布时间
                    }else{
                        $keymap_list[$ks]['B'] = $vv['ver'];
                        $keymap_list[$ks]['keymap_id_B'] = $vv['id'];//keymap版本的唯一id
                    }

                }
            }
        }

        return $keymap_list;

    }

    /**
     * 带参数过滤条件
     * @param string $type 设备id
     * @return array
     */
    protected function disKeymapListDataParam($typeId='')
    {
        $Keymap = new Keymap();
        $keymap_data = $Keymap->getKeymapSelectsAll();//获取所有版本信息
//        $remote_type_list = $Keymap->getSelectRemoteTypeAll('','','','','0');//获取遥控器系列大类
        $remote_type_list = $Keymap->getSelectRemoteTypeAll();//获取遥控器系列大类

        $category_list = $Keymap->getSelectDeviceCategory();//获取终端大类

        $keymap_list = [];
        foreach ($remote_type_list as $key=>$val){
            foreach ($category_list as $k=>$v){
                if($typeId && $val['id']==$typeId){
                    $v['r_name'] = $val['name'];//遥控器名称
                    $v['r_en_name'] = $val['name_en'];//遥控器名称 英文
                    $v['r_type'] = $val['type'];//遥控器型号

                    $v['remote_type_id'] = $val['id'];//类型id
                    $v['remote_type'] = $val['key'];//型号
                    $v['B'] = '--';
                    $v['R'] = '--';
                    $v['manufacture_name'] = $val['m_name'];//厂商名称
                    $v['manufacture_id'] = $val['manufacture_id'];//厂商id
                    $keymap_list[$val['id'].$v['id']] = $v;
                }

            }
        }

        if(!$keymap_list || empty($keymap_list)){
            return $keymap_list;
        }

        foreach ($keymap_list as $ks=>$vs){
            foreach ($keymap_data as $kk=>$vv){
                //判断组合键队是否存在
                if($ks == $vv['remote_type_id'].$vv['category_id']){

                    if($vv['status'] == 'R'){
                        $keymap_list[$ks]['R'] = $vv['ver'];
                        $keymap_list[$ks]['keymap_id_R'] = $vv['id'];//keymap版本的唯一id
                        $keymap_list[$ks]['release_time'] = $vv['release_time'];//发布时间
                    }else{
                        $keymap_list[$ks]['B'] = $vv['ver'];
                        $keymap_list[$ks]['keymap_id_B'] = $vv['id'];//keymap版本的唯一id
                    }

                }
            }
        }

        return $keymap_list;

    }


    /**
     * 根据remote_type_id 判断遥控器是否存在 是否合法
     * @param $remote_type_id
     */
    protected function verifyIsRemote($remote_type_id)
    {
        $Keymap =  new Keymap();
//        $remote_type_list = $Keymap->getSelectRemoteTypeAll('','','','','0');//获取遥控器系列大类
        $remote_type_list = $Keymap->getSelectRemoteTypeAll();//获取遥控器系列大类
        $remote_id_array = [];
        foreach ($remote_type_list as $key=>$val){
            array_push($remote_id_array,$val['id']);
        }

        if(!in_array($remote_type_id,$remote_id_array)){
            show_json(100202,Yii::$app->params['errorCode'][100202]);
        }
    }

    /**
     * 根据categoryid 判断终端是否存在 是否合法
     * @param $remote_type_id
     */
    protected function verifyIsCategory($category_id)
    {
        $Keymap =  new Keymap();
        $category_list = $Keymap->getSelectDeviceCategory();//获取终端大类
        $category_array = [];
        foreach ($category_list as $key=>$val){
            array_push($category_array,$val['id']);
        }

        if(!in_array($category_id,$category_array)){
            show_json(100204,Yii::$app->params['errorCode'][100204]);
        }
    }


    /**
     * 根据终端大类返回相关的数据 返回对应的命令
     * @param $category_id
     * @return array
     */
    protected function getCommand($category_id)
    {
        $Keymap =  new Keymap();
        $command_list = $Keymap->getSelectCommandAll();//获取所有命令

        //获取设备特有与通用的命令

        $command = [];
        foreach ($command_list as $k => $v) {
            $v_arr = explode(',',$v['category_id']);

            if(in_array($category_id,$v_arr) || $v['category_id'] == '0'){
                if($v['can_map'] == 1){
                    array_push($command,$v['key']);
                }

            }

        }

        return $command;
    }

    /**
     * 处理keycode
     * @param int $int_type 当值为1只显示 _PRESSED相关的 其他值时显示除了 _PRESSED 以外的所有值
     * @return array 1【'A'=>[['click'],['double_click'],['long_press']],'B'=>[[],[],[]]】 / 2 ['A'='A_PRESSED','B'=>'PRESSED']
     */
 /*   protected function disKeycodeListshow($int_type = 2)
    {
        $Keymap = new Keymap();
        $keycode_list = $Keymap->getSelectKeycodeAll();//获取所有的keycode

        $new_keycoce_all = [];
        foreach ($keycode_list as $key => $val){
            if($int_type === 1){
                if(strstr($val['key'],'_PRESSED')){
                //if($val['key'] == $val['parent'].'_PRESSED'){
                   // $new_keycoce_all[$val['parent']] = $val['key']; //new
                    //$new_keycoce_all[$val['parent']] = Yii::t('db',$val['key']);
                    $new_keycoce_all[$val['key']] = Yii::t('db',$val['key']);
                }
            }else{
                //if($val['key'] == $val['parent'].'_PRESSED'){
                if(strstr($val['key'],'_PRESSED')){
                    continue;
                }else{
                    if($val['parent'] == isset($new_keycoce_all[$val['parent']])){
                        $val['language_name'] = Yii::t('db',$val['key']);//new
                        array_push($new_keycoce_all[$val['parent']],$val);
                    }else{
                        $val['language_name'] = Yii::t('db',$val['key']);//new
                        $arr = [];
                        array_push($arr,$val);
                        $new_keycoce_all[$val['parent']] = $arr;
                    }
                }
            }

        }

        return $new_keycoce_all;
    }*/
   /* protected function disKeycodeListshow($int_type = 2)
    {

        $Keymap = new Keymap();
        $keycode_list = $Keymap->getSelectKeycodeAll();//获取所有的keycode

        $new_keycoce_all = [];
        foreach ($keycode_list as $key => $val){
            if($int_type === 1){
                if(strstr($val['key'],'_PRESSED')){
                    //if($val['key'] == $val['parent'].'_PRESSED'){
                    // $new_keycoce_all[$val['parent']] = $val['key']; //new
                    //$new_keycoce_all[$val['parent']] = Yii::t('db',$val['key']);
                    $new_keycoce_all[$val['key']] = Yii::t('db',$val['key']);
                }
            }else{
                //if($val['key'] == $val['parent'].'_PRESSED'){
                if(strstr($val['key'],'_CLICK') || strstr($val['key'],'_DOUBLE_CLICK') || strstr($val['key'],'_LONG_PRESS')){
                    if($val['parent'] == isset($new_keycoce_all[$val['parent']])){
                        $val['language_name'] = Yii::t('db',$val['key']);//new
                        array_push($new_keycoce_all[$val['parent']],$val);
                    }else{
                        $val['language_name'] = Yii::t('db',$val['key']);//new
                        $arr = [];
                        array_push($arr,$val);
                        $new_keycoce_all[$val['parent']] = $arr;
                    }
                }
            }

        }
        ksort($new_keycoce_all);
        return $new_keycoce_all;
    }*/
   //2018-8-08
    protected function disKeycodeListshow($int_type = 2)
    {

        $Keymap = new Keymap();
        $keycode_list = $Keymap->getSelectKeycodeAll();//获取所有的keycode

        $new_keycoce_all = [];
        foreach ($keycode_list as $key => $val){
            if($int_type === 1){
                if(strstr($val['key'],'_PRESSED')){
                    //if($val['key'] == $val['parent'].'_PRESSED'){
                    // $new_keycoce_all[$val['parent']] = $val['key']; //new
                    //$new_keycoce_all[$val['parent']] = Yii::t('db',$val['key']);
                    $new_keycoce_all[$val['key']] = Yii::t('db',$val['key']);
                }
            }else{
                //if($val['key'] == $val['parent'].'_PRESSED'){
                /*if(strstr($val['key'],'_CLICK') || strstr($val['key'],'_DOUBLE_CLICK') || strstr($val['key'],'_LONG_PRESS')){*/
                if($val['keytype'] == 0){
                   /* if(isset($new_keycoce_all[$val['parent']]) && $val['parent'] == $new_keycoce_all[$val['parent']]){*/
                    if(isset($new_keycoce_all[$val['parent']])){
                        $val['language_name'] = Yii::t('db',$val['key']);//new
                        array_push($new_keycoce_all[$val['parent']],$val);
                    }else{
                        $val['language_name'] = Yii::t('db',$val['key']);//new
                        $arr = [];
                        array_push($arr,$val);
                        $new_keycoce_all[$val['parent']] = $arr;
                    }
                }
            }

        }
        ksort($new_keycoce_all);
        return $new_keycoce_all;
    }



    /**
     * @param $category_id 大类id
     * [0-3] 组装对应的key=>value
     */
   /* protected function selectConditionTypeValue($category_id)
    {
        $Keymap = new Keymap();
        $condition_value_list = $this->disConditionValueList(2);

        $condition_type_list = $Keymap->getSelectConditionTypeAll('','',$category_id);

        $new_condition_value_and_type_list = [];
        if($condition_type_list){
            foreach ($condition_type_list as $key=>$val)
            {
                if(isset($condition_value_list[$val['key']])){
                    $new_condition_value_and_type_list[$val['key']] = $condition_value_list[$val['key']];
                }

            }
        }

        return $new_condition_value_and_type_list;
    }*/
    protected function selectConditionTypeValue($category_id)
    {
        $Keymap = new Keymap();
        $condition_value_list = $this->disConditionValueList(2);

        $condition_type_list = $Keymap->getSelectConditionTypeAll('','',$category_id);

        $new_condition_value_and_type_list = [];
        if($condition_type_list){
            foreach ($condition_type_list as $key=>$val)
            {
                if(isset($condition_value_list[$val['key']])){
                    $data = [];
                    $data[0] = $condition_value_list[$val['key']];
                    $data[1] = Yii::t('db',$val['key']);
                    $new_condition_value_and_type_list[$val['key']] = $data;
                }

            }
        }
        ksort($new_condition_value_and_type_list);//排序
        return $new_condition_value_and_type_list;
    }

    /**
     * @param $remote_type_id 遥控器类型id
     * [4-7] 组装对应的key=>value
     */
    /*protected function selectKeycodeTypeValue($remote_type_id)
    {
        $Keymap = new Keymap;
        $rc_all_key_list = $Keymap->getSelectRcAllKey($remote_type_id);//当前rc所有按键
        $condition_value_list = $this->disConditionValueList(1);//keycode 的 value值 目前只有两个值【KEY_RELEASED，KEY_PRESSED】

        $new_keycode_type_value_list = [];
        if($rc_all_key_list){
            foreach ($rc_all_key_list as $key=>$val){

                $new_keycode_type_value_list[$val['key'].'_PRESSED'] = $condition_value_list['KEY_PRESSED'];
            }
        }

        return $new_keycode_type_value_list;
    }*/
  /*  protected function selectKeycodeTypeValue($remote_type_id)
    {
        $Keymap = new Keymap;
        $rc_all_key_list = $Keymap->getSelectRcAllKey($remote_type_id);//当前rc所有按键
        $condition_value_list = $this->disConditionValueList(1);//keycode 的 value值 目前只有两个值【KEY_RELEASED，KEY_PRESSED】

        echo '<pre>';
        print_r($rc_all_key_list);die;
        $new_keycode_type_value_list = [];
        if($rc_all_key_list){
            foreach ($rc_all_key_list as $key=>$val){
                $data = [];
                $data[0] = $condition_value_list['KEY_PRESSED'];
                $data[1] = Yii::t('db',$val['key']);
                $new_keycode_type_value_list[$val['key'].'_PRESSED'] = $data;
            }
        }

        return $new_keycode_type_value_list;
    }*/
    protected function selectKeycodeTypeValue($remote_type_id)
    {
        $Keymap = new Keymap;
        $rc_all_key_list = $Keymap->getSelectRcAllKey($remote_type_id);//当前rc所有按键
        $condition_value_list = $this->disConditionValueList(1);//keycode 的 value值 目前只有两个值【KEY_RELEASED，KEY_PRESSED】

        $keycode_list = $this->disKeycodeListshow(1);

        $new_keycode_type_value_list = [];
        /*if($keycode_list){
            foreach ($keycode_list as $key=>$val){
                $data = [];
                $data[0] = $condition_value_list['KEY_PRESSED'];
                $data[1] = Yii::t('db',$val);
                $new_keycode_type_value_list[$key] = $data;
            }
        }*/

        foreach ($rc_all_key_list as $key=>$val){
            foreach ($keycode_list as $k=>$v){
               /* if(strstr($k,$val['key'])) {*/
                if(substr($k,0,strlen($val['key'])) == $val['key']) {
                    $data = [];
                    $data[0] = $condition_value_list['KEY_PRESSED'];
                    $data[1] = Yii::t('db', $k);
                    $new_keycode_type_value_list[$k] = $data;
                }
            }
        }
        ksort($new_keycode_type_value_list);//排序
        return $new_keycode_type_value_list;
    }

    /**
     * 处理 Condition 类型与值相关数据
     * @type 2 是默认条件执行condition条件  1 只查询key相关条件
     * 【0-3】默认type 值为2     【4-7】type值为1
     * @return array
     */
/*    protected function disConditionValueList($type=2)
    {
        $Keymap = new Keymap();
        $condition_value_list = $Keymap->getSelectConditionValueAll();

        $new_condition_value_list = [];
        foreach ($condition_value_list as $k=>$v){

            if($type === 1){
                //执行key条件
                if($v['type'] == '999'){
                    if(isset($new_condition_value_list['KEY_PRESSED']) && is_array($new_condition_value_list['KEY_PRESSED'])){
                        array_push($new_condition_value_list['KEY_PRESSED'],$v['key']);
                    }else{
                        //默认key写死
                        $new_condition_value_list['KEY_PRESSED'] = [$v['key']];
                    }

                }

            }else{
                //执行condition条件
                if($v['condition_type_key'] && $v['type'] != '999'){
                    if(isset($new_condition_value_list[$v['condition_type_key']]) && is_array($new_condition_value_list[$v['condition_type_key']])){
                        array_push($new_condition_value_list[$v['condition_type_key']],$v['key']);
                    }else{
                        $new_condition_value_list[$v['condition_type_key']] = [$v['key']];
                    }
                }
            }

        }
        echo '<pre>';
        print_r($new_condition_value_list);die;
        return $new_condition_value_list;
    }*/
    protected function disConditionValueList($type=2)
    {
        $Keymap = new Keymap();
        $condition_value_list = $Keymap->getSelectConditionValueAll();

        $new_condition_value_list = [];
        foreach ($condition_value_list as $k=>$v){

            if($type === 1){
                //执行key条件
                if($v['type'] == '999'){
                    if(isset($new_condition_value_list['KEY_PRESSED']) && is_array($new_condition_value_list['KEY_PRESSED'])){
                        $new_n = [0=>$v['key'],1=>Yii::t('db',$v['key'])];
                        array_push($new_condition_value_list['KEY_PRESSED'],$new_n);
                    }else{
                        //默认key写死
                        /*$new_condition_value_list['KEY_PRESSED'] = [$v['key']];*/
                        $new_n = [0=>$v['key'],1=>Yii::t('db',$v['key'])];
                        $new_condition_value_list['KEY_PRESSED'] = [$new_n];
                    }

                }

            }else{
                //执行condition条件
                if($v['condition_type_key'] && $v['type'] != '999'){
                    if(isset($new_condition_value_list[$v['condition_type_key']]) && is_array($new_condition_value_list[$v['condition_type_key']])){
                        $new_n = [0=>$v['key'],1=>Yii::t('db',$v['key'])];
                        array_push($new_condition_value_list[$v['condition_type_key']],$new_n);
                    }else{
                        $new_n = [0=>$v['key'],1=>Yii::t('db',$v['key'])];
                        $new_condition_value_list[$v['condition_type_key']] = [$new_n];
                    }
                }
            }

        }

        return $new_condition_value_list;
    }

    /**
     * 根据rc 查询当前偏移量拥有的值
     * @param $remote_type_id
     * 返回数据 整理后的数据 'analog'=>['xx','vv']
     * @return array
     */
    protected function selectRemoteAnalogList($remote_type_id)
    {
        $Keymap = new Keymap();
        $analog_list = $Keymap->getSelectRemoteAnalogAll('','',$remote_type_id);

        $new_analog_list = [];
        if($analog_list){
            foreach ($analog_list as $key=>$val){
                array_push($new_analog_list,$val['analog']);
            }
        }

        return $new_analog_list;
    }

    /**
     * 过滤条件的值 不合法的／重复的值
     * @param $CONDITIONS
     * @return array
     */
    protected function disConditionValue($CONDITIONS)
    {

        $new_condition_arr = [];
        if(is_array($CONDITIONS)){
            $unq = [];//去重 根据judge拼接CONDITION_TYPE字符串
            foreach ($CONDITIONS as $key=>$val){
                //必须三个对应的值都合法 才能是条完整的条件
                if($val['CONDITION_JUDGE_TYPE'] && $val['CONDITION_TYPE'] && $val['CONDITION_VALUE']){
                    if(!in_array($val['CONDITION_JUDGE_TYPE'].':'.$val['CONDITION_TYPE'],$unq)){
                        array_push($unq,$val['CONDITION_JUDGE_TYPE'].':'.$val['CONDITION_TYPE']);//去重条件
                        $new_condition_arr[$key]['CONDITION_JUDGE_TYPE'] = $val['CONDITION_JUDGE_TYPE'];
                        $new_condition_arr[$key]['CONDITION_TYPE'] = $val['CONDITION_TYPE'];
                        $new_condition_arr[$key]['CONDITION_VALUE'] = $val['CONDITION_VALUE'];
                    }
                }

            }
        }
        return $new_condition_arr;
    }

    /**
     * 参数过滤条件 偏移量参数／正常参数
     * @param $params
     * @return array
     */
    protected function disParamsValue($params)
    {
        $new_params_arr = [];
        if(is_array($params) && count($params) > 0){
            foreach ($params as $key=>$val){
                //偏移量参数
                if(isset($val['TYPE']['ANALOG']) && $val['TYPE']['ANALOG']){
                    if(is_array($val['TYPE']['ANALOG'])){
                        if($val['TYPE']['ANALOG']['USA'] && $val['TYPE']['ANALOG']['JAPAN'] && $val['TYPE']['ANALOG']['CHINA']){
                            $new_params_arr[$key]['TYPE'] = 'ANALOG';
                            $new_params_arr[$key]['VALUE'] = $val['TYPE']['ANALOG']['USA'];//有操作手 默认都是美国
                            $new_params_arr[$key]['OP_STYLE'] = $val['TYPE']['ANALOG'];
                        }
                    }else{
                        $new_params_arr[$key]['TYPE'] = 'ANALOG';
                        $new_params_arr[$key]['VALUE'] = $val['TYPE']['ANALOG'];
                    }
                }else if(isset($val['TYPE']['TRANSPARENT']) && $val['TYPE']['TRANSPARENT']){
                    //正常参数
                    if(is_array($val['TYPE']['TRANSPARENT'])){
                        if($val['TYPE']['TRANSPARENT']['USA'] && $val['TYPE']['TRANSPARENT']['JAPAN'] && $val['TYPE']['TRANSPARENT']['CHINA']){
                            $new_params_arr[$key]['TYPE'] = 'TRANSPARENT';
                            //$new_params_arr[$key]['VALUE'] = $val['TYPE']['TRANSPARENT']['USA'];//有操作手 默认都是美国
                              $new_params_arr[$key]['VALUE'] = sprintf('%02s',$val['TYPE']['TRANSPARENT']['USA']);
                            $new_params_arr[$key]['OP_STYLE'] = $val['TYPE']['TRANSPARENT'];
                        }
                    }else{
                        $new_params_arr[$key]['TYPE'] = 'TRANSPARENT';
                       // $new_params_arr[$key]['VALUE'] = $val['TYPE']['TRANSPARENT'];
                        $new_params_arr[$key]['VALUE'] = sprintf('%02s',$val['TYPE']['TRANSPARENT']);
                    }
                }
            }
        }

        return $new_params_arr;
    }

    /**
     * keymap 以json数据格式数据存入db
     *
     * save
     * 保存keymap 需要参数
     * post 参数如下
     * @param COMMAND 命令作用
     * @param KEYMAP_TYPE 驱动方式
     * @param EVENT_DRIVE 事件
     * @param CONDITIONS 条件
     * @param OP_STYLE 操作手
     * @param PARAMS 参数
     * @param $CONDITIONS
     */
    protected function setConditionDataFromDb($id,$km_id,$remote_type_id,$category_id,$jsonData)
    {
        $command = $jsonData['COMMAND'];
        $jsonData = json_encode($jsonData);
        $km_name = '';

        $Keymap =new Keymap;

        $bool = $Keymap->addKmKeymapData($id,$remote_type_id,$category_id,$command,$jsonData,$km_id,$km_name);

        if($bool){
            return true;
        }
       return false;
    }

    /**
     * keymap 以json数据格式数据存入db
     *
     * Update
     * 保存keymap 需要参数
     * post 参数如下
     * @param COMMAND 命令作用
     * @param KEYMAP_TYPE 驱动方式
     * @param EVENT_DRIVE 事件
     * @param CONDITIONS 条件
     * @param OP_STYLE 操作手
     * @param PARAMS 参数
     * @param $CONDITIONS
     */
    protected function setUpConditionDataFromDb($id,$km_id,$remote_type_id,$category_id,$jsonData)
    {
        $command = $jsonData['COMMAND'];
        $jsonData = json_encode($jsonData);
        $km_name = '';

        $Keymap =new Keymap;

        $bool = $Keymap->setUpKeymapDataEdit($id,$remote_type_id,$category_id,$command,$jsonData,$km_id,$km_name);

        if($bool){
            return true;
        }
        return false;
    }


    /**
     * 查询 judge type 以及condition ／keycode 相关的type与value整体数组 一次返回前端
     * @category_id  device大类 小车 无人机等
     * @param $remote_type_id
     * 根据judge type【0-3】type 默认2  【4-7】type值 1 需要判断type的范围
     */
    protected function getConditionListAndValue($category_id,$remote_type_id)
    {

        if(!$category_id){
            show_json(200204,Yii::$app->params['errorCode'][200204]);
        }

        //$judge_type_list = $Keymap->getSelect_judge_type_all();

        /*根据judge type【0-3】type 默认2  【4-7】type值 1 需要判断type的范围*/

        $type_value_list = [];
        $data = $this->selectKeycodeTypeValue($remote_type_id);
        if($data){
            $type_value_list['keycode'] = $data;//[4-7] 返回
        }

        $data1 = $this->selectConditionTypeValue($category_id);
        if($data1){
            $type_value_list['condition'] = $data1;//[0-3] 返回
        }

        return $type_value_list;
        //show_json(0,'success',$type_value_list);
    }


    /**
     * 根据device大类／remote大类获取拥有的偏移量及对应命令下可以带参数的个数
     * @param $category_id
     * @param $remote_type_id
     * @return array
     */
   /* protected function disCommandAndParams($category_id,$remote_type_id)
    {
        $Keymap = new Keymap;
        $command_list = $Keymap->getSelectCommandAll();//获取所有命令

        //获取设备特有与通用的命令

        $result = [];
        foreach ($command_list as $k => $v) {
            $c_arr = explode(',',$v['category_id']);
            if(in_array($category_id,$c_arr) || $v['category_id'] == '0'){
                $result[$v['key']] = ['analog_num'=>$v['analog_params'],'normal_num'=>$v['normal_params']];
            }

        }

        $anlog_list = $this->getAnalogParametersList($remote_type_id);

        $data =  [
            'analog_value' =>$anlog_list,
            'params_num' => $result
        ];

        return $data;
    }*/
    protected function disCommandAndParams($category_id,$remote_type_id)
    {
        $Keymap = new Keymap;
        $command_list = $Keymap->getSelectCommandAll();//获取所有命令

        //获取设备特有与通用的命令

        $result = [];
        foreach ($command_list as $k => $v) {
            $c_arr = explode(',',$v['category_id']);
            if(in_array($category_id,$c_arr) || $v['category_id'] == '0'){
                $result[$v['key']] = ['analog_num'=>$v['analog_params'],'normal_num'=>$v['normal_params']];
            }

        }

        $anlog_list = $this->getAnalogParametersList($remote_type_id);
        $language = $this->getLanguageFromCookie();
        $new_anlog_list = [];

        if($anlog_list && $language){
           /* foreach ($anlog_list as $k=>$v){
                array_push($new_anlog_list,Yii::t('db',$v));
            }*/
            foreach ($anlog_list as $k=>$v){
                $new_anlog_list[$v] = Yii::t('db',$v);
            }
        }

        /*z注意当前category_id 如果是无人机和飞机 当前下面就不存在操作手 无人机5 飞机6*/
        $data =  [
            'analog_value' =>$new_anlog_list,
            'params_num' => $result
        ];

        return $data;
    }

    /**
     * @param $remote_type_id 遥控器类型id
     * 偏移量参数 列表信息
     */
    protected function getAnalogParametersList($remote_type_id)
    {

        $analog_list = $this->selectRemoteAnalogList($remote_type_id);

        return $analog_list;
    }

    /**
     * 获取当前的语言做数据展示
     * @return string
     */
    protected function getLanguageFromCookie()
    {
        return isset($_COOKIE['language'])?$_COOKIE['language']:'en';
    }

    /**
     * 处理judgetype 语言类型数据
     * @return array
     */
    protected function getSelectJudgeTypeAll()
    {
        $Keymap = new Keymap();
        $judge_type_list = $Keymap->getSelectJudgeTypeAll();

        $new_judge_type_list = [];
        if($judge_type_list){
            foreach ($judge_type_list as $k=>$v){
                $v['language_name'] = Yii::t('db',$v['key']);
                $new_judge_type_list[$k] = $v;
            }
        }

        return $new_judge_type_list;
    }
}
