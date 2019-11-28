<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-19
 * Time: 10:17
 */

namespace app\models\batch;

use Yii;
use app\models\ARBaseActiveModel;

/*
 * 所有使用 leaplink 数据库的 AR的基类
 */
class ARLeapLinkBase extends ARBaseActiveModel
{
    public static function getDb()
    {
        // 使用 "leapid" 组件
        return Yii::$app->leapid;
    }
}