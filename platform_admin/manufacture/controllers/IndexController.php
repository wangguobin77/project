<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-20
 * Time: 17:58
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;

class IndexController extends Controller
{
    public function actionIndex()
    {
        header('location:' . Yii::$app->urlManager->createUrl('manufacture/list'));
    }
}