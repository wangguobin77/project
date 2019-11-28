<?php
/**
 * Created by PhpStorm.
 * User: zhengjiang
 * Date: 2017/7/26
 * Time: 15:46
 */

namespace app\controllers;

use app\controllers\base\KeymapBaseController;
use Yii;

use app\models\keymap\Keymap;
use  yii\helpers\Url;

use  common\util\IFilter;
use  common\util\IReq;

class KeymapController extends KeymapBaseController
{

    private $pub_url;
    private $drive_way = [
        'EVENT_DRIVE',
        'COMMAND_DRIVE'
    ];//驱动方式 目前只有两种

    protected $p_title= '<li>keymap管理</li>';//注意更具自己项目做具体定义名称 一级

    protected $d_title= '';//二级

    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分

    public function init()
    {
//        $this->enableCsrfValidation = false;//test1111

        $this->pub_url = Yii::$app->request->hostInfo.'/keymap/web/';
        $this->layout = false;//暂时默认

    }

    /**
     * keymap 列表页
     * @return string
     */
    public function actionKeymap_list()
    {

        $this->d_title = 'keymap列表';

        $typeId = IFilter::act(IReq::get('rc'));// 可以不传  id  D062CF0972BF7867507B9EF3D7D5F66F

        $Keymap = new Keymap();

        $manufacture_remote = $this->disManufactureToRemoteAll($Keymap->getManufactureToRemoteAll());//遥控器和厂商相关的信息

        $keymap_list = $this->disKeymapListData();

        $m_id = 0;//厂商id

        $r_id = 0;//rc id

        if($typeId){
            $keymap_list = $this->disKeymapListDataParam($typeId);

            $m_id = $this->getMid($keymap_list);//厂商id

            $r_id = $typeId;//rc id
        }

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('keymap_list',['keymap_list'=>$keymap_list,'m_r_data'=>json_encode($manufacture_remote),'m_id'=>$m_id,'r_id'=>$r_id]);
    }


    /**
     * 1 键盘操作事件类型 以及 展示所有的按键
     * 2 条件类型
     * 3 条件值相关
     * 4 参数设置
     * 返回按键设置接口
     */
    public function actionGet_all_jsondata()
    {

        $category_id = IFilter::act(IReq::get('category_id'),'string',32);//大类

        $remote_type_id = IFilter::act(IReq::get('remote_type_id'),'string',32);// 遥控器id

        if(!$category_id){
            show_json(100204,'category_id 错误或者缺失');
        }

        if(!$remote_type_id){
            show_json(200202,'remote_type_id 错误或者缺失');
        }

        $Keymap = new Keymap();

        $rc_event_type_list = $Keymap->getSelectRcEventType($remote_type_id);//类型

        $rc_all_key_list = $Keymap->getSelectRcAllKey($remote_type_id);//所有按键

        $keycode_list = $this->disKeycodeListshow();

        $rc_all_key_sort_list = [];
        if($rc_all_key_list){

            foreach($rc_event_type_list as $kk=>$vv){
                //为展示的键排序 start
                $all_key_list = [];
                foreach ($rc_all_key_list as $key => $val){
                    if($val['type'] == $vv['type']){//过滤条件 只留下当前类型的键值
                        if(isset($keycode_list[$val['key']]) && $keycode_list[$val['key']]){
                            $val['keycode'] = $keycode_list[$val['key']];
                            $all_key_list[$val['key']] = $val;
                        }

                    }

                }
                ksort($all_key_list);
                $rc_all_key_sort_list[Yii::t('db',$vv['type'])] = $all_key_list;
            }

            //end
        }

        $Condition = $this->getConditionListAndValue($category_id,$remote_type_id);//条件相关

        $judge_type_list = $this->getSelectJudgeTypeAll();

        $params_list = $this->disCommandAndParams($category_id,$remote_type_id);

        $data = [
            'rc_all_key_sort_list' => $rc_all_key_sort_list,//按键相关
            'judge_type_list' =>$judge_type_list,//条件类型相关 keycode condiotion
            'Condition_list' => $Condition,//条件相关 keycode condition
            'params_list' => $params_list,//偏移量
        ];

        show_json(0,'success',$data);
    }

