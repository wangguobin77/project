<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "version".
 *
 * @property int $ver_id 版本id
 * @property string $ver_name 版本名称
 * @property int $pro_id 产品id
 * @property int $is_full 是否整包升级
 * @property int $is_init 是否是初始化
 * @property int $status 版本发布状态：-1:禁用；0：未发布，1：已发布
 * @property int $staff_id 操作人id
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ver_name', 'pro_id', 'is_init', 'staff_id'], 'required'],
            [['pro_id', 'is_init', 'is_up_holt','is_full','status', 'staff_id', 'created_ts', 'updated_ts'], 'integer'],
            [['ver_name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ver_id' => 'Ver ID',
            'ver_name' => 'Ver Name',
            'pro_id' => 'Pro ID',
            'is_up_holt' => 'Is Up Holt',
            'is_full' => 'Is Full',
            'is_init' => 'Is Init',
            'status' => 'Status',
            'staff_id' => 'Staff ID',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
