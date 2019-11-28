<?php

// +----------------------------------------------------------------------
// | TITLE: 用户管理
// +----------------------------------------------------------------------

namespace app\controllers;

use app\models\AdminUserProfile;
use Yii;
use app\models\AdminUser;
use app\models\AdminBranch;
use app\models\AdminUserRole;
use app\helps\Tree;
use yii\data\Pagination;
use yii\web\Response;
use common\library\Upload;
use common\library\SmsService;
use app\models\AdminUserBranch;

/**
 * Class AdminUserController
 * @package controllers
 */
class AdminUserController extends BaseController
{
    private static $orgInfo = [];//全局部门组装
    public function init()
    {
        $this->layout = false;
    }

    public function actionIndex()
    {

        $connection  = Yii::$app->db;
        $sql     = "select a.id,a.username,a.real_name,a.pid,a.status,b.work_number,b.position_id,b.img_path,b.mobile,b.sex,b.email,c.name as position_name 
from admin_user a left join admin_user_profile b on a.id=b.uid left join admin_position c on b.position_id = c.id order by a.created_at desc";
        $command = $connection->createCommand($sql);
        $models     = $command->queryAll();

        $pages = new Pagination(['totalCount'=>count($models),'pageSize'=>10]);

        $sql1     = "select a.id,a.username,a.real_name,a.pid,a.status,b.work_number,b.position_id,b.img_path,b.mobile,b.sex,b.email,c.name as position_name 
from admin_user a left join admin_user_profile b on a.id=b.uid left join admin_position c on b.position_id = c.id order by a.created_at desc limit $pages->offset,$pages->limit";

        $command = $connection->createCommand($sql1);
        $models2     = $command->queryAll();

        return $this->render('user_list',
            [
                'model' => $models2,
                'pages' => $pages,
            ]);
    }

    /**
     * 判断用户是否存在
     */
    protected function isUserInfo($mobile,$uid=0){
        //判断该用户名是否已经存在
        $u = AdminUser::find();
        $d = $u->where(['username'=>$mobile])
            ->one();

        if($d['id'] == $uid) return true;
        if($d){
            show_json(100000,'用户名已经存存在');//用户已经存在不能添加相同的用户名
        }

        return true;
    }

    /**
     * 创建用户
     * @return array|string
     */
    public function actionCreate()
    {

        $AdminUser = new AdminUser;
        if (Yii::$app->request->isPost) {

            $AdminUserData = Yii::$app->request->post();

            $AdminUser->disValidationUserInfo($AdminUserData);//验证字段

            $this->isUserInfo($AdminUserData['mobile']);//判断用户名是否已存在

            $branch_list_info = implode(',',$AdminUserData['branch']);//组织部门

            $transaction = Yii::$app->db->beginTransaction();

            try{
                $pwd = getIntCode(6);//随机六位密码
                $username = $AdminUserData['mobile'];

                $birthday = $AdminUserData['bathday'];//生日

                $bool = $AdminUser->addUser(
                    $username,
                    /*Yii::$app->user->identity->id,//创建者 父类id*/
                    $_SESSION['uid']['uid'],
                    $AdminUserData['real_name'],//真实姓名
                    $AdminUserData['mobile'],//手机号码
                    $AdminUser->setPassword($pwd),//加密后的密码
                    $birthday,//生日
                    $AdminUserData['status'],//在职or离职
                    $AdminUserData['sex'],
                    ($AdminUserData['position_id'])?$AdminUserData['position_id']:0,
                    $AdminUserData['des'],
                    $AdminUserData['work_number'],
                    $branch_list_info
                );

                $transaction->commit();

                if ($bool){
                    // var_dump($pwd);die;
                    //添加账号成功后需要将密码发送到手机上
                    if((new SmsService)->sendAuthCodeToQueue($AdminUserData['mobile'],$pwd)){
                        show_json(0,'添加成功');
                    }
                }

                show_json(100000,'添加失败');
            }catch (\Exception $e){
                $transaction->rollBack();
                // var_dump($e->getMessage());die;
                show_json(100000,'添加失败');
            }



        } else {

            $work_number_id = AdminUserProfile::find()->select('max(work_number) as work')->asArray()->one()['work'];//获取员工最大工号 +1
           /* echo '<pre>';
            print_r($this->disAdminBranchInfo());die;*/
            return $this->render('create_user', ['model' => $AdminUser,'select_val'=>$this->disAdminBranchInfo(),'work_number_id'=>intval($work_number_id+1)]);
        }
    }


