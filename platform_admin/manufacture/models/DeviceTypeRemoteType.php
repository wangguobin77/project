<?php
namespace app\models;

class DeviceTypeRemoteType extends BaseModel
{
    public static function tableName()
    {
        return 'device_type_remote_type';
    }

    public function rules()
    {
        return [
            [['device_type_id', 'remote_type_id'], 'required'],
            ['device_type_id', 'exist', 'targetClass'=>'\app\models\DeviceType', 'targetAttribute'=>'id'],
//            ['device_type_id', 'exist', 'targetClass'=>'\manufacture\models\DeviceType', 'targetAttribute'=>'id', 'filter' => ['is_deleted' => 0]],
//            ['remote_type_id', 'exist', 'targetClass'=>'\manufacture\models\RemoteType', 'targetAttribute'=>'id', 'filter' => ['is_deleted' => 0]],
            ['remote_type_id', 'exist', 'targetClass'=>'\app\models\RemoteType', 'targetAttribute'=>'id'],
            ['is_deleted','default','value'=>0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'device_type_id' => 'Device Type',
            'remote_type_id' => 'Remote Type',
        ];
    }
}
