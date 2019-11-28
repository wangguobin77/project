<?php

namespace app\models\db;

use Yii;

/**
 * This is the model class for table "diff_package".
 *
 * @property int $sp_pack_id 差分包id
 * @property string $b_ver_id 建立每个产品版本号与对应包的关系
 * @property string $sp_pack_name 差分包名称
 * @property int $status 差分包状态-1:禁用;0：未发布，1：小范围测试 2. 已测试 3 已发布
 * @property int $type 差分包类型
 * @property int $from_ver_id 开始版本
 * @property int $to_ver_id 更新到最新版本
 * @property string $lang 差分包语言
 * @property string $description
 * @property int $auto_download 自动下载选择0：否，1：仅wifi,2:任意网络
 * @property int $force_update 是否强制更新0：否，1：是
 * @property int $alt_style 消息提示类型1：通知栏提示，2：弹窗提示，3：全选
 * @property int $fullupdate 是否允许整包升级：0：不允许，1:允许
 * @property int $created_ts 创建时间
 * @property int $updated_ts 修改时间
 */
class DiffPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'diff_package';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sp_pack_name', 'from_ver_id', 'to_ver_id', 'lang', 'auto_download', 'force_update', 'alt_style', 'fullupdate', 'created_ts', 'updated_ts'], 'required'],
            [['status', 'type', 'from_ver_id', 'to_ver_id', 'type', 'auto_download', 'force_update', 'alt_style', 'fullupdate', 'created_ts', 'updated_ts'], 'integer'],
            [['b_ver_id','description'], 'string'],
            [['sp_pack_name'], 'string', 'max' => 128],
            [['lang'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sp_pack_id' => 'Sp Pack ID',
            'b_ver_id' => 'B Ver ID',
            'sp_pack_name' => 'Sp Pack Name',
            'status' => 'Status',
            'type' => 'Type',
            'from_ver_id' => 'From Ver ID',
            'to_ver_id' => 'To Ver ID',
            'lang' => 'Lang',
            'description' => 'Description',
            'auto_download' => 'Auto Download',
            'force_update' => 'Force Update',
            'alt_style' => 'Alt Style',
            'fullupdate' => 'Fullupdate',
            'created_ts' => 'Created Ts',
            'updated_ts' => 'Updated Ts',
        ];
    }
}
