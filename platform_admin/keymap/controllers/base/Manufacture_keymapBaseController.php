<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/12/7
 * Time: 下午2:07
 */

namespace app\controllers\base;

use Yii;
use app\models\app\Keymap;
class Manufacture_keymapBaseController extends BaseController
{
    /**
     * 处理厂商展示列表
     * @return array
     */
    protected function disKeymapListData($keymap_data)
    {
        $Keymap = new Keymap();
        $category_list = $Keymap->getSelectDeviceCategory();//获取终端大类

//        $remote_type_list = $Keymap->getSelectRemoteTypeAll('','','','','0');//获取遥控器系列大类
        $remote_type_list = $Keymap->getSelectRemoteTypeAll();//获取遥控器系列大类


        //获取终端大类
        $new_data = [];
        foreach ($category_list as $key=>$val){
            $new_data[$val['id']] = $val['tag'];
        }


        $remote_data = [];
        foreach ($remote_type_list as $key=>$val){
            $remote_data[$val['id']] = $val['tag'];
        }

        $keymap_list = [];
        if($keymap_data){
            foreach ($keymap_data as $key=>$val){
                $val['category_name'] = (isset($new_data[$val['category_id']]))?$new_data[$val['category_id']]:'未设置';
                $val['remote_type_name'] = (isset($remote_data[$val['remote_type_id']]))?$remote_data[$val['remote_type_id']]:'未设置';
                $val['manufacture_info'] = $Keymap->getdeviceTypeSelectOne($val['device_type_id']); //获取大类厂商相关信息
                $keymap_list[$key] = $val;
            }
        }

        return $keymap_list;

    }
}