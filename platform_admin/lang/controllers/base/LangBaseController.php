<?php
namespace app\controllers\base;

use Yii;
use app\models\LanguageModel;
use app\models;
class LangBaseController extends \yii\base\Controller
{
    /**php生成配置文件
     *
     */
    public function make_file($file, $lang, $value){
        $data = array();
        if ($value) {
            foreach ($value as $key=>$val){
                //在value表中有数据的语言给取到的值
                foreach ($lang as $lk=>$lv) {
                    if ($val['lang_id']==$lv['id']) {
                        $val['lang_short'] = $lv['lang_short'];
                        $data[$val['lang_key']][$lv['id']] = $val;
                    }
                }
                //在value表中没有数据的语言，人工赋值，其中lang_value赋值为lang_key的值
                foreach ($lang as $lk => $lv) {
                    if (!isset($data[$val['lang_key']][$lv['id']])) {
                        $data[$val['lang_key']][$lv['id']] = array(
                            'id'    =>  '',
                            'file_id'   =>  $file['id'],
                            'lang_key'  =>  $val['lang_key'],
                            'key_id'    =>  $val['key_id'],
                            'lang_id'   =>  $lv['id'],
                            'lang_value'    =>  $val['lang_key'],
                            'lang_short'    =>  $lv['lang_short'],
                        );
                    }
                }
            }
        }

        foreach ($lang as $k=>$v) {
            $str_tmp = "<?php\r\n";
            $str_end = "?>";
            $str_tmp.= "return [ \r\n";
            foreach ($data as $kk=>$vv) {
                foreach ($vv as $key => $val) {
                    if ($val['lang_id'] == $v['id']) {
                        // $v['data'][$key] = $val;
                        if (isset($val['lang_value'])) {    //value值存在（在value表存在记录）写入value值
                            $str_tmp.= "        '".$val['lang_key']."'  =>  "."'".$val['lang_value']."'"." , \r\n";
                        } else {          //value不存在，给空值
                            $str_tmp.= "        '".$val['lang_key']."'  =>  "."''"." , \r\n";
                        }
                    }
                }
            }
            $str_tmp .= "] \r\n";
            $str_tmp .= $str_end;
            $sf = $file['file_path'].$v['lang_short'];   //文件夹路劲
            if (!file_exists($sf)) {
                mkdir($sf,0755,true);
            }
            $sf = $file['file_path'].$v['lang_short'].'/'.$file['file_name'];    //创建文件

            $fp = fopen($sf,"w+");
            fwrite($fp,$str_tmp);
            fclose($fp);
        }
        show_json(0, 'Make file successful.');
    }
    /*  U3D 生成文件
     *  @params
     */
    public function u3d_make_file($file, $lang, $value){
        $data = array();
        if ($value) {
            foreach ($value as $key=>$val) {
                foreach($lang as $lk=>$lv){
                    if ($val['lang_id']==$lv['id']) {
                        $val['lang_short'] = $lv['lang_short'];
                        $data[$val['lang_key']][] = $val;
                    }
                }
                foreach ($lang as $lk => $lv) {
                    if (!isset($data[$val['lang_key']][$lv['id']])) {
                        $data[$val['lang_key']][$lv['id']] = array(
                            'id'    =>  '',
                            'file_id'    =>  $file['id'],
                            'lang_key'    =>  $val['lang_key'],
                            'key_id'    =>  $val['key_id'],
                            'lang_id'    =>  $lv['id'],
                            'lang_value'    =>  $val['lang_key'],
                            'lang_short'    =>  $lv['lang_short'],
                        );
                    }
                }
            }
        }

        $str_tmp = '<?xml version="1.0" encoding="utf-16"?>'."\r\n";
        $str_tmp.= '<Localization>'."\r\n";
        $str_tmp.= '    <Section>'." \r\n\n";
        foreach ($data as $key=>$val) {
            $str_tmp.= '        <TextKey name='."'".$key."'".'> '."\r\n";
            foreach ($val as $k=>$v) {
                // $v['data'][$key] = $val;
                if (isset($v['lang_value'])) {    //value值存在（在value表存在记录）写入value值
                    $str_tmp.= "        <".$v['lang_short'].'>'.$v['lang_value'].'<'.$v['lang_short'].">\r\n";
                } else {          //value不存在，给空值
                    $str_tmp.= "        <".$v['lang_short'].'><'.$v['lang_short']."> , \r\n";
                }
            }
            $str_tmp .= '        </TextKey>'."\r\n\n";
        }
        $str_tmp .= ' </Section> '."\r\n";
        $str_tmp .= '</Localization>'."\r\n";
        $sf = $file['file_path'];   //文件夹路劲
        if (!file_exists($sf)) {
            mkdir($sf,0755,true);
        }
        $sf = $file['file_path'].$file['file_name'];    //创建文件

        $fp = fopen($sf,"w");
        fwrite($fp,$str_tmp);
        fclose($fp);
        show_json(0, 'Make file successful.');
    }

    /*  Android生成文件
     *  @params
     */
    public function android_make_file($file, $lang, $value)
    {
        $data = array();
        if ($value) {
            foreach ($value as $key=>$val) {
                //在value表中有数据的语言给取到的值
                foreach ($lang as $lk=>$lv) {
                    if ($val['lang_id']==$lv['id']) {
                        $val['lang_short'] = $lv['lang_short'];
                        $data[$val['lang_key']][$lv['id']] = $val;
                    }
                }
                //在value表中没有数据的语言，人工赋值，其中lang_value赋值为lang_key的值
                foreach ($lang as $lk => $lv) {
                    if (!isset($data[$val['lang_key']][$lv['id']])) {
                        $data[$val['lang_key']][$lv['id']] = array(
                            'id'    =>  '',
                            'file_id'    =>  $file['id'],
                            'lang_key'    =>  $val['lang_key'],
                            'key_id'    =>  $val['key_id'],
                            'lang_id'    =>  $lv['id'],
                            'lang_value'    =>  $val['lang_key'],
                            'lang_short'    =>  $lv['lang_short'],
                        );
                    }
                }
            }
        }
        foreach ($lang as $k=>$v) {
            $str_tmp = "<resources>\r\n";
            foreach ($data as $kk=>$vv) {
                foreach ($vv as $key => $val) {
                    if ($val['lang_id']==$v['id']) {

                        if (isset($val['lang_value'])) {    //value值存在（在value表存在记录）写入value值
                            $str_tmp .= '    <string name="'.$val['lang_key'].'">'.$val['lang_value'].'</string>'."\r\n";
                        } else {          //value不存在，给空值
                            $str_tmp .= '    <string name="'.$val['lang_key'].'">'.$val['lang_key'].'</string>'." \r\n";
                        }
                    }
                }
            }
            $str_tmp .= "</resources>";
            $sf = $file['file_path'].$v['lang_short'];   //文件夹路劲
            if (!file_exists($sf)) {
                mkdir($sf,0755,true);
            }
            $sf = $file['file_path'].$v['lang_short'].'/'.$file['file_name'];    //创建文件

            $fp = fopen($sf,"w");
            fwrite($fp,$str_tmp);
            fclose($fp);
        }
        show_json(0, 'Make file successful.');
    }
}