    /***************************添加 编辑*******************************/
    /**
     * add keymap view
     * 创建keymap 需要得到基于什么版本下创建的keymap
     *
     * post 参数
     * @param remote_type_id
     * @param category_id
     * @param device_type_id
     * @param keymap_name
     * @param version
     */
    public function actionAdd_keymap()
    {
        if(Yii::$app->request->post()){

            $category_id = IFilter::act(IReq::get('category_id'),'string',32);// 获取终端大类

            if(!$category_id){
                show_json(100204,'category_id 错误或者缺失');
            }

            $remote_type_id = IFilter::act(IReq::get('remote_type_id'),'string',32);// 遥控器大类

            $command_list = $this->getCommand($category_id);

            $keymap_id = createGuid();//guid

            $is_offical = 0;//暂时默认官方
            $device_type_id = '';
            /*$version = filterData(Yii::$app->request->post('version'),'string',32);//当前版本*/
            $version = IFilter::act(IReq::get('version'),'string',32);//当前版本

            $Keymap = new Keymap;
            if(isset(Yii::$app->request->post()['keymap_id']) && Yii::$app->request->post()['keymap_id']){
               /* $result['id'] = filterData(Yii::$app->request->post('keymap_id'),'string',32);*/
                $result['id'] = IFilter::act(IReq::get('keymap_id'),'string',32);
                $result['status'] = 0;
            }else{
                $result = $Keymap->addKmKeymap($keymap_id,$remote_type_id,$category_id,$device_type_id,$is_offical,$version,'','','');
            }

            //new
            $keymap_data = $Keymap->getSelectKeymapDataAll('','',$result['id']);
            if(!$keymap_data){
                $keymap_data = [];
            }

            $r_c_info = $Keymap->getRcinfoAndCategoryinfo($remote_type_id,$category_id);
            //end
            //正确的时候
            if($result['status'] == 0){
                $k_type_id = isset(Yii::$app->request->post()['k_type_id'])?intval(Yii::$app->request->post()['k_type_id']):0;//区分进入编辑还是添加页面

                $this->d_title = '<li>添加keymap</li>';

                $this->o_text = '<li>'.$r_c_info['rc_key'].'</li><li>'.$r_c_info['c_key'].'</li>';//o_title 内容可以为空，内容自己可以组装

                setNavHtml($this->p_title,$this->d_title,$this->o_text);

                if($k_type_id === 2){
                    return $this->render('add_keymap_config',
                        [
                            'keymap_id' => $result['id'],//keymap id
                            'command_list' => $command_list,//所支持命令数组
                            'remote_type_id' => $remote_type_id,
                            'drive_way' => $this->drive_way,//驱动方式
                            'category_id'=> $category_id,
                            'keymap_data_list' => $keymap_data,

                        ]);
                }else{
                    $url = Url::toRoute(['keymap/detail_view','keymap_id'=>$result['id'],'request_type'=>1]);

                    header("Location: $url");

                    exit;
                }


            }else{
                show_json(100214,'创建keymap时获取版本失败');
            }
        }else{
            show_json(100015,'无表单数据提交');
        }

        //GetVersion_info 添加keymap 需要知道当前大类最新的版本号 是否是正式版／beta版 才能返回在什么版本下添加

    }

