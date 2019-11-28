<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_user_profile".
 *
 * @property int $id
 * @property int $uid 用户表id
 * @property string $img_path 头像
 * @property string $mobile 手机
 * @property string $birthday 出生年月
 * @property int $sex 0未定,1男,2女
 * @property string $email
 * @property int $updated_at 修改时间
 */
class AdminUserProfile extends \yii\db\ActiveRecord
{
    /**
     * 指定获取数据库
     * @return null|object
     */
    public static function getDb()
    {
        return Yii::$app->get('rbacDb');
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'mobile', 'birthday'], 'required'],
            [['uid', 'sex', 'updated_at'], 'integer'],
            [['img_path', 'mobile', 'birthday', 'email'], 'string', 'max' => 128],
            [['uid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'work_number' => 'Work Number',
            'position_id' => 'Position Id',
            'img_path' => 'Img Path',
            'mobile' => 'Mobile',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'email' => 'Email',
            'updated_at' => 'Updated At',
        ];
    }
}
