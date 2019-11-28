<?php
namespace app\models;

class LangAppFileKeyValue extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lang_app_file_key_value';
    }

    public function rules()
    {
        return [
            [['key_id', 'lang_id', 'lang_value'], 'required'],
            ['key_id', 'exist', 'targetClass'=>'lang\models\LangAppFileKey', 'targetAttribute'=>'id'],
            ['lang_id', 'exist', 'targetClass'=>'lang\models\Lang', 'targetAttribute'=>'id'],
            [['lang_value'], 'string', 'length'=>[1, 255]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'key_id' => 'Key Id',
            'lang_id' => 'Lang id',
            'lang_value' => 'Value',
        ];
    }

    /**
     * 添加修改value
     * @param $key_id
     * @param $lang_id
     * @param $value
     */
    public function addEdit($key_id, $lang_id, $value)
    {
        $model = self::find()->where(['key_id'=>$key_id,'lang_id'=>$lang_id])->one();
        if ($model) {
            $model->lang_value = $value;
            if (!$model->save()) {
                show_json(100000, 'modify value failed.');
            }
        } else {
            $model = new LangAppFileKeyValue();
            $model->key_id = $key_id;
            $model->lang_id = $lang_id;
            $model->lang_value = $value;
            if (!$model->save()) {
                show_json(100000, 'Add value failed.');
            }
        }
        show_json(0, 'successful.', $model->find()->where(['id'=>$model->getPrimaryKey()])->asArray()->one());
    }
}
