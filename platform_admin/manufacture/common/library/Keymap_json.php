<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/5/3
 * Time: 上午10:07
 */

namespace common\library;

use Yii;
use app\models\Keymap;
class Keymap_json
{
    public function rc_json_data()
    {

        $Keymap = new Keymap();

       /* $data = $Keymap->getSelectRemoteTypeAllNew();*/
        $data = $Keymap->getSelectRemoteTypeAll();

        $keysetcode = $this->getRcKeyCode();

        $path = SERVER_PATH.'/keymap/web/keymap/files/rc_setting';
        //$path = '/data/www/av/keymap/web/keymap/files/rc_setting';

        if(count($data) > 0 && count($keysetcode) > 0){
            $all_json_data = [];

            foreach ($data as $key=>$val){

                $all_json_data['RC_TYPE']['NAME'] =  $val['key'];
                $all_json_data['RC_TYPE']['CODE'] =  $val['code'];
                $all_json_data['RC_TYPE']['MANUFACTURE'] =  $val['m_name_en'];
                $all_json_data['RC_TYPE']['SCREEN'] =  $val['screen'];
                $all_json_data['RC_TYPE']['CARRY_TYPE'] =  $val['carry_type'];

                /*$fh = fopen($path.'/'.strtolower($val['key']).'.json','w');*/
                $fh = fopen($path.'/'.strtolower($val['short_name']).'.json','w');//更改以缩写为名称
                $json = [];
                foreach ($keysetcode as $k=>$v){
                    if($val['id'] == $v['remote_type_id']){
                        if($v['type'] == 'KEY_JOYSTICK'){

                            if(isset($json['KEY_JOYSTICK']) && $json['KEY_JOYSTICK']){
                                array_merge($json['KEY_JOYSTICK'],[$v['key'],$v['key'].'_UP',$v['key'].'_RT',$v['key'].'_DN',$v['key'].'_LT']);
                                continue;
                            }else{
                                $json['KEY_JOYSTICK'] = [$v['key'],$v['key'].'_UP',$v['key'].'_RT',$v['key'].'_DN',$v['key'].'_LT'];
                                continue;
                            }

                        }

                        if(isset($json[$v['type']]) && $json[$v['type']]){
                            array_push($json[$v['type']],$v['key']);

                        }else{
                            $json[$v['type']] = [$v['key']];

                        }
                    }
                }
                $all_json_data['RC_TYPE']['KEYSET'] =  $json;

                fwrite($fh,json_encode($all_json_data));
                fclose($fh);
            }

            //todo 远程同步文件
            $scp_data = "cp -r $path/* /data/www/platform_api/keymap/public/keymap/files/rc_setting/";

            $bool = shell_exec($scp_data);//生成keymap 需要同步远程云端接口数据
            return true;
        }
        return false;
        //show_json(100000,'no write data');

    }


    /**
     * 获取键值
     * @return array
     */
    protected function getRcKeyCode()
    {
        $Keymap = new Keymap();
        $data = $Keymap->getSelectRcEventType('','');

        return $data;
    }
}