<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/8/20
 * Time: 上午11:35
 */

namespace app\models\broadcasts;

use yii;
/**
 * This is the model class for table "role".
 * @property bigint $id 自增id
 * @property int $broadcast_id 广告id
 * @property bigint $coupon_type_id 优惠券id
 * @property int $created_at 创建时间
 */
class BroadcastCoupon extends \yii\db\ActiveRecord

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
        return 'broadcast_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['broadcast_id','coupon_type_id', 'created_at'], 'required'],
            [['broadcast_id', 'created_at', 'coupon_type_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'broadcast_id' => 'Broadcast Id',
            'coupon_type_id' => 'Coupon Type Id',
            'created_at' => 'Created At'
        ];
    }

}