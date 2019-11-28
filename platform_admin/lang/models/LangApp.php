<?php
namespace app\models;

class LangApp extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lang_app';
    }

    public function rules()
    {
        return [
            [['app_name'], 'required'],
            [['app_name'], 'string', 'length'=>[1, 255]],
            ['is_delete', 'default', 'value'=>0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_name' => 'App Name',
        ];
    }
}
