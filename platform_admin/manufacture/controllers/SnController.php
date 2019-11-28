<?php

namespace app\controllers;

use app\controllers\bis\snBis;
use common\ErrorCode;
use common\helpers\Exception;
use common\helpers\File;
use common\helpers\ValidateHelper;
use common\library\resource\BaseResource;
use common\Result;
use Yii;
use app\controllers\base\SnBaseController;
use app\models\Sn;
use yii\data\Pagination;
class SnController extends SnBaseController
{
    /**
     * 登陆用户uid
     * @var
     */
    private $uid;

    public function init()
    {
        $this->layout = false;
        $this->enableCsrfValidation = false;
    }

    /**
     * 创建与修改缩写code
     * @return string
     */
    public function actionCreate_short_view()
    {
       /* $data = $this->getFactoryInfoAll('9AC2DC921318B1B264EA46DB877818A2');
        echo '<pre>';
        print_r($data);die;*/
        $mid = filterData(Yii::$app->request->get('mid'),'string',32);
       // $mid = '9AC2DC921318B1B264EA46DB877818A2';
        if(!$mid){
            show_json(100000,'parameter not legal');
        }

        $Sn = new Sn;

        $manufacture_info = $Sn->selectSnManufactureShortOne($mid);//厂商信息

       // $factory_info_list = $this->getFactoryInfoAll($mid);//厂商下 所有工厂

        $device_info_list = $this->getDeviceTypeAndCategoryShortInfoAll($mid);

        $remote_info_list = $this->getRemoteTypeShortInfoAll($mid);

        return $this->render('short_view',[
            'manufacture_info' => $manufacture_info,
           // 'factory_info_list' => $factory_info_list,
            'device_info_list' => $device_info_list,
            'remote_info_list' => $remote_info_list
        ]);
    }

    /**********厂商相关 start*********/
    /**
     * 根据厂商id 添加缩写
     */
    public function actionManufacture_short_add()
    {
        try{
            $ret = new Result();
            $params['mid'] = filterData(Yii::$app->request->post('mid'),'string',32);//厂商id
            $params['short'] =  filterData(Yii::$app->request->post('short'),'string',2,2);//厂商id

            $rules = [
                [['mid', 'short'], 'required'],
            ];
            $ret = ValidateHelper::validate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                $bis = new snBis();
                $ret = $bis->addManufactureShort($params);
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 根据厂商id 更新缩写
     */
    public function actionManufacture_short_up()
    {
        $id = intval(Yii::$app->request->post('id'));//厂商short 表id

        $m_short =  filterData(Yii::$app->request->post('short'),'string',2,2);//厂商id

        if(!$id || !$m_short){
            show_json(100000,'parameter not legal');
        }

        if(!preg_match("/^[A-Za-z0-9]{2}$/u",$m_short)){
            show_json(100000,'data formatting error');
        }

        $updated_ts = time();
        $Sn = new Sn;
        $res = $Sn->upSnManufactureShortInfo($id,strtoupper($m_short),$updated_ts);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'update','sn_manufacture_short_info',[$id,$m_short,$updated_ts]);

            show_json(0,'update manufactur short success');
        }

        show_json(100000,'update manufactur short be defeated');
    }

    /**
     * 根据厂商id 删除当前记录
     */
    public function actionManufacture_short_del()
    {
        $id = intval(Yii::$app->request->post('id'));//厂商short 表id


        if(!$id){
            show_json(100000,'parameter not legal');
        }

        $Sn = new Sn;

        $info = $Sn->getManufactureShortSelectOne($id);

        if(!$info){
            show_json(100000,'no data exist');
        }

        $res = $Sn->delSnManufactureShortInfo($id);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'delete','sn_manufacture_short_info',$info);

