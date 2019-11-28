<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/18
 * Time: 下午4:59
 */

namespace app\service;

use Yii;
use yii\base\Behavior;
use app\models\AdminRole;
use yii\helpers\ArrayHelper;
use app\models\AdminUserRole;
class rbac_service extends Behavior
{
    public static $_instance;

    //初始化该类
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();//当前配置文件
        }
        return self::$_instance;
    }

    /**
     * 放行路由
     * @var array
     */
    public $allowUrl = [
        'site/logout',
        'site/login',
        'index/index',
        'index/main'
    ];

    private $adminId = 1;//系统id

    /**
     * 获取当前route 的id
     * @param $data
     * @return bool
     */
    private function isRulePermissions($data,$HTTP_HOST,$REQUEST_URI)
    {
        $url_arr = [];//url 数组
        $url_arr[0] =  'http://'.$HTTP_HOST.$REQUEST_URI;

        $route = Yii::$app->request->getQueryParam('r');//当前路由模块 不同项目里可能访问形式不一样 所以倒是这里需要更改
        $url_arr[1] = $route;
        if(!$route){
            $route = 'index/index';
        }
        foreach ($data as $key=>$val){

            if(in_array($val['route'],$url_arr) || in_array($route,$this->allowUrl)){
                return true;//说明有权限
            }

        }

        return false;//没有权限
    }

    /**
     * 验证登陆的用户是否有访问权限
     * @param $rule_list
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function verifyRule($rule_list,$uid,$HTTP_HOST,$REQUEST_URI)
    {

        $this->allowUrl = array_merge(Yii::$app->params['allowUrl'], $this->allowUrl);


        if($role_list = $this->getRoleIdList($uid)){

            if(in_array(AdminRole::ADMIN_ID,$role_list))return true;


            return  $this->isRulePermissions($rule_list,$HTTP_HOST,$REQUEST_URI);//根据角色获取所有的路由

        }
        return false;
    }

    /**
     * 根据用户id获取用户的所有角色
     * @param $userid 登陆用户id 获取该用户所有的角色
     * @return array
     */
    protected function getRoleIdList($userid)
    {
        $AdminUserRole = AdminUserRole::find()->where(['uid' => $userid])->asArray()->all();

        $role_list = [];
        if($AdminUserRole && count($AdminUserRole) > 0){
            foreach ($AdminUserRole as $k=>$v){
                array_push($role_list,$v['role_id']);
            }


        }
        return $role_list;
    }
}