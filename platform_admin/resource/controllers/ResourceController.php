<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/17
 * Time: 14:17
 */

namespace app\controllers;

use app\models\client;

use Yii;
use yii\web\Controller;
use app\common\util\IUpload;
use app\common\util\Upload;
use yii\data\Pagination;
use app\controllers\base\BaseController;

class ResourceController extends BaseController
{

    protected $p_title= 'resource 管理';//注意更具自己项目做具体定义名称 一级

    protected $d_title= '';//二级

    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分

    protected $redis = null;
    public function init()
    {
        $this->redis = Yii::$app->redis_3;
        $this->layout = false;
        $this->enableCsrfValidation = false;
    }

    /**
     * 通过录入订单码 获取退货订单具体信息
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    //
    public function actionAdd_resource()
    {
        //$_SESSION['user']['username'] = 'zhanglu';
        return $this->render('add_resource');
    }



    public function actionList(){
        $data = Yii::$app->request->queryParams;

        $redis = $this->redis;

        // 缺少用户的机构ID
//        $pro = new client();

        //device type 状态的获取以及检验

        // 名称
        $sn = Yii::$app->request->get('sn','');

        $page = Yii::$app->request->get('page','');

        $buff = substr($sn,0,8);

        $data = $this->redis_common($redis,$page,10,$buff);

        $count = $data['total'];

        $pages = new Pagination(['totalCount'=>$count,'defaultPageSize'=>10] );

//        $data = $pro->getClubClientList($pages->offset,$pages->limit,$client_name)['data'];

        $this->d_title = '资源列表';

        if(isset($data['data'])){

            foreach ($data['data'] as $k=>$val){
                if($val['resource_back_img']){
                    $data['data'][$k]['resource_back_img'] = 'http://'.$_SERVER['HTTP_HOST'].$val['resource_back_img'];
                }
                if($val['resource_icon_img']){
                    $data['data'][$k]['resource_icon_img'] = 'http://'.$_SERVER['HTTP_HOST'].$val['resource_icon_img'];
                }
            }
        }

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('list', [
            'data'=>$data['data'],
            'pages'=>$pages,
            'sn'=>$sn,
        ]);
    }
    /**
    处理列表公共方法
     */

    public function redis_common($redis,$offset=0, $limit=10,$sn='')
    {

        $data = $redis->keys("*");

        $pro_data = [];
        $count = 0;
        foreach ($data as $k=>$val){
            $pos = strstr($val,'resource_img_info:');
            if($pos){
                $pos_arr = explode(':',$pos);
                if($pos_arr && !empty($pos_arr)){
                    $user= $redis->get(Yii::$app->params['resource_img_info'].$pos_arr[1]);
                    $pro_data[] = json_decode($user,true);
                    $count ++;
                }
            }

        }

        $new_data = array_column($pro_data,'data_json');

        $ctime_str = array();
        foreach($new_data as $key=>$v){
            if(isset($v['created_ts'])){
                $arr[$key]['ctime_str'] = $v['created_ts'];
                $ctime_str[] = $arr[$key]['ctime_str'];
            }else{
                continue;
            }
        }

        array_multisort($ctime_str,SORT_ASC,$new_data);
        // 键值排序
        if(!empty($pro_data)){
            krsort($new_data);
        }

        $i=0;
        $list=[];
        $search_data=[];
        $list_data=[];

        if($new_data) {
            // 搜索查找
            if($sn <> ''){
                foreach ($new_data as $key=>$ite){
                    if(isset($ite['sn'])){
                        if($sn == $ite['sn']){
                            $i ++;
                            $search_data[] = $ite;
                        }
                    }

                }

                if ($search_data){
                    $list_data = $search_data;
                }
                unset($search_data);

            }else{
                $list_data = $new_data;

            }

            /*echo '<pre>';
            var_dump($pro_data);
            //var_dump($list);
           die;*/

            // 分页
            $list = page_array($offset, $limit,$list_data,0);


        }
        if($i <> 0){
            $total = $i;
        }else{
            $total= count($list_data);
        }


        return ['total'=>$total,'data'=>$list];
    }
    /**
     * 通过录入订单码 获取退货订单具体信息
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    //
    public function actionEdit()
    {
        $sn = Yii::$app->request->get('sn','');

        $redis = $this->redis;

        $resource_img_info_key = Yii::$app->params['resource_img_info'].$sn;

        if($redis->exists($resource_img_info_key)){

            $data = json_decode($redis->get($resource_img_info_key),true);

            $this->d_title = ' / 编辑资源';

            //$this->o_text = '添加keymap/小车';//o_title 内容可以为空，内容自己可以组装

            setNavHtml($this->p_title,$this->d_title);

            return $this->render('edit',['data'=>$data['data_json']]);
        }

    }

    public function actionClient_detail()
    {
        $client_id = Yii::$app->request->get('client_id','0');
        $user_id = $_SESSION['user_id'] = '10010';
        $user_name = $_SESSION['user_name'] = '张大仙';
        $product = new client();
        // 查找当前产品下的具体信息
        $data = $product->getDetailClientId($client_id);

        $this->d_title = ' / 账号详情';

        //$this->o_text = '添加keymap/小车';//o_title 内容可以为空，内容自己可以组装

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('client_detail',['data'=>$data,'user_id'=>$user_id,'user_name'=>$user_name,]);

    }
    /**
     * 产品删
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionDelete()
    {

        $resource_id = Yii::$app->request->post('resource_id','');
        if(empty($resource_id)){
            show_json(100000,'选择删除项~');
        }

        $redis = $this->redis;

        $resource_img_info_key = Yii::$app->params['resource_img_info'].$resource_id;

        if($redis->exists($resource_img_info_key)){
            $redis->del($resource_img_info_key);
            show_json(0,'删除成功！');
        }
        show_json(100000,'删除失败！');


    }

    /**
     * 产品删
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionUpload()
    {
        $base64_img = Yii::$app->request->post('file','');

//        $base64_img = trim($_POST['img']);
        $up_dir = realpath(DOCROOT . DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'upload/';//存放在当前目录的upload文件夹下

        if(!file_exists($up_dir)){
            mkdir($up_dir,0777);
        }

        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)){
            $type = $result[2];
            if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
                $new_file = $up_dir.date('YmdHis_').'.'.$type;
                if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))){
                    $img_path = str_replace('../../..', '', $new_file);
//                    echo '图片上传成功</br>![](' .$img_path. ')';
                    $new_file = strstr($new_file,'/resource');
                    show_json(0,'图片上传成功',$new_file);

                }else{
                    show_json(100000,'图片上传失败');
                }
            }else{
                //文件类型错误
                show_json(100000,'图片上传类型错误');
            }

        }else{
            //文件错误
            show_json(100000,'文件错误');
        }

    }

    /**
     * 视频上传
     *
     */
    public function actionVideo_upload(){
        $videos= Upload::saveHash($_FILES['file']);

        if($videos){
            show_json(0,'视频上传成功',strstr($videos['filename'],'/resource'));
        }
        show_json(100000,'上传失败');

    }

