<?php
namespace app\models;

use Yii;
class IniModel extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'ini_files';
    }

    public function rules()
    {
        return [
            [['ini_content', 'file_name', 'desc', 'crc32', 'category_id'], 'required', 'message'=>'{attribute}不能为空'],
            [['file_name'], 'unique', 'message'=>'{attribute}已存在'],
            ['category_id', 'exist', 'targetClass'=>'\app\models\ParamModel', 'targetAttribute'=>'id', 'filter' => ['parent_id' => 0]],
            ['desc', 'string', 'length'=>[1, 255]],
            [['ini_content'], 'string', 'length'=>[1, 6*1024]],
            [['file_name'], 'string', 'length'=>[1, 32]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ini_content' => 'ini文件内容',
            'file_name' => 'ini文件名称',
            'desc' => 'ini文件描述',
            'crc32' => 'iniCrc32值',
            'category_id' => '分类',
        ];
    }

    /**
     * 获取ini文件已存在的关联
     * @param $id
     * @return array|bool
     */
    public function getExistsRelation($id)
    {
        try {
            return (new \yii\db\Query())
                ->select('a.id, a.category_group_id, b.group_name')
                ->from('ini_files_group a')
                ->join('left join', 'group b', 'a.category_group_id=b.id')
                ->where(['a.ini_id'=>$id])
                ->all();
        } catch (\Exception $e) {
            return false;
        }

    }
}