    /**
     * 处理post 表单提交过来的数据
     */
    public function actionDis_jsondata()
    {

        $km_id = IFilter::act(IReq::get('keymap_id'),'string',32);// km_id

        $category_id = IFilter::act(IReq::get('category_id'),'string',32);// 大类id

        $remote_type_id = IFilter::act(IReq::get('remote_type_id'),'string',32);// 遥控器大类

        if(!$km_id){
            show_json(100212,Yii::$app->params['errorCode'][100212]);
        }
        if(!$category_id){
           show_json(100204,Yii::$app->params['errorCode'][100204]);
        }

        if(!$remote_type_id){
            show_json(200202,Yii::$app->params['errorCode'][200202]);
        }

        $jsonData = [];

        $COMMAND = Yii::$app->request->post('COMMAND');//命令
        $KEYMAP_TYPE = Yii::$app->request->post('KEYMAP_TYPE');//驱动方式

        if(!$COMMAND){
            show_json(100206,Yii::$app->params['errorCode'][100206]);
        }
        $jsonData['COMMAND'] = $COMMAND;

        if(!$KEYMAP_TYPE){
            show_json(100207,Yii::$app->params['errorCode'][100207]);
        }
        $jsonData['KEYMAP_TYPE'] = $KEYMAP_TYPE;

        if($KEYMAP_TYPE == $this->drive_way[0]){//选择事件驱动可以没有条件
            $EVENT = Yii::$app->request->post('EVENT');//事件
            if(!$EVENT){
                show_json(100208,Yii::$app->params['errorCode'][100208]);
            }
            $jsonData['EVENT'] = $EVENT;
        }

        $CONDITIONS = Yii::$app->request->post('CONDITIONS');//条件
        if($KEYMAP_TYPE == $this->drive_way[1]){//选择是命令驱动时 必须要有条件配合，否则当前配置不成立
            if($CONDITIONS[0]['CONDITION_VALUE'] === '0'){//判断第一个条件的值是否存在
                show_json(100215,Yii::$app->params['errorCode'][100215]);
            }
        }

        $params = Yii::$app->request->post('PARAMS');//参数
        //处理条件 过滤不合法值 / 重复值
        $CONDITIONS = $this->disConditionValue($CONDITIONS);
        $PARAMS = $this->disParamsValue($params);

        if($CONDITIONS && !empty($CONDITIONS)){
            $jsonData['CONDITIONS'] = $CONDITIONS;
        }

        if($PARAMS && !empty($PARAMS)){
            $jsonData['PARAMS'] = $PARAMS;
        }

        $id = createGuid();//guid keymap_data id 字段

        $bool = $this->setConditionDataFromDb($id,$km_id,$remote_type_id,$category_id,$jsonData);

       if($bool) {

           set_ses_data(0,'add success');
           $url = Url::toRoute(['keymap/detail_view','id'=>$id,'keymap_id'=>$km_id,'request_type'=>2]);

           header("Location: $url");
            exit;
       }
        show_json(100213,'添加keymap配置失败');

    }

    /**
     * 单条keymap_data 详情页面
     * @return string
     */
    public function actionDetail_view()
    {
        if(Yii::$app->request->isGet){
            $this->d_title = '<li>keymap详情</li>';

            $g_type = IFilter::act(IReq::get('request_type'),'int',1);// 2 是修改模式 其他都默认展示

            $keymap_id = IFilter::act(IReq::get('keymap_id'),'string',32);// keymap_id

            if(!$keymap_id){
                show_json(100212,Yii::$app->params['errorCode'][100212]);
            }

            $Keymap = new Keymap();

            $keymap_data = $Keymap->getSelectKeymapDataAll('','',$keymap_id);

            //start
            if(!$keymap_data || count($keymap_data) <= 0){
                $keymap_data = [];
            }

            if($g_type == 2){

                $id = IFilter::act(IReq::get('id'),'string',32);// 页面显示使用

                if(!$id) show_json(100000,'缺少信息参数');

                $data = $Keymap->getKeymapDataSelectOne($id);

                if(!$data)  show_json(100212,'keymap id 参数不存在或者错误');

                $command_list = $this->getCommand($data['category_id']);
                sort($command_list);//排序
                //start
                if(isset(json_decode($data['km_data'],true)['EVENT']) && json_decode($data['km_data'],true)['EVENT']){
                    $keyInfo = $Keymap->getKeycodeSelectByKeyOne(json_decode($data['km_data'],true)['EVENT']);

                    $data['event_1'] = $keyInfo['type'];
                    $data['event_2'] = $keyInfo['parent'];
                }
                //end


                $this->o_text = '<li>'.$data['r_name'].'</li><li>'.$data['c_key'].'</li><li class="active">'.$data['ver'].'</li>';//o_title 内容可以为空，内容自己可以组装

                setNavHtml($this->p_title,$this->d_title,$this->o_text);
                return $this->render('command_show',
                    [
                        'id' =>$id,//当前一条的keymapdata 的id
                        'keymap_id' => $keymap_id,//keymap id
                        'command_list' => $command_list,//所支持命令数组 排序
                        'remote_type_id' => $data['remote_type_id'],
                        'drive_way' => $this->drive_way,//驱动方式
                        'category_id'=> $data['category_id'],
                        'version' =>$data['ver'],//版本
                        'data' => $data,//当前编辑的历史信息
                        'keymap_data_list'=>$keymap_data,
                        'type' => 2,//1 无编辑模式  2 进入编辑模式
                        'c_type' => $data['c_key'],
                        'r_type' => $data['r_key']
                    ]);
            }else{
                $data = $Keymap->getKeymapSelectOne($keymap_id);

                $this->o_text = '<li>'.$data['r_name'].'</li><li>'.$data['c_key'].'</li><li class="active">'.$data['ver'].'</li>';//o_title 内容可以为空，内容自己可以组装

                setNavHtml($this->p_title,$this->d_title,$this->o_text);
                return $this->render('command_show',
                    [
                        'keymap_id' => $keymap_id,//keymap id 所属keymap的i
                        'keymap_data_list'=>$keymap_data,
                        'version' =>$data['ver'],//版本
                        'remote_type_id' => $data['remote_type_id'],
                        'category_id' => $data['category_id'],
                        'type' => 1,//1 无编辑模式  2 进入编辑模式
                        'c_type' => $data['c_key'],
                        'r_type' => $data['r_key']
                    ]);
            }

        }

        show_json(100000,'请求方式错误');//请求方式错误

    }


