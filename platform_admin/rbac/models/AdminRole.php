<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_role".
 *
 * @property int $id 主键
 * @property string $code 角色编号
 * @property string $name 角色名称
 * @property string $des 角色描述
 * @property int $create_date 创建时间
 * @property int $update_date 时间
 * @property int $status 状态 0:禁用 1:启用
 */
class AdminRole extends \yii\db\ActiveRecord
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
     * 超级管理员分组
     */
    const ADMIN_ID = 1;

    public static $statusList = [
        1 => '开启',
        0 => '关闭',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['create_date', 'update_date', 'status'], 'integer'],
            [['code', 'name'], 'string', 'max' => 50],
            [['des'], 'string', 'max' => 400],
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
            'code' => 'Code',
            'name' => 'Name',
            'des' => 'Des',
            'create_date' => 'Create Date',
            'update_date' => 'Update Date',
            'status' => 'Status',
        ];
    }

    public static function getDate()
    {
        /*return date('Y-m-d H:i:s');*/
        return time();
    }

    /**
     * 转换状态
     * @param $status
     * @return mixed
     */
    public static function status_to_str($status)
    {
        return self::$statusList[$status];
    }

    /**
     * 删除角色
     * @param $id
     * @return bool
     */
    public static function deleteRole($id)
    {
        $model = self::findOne($id);
        if ($model) {
            $model->status = 0;
            $model->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取权限
     * @param $id 用户角色
     * @return array|\yii\db\ActiveRecord[]
     */
    /*  public static function getRule($id)
      {
          $AdminRule = AdminRule::find();
          $AdminRule->where(['status' => 1]);
          $AdminRule->andWhere(['is_show' => 1]);
          $AdminRule->orderBy('order desc');
          if (self::ADMIN_ID != $id) {//系统管理员 拥有全部权限
              $roleOne = AdminRole::findOne($id);
              $roleOne->rule = explode(',', $roleOne->rule);
              $AdminRule->andWhere(['in', 'id', $roleOne->rule]);
          }
          return $AdminRule->asArray()->all();
      }*/
    /**
     * 获取角色与路由的对应关系
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getRule($id)
    {
        $AdminRoleRule = AdminRoleRule::find();

        $AdminRoleRule->where(['role_id' => $id]);


        return $AdminRoleRule->asArray()->all();
    }

    /**
     * 获取全部路由
     */
    public static function getAllRull()
    {
        $AdminRule = AdminRule::find();

        return $AdminRule->asArray()->all();
    }

    /**
     * 获取角色
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRoleAll()
    {
        $AdminRule = AdminRole::find();
        $AdminRule->where(['status' => 1]);

        return $AdminRule->asArray()->all();
    }

    /**
     * 根据role_id 获取角色名称
     * @param $role_id
     * @return string
     */
    public function disRoleName($role_id)
    {
        $AdminRole = new AdminRole;

        $role_list  = disArrIndex($AdminRole->getRoleAll());

        if($role_list && isset($role_list[$role_id])){
            return $role_list[$role_id]['name'];
        }

        return '';
    }

    /**
     * 为用户添加角色 用户可以拥有多个角色
     * @param $uid
     * @param $role_list
     * @return bool
     */
    public function addUserRoleFromRoleId($role_id,$user_list)
    {
        Yii::$app->db->createCommand("call sp_role_user_from_rid_add(".$role_id.",'".$user_list."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

}
