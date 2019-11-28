<?php
namespace app\models;

class RemoteKeyset extends BaseModel
{
    /**
     * @验证的表
     */
    public static function tableName()
    {
        return 'remote_keyset';
    }

    public function rules()
    {
        return [
            [['remote_type_id', 'key'], 'required'],
//            ['remote_type_id', 'exist',
//                'targetClass' => '\manufacture\models\RemoteType',
//                'filter' => ['id' => $value],
        ];
    }
    public function attributeLabels()
    {
        return [
            'remote_type_id' => 'Remote Type',
            'key' => 'Key',
        ];
    }

    public function getRemoteKeysetAll($id)
    {
        $keyset = self::find()->select('key')->where(['remote_type_id'=>$id])->asArray()->all();
        $data = [];
        foreach ($keyset as $item) {
            $data[] = $item['key'];
        }
        return $data;
    }
}