    /**
     * 处理修改 表单提交过来的数据
     */
    public function actionEdit_jsondata()
    {

        $keymap_data_id = filterData(Yii::$app->request->post('keymap_data_id'),'string',32);//单条配置唯一id

        if(!$keymap_data_id){
            show_json(100000,'缺少参数');
        }
        $km_id = filterData(Yii::$app->request->post('keymap_id'),'string',32);//km_id

        $category_id = filterData(Yii::$app->request->post('category_id'),'string',32);//大类id
        $remote_type_id = filterData(Yii::$app->request->post('remote_type_id'),'string',32);//遥控器大类

        if(!$km_id){
            show_json(100212,'keymap id 参数不存在或者错误');
        }
        if(!$category_id){
            show_json(100204,'category_id 错误或者缺失');
        }

        if(!$remote_type_id){
            show_json(200202,'remote_type_id 错误或者缺失');
        }

        $jsonData = [];

        $COMMAND = filterData(Yii::$app->request->post('COMMAND'),'string',32);//命令
        $KEYMAP_TYPE = filterData(Yii::$app->request->post('KEYMAP_TYPE'),'string',32);//驱动方式

        if(!$COMMAND){
            show_json(100206,'COMMAND数据错误或者缺失');
        }
        $jsonData['COMMAND'] = $COMMAND;

        if(!$KEYMAP_TYPE){
            show_json(100207,'KEYMAP_TYPE数据错误或者缺失');
        }
        $jsonData['KEYMAP_TYPE'] = $KEYMAP_TYPE;

        if($KEYMAP_TYPE == $this->drive_way[0]){//选择事件驱动可以没有条件
            $EVENT = filterData(Yii::$app->request->post('EVENT'),'string',32);//事件
            if(!$EVENT){
                show_json(100208,'EVENT错误或者缺失');
            }
            $jsonData['EVENT'] = $EVENT;
        }

        $CONDITIONS = Yii::$app->request->post('CONDITIONS');//条件
        if($KEYMAP_TYPE == $this->drive_way[1]){//选择是命令驱动时 必须要有条件配合，否则当前配置不成立
            if($CONDITIONS[0]['CONDITION_VALUE'] === '0'){//判断第一个条件的值是否存在
                show_json(100215,'必须配置condition条件');
            }
        }

        $params = Yii::$app->request->post('PARAMS');//参数
        //处理条件 过滤不合法值 / 重复值
        $CONDITIONS = $this->disConditionValue($CONDITIONS);
        $PARAMS = $this->disParamsValue($params);

        if($CONDITIONS && !empty($CONDITIONS)){
            $jsonData['CONDITIONS'] = $CONDITIONS;
        }

        if($PARAMS && !empty($PARAMS)){
            $jsonData['PARAMS'] = $PARAMS;
        }

        $bool = $this->setUpConditionDataFromDb($keymap_data_id,$km_id,$remote_type_id,$category_id,$jsonData);

        if($bool) {
            set_ses_data(0,'修改成功');

            $url = Url::toRoute(['keymap/detail_view','id'=>$keymap_data_id,'keymap_id'=>$km_id,'request_type'=>2]);
            header("Location: $url");

            exit;

        }
        show_json(100213,'添加keymap配置失败');

    }

