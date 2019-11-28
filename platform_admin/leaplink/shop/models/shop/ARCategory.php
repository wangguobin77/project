<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-22
 * Time: 09:54
 */

namespace app\models\shop;

/*
 * 商户类别表
 */
class ARCategory extends ShopBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

    }

    public function attributes()
    {
        return [
            'category_id',
            'name',
            'view_sort',
        ];
    }

    /**
     * 获取商户类别
     */
    public static function getCategory()
    {
        return ARCategory::find()
            ->orderBy(['view_sort'=> SORT_ASC])
            ->asArray()
            ->all();
    }
}