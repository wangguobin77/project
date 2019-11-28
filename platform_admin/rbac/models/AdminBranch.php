<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_branch".
 *
 * @property int $id
 * @property int $pid
 * @property string $title 部门或者机构名称
 * @property string $contact_name 联系人 机构必填／部门不必填
 * @property string $region 所在地区
 * @property string $mobile 手机 联系方式
 * @property string $c_address 联系地址
 * @property string $c_email 联系邮箱地址
 * @property string $condition 描述
 * @property int $i_b_type_id 定义机构或者部门类型 0 未定义
 * @property int $type 状态 0：未定义 1：组织 2:部门 注意：只有在组织下or部门下可以添加部门，如果类型为部门是不能在添加组织的
 * @property int $is_show 是否显示 0：不显示 1：显示
 * @property int $status 是否禁用状态 0：禁用 1：启用
 */
class AdminBranch extends \yii\db\ActiveRecord
{
    /**
     * 指定获取数据库
     * @return null|object
     */
    public static function getDb()
    {
        return Yii::$app->get('rbacDb');
    }

    /**
     * 顶级组织 如果账号被分配到顶级组织下面 相当于超级管理员，可以模拟多个组织身份id
     */
    const ORGANIZATION_ID = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'i_b_type_id', 'type', 'is_show', 'status'], 'integer'],
            [['title', 'contact_name', 'region', 'mobile', 'c_address', 'c_email', 'condition'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'title' => 'Title',
            'contact_name' => 'Contact Name',
            'region' => 'Region',
            'mobile' => 'Mobile',
            'c_address' => 'C Address',
            'c_email' => 'C Email',
            'condition' => 'Condition',
            'i_b_type_id' => 'I B Type ID',
            'type' => 'Type',
            'is_show' => 'Is Show',
            'status' => 'Status',
        ];
    }


    /**
     * 获取组织与部门的一级父类，作为显示与选择
     * @return array
     */
    public function getLevel1Organization()
    {
        $branch_list = AdminBranch::find()->asArray()->all();//所有组织机构类型

        $new_list = [];
        if($branch_list){
            foreach ($branch_list as $k=>$v){
                if($v['type'] == 1 && $v['pid'] == 1){//类型必须是组织  父类是1 的才是一级菜单
                    array_push($new_list[$k],$v);
                }
            }
        }

        return $new_list;
    }

    /**
     * 根据当前的id 组装组装部门的信息
     * @return array
     */
    public function getOrgAndBranchAllListName()
    {
        $branch_list = AdminBranch::find()->asArray()->all();//所有组织机构类型

        $new_data = [];
        if($branch_list){
            foreach ($branch_list as $key=>$val){
                $new_data[$val['id']] = $val['title'];
            }
        }

        return $new_data;
    }

    /**
     * 根据用户的uid 获取组织部门信息
     * @param $uid
     * @return string
     */
    public  function getOrgInfoList($uid)
    {
        $connection  = Yii::$app->db;
        $sql     = "select a.uid,b.pid,b.title,b.id,b.contact_name,b.region,b.mobile,b.c_address,b.type,b.i_b_type_id,b.is_show from 
admin_user_branch a left join admin_branch b on a.branch_id=b.id where b.is_show=1 and b.status=1 and  a.uid=".$uid;

        $command = $connection->createCommand($sql);
        $model     = $command->queryAll();

        $new_data = '';
        if($model){
            $total = count($model);

            $index = 1;
            foreach ($model as $key=> $val){
                if($index == $total){
                    $new_data .= $val['title'];
                }else{
                    $new_data .= $val['title'].'/';
                }

                $index++;
            }


        }

        return $new_data;

    }

}