    /**
     * 产品提交
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionSubmit()
    {

        $buff_post = filterData(Yii::$app->request->post('buff',''),'string',32);
        $sn = filterData(Yii::$app->request->post('sn',''),'string',32);

        if(empty($sn)){
            show_json(100000,'请输入设备sn.');
        }

        if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$sn)){
            show_json(100000,'SN 不能有中文，请重新输入。');
        }

        $buff = substr($sn,0,8);
        //
        $resource_rotate_video= filterData(Yii::$app->request->post('resource_rotate_video',''),'string',255);
        $resource_back_img= filterData(Yii::$app->request->post('resource_back_img',''),'string',255);
        $resource_icon_img= filterData(Yii::$app->request->post('resource_icon_img',''),'string',255);

//        $pathinfo = pathinfo($resource_rotate_video);
//
//        $ext = $pathinfo['extension'];


        // desc
        $desc = filterData(Yii::$app->request->post('desc',''),'string',1024);
        $type = filterData(Yii::$app->request->post('type','1'),'int',11);

        $redis = $this->redis;

        $resource_img_info_key = Yii::$app->params['resource_img_info'].$buff;

        if($buff_post <> ''){
            $resource__key = Yii::$app->params['resource_img_info'].$buff_post;
            $ori_data = json_decode($redis->get($resource__key),true);

            if($resource_back_img <> ''){
                $ori_data['data_json']['resource_back_img'] = $resource_back_img;
            }
            if($resource_icon_img <> ''){
                $ori_data['data_json']['resource_icon_img'] = $resource_icon_img;
            }
            if($resource_rotate_video <> ''){
                // 后缀不是MP4 则返回
                if(strpos($resource_rotate_video,'mp4') === false){
                    show_json(100000,'视频格式不是mp4,请重新选择.');
                }
                $ori_data['data_json']['resource_rotate_video'] = $resource_rotate_video;
            }
            $ori_data['data_json']['desc'] = $desc;
            $ori_data['data_json']['updated_ts'] = time();

            $redis->set($resource__key,json_encode($ori_data));

            show_json(0,'修改成功');

        }else {
            // 验证sn 是否已经添加过
            $ori_key=Yii::$app->params['resource_img_info'].$buff;

            if($redis->exists($ori_key)){
                show_json(100000,'该SN已添加过资源，请重新添加');
            }

            // 后缀不是MP4 则返回
            if(strpos($resource_rotate_video,'mp4') === false){
                show_json(100000,'视频格式不是mp4,请重新选择.');
            }

            $data_json = array(
                'resource_id'=>createGuid(),
                'sn'=>$buff,
                'resource_rotate_video'=>$resource_rotate_video,
                'resource_back_img'=>$resource_back_img,
                'resource_icon_img'=>$resource_icon_img,
                'desc'=>$desc,
                'type'=>$type,
                'created_ts'=>time(),
                'updated_ts'=>0,
            );

            $data =[
                'data_table'=>'resource',
                'data_json'=>$data_json
            ];

//            $files = (new IUpload('2024',['jpg','gif','png']))->execute();
//
//            if($files){
//                foreach ($files as $k=>$file){
//                    if($file[0]['error'] == '上传成功'){
//                        $files[$k] =strstr($file[0]['fileSrc'],'/resource');
//                    }
//                }
//            }

//            $data = array_merge($data,$files);

            $redis->set($resource_img_info_key,json_encode($data));

//            Yii::$app->getSession()->setFlash('success', '添加成功');

//            $this->redirect(array('/resource/list'));
            show_json(0,'添加成功');

        }
//        Yii::$app->getSession()->setFlash('error', '添加失败！');

    }

    public function actionCheck_sn(){
        $sn = filterData(Yii::$app->request->post('sn',''),'string',128);

        $buff = substr($sn,0,8);
        $ori_key=Yii::$app->params['resource_img_info'].$buff;
        $redis = $this->redis;
        if($redis->exists($ori_key)){
            show_json(100000,'该SN已添加过图片资源，请勿重复添加.');
        }
    }


}
