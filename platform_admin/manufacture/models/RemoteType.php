<?php
namespace app\models;

use Yii;

class RemoteType extends BaseModel
{
    public static function tableName()
    {
        return 'remote_type';
    }

    public function rules()
    {
        return [
            [['id','name','name_en','type','type_en','key','code','screen','screen_code','carry_type','carry_type_code','manufacture_id'],'required'],
            [['name','name_en','key','type','type_en'],'unique'],
            ['description', 'string', 'length'=>[0,6*1024]],
            ['status','integer'],
            ['status','default','value'=>0],
        ];
    }
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'manufacture_id' => 'Manufacture',
            'name' => 'Name',
            'name_en' => 'English Name',
            'key' => 'Key',
            'code' => 'Code',
        ];
    }

    /*
    * REMOTE SERIAL KEYSET
     * @param manufacture_id str manufacture id
     * @param name_en str size  name_en
     * @param delete int size  is_deleted
    *
    */
    public function getKeySetAdd($data)
    {
        Yii::$app->db->createCommand("call sp_remote_keyset_add(
            '". $data['id'] ."'
            ,'". $data['keyset'] ."'
            ,'". $data['analog'] ."'
            ,@ret
        );")->query();

        return  yii::$app->db->createCommand("select @ret")->queryOne();
    }

}
