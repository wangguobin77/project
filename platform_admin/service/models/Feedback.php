<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/16
 * Time: 下午2:00
 */

namespace app\models;



use Yii;
class Feedback extends BaseModel
{
    /** @var int 沟通状态 */
    const STATUS_DEAL = 0; //待沟通
    const STATUS_OK = 1; //沟通完成
    const STATUS_DELETE = 2; //废弃

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }


    public function attributes()
    {
        return [
            'id',
            'feedback_data_id',
            'company',
            'account',
            'created_at',
            'contact_status',
            'contact_status_time',
        ];
    }
}