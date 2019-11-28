<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_user".
 *
 * @property int $id
 * @property string $user_name 用户登陆账号，这里默认是手机号码
 * @property string $real_name 真实姓名
 * @property int $pid 最高创建者0 系统创建admin
 * @property string $password_hash
 * @property int $status 禁用状态 0：禁用 1：正常使用
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class AdminUser extends BackendUser
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
        return 'admin_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'real_name', 'password_hash'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'UserName',
            'real_name' => 'Real Name',
            'pid' => 'Pid',
            'password_hash' => 'Password Hash',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 处理验证用户信息
     */
    public function disValidationUserInfo($userInfo)
    {
        if(!isset($userInfo['real_name'])){
            show_json(100000,'Lack of parameter real_name');
        }

        if(!isset($userInfo['mobile']) || !checkmobile($userInfo['mobile'])){
            show_json(100000,'Lack of parameter mobile');
        }

        return true;
    }

    /**
     * @param $username 登陆用户名 这里默认是手机号码
     * @param $pid  父类 创建者
     * @param $real_name  真实姓名
     * @param $mobile  手机号码 就是登陆账号
     * @param $password_hash  密码 由系统默认定义 系统会把密码发送到手机账号
     * @param $birthday  生日
     * @param $status 在职或者离职 离职就是禁用账号
     * @param $sex 男女
     * @param $position_id 职位id
     * @param $work_number 员工工号
     * @param $branch_list_info 员工组织机构部门
     * @return bool
     */
    public function addUser($username,$pid,$real_name,$mobile,$password_hash,$birthday,$status,$sex,$position_id=0,$des,$work_number,$branch_list_info)
    {
        Yii::$app->db->createCommand("call sp_admin_user_add('"
            .$username."',"
            .$pid.",'"
            .$real_name."','"
            .$mobile."','"
            .$password_hash."','"
            .$birthday."',"
            .$status.","
            .$sex.","
            .$position_id.",'"
            .$des."',"
            .$work_number.",'"
            .$branch_list_info
            ."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 修改用户信息
     * @param $uid 用户id
     * @param $username
     * @param $pid
     * @param $real_name
     * @param $mobile
     * @param $password_hash
     * @param $birthday
     * @param $status
     * @param $sex
     * @param $position_id
     * @return bool
     */
    public function upUser($uid,$username,$pid,$real_name,$mobile,$password_hash,$birthday,$status,$sex,$position_id,$des,$work_number,$branch_list_info){

        Yii::$app->db->createCommand("call sp_admin_user_up("
            .$uid.",'"
            .$username."',"
            .$pid.",'"
            .$real_name."','"
            .$mobile."','"
            .$password_hash."','"
            .$birthday."',"
            .$status.","
            .$sex.","
            .$position_id.",'"
            .$des."',"
            .$work_number.",'"
            .$branch_list_info
            ."',@ret)")->query();

        $ret = Yii::$app->db->createCommand("select @ret")->queryOne();

        if(intval($ret['@ret']) == 1){
            return true;
        }
        return false;
    }

    /**
     * 根据用户id获取用户信息
     * @param $uid
     * @return array|false
     */
    public function getUserInfoOne($uid)
    {
        $connection  = Yii::$app->db;
        $sql     = "select a.id,a.username,a.real_name,a.pid,a.password_hash,a.status,b.work_number,b.position_id,b.img_path,b.mobile,
b.sex,b.email from admin_user a left join admin_user_profile b on a.id=b.uid where a.id=".$uid;
        $command = $connection->createCommand($sql);
        $userInfo  = $command->queryOne();

        return $userInfo;
    }


    /**
     * 根据登陆用户名 与密码 判断用户是否存在
     * @param $username
     * @param $pwd
     */
    public function verifyUserinfo($username,$pwd)
    {
        //判断该用户名是否已经存在
        $u = AdminUser::find();
        $d = $u->where(['username'=>$username])
            ->one();

        if(!$d){
            show_json(100000,'Account number does not exist');//账号不存在
        }

        $u1 = AdminUserProfile::find();
        $d1 = $u1->where(['uid'=>$d['uid']])
            ->one();

        if($d['password_hash'] !=  $this->setPassword($pwd)){//判断密码是否正确
            show_json(100000,'Login password error');
        }

        return array_merge($d,$d1);//返回用户信息
    }
}
