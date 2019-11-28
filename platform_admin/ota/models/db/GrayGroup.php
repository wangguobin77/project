<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "gray_group".
 *
 * @property int $group_id 灰度组id
 * @property string $group_name 灰度组名
 * @property string $description 描述
 * @property int $status 灰度组状态-1：删除，0：禁用，1:正常
 * @property int $staff_id 操作人id
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 */
class GrayGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gray_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_name', 'status', 'staff_id', 'created_ts', 'updated_ts'], 'required'],
            [['description'], 'string'],
            [['status', 'staff_id', 'created_ts', 'updated_ts'], 'integer'],
            [['group_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'group_name' => 'Group Name',
            'description' => 'Description',
            'status' => 'Status',
            'staff_id' => 'Staff ID',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
