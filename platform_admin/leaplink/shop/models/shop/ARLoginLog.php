<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-23
 * Time: 14:55
 */

namespace app\models\shop;

/**
 * 商户登录日志表
 * Class ARLoginLog
 * @package app\models
 */
class ARLoginLog extends ShopBase
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{loginlog}}';
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
            'ip',
            'created_time',
            'email',
            'type',
        ];
    }

    /**
     * 单条插入日志
     * @param array [,array] ...$log 日志 key-value
     * @return integer $ret  插入条数
     */
    public static function saveLog(array ...$log)
    {
        $insertData = [];
        foreach ($log as $item){
            foreach ($item as $k => $v) {
                $insertData[$k] = $v;
            }
        }

        $ret = self::getDb()->createCommand()
            ->insert(self::tableName(), $insertData)
            ->execute();
        return $ret;
    }
}