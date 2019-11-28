<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/17
 * Time: 14:17
 */

namespace app\controllers;

use app\models\db\DiffPackage;
use Yii;
use app\models\db\Version;
use app\models\db\Product;

use yii\data\Pagination;
use app\controllers\base\OtaComBaseController;

use  common\util\IFilter;
use  common\util\IReq;
class OtaVersionController extends OtaComBaseController
{

    protected $p_title= '产品管理';//注意更具自己项目做具体定义名称 一级
    protected $d_title= '';//二级
    protected $o_text= '';//三级如果存在 自定义追加的内容 如果存在四五级 请自己用／划分

    protected $redis = null;
    public function init()
    {

        $this->redis = Yii::$app->redis_3;
        $this->layout = false;
        $this->enableCsrfValidation = false;
    }

    public function actionVersion_list(){

        $pro_id = IFilter::act(IReq::get('pro_id'));// 产品id

        if(!$pro_id) show_json(100000,'缺少产品id');

        $ver_data_list = Version::find()->where(['pro_id'=>$pro_id]);//根据产品id 查询相关信息

        $Product_data_list = Product::find()->where(['pro_id'=>$pro_id])->asArray()->one();//获取全部产品列表

        $b_ver_data_list = clone $ver_data_list;
        $pages = new Pagination(['totalCount'=>$b_ver_data_list->count(),'defaultPageSize'=>10] );

        $models = $ver_data_list->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_ts DESC')->all();

        $this->d_title = ' / '.isset($Product_data_list['pro_name'])?$Product_data_list['pro_name']:'';

        $this->o_text = " / 版本列表";

        return $this->render('version_list', [
            'data'=>$models,
            'pages'=>$pages,
            'pro_id'=>$pro_id,
            'pro_names'=>Product::find()->asArray()->all(),
            'user_id'=>$_SESSION['uid']['uid'],
        ]);
    }

    /**
     * 添加新版本 提交信息
     */
    public function actionVersion_submit()
    {
        $ver_name = IFilter::act(IReq::get('ver_name'),'string',128); //版本名称 r如：v1.0.1

        $is_full = intval(IFilter::act(IReq::get('is_full'),'int'));// 是否整包升级

        $is_up_holt = intval(IFilter::act(IReq::get('is_up_holt'),'int'));// 是否整体资源更新

        $pro_id = IFilter::act(IReq::get('pro_id'));// 产品id

        if(!$ver_name)  show_json(100000,'缺少版本名称');

        if(!$pro_id)  show_json(100000,'缺少产品id');


        //判断是否存相同的版本名称
        $data = Version::find()->where(['ver_name'=>$ver_name,'pro_id'=>$pro_id])->one();

        $ischeckStatusInfo = Version::find()->where(['is_init'=>0])->one();//查看是否有存在初始的版本 如果已经存在后面的版本就不能再是初始化版本了

        if($data) show_json(100000,'当前版本名称已经存在');

        $Version = new Version;

        $Version->ver_name = $ver_name;//版本名称
        $Version->pro_id = $pro_id;//产品id
        $Version->is_up_holt = $is_up_holt;//是否整体资源更新
        $Version->is_full = $is_full;//是否整包升级  1是整包  0是差分包
        $Version->is_init = $ischeckStatusInfo?1:0;//是否是初始化 如已存在初始化版本 后面只能是1
        $Version->status = 0;//版本发布状态 -1 警用 0 未发布  1 已发布
        $Version->staff_id = $_SESSION['uid']['uid'];//操作人id
        $Version->created_ts = time();//创建时间
        $Version->updated_ts = 0;//修改时间

        if ($Version->save()) {
            show_json(0,'添加版本成功');
        }

        show_json(100000,'添加版本失败');
    }

    /**
     * 删除版本  只有当该版本下没有包才可以删除
     */
    public function actionVersion_del()
    {
        $ver_id = IFilter::act(IReq::get('ver_id')); //版本名称 r如：v1.0.1

        if(!$ver_id) show_json(100000,'缺少版本id');

        $verInfo = Version::findOne($ver_id);

        if(!$verInfo) show_json(100000,'不存在当前的版本信息');

        $proInfo = Product::find()->where(['pro_id'=>$verInfo['pro_id']])->asArray()->one();

        if(!$proInfo) show_json(100000,'所属的产品信息丢失');

        $data = DiffPackage::find()->where(['b_ver_id'=>$this->setonlyPackId($proInfo['pro_code'],$verInfo['ver_name'])])->asArray()->all();

        $data1 = DiffPackage::find()->where(['from_ver_id' =>$ver_id])->asArray()->all();
        if($data || $data1) show_json(100000,'该版本下已存在包数据,不能删除');

        if(Version::findOne($ver_id)->delete()) show_json(0,'删除成功');

        show_json(100000,'删除失败');
    }


    public function actionVersion_edit()
    {
        $ver_id = IFilter::act(IReq::get('ver_id')); //版本名称 r如：v1.0.1

        if(!$ver_id) show_json(100000,'缺少版本id');

        $verInfo = Version::findOne($ver_id);

        if(!$verInfo) show_json(100000,'不存在当前的版本信息');

        $proInfo = Product::find()->where(['pro_id'=>$verInfo['pro_id']])->asArray()->one();

        if(!$proInfo) show_json(100000,'所属的产品信息丢失');

        $data = DiffPackage::find()->where(['b_ver_id'=>$this->setonlyPackId($proInfo['pro_code'],$verInfo['ver_name'])])->asArray()->all();

        if($data) show_json(100000,'该版本下已存在包数据,不能编辑');

        $this->d_title = '编辑版本';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('version_edit',['data'=>$verInfo,'user_id'=>$_SESSION['uid']['uid']]);
    }

    public function actionVersion_edit_submit()
    {
        $ver_id = IFilter::act(IReq::get('ver_id')); //版本名称 r如：v1.0.1

        $ver_name = IFilter::act(IReq::get('ver_name'));

        if(!$ver_id) show_json(100000,'缺少版本id');

        if(!$ver_name) show_json(100000,'缺少版本名称');

        $Version = Version::findOne($ver_id);

        $Version->ver_name = $ver_name;

        if($Version->save()) show_json(0,'修改版本信息成功');


        show_json(100000,'修改版本信息失败');
    }
}
