<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/12/4
 * Time: 下午4:47
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\keymap\Keymap;
use app\controllers\base\BaseController;
/*class Keymap_jsonController extends BaseController*/
class Keymap_jsonController extends Controller
{
    public function init()
    {
        $this->enableCsrfValidation = false;
    }
    /*******************************生成json文件的接口******************************************/
    //临时被移到library下面 全局可以调用
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


    /**
     * 获取keycode json
     */
    public function actionKeycode_json()
    {
        $Keymap = new Keymap();
        $data = $Keymap->getSelectKeycodeAll('','');

        $this->getRc_category();
        if(count($data) > 0){
            $json_data = [];
            foreach ($data as $key=>$val){
                $json_data[$val['key']] = [
                    'CODE'=>$val['code'],
                    'PARENT'=>$val['parent'],
                    'CATEGORY'=>$val['type']
                ];
            }

            ksort($json_data);

            $path = ROOT_PATH.'keymap/files';
            //$path = '/data/www/a/keymap/frontend/web/keymap/files/';
            $fh = fopen($path.'/keycode.json','w');
            fwrite($fh,json_encode($json_data));
            fclose($fh);

            //远程同步文件
            $scp_data = "cp $path/keycode.json /data/www/platform_api/keymap/public/keymap/files/";

            $bool = shell_exec($scp_data);//生成keymap 需要同步远程云端接口数据
            show_json(0,'write ok',$bool);
        }
        show_json(100000,'no write data');
    }

    /**
     * 获取Command json
     */
    public function actionCommand_all_json()
    {

        $json = [];

        $json['RC_CATEGORY'] = $this->getRc_category();

        $json['KEYMAP_TYPE'] = $this->getKeymap_type();

        $json['CONDITION_JUDGE_TYPE'] = $this->getCondition_judge_type();

        $json['CONDITION_TYPE'] = $this->getCondition_type();

        $json['CONDITION_VALUE'] = $this->getCondition_value();

        $json['COMMAND_TYPE'] = $this->getCommand_type();

        $json['CATEGORY'] = $this->getCategory();

        $json['OP_STYLE'] = $this->getOp_style();

        $json['COMMAND'] = $this->getCommandJson();

        $path = ROOT_PATH.'keymap/files';
        // $path = '/data/www/a/keymap/frontend/web/keymap/files/';
        $fh = fopen($path.'/command.json','w');
        if(fwrite($fh,json_encode($json))){
            $code = 0;
            $msg = 'write ok';

            //todo 远程同步文件
            $scp_data = "cp $path/command.json /data/www/platform_api/keymap/public/keymap/files/";

            $bool = shell_exec($scp_data);//生成keymap 需要同步远程云端接口数据
            show_json(0,'write ok',$bool);

        }else{
            $code = 100000;
            $msg = 'write error';
        }
        fclose($fh);

        show_json($code,$msg);
    }

    protected function getRc_category()
    {

        //new
        $Keymap = new Keymap();
        $data = $Keymap->getRemoteCategorySelectAll();
        //end

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getKeymap_type()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getKeymapTypeSelectAll('','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getCondition_judge_type()
    {
        $Keymap = new Keymap();
        $data  = $Keymap->getSelectJudgeTypeAll('','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getCondition_type()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getSelectConditionTypeAll('','','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getCondition_value()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getSelectConditionValueAll('','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getCommand_type()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getCommandTypeSelectAll('','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getCategory()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getSelectDeviceCategory('','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;
    }

    protected function getOp_style()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getOpStyleSelectAll('','');

        $new_data = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                $new_data[$val['key']] = $val['code'];
            }
        }

        return $new_data;



    }

    protected function getCommandJson()
    {

        $Keymap = new Keymap();
        $data = $Keymap->getSelectCommandAll('','','');

        $json_data = [];
        if (count($data) > 0) {

            foreach ($data as $key => $val) {
                $json_data[$val['key']] = [
                    'CODE' => $val['code'],
                    'VER' => $val['version'],
                    'PARAMS' => $val['analog_params'],
                    'CAN_MAP' => $val['can_map']
                ];
            }
        }

        return $json_data;
    }
}