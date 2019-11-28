<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "diff_package_group".
 *
 * @property int $gray_id 版本灰度组id
 * @property int $sp_pack_id 差分包ID
 * @property int $group_id 灰度组ID
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 */
class DiffPackageGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'diff_package_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sp_pack_id', 'group_id', 'created_ts', 'updated_ts'], 'required'],
            [['sp_pack_id', 'group_id', 'created_ts', 'updated_ts'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gray_id' => 'Gray ID',
            'sp_pack_id' => 'Sp Pack ID',
            'group_id' => 'Group ID',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
