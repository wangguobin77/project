<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-23
 * Time: 15:00
 */

namespace app\models\shop;

/**
 * 商户操作 model
 * 说明:该 model 用于标量用户操作,暂时不关联实体表
 * Class ARShopEvent
 * @package app\models
 */
class ARShopEvent extends ShopBase
{
    const EVENT_LIST = [
        'LOGINLOGIN' => 1,   //商户登录操作
        'REFRESH_TOKEN' => 2,  //商户刷新 token 操作
        'REGISTER' => 3,  //商户注册
        'FIND_PASSWORD' => 4, //商户找回密码
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
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
        return [];
    }
}