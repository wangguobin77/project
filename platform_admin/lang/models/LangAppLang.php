<?php
namespace app\models;

class LangAppLang extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lang_app_lang';
    }

    public function rules()
    {
        return [
            [['app_id', 'lang_id'], 'required'],
            ['app_id', 'exist', 'targetClass'=>'lang\models\LangApp', 'targetAttribute'=>'id'],
            ['lang_id', 'exist', 'targetClass'=>'lang\models\Lang', 'targetAttribute'=>'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_name' => 'App Name',
        ];
    }

    public function getLangAppLangByFileId($file_id)
    {
        return self::find()
            ->select('*')
            ->from('lang_app_lang a')
            ->join('left join', 'lang b', 'a.lang_id = b.id')
            ->join('left join', 'lang_app c', 'a.app_id = c.id')
            ->join('left join', 'lang_app_file d', 'd.app_id = c.id')
            ->where(['d.id' => $file_id])
            ->asArray()
            ->all();
    }
}
