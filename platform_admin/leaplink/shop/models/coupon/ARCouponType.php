<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-01
 * Time: 16:53
 */

namespace app\models\coupon;

/**
 * 优惠券模型
 * Class ARCouponType
 * @package app\models\coupon
 */
class ARCouponType extends CouponBase
{
    /** 是否有效 */
    const STATUS0 = 0;  //有效
    const STATUS1 = 1;  //下架

    const STATUS_LABLE = [
        self::STATUS0 => '已上架',
        self::STATUS1 => '已下架',
    ];

    /** 状态 */
    const CHECK_STATUS0 = 0; //草稿
    const CHECK_STATUS1 = 1; //审核中
    const CHECK_STATUS2 = 2; //已生效
    const CHECK_STATUS3 = 3; //已删除
    const CHECK_STATUS4 = 4; //审核不通过

    const CEHCK_STATUS_LABLE = [
        self::CHECK_STATUS0 => '草稿',
        self::CHECK_STATUS1 => '审核中',
        self::CHECK_STATUS2 => '已生效',
        self::CHECK_STATUS3 => '已删除',
        self::CHECK_STATUS4 => '审核不通过',
    ];

    const TYPE1 = 1; //虚拟券
    const TYPE2 = 2; //实物券

    /** @var array 券对应的额外信息表 */
    const TABLE_RELATION = [
        self::TYPE1 => 'coupon_virtual',
        self::TYPE2 => 'coupon_actual',
    ];

    /** 周 1 - 周 7 对应标识位 */
    const WEEK_1 = 0b00000001;
    const WEEK_2 = 0b00000010;
    const WEEK_3 = 0b00000100;
    const WEEK_4 = 0b00001000;
    const WEEK_5 = 0b00010000;
    const WEEK_6 = 0b00100000;
    const WEEK_7 = 0b01000000;

    const I18N_WEEK_LABEL = [
        self::WEEK_1 => '周一',
        self::WEEK_2 => '周二',
        self::WEEK_3 => '周三',
        self::WEEK_4 => '周四',
        self::WEEK_5 => '周五',
        self::WEEK_6 => '周六',
        self::WEEK_7 => '周日',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{coupon_type}}';
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
            'coupon_category_id',
            'title',
            'price',
            'worth',
            'scope',
            'info',
            'type',
            'shop_id',
            'status',
            'check_status',
            'start_at',
            'end_at',
            'available_date',
            'created_at',
            'updated_at',
            'number',
        ];
    }

    /**
     * 批量保存
     * @param $shops
     * @throws \yii\db\Exception
     */
    public static function saveConponTypes( $coupons )
    {
        self::getDb()->createCommand()->batchInsert(self::tableName(), [
            'id',
            'coupon_category_id',
            'title',
            'price',
            'worth',
            'scope',
            'info',
            'type',
            'shop_id',
            'status',
            'start_at',
            'end_at',
            'available_date',
            'created_at',
            'updated_at',
            'number',
        ],
            $coupons
        )->execute();
    }
}