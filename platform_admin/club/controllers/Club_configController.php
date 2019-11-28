<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/17
 * Time: 14:17
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\client;
use app\models\club_config;

use yii\data\Pagination;
use app\controllers\base\BaseController;

class Club_configController extends BaseController
{


    protected $p_title= '配置管理';//注意更具自己项目做具体定义名称 一级

    protected $d_title= '';//二级

    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分

    public function init()
    {
        $this->layout = false;
        $this->enableCsrfValidation = false;
    }
    /**
     * 添加版本
     * @return array|false
     */
    //
    public function actionConfig_add()
    {
        $config = new club_config();

        $types = $config->getClubTypesAll();
        $clients = $config->getClubClientsAll();


        return $this->render('config_add',['version_types'=>$types]);

    }


    public function actionConfig_list(){
//        $data = Yii::$app->request->queryParams;

        // 缺少用户的机构ID
        $config = new club_config();

        // 名称
        $client_id = Yii::$app->request->get('client_id','0');

        //符合条件的记录总数
        $count = $config->getClubConfigList('','',$client_id)['count'];

        $pages = new Pagination(['totalCount'=>$count,'defaultPageSize'=>10] );

        $data = $config->getClubConfigList($pages->offset,$pages->limit,$client_id)['data'];
        // 产品类型

        $this->d_title = ' / 配置列表';

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('config_list', [
            'data'=>$data,
            'pages'=>$pages,
            'client_id'=>$client_id,
        ]);
    }




    /**
     * 添加TYPE
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionType_submit()
    {
        $client_id = Yii::$app->request->post('client_id','');
        $type = Yii::$app->request->post('type','');


        if(empty($client_id)){
            show_json(100000,'CLIENT ID ERROR!');
        }
        if(empty($type)){
            show_json(100000,'PLS ENTER TYPE!');
        }



        $config = new club_config();
        // 验证输入的type 是不是归属定义好的
        $client_res = $config->getExistClientId($client_id);

        if(!$client_res){
            show_json(100000,'来源信息错误');
        }

        $types = $config->getClubTypesAll();


        $types = array_column($types, 'type');

        if(!in_array($type,$types)){
            show_json(100000,'输入的TYPE有误，请规范输入!(PS:小写为主)');
        }


        // 查找当前产品下的具体信息
        $res = $config->getTypeSubmit($client_res['client_id'],$type);

        if($res['@ret'] == '-1'){
            show_json(100000,'来源类型已存在！');
        }
        if(isset($res) && $res['@ret'] > 0){
            show_json(0,'添加成功！');
        }else{
            show_json(100000,'添加失败！');
        }

    }


    /**
     * 产品删
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionType_del()
    {

        $ver_id = Yii::$app->request->post('id','0');

        if(empty($ver_id)){
            show_json(100000,'选择删除项~');
        }

        $product = new club_config();


        $res = $product->getTypeDelete($ver_id);


        if(isset($res)){
            show_json(0,'删除成功！');
        }else{
            show_json(100000,'删除失败！~');
        }

    }


    /**
     * 产品提交
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionVersion_submit()
    {

        $ver_id = Yii::$app->request->post('ver_id','0');
        $pro_id = Yii::$app->request->post('pro_id','0');
        $user_id = Yii::$app->request->post('user_id','100088');
        // 描述
        //$status = Yii::$app->request->post('status','0');
        // 产品名称
        $ver_name= Yii::$app->request->post('ver_name','');
        // 产品类型
        $is_init= Yii::$app->request->post('is_init','1');
        /*if(empty($user_id)){
            show_json(100000,'提交人不能为空！');
        }*/





        $product = new version;
        // 编辑版本
        if($ver_id > 0){

            // 验证版本名 及产品名 是否已经存在。
            $pack_data = $product->getVersionDetailInfo($ver_id);
            $res = $product->getExistVersion($pack_data['pro_id'],$ver_name);
            if(!empty($res) && $pack_data['ver_name'] <> $ver_name){
                show_json(100000,'编辑产品版本名已经存在，请修改');
            }


            $res = $product->getVersionEdit($ver_id,$pro_id,$ver_name,$is_init);
            if($res){
                show_json(0,'修改成功',$ver_id);
            }else{
                show_json(100000,'修改失败！');
            }
        }
        // 新增
        else{

            // 验证版本名 及产品名 是否已经存在。
            $res = $product->getExistVersion($pro_id,$ver_name);
            if(!empty($res)){
                show_json(100000,'添加产品版本名已经存在，请勿重复添加！');
            }
            $version_id = $product->getVersionAdd($pro_id,$ver_name,$is_init,0,$user_id)['@ret'];

            if($version_id > 0 ){
                show_json(0,'版本添加成功',$version_id);
            }else{
                show_json(100000,'版本添加失败！');
            }
        }

    }



}
