<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/10/28
 * Time: 下午2:23
 */

namespace app\models\broadcasts;

use Yii;
/**
 * This is the model class for table "schedule".
 * @property bigint $id 自增id
 * @property string $title
 * @property bigint $bid 18id 广告id
 * @property string $description 描述
 * @property string $week 周期 0，1，2 用逗号分割
 * @property int $type 类型
 * @property int $interval_type 是否存在时间段推送 0 无  1 存在
 * @property int $start_ts 规则有效开始时间
 * @property int $end_ts 规则有效结束时间
 * @property int $send_start_ts 规则发送开始时间
 * @property int $send_end_ts 规则发送开始时间
 * @property int $interval_ts 间隔时间
 * @property int $created_ts 创建时间
 */
class Schedule extends \yii\db\ActiveRecord

{

    public static function getDb()
    {
        // 使用 "broadcast" 组件(数据库)
        return Yii::$app->broadcast;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schedule';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','bid'], 'required'],
            [['id', 'bid','type','interval_type','start_ts','end_ts','interval_ts', 'created_ts'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'bid' => 'Bid',
            'description' => 'Description',
            'week' => 'Week',
            'type' => 'Type',
            'interval_type' => 'Interval Type',
            'start_ts' => 'Start Ts',
            'end_ts' => 'End Ts',
            'send_start_ts' => 'Send Start Ts',
            'send_end_ts' => 'Send End Ts',
            'interval_ts' => 'Interval Ts',
            'created_ts' => 'Created Ts',
        ];
    }

}