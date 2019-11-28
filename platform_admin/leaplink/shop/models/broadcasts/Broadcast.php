<?php
namespace app\models\broadcasts;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/8/20
 * Time: 上午10:22
 */
use Yii;
/**
 * This is the model class for table "role".
 * @property bigint $id 18id
 * @property bigint $shop_id 商家id
 * @property string $shop_name 商家name
 * @property string $title 广告标题
 * @property string $desc_short 简短描述
 * @property string $cover 封面图
 * @property string $url 广告地址
 *  @property string $conts 广告内容
 * @property int $created_at 创建时间
 * @property int $status 禁用状态 0：未审核 1 审核中 2审核通过  3 下架
 */

class Broadcast extends \yii\db\ActiveRecord

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
        return 'broadcast';
    }

    //关联表      get(关联表Model名)
    public function getBroadcast_coupon(){
        // 参数一 关联Model名   参数二 关联字段 不能写表.t_id 自己默认后边是本Model的表id  前边是关联表的id
        return $this->hasOne(BroadcastCoupon::className(),['broadcast_id'=>'id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id','title', 'created_at'], 'required'],
            [['shop_id', 'created_at', 'status'], 'integer'],
            [['title'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop Id',
            'shop_name' => 'Shop Name',
            'title' => 'Title',
            'desc_short' => 'Desc Short',
            'cover' => 'Cover',
            'url' => 'Url',
            'conts' => 'Conts',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

}

