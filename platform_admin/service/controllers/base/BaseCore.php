<?php
namespace app\controllers\base;
/**
 * 权限控制继承核心类
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/9/10
 * Time: 下午1:59
 */
use yii\web\Controller;

class BaseCore extends Controller
{
    const ADMIN_DOING = 'ADMIN_RUN';

    const ADMIN_ID = 1;

    public $layout = 'public';

    public $menu;

    public $menuHtml;

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

     public $dMenuHtml;//顶级菜单

    public static  $dChildrenMenuHtml;//二级菜单

    public static $_menuCurrentId = [];//记录当前子菜单以上的父类

    public static $allMenu;//全局菜单

    public static $rule_list = [];//路由

    public static $role_list;//角色列表

    public static $menu_key = [];


}