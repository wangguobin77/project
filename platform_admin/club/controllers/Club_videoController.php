<?php
/**
 * Created by PhpStorm.
 * User: zhanglu
 * Date: 18-10-22
 * Time: 上午10:43
 */



namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\video;
use yii\data\Pagination;
use common\core\BaseCore;

class Club_videoController extends Controller
{

    protected $p_title= '视频管理';//注意更具自己项目做具体定义名称 一级

    protected $d_title= '';//二级

    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分

    public $enableCsrfValidation=false;

    private $redis = null;
    public function init()
    {
        parent::init();
        $this->redis = Yii::$app->redis_8;
        $this->enableCsrfValidation = false;
        $this->layout = 'main';
    }

    public function actionTest()
    {
        echo 11111;
    }


    /**
        视频列表
     */

    public function actionVideo_list(){

        $data = Yii::$app->request->queryParams;

        // 缺少用户的机构ID
        $pro = new video();
        //device type 状态的获取以及检验

        $redis = $this->redis;
        $all_data = $redis->keys("*");
        $video_list = [];
        $count = 0;
        foreach ($all_data as $k=>$val){
            $pos = strstr($val,'video_detail_key:');
            if($pos){
                $pos_arr = explode(':',$pos);
                if($pos_arr && !empty($pos_arr)){
                    $user= $redis->get(Yii::$app->params['video_detail_key'].$pos_arr[1]);
                    $video_list[] = json_decode($user,true);
                    $count ++;
                }
            }

        }



        // 推荐
        $status = Yii::$app->request->get('status','');

        //符合条件的记录总数
        //$count = $pro->getClubVideoList('','',$status)['count'];

        $pages = new Pagination(['totalCount'=>$count,'defaultPageSize'=>15] );

        $data = $this->redis_video_common($redis,$pages->offset,$pages->limit,$status);

        //$data = $pro->getClubVideoList($pages->offset,$pages->limit,$status)['data'];

        $this->d_title = '视频列表';

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('video_list', [
            'data'=>$data,
            'pages'=>$pages,
            'status'=>$status,
        ]);
    }

    /**
    处理列表公共方法
     */

    public function redis_video_common($redis,$offset=0, $limit=20,$status)
    {
        $data = $redis->keys("*");
        $pro_data = [];
        $count = 0;
        foreach ($data as $k=>$val){
            $pos = strstr($val,'ota_version_detail_key:');
            if($pos){
                $pos_arr = explode(':',$pos);
                if($pos_arr && !empty($pos_arr)){
                    $user= $redis->get(Yii::$app->params['ota_version_detail_key'].$pos_arr[1]);
                    $pro_data[] = json_decode($user,true);
                    $count ++;
                }
            }

        }

        $i=0;
        $list=[];
        $search_data=[];
        $list_data=[];
        if($pro_data) {
            // 搜索查找
            if($status <> ''){
                //echo $ver_name;
                foreach ($pro_data as $key=>$ite) {
                    if (isset($ite['status']) && !empty($ite['pro_id'])) {

                        if ($status == $ite['status'] && $ite['pro_id'] == $pro_id) {
                            //var_dump($ite);
                            $search_data[] = $ite;
                        }else{
                            unset($pro_data[$key]);
                        }
                    }

                }

                if ($search_data){
                    $list_data = $search_data;
                }
                //unset($search_data);

            }else{

                $list_data = $pro_data;

            }


            // 分页显示数据
            if($list_data){
                foreach ($list_data as $k=>$val) {
                    if(isset($val['pro_id'])){
                        if($pro_id == $val['pro_id']){
                            $list[] = $list_data[$k];
                            $i++;
                        }else{
                            continue;
                        }
                    }
                }
            }
            unset($search_data,$pro_data,$list_data);
        }


        return ['total'=>$i,'data'=>$list];
    }



    /**
     * 编辑视频
     */
    public function actionVideo_edit()
    {
        $v_id = Yii::$app->request->get('id','0');

        $product = new video();
        // 查找当前产品下的具体信息
        $data = $product->getDetailVideoId($v_id);
        $this->d_title = '编辑视频';

        //$this->o_text = '添加keymap/小车';//o_title 内容可以为空，内容自己可以组装

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('video_edit',['data'=>$data]);

    }
    /**
     * 提交视频
     */
    public function actionVideo_submit()
    {
        $video_id = filterData(Yii::$app->request->post('video_id','0'),'int',11);
        $is_recommended = filterData(Yii::$app->request->post('is_recommended','0'),'int',2);

        $product = new video();

        // 设置推荐
        $res = $product->getEditVideoId($video_id,$is_recommended);

        if(isset($res)){
            show_json(0,'修改成功');
        }else{
            show_json(100000,'修改失败！');
        }

    }


    /**
     * 删除视频
     */
    public function actionVideo_del()
    {
        $video_id = filterData(Yii::$app->request->post('video_id','0'),'int',11);

        $product = new video();

        // 设置推荐
        $res = $product->getVideoDel($video_id);


        if(isset($res)){
            // 同步缓存中的数据


            show_json(0,'删除成功');
        }else{
            show_json(100000,'删除失败！');
        }

    }


}