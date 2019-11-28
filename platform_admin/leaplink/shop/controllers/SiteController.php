<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-20
 * Time: 17:58
 */

namespace app\controllers;


use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['app/login']);
    }
}