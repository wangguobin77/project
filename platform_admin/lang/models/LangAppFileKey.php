<?php
namespace app\models;

use Yii;
class LangAppFileKey extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lang_app_file_key';
    }

    public function rules()
    {
        return [
            [['file_id', 'lang_key'], 'required'],
            ['file_id', 'exist', 'targetClass'=>'lang\models\LangAppFile', 'targetAttribute'=>'id'],
            [['lang_key'], 'string', 'length'=>[1, 255]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_id' => 'App',
            'file_path' => 'File Path',
            'file_name' => 'File Name',
        ];
    }

    public function getKeyValueByFileId($file_id)
    {
//        return self::find()
//            ->select("a.*,ifnull(b.`lang_value`,a.`lang_key`) lang_value,b.key_id,b.lang_id")
//            ->from('lang_app_file_key  a')
//            ->join('left join', 'lang_app_file_key_value b', 'a.id = b.key_id')
//            ->where(['a.file_id'=>$file_id])
//            ->asArray()
//            ->all();
        return  Yii::$app->db->createCommand("call sp_lang_app_file_value_select_by_fileid(
            '". $file_id ."'
        )")->queryAll();
    }
}