    /**
     * 删除一条keymap
     */
    public function actionDel_keymap_data()
    {

        $id = filterData(Yii::$app->request->post('id'),'string',32);//大类id
        $keymap_id = filterData(Yii::$app->request->post('keymap_id'),'string',32);//大类id

        if(!$keymap_id){
            show_json(100212,'keymap id 参数不存在或者错误');
        }
        if(!$id){
            show_json(100212,'大类 id 参数不存在或者错误');
        }

        $Keymap = new Keymap();
        if($Keymap->setDelKeymapData($id)){
            show_json(0,'删除keymap数据成功');

        }
        show_json(100000,'删除keymap数据失败');
    }
    /**
     * 进入创建keymap页面
     * @return string
     */
    public function actionCreate_keymap()
    {

        $category_id = filterData(Yii::$app->request->get('category_id'),'string',32);//大类id
        $remote_type_id = filterData(Yii::$app->request->get('remote_type_id'),'string',32);//遥控器大类

        if(!$category_id){
            show_json(100204,'大类 id 参数不存在或者错误');
        }

        if(!$remote_type_id){
            show_json(200202,'遥控器类id 参数不存在或者错误');
        }

        //new
        $Keymap = new Keymap();
        $data = $Keymap->getNewRKeymapInfoOne($category_id,$remote_type_id);
        if(!$data){
            $ver = 'V0.0.0';
            $b_ver = 'V1.0.0';
            $m_ver = 'V0.1.0';
            $s_ver = 'V0.0.1';
        }else{
            $ver = $data['ver'];
            $c_ar = explode('.',$ver);
            $b_ver = 'V'.intval($c_ar[0]+1).'.0.0';
            $m_ver =  'V'.$c_ar[0].'.'.intval($c_ar[1]+1).'.0';
            $s_ver = 'V'.$c_ar[0].'.'.$c_ar[1].'.'.intval($c_ar[2]+1);
        }

        $this->verifyIsRemote($remote_type_id);
        $this->verifyIsCategory($category_id);


        $this->d_title = '<li>keymap创建</li>';

        $this->o_text = $ver;//o_title 内容可以为空，内容自己可以组装

        setNavHtml($this->p_title,$this->d_title,$this->o_text);


        return $this->render('create_keymap',
            [
                'category_id' => $category_id,
                'remote_type_id'=> $remote_type_id,
                'ver' => $ver,
                'b_ver' => $b_ver,
                'm_ver' => $m_ver,
                's_ver' => $s_ver
            ]);
    }


    /**
     * 查看正式版详情 不能做任何修改
     * @return string
     */
    public function actionCheck_r_keymap_show()
    {
        $Keymap = new Keymap();
        $keymap_id = filterData(Yii::$app->request->get('keymap_id'),'string',32);//keymap_id

        $keymap_data = $Keymap->getSelectKeymapDataAll('','',$keymap_id);

        //在没有keymap_data_id的情况下需要显示一个做展示
        if(isset(Yii::$app->request->get()['id']) && Yii::$app->request->get()['id']){
            $keymap_data_id = filterData(Yii::$app->request->get('id'),'string',32);
        }else{

            if($keymap_data && $keymap_data[0]){
                $keymap_data_id = $keymap_data[0]['id'];
            }else{
                show_json(100000,'无数据详情');
            }
        }


        $data = $Keymap->getKeymapDataSelectOne($keymap_data_id);

        if(!$data){
            show_json(100212,'无keymap数据');
        }

        $jsonData = json_decode($data['km_data'],true);

        if(isset($jsonData['EVENT']) && $jsonData['EVENT']){
            $keyInfo = $Keymap->getKeycodeSelectByKeyOne($jsonData['EVENT']);
            $jsonData['event_1'] = $keyInfo['type'];
            $jsonData['event_2'] = $keyInfo['parent'];
        }

        $this->d_title = '<li>keymap正式版本详情</li>';

        $this->o_text = '<li>'.$data['r_name'].'</li><li>'.$data['c_key'].'</li><li class="active">'.$data['ver'].'</li>';//o_title 内容可以为空，内容自己可以组装
      //  $this->o_text = '<li>'.$data['ver'].'</li>';//o_title 内容可以为空，内容自己可以组装

        setNavHtml($this->p_title,$this->d_title,$this->o_text);

        return $this->render('check_r_show',
            [
                'id' =>$keymap_data_id,//当前一条的keymapdata 的id
                'keymap_id' => $keymap_id,//keymap id 所属keymap的id
                'jsonData' =>$jsonData,//返回当前编辑的内容 展示
                'keymap_name' =>$data['keymap_name'],
                'version' =>$data['ver'],//版本
                'remote_type_id' => $data['remote_type_id'],
                'category_id' => $data['category_id'],
                'keymap_data_list'=>$keymap_data
            ]);
    }