            show_json(0,'delete manufactur short success');
        }

        show_json(100000,'delete manufactur short be defeated');
    }
    /**********厂商相关 end*********/

    /*****device short 与 category short 统一处理***/
    /**
     * 添加Device_type short
     */
    public function actionDevice_type_short_add()
    {
        $device_type_id = filterData(Yii::$app->request->post('device_type_id'),'string',32);//终端类型id

        $short = filterData(Yii::$app->request->post('short'),'string',4,4);

        if(!$device_type_id || !$short){
            show_json(100000,'parameter not legal');
        }
        if(!preg_match("/^[A-Za-z0-9]{4}$/u",$short)){
            show_json(100000,'data formatting error');
        }

        $created_ts = $updated_ts = time();

        $Sn = new Sn;

        //检测终端缩写是否被使用
        if($Sn->getSnDeviceShortExist(strtoupper($short))){
            show_json(100000,'the short has being used');
        }

        $res = $Sn->addSnDeviceTypeShortInfo($device_type_id,strtoupper($short),$created_ts,$updated_ts);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'insert','sn_device_type_short_info',[$device_type_id,strtoupper($short),$created_ts]);
            insert_db_log('insert', "添加终端缩写:终端类型id-{$device_type_id} 缩写-{$short}");
            show_json(0,'add device type short success');
        }

        show_json(100000,'add device type short be defeated');
    }

    /**
     * 根据device type short id 更新数据
     *
     */
    public function actionDevice_type_short_up()
    {
        $id = intval(Yii::$app->request->post('id'));//device type short表 id

        $short = filterData(Yii::$app->request->post('short'),'string',4,4);//工厂id

        if(!$id || !$short){
            show_json(100000,'parameter not legal');
        }
        if(!preg_match("/^[A-Za-z0-9]{4}$/u",$short)){
            show_json(100000,'data formatting error');
        }

        $updated_ts = time();

        $Sn = new Sn;
        $res = $Sn->upSnDeviceTypeShortInfo($id,strtoupper($short),$updated_ts);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'update','sn_device_type_short_info',[$id,strtoupper($short),$updated_ts]);
            insert_db_log('update', "更新终端缩写:缩写id-{$id} 更新为-{$short}");
            show_json(0,'update device type short success');
        }

        show_json(100000,'update device type short be defeated');
    }

    /**
     * 删除device type short info
     */
    public function actionDevice_type_short_del()
    {
        $id = intval(Yii::$app->request->post('id'));//厂商short 表id


        if(!$id){
            show_json(100000,'parameter not legal');
        }

        $Sn = new Sn;

        $info = $Sn->getDeviceTypeShortSelectOne($id);

        if(!$info){
            show_json(100000,'no data exist');
        }

        $res = $Sn->delSnDeviceTypeShortInfo($id);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'delete','sn_device_type_short_info',$info);

            show_json(0,'delete device type short success');
        }

        show_json(100000,'delete device type short be defeated');
    }

    /*****************************remote short 相关********************************/

    /**
     * 添加 remote short info
     */
    public function actionRemote_type_short_add()
    {

        $remote_type_id = filterData(Yii::$app->request->post('remote_type_id'),'string',32);//终端类型id

        $short = filterData(Yii::$app->request->post('short'),'string',4,4);

        if(!$remote_type_id || !$short){
            show_json(100000,'parameter not legal');
        }
        if(!preg_match("/^[A-Za-z0-9]{4}$/u",$short)){
            show_json(100000,'data formatting error');
        }

        $created_ts = $updated_ts = time();

        $rc_category_short = 'RC';//默认写死
        $Sn = new Sn;

        //检测遥控器缩写是否被使用
        if($Sn->getSnRemoteShortExist(strtoupper($short))){
            show_json(100000,'the short has being used');
        }

        $res = $Sn->addSnRemoteShortInfo($remote_type_id,strtoupper($short),strtoupper($rc_category_short),$created_ts,$updated_ts);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'insert','sn_remote_short_info',[$remote_type_id,strtoupper($short),$rc_category_short,$created_ts]);
            insert_db_log('insert', "添加遥控器缩写:终端类型id-{$remote_type_id} 缩写-{$short}");
            show_json(0,'add remote type short success');
        }

        show_json(100000,'add remote type short be defeated');
    }

    /**
     * 修改remote short info
     */
    public function actionRemote_type_short_up()
    {
        $id = intval(Yii::$app->request->post('id'));

        $short = filterData(Yii::$app->request->post('short'),'string',4,4);

        if(!$id || !$short){
            show_json(100000,'parameter not legal');
        }
        if(!preg_match("/^[A-Za-z0-9]{4}$/u",$short)){
            show_json(100000,'data formatting error');
        }

        $updated_ts = time();

        $Sn = new Sn;
        $res = $Sn->upSnRemoteShortInfo($id,strtoupper($short),$updated_ts);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'update','sn_remote_short_info',[$id,strtoupper($short),$updated_ts]);
            insert_db_log('update', "更新遥控器缩写:缩写id-{$id} 更新为-{$short}");
            show_json(0,'update remote type short success');
        }

        show_json(100000,'update remote type short be defeated');
    }

    /**
     * 删除 remote short info 单条记录
     */
    public function actionRemote_type_short_del()
    {
        $id = intval(Yii::$app->request->post('id'));//厂商short 表id

        if(!$id){
            show_json(100000,'parameter not legal');
        }

        $Sn = new Sn;

        $info = $Sn->getSnRemoteShortInfoSelectOne($id);

        if(!$info){
            show_json(100000,'no data exist');
        }

        $res = $Sn->delSnRemoteShortInfo($id);

        if($res){
            //添加成功需要记录log $uid,$act,$table,$data=array()
            var_db_log($_SESSION['uid']['uid'],'delete','sn_remote_short_info',$info);

            show_json(0,'delete remote type short success');
        }

        show_json(100000,'delete remote type short be defeated');
    }



    /***********************************根据厂商申请的批次号 官方处理通过 申城sn号********************************************/
    /**
     * 展示厂商申请的批次列表
     */
    public function actionBatch_list()
    {
        $Sn = new Sn;

        $status = Yii::$app->request->get('status','');
        $m_name = Yii::$app->request->get('m_name','');

        if($status){
            $status = filterData($status,'integer',1);//查询条件 审核状态
        }else{
            $status = 0;
        }

        $m_id = '';
        if($m_name){
            $m_name = filterData($m_name,'string',20);//查询条件 厂商名字

            $m_info = $Sn->getManufactureFromNameInfo($m_name);//根据名字判断厂商信息是否存在

            if($m_info){
                $m_id = $m_info['id'];//获取厂商的唯一id
            }
        }


     /*   $totalCount = count($Sn->getSnManufactureBatchInfoSelectAll(0,0,'',0));*/
        $totalCount = count($Sn->getBatchInfoFromCondition(0,0,$status,$m_id));

        $pages = new Pagination(['totalCount'=>$totalCount,'defaultPageSize'=>20] );  //传入页面的总页数格式

        //获取总页数

        $data = $Sn->getBatchInfoFromCondition($pages->offset,$pages->limit,$status,$m_id);

        $device_type_info = $Sn->getSnDeviceTypeSelectAll('');

        $remote_type_info = $Sn->getSnRemoteTypeSelectAll('');

        return $this->render('batch_list',[
            'data'=> $this->disBatchInfoList($data,$device_type_info,$remote_type_info),//处理列表负值
            'pages'=>$pages,
        ]);
    }

    /**
     * 官方审批处理
     */
    public function actionCheck_pass()
    {
        $ret = new Result();
        try{
            $id = intval(Yii::$app->request->post('id'));//batch info 自增id

            $new_status = filterData(Yii::$app->request->post('check_status'),'integer',1);//提交申请 状态就是等待审核 1 等待审核 ／2 审核不通过 ／3 审核通过

            //审核
            snBis::checkStatus($id, $new_status);

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }

        return $ret;

    }

    /**
     * @param $m_short 厂商缩写
     * @param $c_short 大类缩写
     * @param $d_short 终端缩写
     * @param $y 年分
     * @param $batch_no 批次号
     * @param $batch_count 生产数量
     */
    protected function disCheckSnInfo($id,$m_short,$c_short,$d_short,$y,$batch_no,$batch_count)
    {

        if(!$m_short){
            show_json(100000,'m_short parameter not legal');
        }


        if(!$c_short){
            show_json(100000,'rc_short parameter not legal');
        }

        if(!$d_short){
            show_json(100000,'d_short parameter not legal');
        }

        if(intval($y) && intval($batch_no)){
            $batch_no = $y.sprintf('%02s',$batch_no);

        }else{
            show_json(100000,'year parameter not legal');
        }

        if(!intval($batch_count)){
            show_json(100000,'batch_count parameter not legal');
        }

        $Sn = new Sn;

        if($Sn->addSnInfo($id,$m_short,$c_short,$d_short,$batch_no,$batch_count)){
            var_db_log($_SESSION['uid']['uid'],'insert','sn_info',[$id,$m_short,$c_short,$d_short,$batch_no,$batch_count,time()]);
            return true;
        }
        return false;
    }


    /****************************所有的sn号列表展示*/
    /**
     * sn 列表
     * @return string
     */
    public function actionSn_info_list()
    {
        $Sn = new Sn;

        //$bid = filterData(Yii::$app->request->get('batch_id',''),'integer',1000);//批次号id

        $res = $Sn->getSnInfoSelectAll(0,1,'');
        $pages = new Pagination(['totalCount'=>isset($res['total'])?$res['total']:0,'defaultPageSize'=>20] );  //传入页面的总页数格式

        //获取总页数
        if(isset(Yii::$app->request->get()['sn'])){
            $snone = filterData(Yii::$app->request->get('sn'),'string',32);
        }else{
            $snone = '';
        }
        $data = $Sn->getSnInfoSelectAll($pages->offset,$pages->limit,$snone);

        return $this->render('sn_info_list',[
            'data'=> $this->disSnInfoList($data['data']),
            'pages'=>$pages
        ]);
    }

    /**
     * 处理sn 展示列表
     * @param $data
     */
    protected function disSnInfoList($data)
    {
        $Sn = new Sn;
        $manufacture_all_info = $Sn->getSnManufactureNameBid();

        $m_list = [];
        foreach ($manufacture_all_info as $key=>$val){
            $m_list[$val['bid']] = $val;
        }

        $new_data = [];
        if($data){
            foreach ($data as $key=>$val){
                $val['manufacture_name'] = (isset($m_list[$val['bid']]['name']))?$m_list[$val['bid']]['name']:'';
                $new_data[$key] = $val;
            }
        }
        return $new_data;
    }

    /**
     * 获取所有sninfo
     * @return mixed
     */
    public function actionGetSnAllInfo()
    {
        $Sn = new Sn;

        $total = $Sn->getSnInfoSelectCount();//获取总条数

        $pages = new Pagination(['totalCount'=>isset($total['count_all'])?$total['count_all']:0,'defaultPageSize'=>20] );  //传入页面的总页数格式

        $data = $Sn->getNewSnInfoSelectAll($pages->offset,$pages->limit);

        return $this->render('sn_info_list',[
          /*  'data'=> $this->disSnInfoList($data),*/
            'data'=> $data,
            'pages'=>$pages
        ]);
    }

    /**
     * 强制用户绑定sn
     */
    public function actionUserBindSn()
    {

        $Sn = new Sn;

        $sn_v = filterData(Yii::$app->request->post('sn'),'string',32);

        $uuid = filterData(Yii::$app->request->post('uuid'),'string',32);//这里使用的是uuid

        if(!$sn_v){
            show_json(100105,'lack sn parameter');
        }

        if(!$uuid){
            show_json(100000,'lack uuid parameter');//确山绑定激活code 码
        }

        if(!$Sn->getBySpPstUuidInfo($uuid)) show_json(100000,'uuid nonentity');

        $device_info = $Sn->getBySnToSninfoSelectOne($sn_v);
        //验证sn是否合法
        if(!$device_info){
            show_json(100010,'device info facility nonentity');//设备信息不存在
        }
        $d_info = $Sn->getBySnToDeviceInfo($device_info['sn_id']);
        if(!$d_info){
            show_json(100010,'device info facility nonentity');//设备信息不存在
        }

        //判断sn_info表bind_time 是否绑定过，记录第一次被绑定的时间 后面可以不用记录
        $up_sninfo_bind_time = 0;//第一次 没有被绑定过
        if(isset($device_info['bind_time']) && $device_info['bind_time'] != 0){
            $up_sninfo_bind_time = 1;
        }

        $this->UnbindSn($sn_v,$uuid,$d_info['did']);

        $this->BindSn($uuid,$sn_v,$device_info['sn_id'],$d_info['did'],$up_sninfo_bind_time);
    }

    /**
     * 解绑sn
     * @param $sn_v
     * @param $uuid
     */
    public function UnbindSn($sn_v,$uuid,$did)
    {
        $Sn = new Sn;

        /**
         * 不管用户是否绑定该sn 先解绑砸在绑定
         */
         $Sn->unbindDeviceToOpenid($uuid,$sn_v,$did);


    }

    public function BindSn($uuid,$sn_v,$sn_id,$did,$up_sninfo_bind_time)
    {
        $Sn = new Sn;

        //添加device与openid的绑定
        $accessCode = getIntCode(8);//系统默认8位
        $res = $Sn->addDeviceUserSp($uuid,$sn_v,$sn_id,$did,$accessCode,$up_sninfo_bind_time);


        if($res){
            show_json(0,'user bind device success',['access_code'=>$accessCode]);

        }

        show_json(100010,'bind sn error');//设备信息不存在
    }

    /**
     * 上传csv文件并保存数据
     */
    public function actionMultiple_save()
    {

        try{
            //如果有文件上传，则根据附件来构造素材类
            if (isset($_FILES['file'])) {
                $fileData = $_FILES['file'];

                //根据上传文件类型，构建文件类实例
                $mime_type = File::getFileMimeType($fileData['tmp_name']);
                list($fileType, $tmp) = explode('/', $mime_type);

                //创建素材类实例
                $resInst = BaseResource::createInstance($fileType);
            } //否则，直接使用素材基类
            else{
                $resInst = new BaseResource();
            }

            //上传文件
            $ret = $resInst->save($fileData);
            //数据提取保存
            if($ret->code == ErrorCode::SUCCEED){
                $file = fopen($ret->data['url'], 'r');
                $i = 1;
                $sns = ''; //sn(组)
                $set1 = ''; //状态更新部分
                while (!feof($file)) {
                    $data = fgetcsv($file);

                    if($i == 1) { //第一行标题栏不要
                        $i++;
                        continue;
                    }
                    if(!empty($data)){
                        if($data[2] == 2 || $data[2] == 3){
                            $set1 .= sprintf(" when '%s' then '%d' ", $data[0], $data[2]);
                            $sns .= sprintf("'%s'", $data[0]) . ',';
                        }
                    }
                }
                fclose($file);
                if($set1 && $sns){
                    $sns = substr($sns, 0, -1);
                    $sql = "update sn_info set `check_status` = case sn {$set1} end where sn in({$sns}) and check_status=1";
                    Yii::$app->db->createCommand($sql)->execute();
                }

                //删除上传文件
                unlink($ret->data['url']);
                show_json(0,'multiple save success');//批量上传成功
            }else{
                @unlink($ret->data['url']);
                show_json($ret->code, $ret->message);//移动文件失败
            }
        }catch (\Exception $e){
            echo $e->getMessage();
            show_json(100010,'fail saved');//设备信息不存在
        }


    }

    public function actionSn_bind_log()
    {
        $ret = new Result();

        if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
            try{
                $sn = filterData(Yii::$app->request->post('sn',''), 'string', 32);//sn
                if(!$sn){
                    throw new Exception('参数错误！', ErrorCode::LACK_PARAMS);
                }

                $obj = new snBis();
                $ret->data = $obj->getSnBindLog($sn);

            }catch (Exception $e){
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
        }else{
            $ret->code = ErrorCode::ERROR;
        }
        return $ret;
    }

}