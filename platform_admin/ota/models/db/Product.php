<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $pro_id 产品ID
 * @property string $pro_name 产品名称
 * @property string $pro_code
 * @property int $staff_id 操作人id
 * @property string $staff_name 操作人名称
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 * @property string $description 产品描述
 * @property int $type 产品型号
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_name', 'pro_code', 'staff_id', 'staff_name', 'created_ts'], 'required'],
            [['staff_id', 'created_ts', 'updated_ts','type'], 'integer'],
            [['description'], 'string'],
            [['pro_name', 'pro_code', 'staff_name'], 'string', 'max' => 128],
            [['pro_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pro_id' => 'Pro ID',
            'pro_name' => 'Pro Name',
            'pro_code' => 'Pro Code',
            'staff_id' => 'Staff ID',
            'staff_name' => 'Staff Name',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
            'description' => 'Description',
            'type' => 'Type',
        ];
    }
}