    /**
     * 发布beta版本
     */
    public function actionIssue()
    {
        $Keymap = new Keymap();
        $keymap_id = filterData(Yii::$app->request->post('keymap_id'),'string',32);//keymap_id

        $keymap_data = $Keymap->getSelectKeymapDataAll('','',$keymap_id);

        if(!$keymap_data || !$keymap_data[0]){
            show_json(100000,'未发现配置数据，不可以发布');
        }
       
        if($Keymap->setUpKeymapRelease($keymap_id)){
            show_json(0,'发布 beta 版本成功');
        }

        show_json(100000,'发布 beta 版本失败');
    }


    /**
     * 根据参数$remote_type_id $category_id 获取当前最新的正式版本一条
     */
    public function actionImport_command()
    {
        $remote_type_id = filterData(Yii::$app->request->post('remote_type_id'),'string',32);//rc类型id

        $category_id = filterData(Yii::$app->request->post('category_id'),'string',32);//大类id

        if(!$category_id){
            show_json(100204,'缺少大类id');
        }

        if(!$remote_type_id){
            show_json(200202,'缺少遥控器类型id');
        }

        $Keymap = new Keymap();

        $data = $Keymap->getNewRKeymapInfoOne($category_id,$remote_type_id);

        if(!$data){
            show_json(100000,'数据不存在');
        }

        $keymap_data = $Keymap->getSelectKeymapDataAll('','',$data['id']);
        if(!$keymap_data){
            show_json(100000,'数据不存在');
        }


        $arr = [];//
        foreach ($keymap_data as $key=>$val){
            $brr = [];
            $brr['km_data'] = $val['km_data'];
            $brr['command'] = $val['command'];
            $brr['language_command'] = Yii::t('db',$val['command']);
            array_push($arr,$brr);
        }
        $data['cm_r'] = $arr;
        show_json(0,'导入成功',$data);
    }

     /**
     * 处理导入命令操作
     */
    public function actionDis_import_command()
    {


        $keymap_id = filterData(Yii::$app->request->post('keymap_id'),'string',32);//beta版keymapid
        $command_arr = Yii::$app->request->post('command');//copy命令数组

        if(!$keymap_id || empty($command_arr)){
            show_json(100000,'导入失败');
        }


        $str = '';
        $km_name = '';//默认都为空
        $num = count($command_arr);
        foreach ($command_arr as $key=>$val){
            if($num - 1 == 0){
                $str .= "('";
                $str .= createGuid()."','";
               /* $str .= $val."','";*/
               foreach ($val as $k=>$v){
                   $str .= $v."','";
               }

               /* $str .= $key."','";*/
                $str .= $k."','";
                $str .= $keymap_id."','";
                $str .= $km_name."','";
                $str .= setDateTime()."');";
            }else{
                $str .= "('";
                $str .= createGuid()."','";
              //  $str .= $val."','";
                foreach ($val as $k=>$v){
                    $str .= $v."','";
                }
                /*$str .= $key."','";*/
                $str .= $k."','";
                $str .= $keymap_id."','";
                $str .= $km_name."','";
                $str .= setDateTime()."'),";
            }
            $num--;

        }
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $sql = "insert into km_keymap_data values ".$str;

            $connection = Yii::$app->db;

            $command = $connection->createCommand($sql);
            $res = $LeapcamBindDeviceInfo = $command->execute();

            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            show_json(100000,'导入失败');
        }


        if($res){
            set_ses_data(0,'导入成功');
           show_json(0,'导入成功');
        }

