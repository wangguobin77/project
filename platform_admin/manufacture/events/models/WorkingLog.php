<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-26
 * Time: 11:17
 */

namespace events\models;

use Yii;
use yii\db\ActiveRecord;

class WorkingLog extends ActiveRecord
{
    public static function tableName()
    {
        return 'working';
    }

    public static function getDb()
    {
        return Yii::$app->get('adminLog');
    }
}