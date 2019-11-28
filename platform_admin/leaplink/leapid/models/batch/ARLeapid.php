<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-11-01
 * Time: 17:43
 */

namespace app\models\batch;

use Yii;
class ARLeapid extends ARLeapLinkBase
{
    const USING1 = 1;
    const USING2 = 2;

    const USING_LABELS = [
        self::USING1  => '手机',
        self::USING2  => '广告机',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{leapid}}';
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
            'id',
            'reserved',
            'key_main',
            'key_ext',
            'batch_id',
            'batch_serial',
            'status',
            'create_ts',
            'activate_ts',
            'using',
        ];
    }

    /**
     * 生产 leapid
     *
     * @param $batch_id int         批次 id
     * @param $batch_count int      该批次生产数量
     */
    public function produceLeapid($batch_id, $batch_count)
    {
        $sql = "call sp_produce_leapid({$batch_id}, {$batch_count}, @return_code, @return_message)";
        self::getDb()->createCommand($sql)->execute();
        $ret = self::getDb()->createCommand("select @return_code, @return_message;")->queryOne();
        return $ret;
    }
}