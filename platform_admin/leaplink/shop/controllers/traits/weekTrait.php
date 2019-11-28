<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-02
 * Time: 09:33
 */

namespace app\controllers\traits;

use app\models\coupon\ARCouponType;

/**
 * 日期特性
 */
trait weekTrait
{
    public $weekStatusLabel = [];

    /**
     * 获取可用和不可用日期
     * @param $value
     * @return array
     * @throws \ReflectionException
     */
    public function getWeekStatusLabel($value)
    {
        if(!empty($this->weekStatusLabel)){
            return $this->weekStatusLabel;
        }

        $rf = new \ReflectionClass(ARCouponType::class);
        $cs = $rf->getConstants();

        foreach ((array)$cs as $const_key => $const_value) {
            if(substr($const_key, 0, 5) == 'WEEK_') {
                if(($value & $const_value) > 0) {
                    $this->weekStatusLabel['available'][] = ARCouponType::I18N_WEEK_LABEL[$const_value]; //可用日期
                } else {
                    $this->weekStatusLabel['unavailable'][] = ARCouponType::I18N_WEEK_LABEL[$const_value]; //不可用日期
                }
            }
        }

        return $this->weekStatusLabel;
    }

    /**
     * 设置可用日期
     * @param array $value      可用日期数组,如 周一 周三可用 对应 [1, 4]
     * @return int|mixed
     */
    public function setWeekStatus($value) {
        if (!is_array($value)) return 255;
        $vl = 0;
        foreach ($value as $v) {
            $vl += $v;
        }
        return $vl;
    }
}