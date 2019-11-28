<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_branch_type".
 *
 * @property int $id 主键
 * @property string $name 职位名称
 * @property string $des 头衔描述
 * @property int $create_date 创建时间
 * @property int $status 状态 0:禁用 1:启用
 */
class AdminBranchType extends \yii\db\ActiveRecord
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
        return 'admin_branch_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['create_date', 'status'], 'integer'],
            [['name'], 'string', 'max' => 128],
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
            'name' => 'Name',
            'des' => 'Des',
            'create_date' => 'Create Date',
            'status' => 'Status',
        ];
    }

    /**
     * 处理部门类型信息
     * @return array
     */
    public function disAdminBranchTypeName()
    {
        $AdminBranchType = AdminBranchType::find()->asArray()->all();//所有部门类型

        $new_data = [];
        if($AdminBranchType){
            foreach ($AdminBranchType as $key=>$val){
                $new_data[$val['id']] = $val['name'];
            }
        }

        return $new_data;

    }
}
