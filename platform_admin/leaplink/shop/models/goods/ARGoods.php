<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-16
 * Time: 16:09
 */

namespace app\models\goods;

/**
 * 临期商品模型表
 * Class ARGoods
 * @package app\models\goods
 */
class ARGoods extends GoodsBase
{
    /** 状态 */
    const CHECK_STATUS0 = 0; //草稿
    const CHECK_STATUS1 = 1; //审核中
    const CHECK_STATUS2 = 2; //已生效
    const CHECK_STATUS3 = 3; //已删除

    const CEHCK_STATUS_LABLE = [
        self::CHECK_STATUS0 => '草稿',
        self::CHECK_STATUS1 => '审核中',
        self::CHECK_STATUS2 => '已生效',
        self::CHECK_STATUS3 => '已删除',
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{goods}}';
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
            'name',
            'worth',
            'price',
            'check_status',
            'created_at',
            'updated_at',
            'upc',
        ];
    }

    /**
     * 批量保存
     * @param $shops
     * @return string
     * @throws \yii\db\Exception
     */
    public static function saveGoods( $goods )
    {
        self::getDb()->createCommand()->batchInsert(self::tableName(), [
            'name',
            'price',
            'worth',
            'shop_id',
            'created_at',
            'updated_at',
            'upc'
        ],
            $goods
        )->execute();
        return self::getDb()->getLastInsertId();
    }
}