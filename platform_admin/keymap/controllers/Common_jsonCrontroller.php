<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2017/12/4
 * Time: 下午5:03
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\app\Keymap;
class Common_jsonCrontroller extends Controller
{
    private $pub_http_url;//访问地址 keymap

    private $pub_home_url;//站点根目录 存地址 keymap

    private $pub_b_home_url;//bate 版本生成地址

    private $pub_b_http_url;//beta 版本访问地址

    private $pub_r_home_url;//正式 版本生成地址

    private $pub_r_http_url;//正式 版本访问地址

    public function init()
    {
        $this->pub_http_url = Yii::$app->request->hostInfo.'/keymap/keymap/files/';

        $this->pub_home_url = ROOT_PATH.'keymap/files/';

        $this->pub_b_home_url = ROOT_PATH.'keymap/files/keymap/beta/';

        $this->pub_b_http_url = Yii::$app->request->hostInfo.'/keymap/keymap/files/keymap/beta/';

        $this->pub_r_home_url = ROOT_PATH.'keymap/files/keymap/official/';

        $this->pub_r_http_url = Yii::$app->request->hostInfo.'/keymap/keymap/files/keymap/official/';

    }
    //生成最终的keymap jsondata 页面
    public function actionJson_view()
    {
        $http_url = $this->pub_http_url;

        $path = $this->pub_home_url;
        $rc = filterData(Yii::$app->request->get('rc'),'string',32);

        if(!$rc){
            show_json(10000,'params not found');
        }

        $json = [];
        $json['code'] = 0;
        /*file_rc*/
        $path_rc_url = $path.'rc_setting/'.strtolower($rc).'.json';
        if(is_file($path_rc_url)){
            $json['data']['file_rc'] = [
                'name'=>strtoupper($rc),
                'lastupdate'=>filemtime($path_rc_url),
                'url'=>$http_url.'rc_setting/'.strtolower($rc).'.json'
            ];
        }

        /*file_keycode*/
        $path_keycode_url = $path.'keycode.json';
        if(is_file($path_keycode_url)){
            $json['data']['file_keycode'] = [
                'name'=>'SP_KEYCODES',
                'lastupdate'=>filemtime($path_keycode_url),
                'url'=>$http_url.'keycode.json'
            ];
        }

        /*file_command*/
        $path_command_url = $path.'command.json';
        if(is_file($path_command_url)){
            $json['data']['file_command'] = [
                'name'=>'SP_COMMAND',
                'lastupdate'=>filemtime($path_command_url),
                'url'=>$http_url.'command.json'
            ];
        }

        /*file_keymap_R*/
        if($r_info = $this->getRJsonData($rc)){
            $json['data']['file_keymap'] = $r_info;
        }

        /*file_keymap_R*/
        if($b_info = $this->getBetaJsonData($rc)){
            $json['data']['file_keymap_beta'] = $b_info;
        }

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $json;
        $response->send();
        // echo json_encode($json);
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
                    $v['remote_type_id'] = $val['id'];//类型id
                    $v['remote_type'] = $val['key'];//型号
                    $v['B'] = '--';
                    $v['R'] = '--';
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

    public function getBetaJsonData($rc_name)
    {
        $Keymap = new Keymap;
        //根据keymap id 获取下面完整的keymap配置
        $data = $this->disKeymapListData();

        $keymap_list = [];
        if(count($data) > 0){

            foreach ($data as $k=>$v){
                if(strtolower($rc_name) == strtolower($v['remote_type']) && $v['B'] != '--'){
                    array_push($keymap_list,$v);
                }
            }


        }

        //遍历获取keymap beta本目录
        $path = $this->pub_b_home_url.strtolower($rc_name);
        if (!is_dir($path)) {
            return [];
        }
        $http_url = $this->pub_b_http_url.strtolower($rc_name).'/';

        if(!empty($keymap_list)){
            $new_data = [];
            foreach ($keymap_list as $k=>$v){
                if(is_file($path.'/'.strtolower($v['key']).'.json')){
                    $new_data[$v['key']] = [
                        'name'=>strtoupper($v['key']),
                        'ver' => $v['B'],
                        'url' => $http_url.strtolower($v['key']).'.json'
                    ];
                }

            }

            return $new_data;
        }

        return [];

    }


    public function getRJsonData($rc_name)
    {
        $Keymap = new Keymap;
        //根据keymap id 获取下面完整的keymap配置
        $data = $this->disKeymapListData();

        $keymap_list = [];
        if(count($data) > 0){

            foreach ($data as $k=>$v){
                if(strtolower($rc_name) == strtolower($v['remote_type']) && $v['R'] != '--'){
                    array_push($keymap_list,$v);
                }
            }


        }

        //遍历获取keymap 正式版本目录
        $path = $this->pub_r_home_url.strtolower($rc_name);
        if (!is_dir($path)) {
            return [];
        }
        $http_url = $this->pub_r_http_url.strtolower($rc_name).'/';


        if(!empty($keymap_list)){
            $new_data = [];
            foreach ($keymap_list as $k=>$v){
                if(is_file($path.'/'.strtolower($v['key']).'.json')){
                    $new_data[$v['key']] = [
                        'name'=>strtoupper($v['key']),
                        'ver' => $v['R'],
                        'url' => $http_url.strtolower($v['key']).'.json'
                    ];
                }

            }

            return $new_data;
        }

        return [];

    }
}