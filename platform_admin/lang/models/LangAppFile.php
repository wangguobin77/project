<?php
namespace app\models;

class LangAppFile extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lang_app_file';
    }

    public function rules()
    {
        return [
            [['app_id', 'file_path', 'file_name', 'function_name', ], 'required'],
            [['file_path', 'file_name'], 'string', 'length'=>[1, 255]],
            ['app_id', 'exist', 'targetClass'=>'lang\models\LangApp', 'targetAttribute'=>'id'],
            ['is_delete', 'default', 'value'=>0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'app_id' => 'App',
            'file_path' => 'File Path',
            'file_name' => 'File Name',
            'function_name' => 'Function Name',
        ];
    }
}
