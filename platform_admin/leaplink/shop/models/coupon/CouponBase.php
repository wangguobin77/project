<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-01
 * Time: 16:54
 */

namespace app\models\coupon;

use Yii;
use app\models\ARBaseActiveModel;

/**
 * 所有优惠券模型的基类
 * Class CouponBase
 * @package app\models\coupon
 */
class CouponBase extends ARBaseActiveModel
{
    public static function getDb()
    {
        // 使用 "coupon" 组件(数据库)
        return Yii::$app->coupon;
    }
}