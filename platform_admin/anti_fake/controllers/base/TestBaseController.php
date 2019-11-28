<?php
namespace app\controllers\base;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/1/21
 * Time: 下午5:42
 */

use yii;
use yii\web\Controller;
use app\service\test_service;
class TestBaseController extends BaseController
{

    public function actionTest()
    {
        $openid = test_service::getInstance()->tt();

        var_dump($openid);
    }

}