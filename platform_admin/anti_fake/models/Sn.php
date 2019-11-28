<?php
namespace app\models;

class Sn extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'sn';
    }

    public function rules()
    {
        return [
            [['sn', 'key', 'batch_id', 'batch_serial'], 'required'],
            [['batch_serial'], 'integer'],
            [['sn', 'key', 'batch_id'], 'string', 'max' => 32],
            [['key'], 'unique'],
            [['batch_id', 'batch_serial'], 'unique', 'targetAttribute' => ['batch_id', 'batch_serial']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sn' => 'Sn',
            'key' => 'Key',
            'batch_id' => 'Batch ID',
            'batch_serial' => 'Batch Serial',
        ];
    }
}