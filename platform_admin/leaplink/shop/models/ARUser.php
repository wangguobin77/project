<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-09-02
 * Time: 13:56
 */

namespace app\models;

use Yii;
class ARUser extends ARBaseActiveModel
{
    public static function getDb()
    {
        // 使用 "coupon" 组件(数据库)
        return Yii::$app->account;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{user}}';
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
}