    /**  处理前端展示的部门组装数据
     * @return string
     */
    protected function disAdminBranchInfo()
    {
        $AdminBranch = AdminBranch::find()->asArray();

        $models = Tree::makeTree($AdminBranch->all());

        return json_encode($this->BranchInfo($models,[]));
    }

    /**组装前端数据
     * @param $data
     * @param string $html
     * @return string
     */
    private function BranchInfo($data,$new_data=[])
    {
        //顶级为系统添加的senseplay
        //默认从二级开始遍历

        if($data){
            foreach ($data as $k => $v) {


                if (isset($v['is_show']) && $v['is_show'] != 0 && $v['status'] == 1) {
                    $new_data[$k]['text'] = $v['title'];
                    $new_data[$k]['value'] = $v['id'];

                    //需要验证是否有子菜单
                    if (isset($v['children']) && is_array($v['children'])) {
                        $new_data[$k]['items'] = $this->BranchInfo($v['children']);
                    }
                }

            }

        }
        return  $new_data;

    }

    /**
     * huq
     * @param $data
     * @param $event 默认1 为添加 2在用户更新用户信息时用户属于哪个部门
     * @param $branch_id 用户分组id
     * @return string
     *
     */
    public function getSelectValue($data,$event=1,$branch_id='')
    {
        $data = $this->orderBranchData($data);

        // var_dump($this->retBranchTtitle($data,5));die;

        $h = '';
        foreach ($data as $k=>$v){
            if($v['pid'] == 0){
                continue;
            }

            if($branch_id && $branch_id==$v['id']){
                $h .= '<option value="'.$v['id'].'" selected title="/'.$this->retBranchTtitle($data,$v['id']).'">'.$this->retBranchTtitle($data,$v['id']).'</option>';
            }else{
                $h .= '<option value="'.$v['id'].'" title="/'.$this->retBranchTtitle($data,$v['id']).'">'.$this->retBranchTtitle($data,$v['id']).'</option>';
            }

        }

        return $h;
    }

    /**
     * 处理部门数据
     * @param $data
     */
    private function orderBranchData($data){
        $list = [];
        foreach ($data as $key=>$val){
            $list[$val['id']] = $val;
        }

        return $list;
    }

    /**
     * 组装部门select 值
     * @param $data
     * @param $index
     * @param string $html
     * @return string
     */
    private function retBranchTtitle($data,$index,$html = '')
    {

        /*$html .= '/'.$data[$index]['title'];
        if($data[$index]['pid'] && $data[$index]['pid'] != 0){
            $html .= $this->retBranchTtitle($data,$data[$index]['pid']);
        }*/
        $html_arr = [];
        //  $html .= '/'.$data[$index]['title'];
        array_unshift($html_arr,$data[$index]['title']);
        if($data[$index]['pid'] && $data[$index]['pid'] != 0){
            array_unshift($html_arr,$this->retBranchTtitle($data,$data[$index]['pid']));
        }

        return implode("/",$html_arr);
        //   return $html_arr;
    }

