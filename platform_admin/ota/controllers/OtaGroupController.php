<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/17
 * Time: 14:17
 */

namespace app\controllers;

use app\models\db\DiffPackage;
use app\models\db\Version;
use app\models\db\Product;
use Yii;
use app\models\db\GrayGroup;
use app\models\db\GroupSn;
use app\models\db\DiffPackageGroup;

use yii\data\Pagination;
use app\controllers\base\OtaComBaseController;

use  common\util\IFilter;
use  common\util\IReq;
class OtaGroupController extends OtaComBaseController
{

    protected $p_title= '灰度组管理';//注意更具自己项目做具体定义名称 一级
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
     * 灰度组列表
     * @return string
     */
    public function actionGroup_list()
    {

        $group_data_list = GrayGroup::find();//获取全部灰度组信息

        $b_group_data_list = clone $group_data_list;

        $pages = new Pagination(['totalCount'=>$b_group_data_list->count(),'defaultPageSize'=>10] );

        $models = $group_data_list->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_ts DESC')->all();

        $this->d_title = ' / 灰度组列表';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('group_list', [
            'data'=>$models,
            'pages'=>$pages,
            'user_id' => $_SESSION['uid']['uid'],
            'sum_info' =>$this->getGroupBindCountSn()

        ]);
    }

    /**
     * 表单
     * 灰度组信息提交
     * @param string $order_type 绑定添加按钮
     * @param string $order_sn
     * @return array|false
     */
    public function actionGroup_submit()
    {

        $group_name = IFilter::act(IReq::get('group_name'));// 灰度组名称

        $description = IFilter::act(IReq::get('description'));// 灰度组描述

        if(!$group_name) show_json(100000,'灰度组名称不能为空');


        //判断是否存相同的版本名称
        $data = GrayGroup::find()->where(['group_name'=>$group_name])->one();

        if($data) show_json(100000,'灰度组名称不能相同');

        $GrayGroup = new GrayGroup;

        $GrayGroup->group_name = $group_name;
        $GrayGroup->description = $description;
        $GrayGroup->status = 1;//灰度组状态-1：删除，0：禁用，1:正常
        $GrayGroup->staff_id = $_SESSION['uid']['uid'];//操作人员id
        $GrayGroup->created_ts = time();
        $GrayGroup->updated_ts = 0;

        if($GrayGroup->save()) show_json(0,'添加灰度组成功');

        show_json(100000,'添加灰度组失败');

    }

    /**
     * 选择关联数组的页面
     * @return string
     */
    public function actionSelect_group()
    {

        $pack_id = IFilter::act(IReq::get('pack_id')); //包id

        $onePackData = DiffPackage::findOne($pack_id);

        if(!$onePackData) show_json(100000,'不存在相关数据');

        $packageGroupList = DiffPackageGroup::find()->where(['sp_pack_id'=>$pack_id])->asArray()->all();//获取包与所有灰度组有绑定关系的

        $BindGroupIdList = $this->getPackageBindGroupList($packageGroupList);//处理数据 返回数组

        $group_data_list = GrayGroup::find();//获取全部灰度组信息

        $b_group_data_list = clone $group_data_list;
        $pages = new Pagination(['totalCount'=>$b_group_data_list->count(),'defaultPageSize'=>10] );

        $data = $group_data_list->offset($pages->offset)
            ->limit($pages->limit)
            ->where(['status'=>1])
            ->all();

        $this->d_title = ' / 灰度组选择';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('select_group',[
            'data'=>$data,
            'pages'=>$pages,
            'bind_group_list'=>$BindGroupIdList,
            'pack_data'=>$onePackData,
            'pack_id'=>$pack_id,
            'bindGroup'=>$this->getPackBindGroupInfoFromPackId($pack_id),
            'sum_info' =>$this->getGroupBindCountSn()
        ]);

    }

    /**
     * 获取当前包 已绑定的组id列表
     * @param $data
     * @return array
     */
    protected function getPackageBindGroupList($data)
    {
        if(!$data) return [];

        $bindGroupList = [];

        foreach ($data as $v)
        {
            array_push($bindGroupList,$v['group_id']);
        }

        return $bindGroupList;
    }


