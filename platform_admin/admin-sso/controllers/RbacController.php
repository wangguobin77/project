<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/18
 * Time: 下午4:20
 */

namespace app\controllers;

use Yii;
use app\models\AdminUserRole;
use app\models\AdminRole;
use app\service\rbac_service;
use common\helps\Tree;
use yii\web\Controller;
class RbacController extends Controller
{
    public function init()
    {
        $this->enableCsrfValidation = false;

    }

    /**
     * 根据用户的uid 获取该用户所有的角色
     * @param $userid
     * @return array
     */
    public function actionGetRoleIdList()
    {
        if(!Yii::$app->request->isPost) return false;
        $userid = Yii::$app->request->post('uid');

        $AdminUserRole = AdminUserRole::find()->where(['uid' => $userid])->asArray()->all();

        $role_list = [];
        if($AdminUserRole && count($AdminUserRole) > 0){
            foreach ($AdminUserRole as $k=>$v){
                array_push($role_list,$v['role_id']);
            }


        }
        return json_encode($role_list);
    }

    /**
     * 获取菜单
     * 取出用户所拥有的角色 正对角色
     */
    public function actionDisUnique()
    {
        if(!Yii::$app->request->isPost) return false;

        $role_list = json_decode(Yii::$app->request->post('role_list'),true);

        if(!$role_list) return false;



        if(in_array(AdminRole::ADMIN_ID,$role_list)){//有角色1 都是具有管理员权限的用户
            return $this->actionGetAllMenuRule();//全部路由
        }

        $key_list = [];//去重 只留下用户独有的
        if(is_array($role_list) && count($role_list) >= 1){
            foreach ($role_list as $key=>$val){

                if($l = AdminRole::getRule($val)){
                    $key_list = array_merge($key_list,$l);
                }

            }
        }

        $data = $this->disToHeavy($key_list);//数组去重

       /* return $this->disRule($data);*/
        return json_encode($this->disRule($data));
    }

    /**
     * 验证用户是否有权限登陆
     */
    public function actionVerifyRule()
    {
        if(!Yii::$app->request->isPost) return false;
        $rule_list = json_decode(Yii::$app->request->post('role_list'),true);
        if(!$rule_list) return false;

        $uid = Yii::$app->request->post('uid');
        $http_host = Yii::$app->request->post('http_host');
        $request_uri = Yii::$app->request->post('request_uri');
       return json_encode(rbac_service::getInstance()->verifyRule($rule_list,$uid,$http_host,$request_uri));
    }

    /**
     * 获取角色对应的路由
     */
    public function actionGetRuleList()
    {
        return json_encode(Tree::makeTree(AdminRole::getAllRull()));//角色对应路由
    }

    /**
     * 获取所有的菜单 每条记录的索引时自己的id
     * @return array
     */
    public function actionGetAllMenuRule()
    {
        return json_encode($this->orderData(AdminRole::getAllRull()));
    }

    /**
     * 处理数组 把每个数字字段的id作为当前索引
     * @param $data
     */
    protected function orderData($data){
        $list = [];
        foreach ($data as $key=>$val){
            $list[$val['id']] = $val;
        }

        return $list;
    }

    /**
     * 数组去重
     */
    protected function disToHeavy($key_list)
    {
        $serializeArrs = array_map('serialize',$key_list);

        $uniqueArrs = array_unique($serializeArrs);

        return array_map('unserialize',$uniqueArrs);

    }

    /**
     * 根据规则 选择对应的路由或者菜单
     */
    protected function disRule($key_list)
    {
        $rule_list = $this->orderData(AdminRole::getAllRull());//全部路由

        $new_list = [];
        if($rule_list){
            foreach ($key_list as $key=>$val){
                if(isset($rule_list[$val['rule_id']])){
                    $new_list[$val['rule_id']] = $rule_list[$val['rule_id']];
                }

            }
        }

        return $new_list;
    }
}