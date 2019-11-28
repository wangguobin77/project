<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-19
 * Time: 14:22
 */

namespace app\models;


use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    const UN_DELETED = 0; //未删除
    const DELETED = 1; //已删除

}