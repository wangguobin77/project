<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "group_sn".
 *
 * @property int $gn_id 灰度组序列号id
 * @property int $group_id 灰度组id
 * @property string $sn 设备序列号
 * @property int $status -1 禁用 0 未激活 1 已激活
 * @property int $staff_id 操作人员id
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 */
class GroupSn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_sn';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'sn', 'staff_id', 'created_ts', 'updated_ts'], 'required'],
            [['group_id', 'status', 'staff_id', 'created_ts', 'updated_ts'], 'integer'],
            [['sn'], 'string', 'max' => 128],
            [['group_id', 'sn'], 'unique', 'targetAttribute' => ['group_id', 'sn']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gn_id' => 'Gn ID',
            'group_id' => 'Group ID',
            'sn' => 'Sn',
            'status' => 'Status',
            'staff_id' => 'Staff ID',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
