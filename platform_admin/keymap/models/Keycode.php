<?php

namespace app\models;

class Keycode extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'km_keycode';
    }

    public function rules()
    {
        return [
            [['key','code','version','parent','type','keytype'],'required'],
            [['key'],'unique'],
            [['category'],'string'],
            [['key','type','tag','parent'],'match','pattern' => '/^[A-Za-z0-9_ ]{1,32}$/u'],
            [['code'],'match','pattern' => '/^[0]{1}[xX]{1}[a-fA-F0-9]{2,3}$/u'],
            [['keytype'],'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'code' => 'Code',
            'parent' => 'Parent',
            'type' => 'Type',
            'tag' => 'Tag',
            'category' => 'Category',
            'keytype' => 'Key Type',
        ];
    }
}
