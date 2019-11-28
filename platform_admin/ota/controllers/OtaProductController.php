<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/4/11
 * Time: 下午3:19
 */

namespace app\controllers;


use yii;
use app\models\db\Product;
use app\models\db\Version;

use yii\data\Pagination;
use app\controllers\base\BaseController;

use  common\util\IFilter;
use  common\util\IReq;
class OtaProductController extends BaseController
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
     * 产品列表
     * @return string
     */
    public function actionProduct_list()
    {
        $type = IFilter::act(IReq::get('pro_type')); // 类型

        if($type){
            $product_list = Product::find()->where(['type'=>$type]);
        }else{
            $product_list = Product::find();
        }

        $b_product_list = clone $product_list;

        $pages = new Pagination(['totalCount'=>$b_product_list->count(),'defaultPageSize'=>10] );

        $models = $product_list->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_ts DESC')->all();
        // 产品类型
        $types = \Yii::$app->params['product_type'];

        $this->d_title = '产品列表';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('product_list',[
            'data'=>$models,
            'types'=>$types,
            'pages'=>$pages,
            'pro_type'=>$type,
            'user_id' => $_SESSION['uid']['uid']
        ]);
    }

    /**
     * 添加产品页面
     * @return string
     */
    public function actionAddProduct()
    {

        $product_type = Yii::$app->params['product_type'];

        $this->d_title = ' / 添加产品';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('addProduct',[
            'product_types'=>$product_type,
            'user_id'=>$_SESSION['uid']['uid']
        ]);

    }

    /**
     * 产品提交
     *
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionProduct_submit()
    {
        $desc = IFilter::act(IReq::get('desc'),'string',2048); // 描述

        $product_name = IFilter::act(IReq::get('product_name'),'string',255);// 产品名称

        $product_code = IFilter::act(IReq::get('product_code'),'string',255);// 产品code

        $type = IFilter::act(IReq::get('type'),'int',10);// 产品类型


        if(!$type){
            show_json(100000,'请选择产品类型！');
        }

        if(!$product_name){
            show_json(100000,'产品名称不能为空！');
        }

        if(!$product_code){
            show_json(100000,'产品code不能为空！');
        }

        //判断是否存在相同产品名称
        $p_data = Product::find()->where(['pro_name'=>$product_name])->all();

        if($p_data) show_json(100000,'已存在相同产品名称');

        $Product = new Product;

        $Product->pro_id = createGuid();
        $Product->pro_name = $product_name;
        $Product->pro_code = $product_code;
        $Product->staff_id = $_SESSION['uid']['uid'];//当前登陆的系统用户id
        $Product->staff_name = isset($_SESSION['uid']['username'])?$_SESSION['uid']['username']:'';//用户名称
        $Product->created_ts = time();
        $Product->updated_ts = 0;
        $Product->description = $desc;//产品描述
        $Product->type = $type;


        if ($Product->save()) {
            show_json(0,'添加产品成功');
        }

        show_json(100000,'添加产品失败');

    }


    /***********修改*****************/
    /**
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionProduct_edit()
    {
        $pro_id = IFilter::act(IReq::get('pro_id')); // 产品id

        if(!$pro_id){
            set_ses_data(100000,'产品id参数不能为空！');
            return $this->redirect(['ota-product/product_list']);
        }

        $ver_info = Version::find()->where(['pro_id'=>$pro_id])->asArray()->all();

        if($ver_info){
            set_ses_data(100000,'该产品下已存在版本,不能再次编辑');
            return $this->redirect(['ota-product/product_list']);
        }

        $data = Product::findOne($pro_id);

        if(!$data){
            set_ses_data(100000,'Data information does not exist');
            return $this->redirect(['ota-product/product_list']);
        }

        $types = \Yii::$app->params['product_type'];

        $this->d_title = '编辑列表';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('product_edit',['data'=>$data,'user_id'=>$_SESSION['uid']['uid'],'product_types'=>$types]);

    }

    /**
     * 修改页提交
     */
    public function actionProduct_edit_submit()
    {

        $pro_id = IFilter::act(IReq::get('pro_id')); // 产品id

        if(!$pro_id){
            show_json(100000,'产品id参数不能为空！');
        }

        $desc = IFilter::act(IReq::get('desc'),'string',2048); // 描述

        $product_name = IFilter::act(IReq::get('product_name'),'string',255);// 产品名称

        $product_code = IFilter::act(IReq::get('product_code'),'string',255);// 产品code

        $type = IFilter::act(IReq::get('type'),'int',10);// 产品类型

        //判断是否存在相同产品名称
        $p_data = Product::find()->where(['pro_name'=>$product_name])->asArray()->one();

        if($p_data && $p_data['pro_id'] !=  $pro_id) show_json(100000,'产品名称已经存在');


        $Product = Product::findOne($pro_id);

        $Product->pro_name = $product_name;
        $Product->pro_code = $product_code;
        $Product->staff_id = $_SESSION['uid']['uid'];//当前登陆的系统用户id
        $Product->staff_name = isset($_SESSION['uid']['username'])?$_SESSION['uid']['username']:'';//用户名称
        $Product->created_ts = $Product['created_ts'];
        $Product->updated_ts = time();
        $Product->description = $desc;//产品描述
        $Product->type = $type;

        if ($Product->save()) {
            show_json(0,'修改产品成功',$pro_id);
        }

        show_json(100000,'修改失败');

    }

    /********************删除*******************/
    /**
     * 产品删
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionProduct_del()
    {
        $pro_id = IFilter::act(IReq::get('pro_id')); // 产品id

        if(!$pro_id){
            show_json(100000,'产品id参数不能为空！');
        }


        $ProductOneInfo = Product::findOne($pro_id);

        if(!$ProductOneInfo) show_json(100000,'删除的设备信息不存在');


        $ver_info = Version::find()->where(['pro_id'=>$pro_id])->asArray()->all();

        if($ver_info)  show_json(100000,'该产品下已存在版本,不能删除');


        $verListInfo = Version::find()->where(['pro_id'=>$pro_id])->all();//查看当前产品下是否已经添加版本 如存在不能删除

        if($verListInfo) show_json(100000,'该产品下已经存在版本,产品不能删除');


        if($ProductOneInfo->delete()){
            show_json(0,'删除成功');
        }

        show_json(100000,'删除失败');

    }
}