<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_role_rule".
 *
 * @property int $id
 * @property int $role_id 角色id
 * @property int $rule_id 路由id
 * @property int $created_at 创建时间
 */
class AdminRoleRule extends \yii\db\ActiveRecord
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_role_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'rule_id'], 'required'],
            [['role_id', 'rule_id', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'rule_id' => 'Rule ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 添加角色与路由之间的关系 采用条件先删除全部 在添加新增的
     * @param $role_id
     * @param $rule_list
     * @return bool
     */
    public function addRoleRuleId($role_id,$rule_list)
    {
        Yii::$app->db->createCommand("call sp_role_rule_add(".$role_id.",'".$rule_list."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }


    /**
     * 获取当前角色 所拥有的路由id
     * @param $roleid 角色id
     * @return array
     */
    public static function getRuleId($roleid)
    {
        $AdminRoleRule = AdminRoleRule::find();

        $data = $AdminRoleRule->where(['role_id' => $roleid])->asArray()->all();

        $new_data = [];
        if($data){
            foreach ($data as $key=>$val){
                array_push($new_data,$val['rule_id']);
            }
        }

        return $new_data;
    }
}
