<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_areas".
 *
 * @property int $area_id
 * @property int $parent_id 上一级的id值
 * @property string $area_name 地区名称
 * @property int $sort 排序
 */
class ShopAreas extends \yii\db\ActiveRecord
{
    /**
     * 指定获取数据库
     * @return null|object
     */
    public static function getDb()
    {
        return Yii::$app->get('shop_area');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_areas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'area_name'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['area_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'area_id' => 'Area ID',
            'parent_id' => 'Parent ID',
            'area_name' => 'Area Name',
            'sort' => 'Sort',
        ];
    }
}
