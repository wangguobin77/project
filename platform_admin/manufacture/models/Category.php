<?php
namespace app\models;

class Category extends BaseModel
{
    public static function tableName()
    {
        return 'category';
    }

    public function rules()
    {
        return [
            [['name', 'name_en', 'key', 'code', 'description'],'required'],
            [['name', 'name_en', 'key'],'unique'],
            ['is_deleted', 'in', 'range'=>[0, 1]],
            ['is_deleted', 'default', 'value'=>0],
            ['description', 'string', 'length' => [0, 6*1024]],
            ['tag', 'default', ''],
            ['tag', 'string', 'length' => [0, 128]]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'name_en' => 'English Name',
            'key' => 'Key',
            'code' => 'Code',
            'description' => 'Description',
            'add_time' => 'Add Time',
            'is_deleted' => 'Is Deleted',
        ];
    }
}
