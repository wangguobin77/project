<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-19
 * Time: 18:24
 */

namespace app\models\shop;


class ARResource extends ShopBase
{
    /** 资源类型 */
    const TYPE = 0; //图片

    /** 资源位置 */
    const POSITION_TYPE1 = 1; //LOGO  logo
    const POSITION_TYPE2 = 2; //店铺插图 plate
    const POSITION_TYPE3 = 3; //营业执照  license
    const POSITION_TYPE4 = 4; //经营许可证 certificate

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{resource}}';
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
            'resource_id',
            'remote_uri',
            'ip',
            'type',
            'position_type',
            'created_at',
        ];
    }


    /**
     * @param string $remote_uri 资源地址
     * @param integer $ip 长整型 ip
     * @param integer $position_type 资源位置
     * @param integer $type 资源类型
     * @return integer
     * @throws \yii\db\Exception
     */
    public static function saveResource($remote_uri, $ip, $position_type, $type = self::TYPE)
    {
        self::getDb()->createCommand()
            ->insert(self::tableName(), [
                'remote_uri' => $remote_uri,
                'ip' => $ip,
                'position_type' => $position_type,
                'type' => $type,
            ])
            ->execute();
        return self::getDb()->getLastInsertId();
    }

    public static function saveShopResourceRelation($shopId, $resourceId)
    {
        self::getDb()->createCommand()
            ->insert(ARShopResourceRelation::tableName(), [
                'shop_id' => $shopId,
                'resource_id' => $resourceId,
            ])
            ->execute();
    }
}