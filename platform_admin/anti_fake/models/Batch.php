<?php
namespace app\models;


class Batch extends \yii\db\ActiveRecord
{
    const STATUS0 = 0; //待审批
    const STATUS1 = 1; //审批通过
    const STATUS2 = 2; //审批不通过

    public static function tableName()
    {
        return 'batch';
    }

    public function rules()
    {
        return [
            [['category_id', 'batch_num', 'quantity'], 'required'],
            [['quantity'], 'integer', 'min'=>1, 'max'=>1000000],
            ['category_id', 'exist', 'targetClass'=>'\fake\models\Category', 'targetAttribute'=>'id'],
            [['batch_num', 'category_id'], 'unique', 'targetAttribute' => ['batch_num', 'category_id']],
            ['status', 'default', 'value'=>0],
            ['status', 'in', 'range'=>[0, 1, 2]],
            ['create_ts', 'default', 'value'=>time()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'batch_num' => 'Batch Num',
            'quantity' => 'Quantity',
            'status' => 'Status',
            'create_ts' => 'Create Ts',
        ];
    }
}