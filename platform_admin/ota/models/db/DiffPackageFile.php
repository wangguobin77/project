<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "diff_package_file".
 *
 * @property int $sp_file_id 文件地址ID
 * @property int $sp_pack_id 差分包id
 * @property string $file_size 文件大小
 * @property string $file_download_uri 差分包下载地址
 * @property string $md5sum 将差分包md5()
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 */
class DiffPackageFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'diff_package_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sp_pack_id', 'created_ts', 'updated_ts'], 'integer'],
            [['file_size', 'file_download_uri', 'md5sum', 'created_ts', 'updated_ts'], 'required'],
            [['file_size', 'file_download_uri', 'md5sum'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sp_file_id' => 'Sp File ID',
            'sp_pack_id' => 'Sp Pack ID',
            'file_size' => 'File Size',
            'file_download_uri' => 'File Download Uri',
            'md5sum' => 'Md5sum',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