    /**
     * 关联灰度组
     * 注意：这里需要处理两部分逻辑 1.关联成功后需要更新包的测试状态 status=1   2.需要将当前包的from_ver_id的ver_name 添加到版本列表中
     */
    public function actionGray_selected()
    {

        $pack_id = IFilter::act(IReq::get('pack_id')); //包id
        $group_id = IFilter::act(IReq::get('group_id')); //灰度组id

        if(!$pack_id) show_json(100000,'缺少包id');
        if(!$group_id) show_json(100000,'缺少灰度组id');

        //验证包是否存在
        $DiffInfo = DiffPackage::findOne($pack_id);
        if(!$DiffInfo) show_json(100000,'相关包数据不存在');

        if(!GrayGroup::findOne($group_id)) show_json(100000,'灰度组数据不存在');

        $Info = DiffPackageGroup::find()->where(['sp_pack_id'=>$pack_id,'group_id'=>$group_id])->asArray()->one();

        //取消关联操作
        if($Info){
            if(DiffPackageGroup::findOne(['gray_id'=>$Info['gray_id']])->delete()){
                $this->disDelGrayPackageBindSnInfo($pack_id,$group_id,$DiffInfo['type']);//清楚缓存的sn信息
                show_json(0,'取消关联成功');
            }

            show_json(100000,'取消关联失败');

        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            //关联操作
            $DiffPackageGroup = new DiffPackageGroup;

            $DiffPackageGroup->sp_pack_id = $pack_id;
            $DiffPackageGroup->group_id = $group_id;
            $DiffPackageGroup->created_ts = time();
            $DiffPackageGroup->updated_ts = 0;

            $DiffPackageGroup->save();

            //更新包的状态未测试状态
            $DiffInfo->status = 1;//测试状态 灰度组测试
            $DiffInfo->updated_ts = time();

            $DiffInfo->save();//更新测试状态

            $transaction->commit();

            $this->disAddGrayPackageBindSnInfo($pack_id,$group_id,$DiffInfo['type']);//同步sn 到缓存
            show_json(0, '关联成功');

        }catch (\Exception $e){
            $transaction->rollBack();
            show_json(0, '关联失败');
        }

    }

    /**
     * 产品删
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionGroup_del()
    {

        $group_id = IFilter::act(IReq::get('group_id')); //灰度组id 唯一id

        if(!$group_id) show_json(100000,'缺少灰度组id');

        $oneInfo = GrayGroup::findOne($group_id);//判断要删除的数据信息是否存在

        //判断该灰度组组下是否有sn列表 存在不能删除
        $sn_data_list = GroupSn::find()->where(['group_id'=>$group_id])->all();

        if($sn_data_list) show_json(100000,'该灰度组下已有关联数据不能删除');

        if($oneInfo->delete()){
            show_json(0,'删除成功');
        }

        show_json(100000,'删除失败');

    }

    /**
     * 设置灰度组状态
     */
    public function actionGroup_status()
    {

        $group_id = IFilter::act(IReq::get('group_id')); //group id
        $status = IFilter::act(IReq::get('status'));

        if(!$group_id) show_json(100000,'缺少灰度组id');

        $GrayGroup = GrayGroup::findOne($group_id);

        if(!$GrayGroup) show_json(100000,'要修改的数据信息不存在');

        $GrayGroup->status = $status;

        if ($GrayGroup->save()) {

            $this->disDisableGrayGroup($group_id,$status);//建立已经绑定的灰度组下的sn添加到缓存 或者 清除灰度组下的sn缓存的信息

            show_json(0,'修改灰度组状态成功');
        }

        show_json(100000,'修改灰度组状态失败');

    }

    /***************************************sn相关操作*******************************/

    /**
     * sn列表
     * @return string
     */
    public function actionSn_list()
    {

        $group_id = IFilter::act(IReq::get('group_id')); //灰度组id 唯一id

        if(!$group_id) show_json(100000,'缺少灰度组id');

        $groupSn_data_list = GroupSn::find()->where(['group_id'=>$group_id]);//根据组id获取全部灰度组信息

        if(!$groupSn_data_list) $groupSn_data_list= [];//设置为空数组

        $b_groupSn_data_list = clone $groupSn_data_list;
        $pages = new Pagination(['totalCount'=>$b_groupSn_data_list->count(),'defaultPageSize'=>10] );

        $models = $groupSn_data_list->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_ts DESC')
            ->all();

        $this->d_title = 'SN列表';
        setNavHtml($this->p_title,$this->d_title);

        return $this->render('sn_list', [
            'data_list'=>$models,
            'pages'=>$pages,
            'group_id'=>$group_id,

        ]);

    }