    /**
     * 更新角色
     * @return array|string
     */
    public function actionUpdate()
    {

        if (Yii::$app->request->isPost) {

            $AdminUser = new AdminUser;
            if( $AdminUser->disValidationUserInfo(Yii::$app->request->post())){//验证字段){
                $AdminUserData = Yii::$app->request->post();

                if(!$AdminUserData['mobile']){
                    show_json(100000,'username or mobile Are required fields');
                }

                $this->isUserInfo($AdminUserData['mobile'],$AdminUserData['id']);//判断用户名是否已存在

                //根据用户id 查询用户信息
                $userInfo = $AdminUser->getUserInfoOne($AdminUserData['id']);

                $branch_list_info = implode(',',$AdminUserData['branch']);//组织部门

                try{
                    $birthday = $AdminUserData['bathday'];//生日

                    //根据用户手机号码是否更新，去判断是否重置密码，发送密码到手机上
                    if($AdminUserData['mobile'] == $userInfo['mobile']){
                        $bool = $AdminUser->upUser(
                            $AdminUserData['id'],
                            $userInfo['username'],
                           /* Yii::$app->user->identity->id,*/
                            $_SESSION['uid']['uid'],
                            $AdminUserData['real_name'],
                            $userInfo['mobile'],
                            $userInfo['password_hash'],
                            $birthday,
                            $AdminUserData['status'],
                            $AdminUserData['sex'],
                            isset($AdminUserData['position_id'])?$AdminUserData['position_id']:0,
                            $AdminUserData['des'],
                            $AdminUserData['work_number'],
                            $branch_list_info
                        );
                    }else{
                        //如果不一致说明更新了手机账号，所以需要重置手机密码
                        $pwd = getIntCode(6);//随机六位密码
                        $username = $AdminUserData['mobile'];
                        $bool = $AdminUser->upUser(
                            $AdminUserData['id'],
                            $username,
                           /* Yii::$app->user->identity->id,//创建者 父类id*/
                            $_SESSION['uid']['uid'],
                            $AdminUserData['real_name'],//真实姓名
                            $AdminUserData['mobile'],//手机号码
                            $AdminUser->setPassword($pwd),//加密后的密码
                            $birthday,//生日
                            $AdminUserData['status'],//在职or离职
                            $AdminUserData['sex'],
                            isset($AdminUserData['position_id'])?$AdminUserData['position_id']:0,
                            $AdminUserData['des'],
                            $AdminUserData['work_number'],
                            $branch_list_info
                        );

                        (new SmsService)->sendAuthCodeToQueue($AdminUserData['mobile'],$pwd);//发送新的密码到手机
                    }

                    if ($bool){
                        //添加账号成功后需要将密码发送到手机上
                        show_json(0,'更新成功');
                    }

                    show_json(100000,'更新失败');
                }catch (\Exception $e){
                   // var_dump($e->getMessage());die;
                    show_json(100000,'更新失败');
                }

            }else{
                show_json(100000,'参数错误');
            }

        } else {
            $id = Yii::$app->request->get('id');

            $connection  = Yii::$app->db;
            $sql     = "select a.id,a.username,a.real_name,a.pid,a.status,b.work_number,b.position_id,b.img_path,b.des,b.birthday,b.mobile,b.sex,b.email from 
admin_user a left join admin_user_profile b on a.id=b.uid where a.id=".$id;

            $command = $connection->createCommand($sql);
            $model     = $command->queryOne();

            if(!$model){
                show_json(100000,'当前数据不存在');
            }

            return $this->render('update_user', ['model' => $model,'select_val'=>$this->disAdminBranchInfo(),'userBranchList'=>$this->getOrgInfo($id)]);
        }
    }

    /**
     * 根据用户id 获取用户组织相关信息
     * @param $uid
     * @return array
     */
    protected function getOrgInfo($uid)
    {
        $connection  = Yii::$app->db;
        $sql     = "select a.uid,b.pid,b.title,b.id,b.contact_name,b.region,b.mobile,b.c_address,b.type,b.i_b_type_id,b.is_show from 
admin_user_branch a left join admin_branch b on a.branch_id=b.id where b.is_show=1 and b.status=1 and  a.uid=".$uid;

        $command = $connection->createCommand($sql);
        $model     = $command->queryAll();

        $new_data = [];
        if($model){
            foreach ($model as $key=> $val){
                array_push($new_data,$val['id']);//只需要把组织或者部门的id提取出来
            }


        }
        return $new_data?json_encode($new_data):'';

    }



    /**
     * 把用户与角色的关联处理成角色id数组 共页面使用
     * @param $uid
     * @return array
     */
    protected function disRoleIdList($uid)
    {
        //判断该用户名是否已经存在
        $role_id_list = AdminUserRole::find()->where(['uid'=>$uid])->all();//获取用户已经拥有的角色id

        $new_list = [];
        if($role_id_list){
            foreach ($role_id_list as $key=>$val){
                array_push($new_list,$val['role_id']);
            }
        }

        return $new_list;
    }


