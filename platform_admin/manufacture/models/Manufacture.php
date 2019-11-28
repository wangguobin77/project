<?php
namespace app\models;

use Yii;

class Manufacture extends BaseModel
{
    public static function tableName()
    {
        return 'manufacture';
    }

    public function rules()
    {
        return [
            [['name','logo','name_en','login_name','password','linkman','email','mobile', 'password'], 'required'],
            [['id','name','name_en','login_name','email','mobile'], 'unique'],
            [['name','name_en','login_name','email','mobile','password'],'string','length'=>[2,128]],
            ['login_name', 'match', 'pattern' => '/^[a-zA-Z0-9_\-\.]{4,32}$/u'],
            ['linkman','string','length'=>[1,128]],
            ['password', 'required'],
            //['repeat_password', 'compare', 'compareAttribute'=>'password',],
            [['address','contact_info','logo'],'string','length'=>[0,128]],
            ['common','string','length'=>[0,6*1024]],
            ['home_page','url','defaultScheme'=>'http'],
            [['status'],'default','value'=>1],
            [['ip'],'default','value'=>0],
            [['salt'],'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'name_en' => 'English Name',
            'login_name' => 'Login Name',
            'home_page' => 'Home Page',
            'password' => 'Password',
            'address' => 'Address',
            'mobile' => 'Mobile',
            'common' => 'Description'
        ];
    }

    /**
     * @验证错误
     */
    public function ReturnError()
    {
        $model_error = $this->getFirstErrors();
        if ($model_error) {
            $error = implode(' ',$model_error);
        } else {
            $error = '失败';
        }
        return ['error_msg'=>$error, 'error_arr'=>$model_error];
    }

    /**
     * @param $mid string len(32) 厂商id
     * @param $short string len(2) 厂商缩写
     */
    public function addShort($mid, $short){
        $spname = 'sp_add_manufacture_short';
        $res = Yii::$app->db->createCommand("call ".$spname."('"
            .$mid."','"
            .$short."')")->queryOne();
        return $res;
    }

    /**
     * 获取厂商列表
     * @param $mid string 32 厂商id
     * @param $offset
     * @param $limit
     */
    public function getList($offset, $limit){
        $spname = 'sp_get_manufacture_list';
        $res['data'] =  Yii::$app->db->createCommand("call ".$spname."("
            .$offset.","
            .$limit.","
            ."@totalCount)")->queryAll();

        $totalCount = Yii::$app->db->createCommand("select @totalCount")->queryOne();
        $res['totalCount'] = $totalCount['@totalCount'];
        return $res;
    }

    /**
     * 获取厂商详细信息
     * @param $mid
     * @return array|false
     * @throws \yii\db\Exception
     */
    public function getInfo($mid){
        $info = self::find()->where(['id' => $mid])->asArray()->One();
        return $info;
    }
}
