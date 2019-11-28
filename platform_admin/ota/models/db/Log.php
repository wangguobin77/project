<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $sp_pack_id 差分包ID
 * @property string $product_code 产品CODE
 * @property string $sn SN码
 * @property string $content 内容
 * @property int $created_ts 数据创建时间
 * @property int $updated_ts 数据更新时间
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sp_pack_id', 'created_ts', 'updated_ts'], 'integer'],
            [['content'], 'string'],
            [['product_code', 'sn'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sp_pack_id' => 'Sp Pack ID',
            'product_code' => 'Product Code',
            'sn' => 'Sn',
            'content' => 'Content',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
