<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_user_branch".
 *
 * @property int $id
 * @property int $branch_id 部门id
 * @property int $uid 用户id
 * @property int $created_at 创建时间
 */
class AdminUserBranch extends \yii\db\ActiveRecord
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
        return 'admin_user_branch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['branch_id', 'uid'], 'required'],
            [['branch_id', 'uid', 'created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Branch ID',
            'uid' => 'Uid',
            'created_at' => 'Created At',
        ];
    }

    /**
     * 添加用户与部门的关系 一个用户可以多个部门
     * @param $uid
     * @param $branch_list 以逗号拼接id的字符串
     * @return bool
     */
    public function addUserBranch($uid,$branch_list)
    {
        Yii::$app->db->createCommand("call sp_user_branch_add(".$uid.",'".$branch_list."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据用户id 获取该用户的组织id
     * @param $uid
     * @return array
     */
    public function getBranchIdList($uid)
    {
        $branch_id_list = AdminUserBranch::find()->where(['uid'=>$uid])->all();

        $new_list = [];
        if($branch_id_list){
            foreach ($branch_id_list as $key=>$val){
                array_push($new_list,$val['branch_id']);
            }
        }
        return $new_list;
    }
}
