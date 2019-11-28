<?php
namespace app\models;

class Lang extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lang';
    }

    public function rules()
    {
        return [
            [['lang', 'lang_show', 'lang_short'], 'required'],
            [['lang', 'lang_show', 'lang_short'], 'string', 'length'=>[1, 255]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lang' => 'Language',
            'lang_show' => 'Language Show',
            'lang_short' => 'Language Short',
        ];
    }
}
