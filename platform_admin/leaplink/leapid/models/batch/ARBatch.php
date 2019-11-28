<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-25
 * Time: 16:29
 */

namespace app\models\batch;


class ARBatch extends ARLeapLinkBase
{
    const CHECK_STATUS1 = 0b00000001;     //等待审批
    const CHECK_STATUS2 = 0b00000010;     //(审核不通过)作废
    const CHECK_STATUS3 = 0b00000100;     //通过审批
    const CHECK_STATUS4 = 0b00001000;     //数据库完成
    const CHECK_STATUS5 = 0b00010000;     //redis 完成
    const CHECK_STATUS6 = 0b00100000;     //bin 完成
    const CHECK_STATUS_LABLES = [
        self::CHECK_STATUS1 => "等待审批",
        self::CHECK_STATUS2 => "(审核不通过)作废",
        self::CHECK_STATUS3 => "通过审批",
        self::CHECK_STATUS4 => "数据库完成",
        self::CHECK_STATUS5 => "redis完成",
        self::CHECK_STATUS6 => "bin完成",
    ];

    const DELETE_STATUS0 = 0;
    const DELETE_STATUS1 = 1;
    const DELETE_STATUS_LABLES = [
        self::DELETE_STATUS0 => "可使用状态",
        self::DELETE_STATUS1 => "删除状态",
    ];

    /** 缓存方式 */
    const TYPE_CACHE1 = 'redis';
    const TYPE_CACHE2 = 'bin';


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{batch}}';
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
            'chip_type',
            'key_update',
            'batch_date',
            'batch_no',
            'batch_count',
            'info',
            'check_status',
            'check_ts',
            'is_delete',
            'created_ts',
        ];
    }

    /**
     * 批量保存
     * @param $shops
     * @throws \yii\db\Exception
     */
    public static function saveBatchs( $batchs )
    {
        self::getDb()->createCommand()->batchInsert(self::tableName(), [
            'chip_type',
            'key_update',
            'batch_date',
            'batch_no',
            'batch_count',
            'info',
            'created_ts',
        ],
            $batchs
        )->execute();
    }

    //修改审核状态
    public function inceaseCheckStatus($batch_id, $crease)
    {
        $sql = 'update leapid set check_status=check_status+'.$crease.' where id = (select id where id = '. $batch_id .')';
        self::getDb()->createCommand($sql)->execute();
    }
}