        show_json(100000,'导入失败');
    }

    /******************/
    /**
     * 添加某个命令之前会检测该命令是否已经添加 是否已超出配置的添加个数
     * @param  keymap_id keymapid
     * @param  command_name 命令名称
     */
    public function actionIs_allow_add_command()
    {
        $keymap_id = filterData(Yii::$app->request->post('keymap_id'),'string',32);//keymapid
        $command_name = filterData(Yii::$app->request->post('command_name'),'string',32);//
        $Keymap = new Keymap();
        $data = $Keymap->getCommandAndKeymapdataCountone($keymap_id,$command_name);

        if($data && $data['canmap_once'] == 0){
            show_json(0,'allow add command',$data);
        }

        if($data && intval($data['num']) < $data['canmap_once']){
            show_json(0,'allow add command',$data);
        }
        show_json(100000,'不允许添加该命令');
    }

    /**********************************注意：云端接口想获取json数据 必须先要生成设备的keymap json数据******************************************/
    /**
     * 根据当前的rc 与 categoryid 生成json数据 正式版
     */
    public function actionWrite_r_keymap_json()
    {
        $Keymap = new Keymap;
        $rc_id = filterData(Yii::$app->request->get('rc_type'),'string',32);//keymap自增id
        $category_id = filterData(Yii::$app->request->get('c_type'),'string',32);

        if(!$rc_id){
            show_json(100000,'缺少参数');
        }

        if(!$category_id){
            show_json(100000,'缺少参数');
        }
        //根据keymap id 获取下面完整的keymap配置
        $data = $this->disKeymapListData();

        $keymap_list = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                if($category_id ==$val['id'] && $rc_id == $val['remote_type_id'] && $val['R'] != '--'){
                    array_push($keymap_list,$val);
                }
            }
        }


        //遍历获取keymap 正式版本目录
        $path = ROOT_PATH.Yii::$app->params['official_path'];

        if(!empty($keymap_list)){
            foreach ($keymap_list as $k=>$v){
                $keymap_data = $Keymap->getSelectKeymapDataAll('','',$v['keymap_id_R']);

                $new_url = $path.strtolower($v['remote_type']);

                if (!is_dir($new_url)) {
                    mkdir($new_url, 0755, true);
                }
                $fh = fopen($new_url.'/'.strtolower($v['key']).'.json','w');
                $new_keymap_all_data_json = [];
                foreach ($keymap_data as $ks=>$vs){
                    if($vs['km_data']){
                        array_push($new_keymap_all_data_json,json_decode($vs['km_data'],true));
                    }

                }
                fwrite($fh,json_encode($new_keymap_all_data_json));
                fclose($fh);
            }

            //远程同步文件
            $scp_data = "cp -r $path* ".Yii::$app->params['remote_official_path'];

            $bool = shell_exec($scp_data);//生成keymap 需要同步远程云端接口数据
            show_json(0,'write ok',$bool);
        }

        show_json(100000,'no data write');

    }

    /**
     * 根据当前的rc 与 categoryid 生成json数据 beta版本
     */
    public function actionWrite_beta_keymap_json()
    {
        $Keymap = new Keymap;
        $rc_id = filterData(Yii::$app->request->get('rc_type'),'string',32);//keymap自增id
        $category_id = filterData(Yii::$app->request->get('c_type'),'string',32);

        if(!$rc_id){
            show_json(100000,'缺少参数');
        }

        if(!$category_id){
            show_json(100000,'缺少参数');
        }
        //根据keymap id 获取下面完整的keymap配置
        $data = $this->disKeymapListData();

        $keymap_list = [];
        if(count($data) > 0){
            foreach ($data as $key=>$val){
                if($category_id ==$val['id'] && $rc_id == $val['remote_type_id'] && $val['B'] != '--'){
                    array_push($keymap_list,$val);
                }
            }
        }


        //遍历获取keymap beta版本目录
        $path = ROOT_PATH.Yii::$app->params['beta_path'];
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        if(!empty($keymap_list)){
            foreach ($keymap_list as $k=>$v){
                $keymap_data = $Keymap->getSelectKeymapDataAll('','',$v['keymap_id_B']);
              
                $new_url = $path.strtolower($v['remote_type']);

                if (!is_dir($new_url)) {
                    mkdir($new_url, 0755, true);
                }
                $fh = fopen($new_url.'/'.strtolower($v['key']).'.json','w');
                $new_keymap_all_data_json = [];
                foreach ($keymap_data as $ks=>$vs){
                    if($vs['km_data']){
                        array_push($new_keymap_all_data_json,json_decode($vs['km_data'],true));
                    }

                }
                fwrite($fh,json_encode($new_keymap_all_data_json));
                fclose($fh);
            }

            //远程同步文件
            $scp_data = "cp -r $path* ".Yii::$app->params['remote_beta_path'];

            $bool = shell_exec($scp_data);//生成keymap 需要同步远程云端接口数据
            show_json(0,'write ok',$bool);
        }

        show_json(100000,'no data write');

    }



}




