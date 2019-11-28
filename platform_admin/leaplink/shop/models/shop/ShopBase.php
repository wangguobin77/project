<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-19
 * Time: 10:17
 */

namespace app\models\shop;

use Yii;
use app\models\ARBaseActiveModel;
/*
 * 所有使用 shop 数据库的 AR的基类
 */
class ShopBase extends ARBaseActiveModel
{
    public static function getDb()
    {
        // 使用 "shop" 组件(数据库配置)
        return Yii::$app->shop;
    }
}