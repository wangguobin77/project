<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-28
 * Time: 10:22
 */

namespace app\controllers;

use app\controllers\base\BaseController;

class AppController extends BaseController
{
    public $layout = false;
    public $enableCsrfValidation = false;
    public $defaultAction = 'welcome';

    public function actionWelcome()
    {
        return $this->render('welcome');
    }
}