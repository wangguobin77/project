<?php
namespace app\models;

use Yii;
class ParamModel extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'category';
    }

    /*
    * 获取小车分类 列表
    */
    public function getParamListModel()
    {
        try {
            $result = Yii::$app->db->createCommand('call category_list_get()')->queryAll();
            return $result;
        } catch (\Exception $e) {
            return [];
        }

    }


    /*
     * 添加新分类
     */
    public function addCategoryModel($id, $parentId, $categoryName, $cnname, $value)
    {
        try {
            Yii::$app->db->createCommand("call category_add_edit(
                '". $id ."'
                ,'". $parentId ."'
                ,'". $cnname ."'
                ,'". $categoryName ."'
                ,'". $value ."'
                ,@ret
            )")->query();

            $result = Yii::$app->db->createCommand('select @ret')->queryScalar();
            if ($result === '1') return 0;
            return $result ? : 100000;
        } catch (\Exception $e) {
            return 100000;
        }
    }

    /*
     * 删除 categorry
     */
    public function deleteCategory($id)
    {
        try {
            Yii::$app->db->createCommand("call category_delete('".$id."', @ret)")->query();
            $result = Yii::$app->db->createCommand("select @ret")->queryScalar();
            if ($result == 1) return 0;
            return $result ? $result : 100000;
        } catch (\Exception $e) {
            print_R($e->getMessage());
            return 100000;
        }
    }

    /**
     * 通过分类查找关联
     * @param $id
     * @return array|bool
     */
    public function getExistsRelationByCategoryId($categoryId)
    {
        try {
            return (new \yii\db\Query())
                ->select('a.*')
                ->from('group a')
                ->join('left join', 'category_group b', 'a.id=b.group_id')
                ->where(['b.category_id'=>$categoryId])
                ->all();
        } catch (\Exception $e) {
            return false;
        }
    }
}