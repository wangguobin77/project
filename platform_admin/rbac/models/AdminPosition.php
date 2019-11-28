<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_position".
 *
 * @property int $id 主键
 * @property int $cid 大类id
 * @property string $name 职位名称
 * @property string $des 头衔描述
 * @property int $create_date 创建时间
 * @property int $status 状态 0:禁用 1:启用
 */
class AdminPosition extends \yii\db\ActiveRecord
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
        return 'admin_position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_ts', 'status'], 'integer'],
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
            'created_ts' => 'Created Ts',
            'status' => 'Status',
        ];
    }

    /**
     * 返回所有职位信息
     * @return static[]
     */
    public function getPositionListInfo()
    {
        $AdminPosition = AdminPosition::find();

        return $AdminPosition->asArray()->all();
    }

    /**
     * 返回所有职位信息 索引id是职位的id
     */
    public function getPositionIdInfo()
    {
        $AdminPosition = AdminPosition::find()->asArray()->all();

        $new_data = [];
        if($AdminPosition){
            foreach ($AdminPosition as $key=>$val){
                $new_data[$val['id']] = $val;
            }
        }

        return $new_data;
    }
}
