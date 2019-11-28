<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-19
 * Time: 18:40
 */

namespace app\models\shop;


class ARShopResourceRelation extends ShopBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{shop_resource_relation}}';
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
            'id',
            'shop_id',
            'resource_id',
        ];
    }

    public function getResource()
    {
        return $this->hasOne(ARResource::className(), ['resource_id' => 'resource_id']);
    }

    public static function updateRelation($id, $data){
        self::getDb()->createCommand()
            ->update(self::tableName(),
                $data,
                ['id' => $id])
            ->execute();
    }

}