    /**
     * 删除用户只能禁用用户的状态
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->get('id');

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = AdminUser::findOne($id);

        if ($model) {
            $model->status = $model['status'] == 1?0:1;

            if($model->save()){
                show_json(0,'删除成功');
            }else{
                var_dump($model->error);die;
            }
            show_json(100000,'删除失败');
        } else {

            show_json(100000,'删除失败');
        }
    }

    /**
     * 上传头像
     */
    public function actionUpload(){

        $base64Data = Yii::$app->request->post('base64');//base64 图片

        if(strlen($base64Data) > 1024*1024*2) show_json(100000,'Upload pictures should be less than 2M!');  //todo  限制图片大小，头像暂定小于2M

        if(!$base64Data){
            show_json(100000,'upload error');
        }

        $userid = time();//默认字符

        $Upload = new Upload;
        $data = $Upload->upload($base64Data,md5($userid),substr($userid,-3));

        if(isset($data['status']) && $data['status'] == 1){


            $host = Yii::$app->request->hostInfo;

            $avatar_url = $host.$data['http_url'];//头像地址 //默认都以小的头像

            show_json(0,'success',['avatar_url'=>$avatar_url]);

        }

        show_json(100000,$data['info']);


    }


    /******用户勾选角色**********/
   /* public function actionSetRole()
    {

        $AdminUserRole = new AdminUserRole;

        if(Yii::$app->request->isPost){
            $uid = Yii::$app->request->post('id');
            $role_id_arr = Yii::$app->request->post('items');


            if(!$uid){
                show_json(100000,'Parameter not valid');
            }

            $role_list_info = '';

            if($role_id_arr){
                $role_list_info = implode(',',$role_id_arr);//组织部门
            }
            // $rule_list = array_diff($rule_list,[$one_rule_id]);

            $bool = $AdminUserRole->addRoleUser($uid,$role_list_info);//添加用户与角色的关系

            if($bool){
                show_json(0,'add user bind role success');
            }

            show_json(100000,'add user bind role error');
        }else{
            $uid = Yii::$app->request->get('id','');

            if(!intval($uid)){
                show_json(100000,'Parameter not valid');
            }
            $myRoleList = $AdminUserRole->getMyRoleAll($uid);//角色id 数组
            return $this->render('selectrole',['myRoleList'=>$myRoleList,'uid'=>$uid]);
        }

    }*/
    public function actionSetRole()
    {

        $AdminUserRole = new AdminUserRole;

        if(Yii::$app->request->isPost){
            $uid = Yii::$app->request->post('id');
            $role_id = Yii::$app->request->post('role_id');//单条

            $type_id = Yii::$app->request->post('type');//1是添加  2是删除


            if(!$uid){
                show_json(100000,'缺少参数');
            }

            if(!$role_id){
                show_json(100000,'缺少参数');

            }

            $myRoleList = $AdminUserRole->getMyRoleAll($uid);//角色id 数组

            if($type_id == 1){
                if(count($myRoleList) <= 0){
                    $myRoleList = [];
                }

                array_push($myRoleList,$role_id);

            }elseif ($type_id == 2){

                $myRoleList = array_diff($myRoleList,[$role_id]);
            }else{
                show_json(100000,'add role bind rule error');
            }


            $role_list_info = implode(',',$myRoleList);//组织部门

            $bool = $AdminUserRole->addRoleUser($uid,$role_list_info);//添加用户与角色的关系

            if($bool){
                show_json(0,'用户角色分配成功');
            }

            show_json(100000,'建立用户下的角色失败');
        }else{
            $uid = Yii::$app->request->get('id','');

            if(!intval($uid)){
                show_json(100000,'Parameter not valid');
            }
            $myRoleList = $AdminUserRole->getMyRoleAll($uid);//角色id 数组
            return $this->render('selectrole',['myRoleList'=>$myRoleList,'uid'=>$uid]);
        }

    }
}