    /**
     * 添加设备SN
     * @param int $pro_id 绑定添加按钮
     * @return array|false
     */
    public function actionSn_add()
    {

        $group_id = IFilter::act(IReq::get('group_id')); //灰度组id 唯一id
        $sn = IFilter::act(IReq::get('sn')); //sn 名称
        $status = IFilter::act(IReq::get('status')); //当前sn状态 -1 禁用 0 未激活 1 已激活

        if(!$group_id) show_json(100000,'缺少灰度组id');

        if(!$sn) show_json(100000,'sn名称不能为空');

        //判断是否存相同的sn
        //sn+group = 唯一
        $data = GroupSn::find()->where(['sn'=>$sn,'group_id'=>$group_id])->one();

        $groupInfo = GrayGroup::find()->where(['group_id'=>$group_id])->asArray()->one();

        if($data) show_json(100000,'相同的sn已经存在');

        $GroupSn = new GroupSn;

        $GroupSn->group_id = $group_id;
        $GroupSn->sn = $sn;
        $GroupSn->status = $status;
        $GroupSn->staff_id = $_SESSION['uid']['uid'];
        $GroupSn->created_ts = time();
        $GroupSn->updated_ts = 0;

        if ($GroupSn->save()) {
            //将sn 的绑定关系添加到redis中
           // show_json(0,'添加sn成功');
            //当前还需判断一下所属的灰度组是否被禁用
            if(isset($groupInfo['status']) && $groupInfo['status'] == 1){
                $this->addSyncSnFromRedis($group_id,$sn);//当当前灰度组没有被任何包关联，不需要进行redis同步操作 所以有两种格式返回
            }

            show_json(0,'添加sn成功');
        }

        show_json(100000,'添加sn失败');

    }



    /**
     * 设置sn状态
     */
    public function actionGroup_sn_status(){

        $sn_id = IFilter::act(IReq::get('sn_id')); //sn id
        $status = IFilter::act(IReq::get('status')); //sn 名称
        $group_id = IFilter::act(IReq::get('group_id')); //灰度组id 唯一id

        if(!$sn_id) show_json(100000,'缺少参数');

        $GroupSn = GroupSn::findOne($sn_id);

        if(!$GroupSn) show_json(100000,'要修改的数据信息不存在');

        $GroupSn->status = $status;

        if ($GroupSn->save()) {
            if($status == 0)  $this->delSyncSnFromRedis($group_id,$GroupSn['sn']);//当当前灰度组没有被任何包关联，不需要进行redis同步操作 所以有两种格式返回
            if($status == 1)  $this->addSyncSnFromRedis($group_id,$GroupSn['sn']);//当当前灰度组没有被任何包关联，不需要进行redis同步操作 所以有两种格式返回
            show_json(0,'修改sn状态成功');
        }

        show_json(100000,'修改sn状态失败');

    }

    /**
     * 删除sn  需要删除缓存的redis数据
     */
    public function actionGroup_sn_del()
    {
        $sn_id = IFilter::act(IReq::get('sn_id')); //sn id

        $group_id = IFilter::act(IReq::get('group_id')); //灰度组id 唯一id

        if(!$sn_id) show_json(100000,'缺少参数');

        $snInfo = GroupSn::find()->where(['gn_id'=>$sn_id])->asArray()->one();

        if(!$snInfo) show_json(100000,'删除的sn信息不存在');

        if(GroupSn::findOne($sn_id)->delete()){

            $this->delSyncSnFromRedis($group_id,$snInfo['sn']);//当当前灰度组没有被任何包关联，不需要进行redis同步操作 所以有两种格式返回
            show_json(0,'删除成功');
        }

        show_json(100000,'删除失败');
    }

}
