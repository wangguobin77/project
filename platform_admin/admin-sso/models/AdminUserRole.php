<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_user_role".
 *
 * @property int $id
 * @property int $role_id 角色id
 * @property int $uid 用户id
 * @property int $created_at 创建时间
 */
class AdminUserRole extends \yii\db\ActiveRecord
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
        return 'admin_user_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'uid'], 'required'],
            [['role_id', 'uid', 'created_at'], 'integer'],
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
            'uid' => 'Uid',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 根据用户uid 获取已勾选的角色
     * @param $uid
     * @return array
     */
    public function getMyRoleAll($uid)
    {
        $myRoleList = AdminUserRole::find()->where(['uid'=>$uid])->asArray()->all();

        $new_data = [];

        if($myRoleList){
            foreach ($myRoleList as $key=>$val){
                array_push($new_data,intval($val['role_id']));
            }
        }

        return $new_data;
    }

    /**
     * 添加用户与角色之间的关系 采用条件先删除全部 在添加新增的
     * @param $uid
     * @param $role_list
     * @return bool
     */
    public function addRoleUser($uid,$role_list)
    {
        Yii::$app->db->createCommand("call sp_user_role_add(".$uid.",'".$role_list."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }
}
