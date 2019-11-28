<?php
namespace app\controllers;

use Yii;
use app\models;
class ValueController extends base\LangBaseController
{
    /**
     * 添加/修改value
     */
    public function actionAdd_edit()
    {
        if (Yii::$app->request->isPost) {
            $key_id = trim(Yii::$app->request->post('key_id'));    //检验int型参数

            $lang_id = trim(Yii::$app->request->post('lang_id'));  //检验int型参数

            $value = trim(Yii::$app->request->post('value'));  //转义,检验value的长度

            $data['id'] = trim(Yii::$app->request->post('id'));
            (new models\LangAppFileKeyValue())->addEdit($key_id, $lang_id, $value);
        }
    }
}