<?php

// +----------------------------------------------------------------------
// | TITLE:后台首页
// +----------------------------------------------------------------------

namespace app\controllers;


use app\helps\Tree;
use Yii;
use app\models\AdminUser;
use app\models\TestForm;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class IndexController
 * @package controllers
 */
class IndexController extends BaseController
{

    public function init()
    {
        $this->layout = false;
    }
    //
    public function actionIndex()
    {
        //$first_login_time = Yii::$app->user->identity->first_login_time;//第一次登陆时间
        $prompt = true;
        /* if(!$first_login_time){
             $prompt = false;//为false 首页需要提示用户修改密码提示框
         }*/
        //$this->layout = false;
        //  return $this->render('index_new',['menu'=>$this->menuHtml]);
        return $this->render('welcome',['menu'=>$this->dMenuHtml,'prompt'=>$prompt]);
    }

    public function actionMain()
    {

        $mysql = Yii::$app->db->createCommand("select VERSION() as version")->queryAll();
        $mysql=$mysql[0]['version'];
        $info =
            [
                '操作系统'=>PHP_OS,
                '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
                'PHP运行方式'=>php_sapi_name(),
                'PHP版本'=>PHP_VERSION,
                'MYSQL版本'=>$mysql,
                '上传附件限制'=>ini_get('upload_max_filesize'),
                '执行时间限制'=>ini_get('max_execution_time').' s',
                '剩余空间'=>round((@disk_free_space(".") / (1024 * 1024)), 2).' M',

            ];
        return $this->render('main',['info'=>$info]);
    }


}
