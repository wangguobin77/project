<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-19
 * Time: 09:57
 */

namespace app\models\shop;


class ARShop extends ShopBase
{
    //商户审核状态
    const STATUS0 = 0;
    const STATUS1 = 1;
    const STATUS2 = 2;

    public static $labelStatus = [
        self::STATUS0 => '审核中',
        self::STATUS1 => '审核通过',
        self::STATUS2 => '未通过审核',
    ];

    public $area;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{shop}}';
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

    /**
     * safe attribute
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'phone',
            'username',
            'email',
            'name',
            'shop_category_id',
            'code_p',
            'code_c',
            'code_a',
            'address',
            'open_time',
            'close_time',
            'created_at',
            'updated_at',
            'ip',
            'latitude',
            'longitude',
            'status',
        ];
    }

    /**
     * 查询手机号是否存在
     * @param $phone
     * @return bool
     */
    public static function existsPhone( $phone )
    {
        $ret = ARShop::find()
            ->where(['phone' => $phone])
            ->exists();
        return $ret;
    }

    /**
     * 批量保存
     * @param $shops
     * @throws \yii\db\Exception
     */
    public static function saveShops( $shops )
    {
        self::getDb()->createCommand()->batchInsert(self::tableName(), [
            'id',
            'phone',
            'username',
            'email',
            'name',
            'password',
            'salt',
            'shop_category_id',
            'code_p',
            'code_c',
            'code_a',
            'address',
            'open_time',
            'close_time',
            'ip',
        ],
            $shops
        )->execute();
    }

    public function getResourceRelations()
    {
        return $this->hasMany(ARShopResourceRelation::className(), ['shop_id' => 'id'])
            ->with('resource')
            ->orderBy('id');
    }

    /**
     * 获取商户基本信息
     * @param $account
     * @return array|false
     * @throws \yii\db\Exception
     */
    public static function getShop($account)
    {
        $sql = 'select * from ' . self::tableName() . ' where phone = :phone';
        $shop = self::getDb()->createCommand($sql)
            ->bindValue(':phone', $account)
            ->queryOne();
        return $shop;
    }

    /**
     * 修改登录密码
     * @param $phone
     * @param $password     加密后密码
     * @param $salt         加盐
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function updatePassword($phone, $password, $salt)
    {
        $ret = self::getDb()->createCommand()
            ->update(self::tableName(),
                ['password' => $password, 'salt' => $salt],
                ['phone' => $phone])
            ->execute();
        if($ret){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 更新商家信息
     */
    public static function updateShop($shop_id, $shop)
    {
        $effect = self::getDb()->createCommand()
            ->update(self::tableName(),
                $shop,
                ['id' => $shop_id])
            ->execute();

        if($effect){ //受影响的条数
            return true;
        }else{
            return false;
        }
    }

    /**
     * 商户是否通过审核
     * @param int $shop_id 商户 id
     */
    public static function isPass($shop_id)
    {
        $exists = static::find()->where(['id' => $shop_id, 'status' => static::STATUS1])->exists();
        return $exists;
    }
}