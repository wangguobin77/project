<?php
namespace app\models;

class DeviceType extends BaseModel
{
    const DEVELOPMENT = 0;
    const PUBLISHED = 1;
    const STOP_PRODUCTION = 2;

    public static function tableName()
    {
        return 'device_type';
    }

    public function rules()
    {
        return [
            [['name','name_en','type','type_en','manufacture_id','category_id','id'],'required'],
            [['name','name_en','type','type_en'],'unique'],
//            ['manufacture_id', 'exist', 'targetClass'=>'\manufacture\models\Manufacture', 'targetAttribute'=>'id', 'filter' => ['is_deleted' => 0]],
            ['manufacture_id', 'exist', 'targetClass'=>'\app\models\Manufacture', 'targetAttribute'=>'id'],
            ['category_id', 'exist', 'targetClass'=>'\app\models\Category', 'targetAttribute'=>'id', 'filter' => ['is_deleted' => 0]],
            ['description', 'string', 'length'=>[0,6*1024]],
            ['status','integer'],
            ['status','default','value'=>0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'type_en' => 'English Type',
            'name' => 'Name',
            'name_en' => 'English Name',
            'manufacture_id' => 'Manufacture',
            'category_id' => 'Category',
        ];
    }
}
