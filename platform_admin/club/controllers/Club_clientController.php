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

use yii\data\Pagination;
use app\controllers\base\BaseController;

class Club_clientController extends BaseController
{

    protected $p_title= '产品管理';//注意更具自己项目做具体定义名称 一级

    protected $d_title= '';//二级

    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分



    public function init()
    {
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
    public function actionAdd_client()
    {
        //$_SESSION['user']['username'] = 'zhanglu';

        return $this->render('add_client');

    }



    public function actionClient_list(){
        $data = Yii::$app->request->queryParams;


        // 缺少用户的机构ID
        $pro = new client();

        //device type 状态的获取以及检验

        // 名称
        $client_name = Yii::$app->request->get('client_name','');

        //符合条件的记录总数
        $count = $pro->getClubClientList('','',$client_name)['count'];

        $pages = new Pagination(['totalCount'=>$count,'defaultPageSize'=>10] );

        $data = $pro->getClubClientList($pages->offset,$pages->limit,$client_name)['data'];

        $this->d_title = '账号列表';

        //$this->o_text = '添加keymap/小车';//o_title 内容可以为空，内容自己可以组装

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('client_list', [
            'data'=>$data,
            'pages'=>$pages,
            'client_name'=>$client_name,
            //'user_id'=>$_SESSION['user_id'] = 100088,
        ]);
    }

    /**
     * 通过录入订单码 获取退货订单具体信息
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    //
    public function actionClient_edit()
    {
        $client_id = Yii::$app->request->get('client_id','0');

        $product = new client();
        // 查找当前产品下的具体信息
        $data = $product->getDetailClientId($client_id);

        $this->d_title = ' / 编辑来源';

        //$this->o_text = '添加keymap/小车';//o_title 内容可以为空，内容自己可以组装

        setNavHtml($this->p_title,$this->d_title);

        return $this->render('client_edit',['data'=>$data]);

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
    public function actionClient_del()
    {

        $client_id = Yii::$app->request->post('client_id','0');
        if(empty($client_id)){
            show_json(100000,'选择删除项~');
        }
        $product = new client();
        // 查找当前产品下是否有版本 有则 不能删除

        $exist = $product->getExistType($client_id);

        if($exist){
            show_json(100000,'该产品有TYPE配置，请前往TYPE管理删除配置。');
        }

        $res = $product->getClientDelete($client_id);

        if($res){
            show_json(0,'删除成功！');
        }else{
            show_json(100000,'删除失败！');
        }

    }


    /**
     * 产品提交
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionClient_submit()
    {

        $pro_id = filterData(Yii::$app->request->post('pro_id','0'),'int',11);

        // 产品名称
        $client_name= filterData(Yii::$app->request->post('client_name',''),'string',255);
        // 产品CODE
        $client_id = filterData(Yii::$app->request->post('client_id',''),'string',255);
        $status = filterData(Yii::$app->request->post('status',''),'string',2);


        $client = new client();

        if($pro_id>0){
            // 查找当前产品下的信息 via id
            $ori_res = $client->getClubClientById($pro_id);

            $code_res = $client->getExistClientId($client_id);
            $name_res = $client->getExistClientName($client_name);


            if($ori_res['client_id'] != $code_res['client_id'] && $code_res['client_id'] <> ''){
                show_json(100000,'来源ID 已存在，请重新输入');
            }
            if(($name_res['client_name'] <> '') && $name_res['client_name'] != $ori_res['client_name']){
                show_json(100000,'来源已存在，请重新输入！');
            }
            $res = $client->getClubClientEdit($pro_id,$client_id,$client_name,$status);

            if(isset($res)){
                show_json(0,'修改成功',$client_id);
            }else{
                show_json(100000,'修改失败！');
            }
        }else {

            $code_res = $client->getExistClientId($client_id);

            $name_res = $client->getExistClientName($client_name);


            if ($code_res) {
                show_json(100000, '来源ID 已存在，请重新输入');
            }
            if ($name_res) {
                show_json(100000, '来源已存在，请重新输入！');
            }


            $id = $client->getClientAdd($client_name, $client_id, $status)['@ret'];

            if ($id > 0) {
                show_json(0, '产品添加成功', $client_id);
            } else {
                show_json(100000, '产品添加失败！');
            }
        }

    }



}
