<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Keycode;
use app\controllers\base\BaseController;

class KeycodeController extends BaseController
//class KeycodeController extends Controller
{
    public $layout = false;

    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = new Keycode();
                if ($model->load(['Keycode'=>Yii::$app->request->post()]) && $model->save()) {
                    show_json(0, Yii::$app->params['errorCode'][0]);
                }
                show_json(100000, current($model->getErrors()) ? : Yii::$app->params['errorCode'][100000]);
            } catch (\Exception $e) {
                show_json(100000, Yii::$app->params['errorCode'][100000]);
            }
        }
        return $this->render('keycode_add');
    }

    public function actionList()
    {
        $data = Keycode::find('id,key,code,parent,type')->orderBy('key')->asArray()->all();

        return $this->render('list', ['data'=>$data]);
    }

    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {

            try {
                $model = Keycode::findOne(trim(Yii::$app->request->post('id')));

                if ($model->load(['Keycode'=>Yii::$app->request->post()]) && $model->save()) {
                    show_json(0, Yii::$app->params['errorCode'][0]);
                }
                show_json(100000, current($model->getErrors()) ? : Yii::$app->params['errorCode'][100000]);
            } catch (\Exception $e) {
                show_json(100000, Yii::$app->params['errorCode'][100000]);
            }
        }

        $info = Keycode::findOne(trim(Yii::$app->request->get('id')));

        if ($info) {
            return $this->render('keycode_edit', ['info'=>$info]);
        }
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = Keycode::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'keycode is not exists.');
                }
                if ($model->delete()) {
                    show_json(0, Yii::$app->params['errorCode'][0]);
                }
                throw new \Exception();
            } catch (\Exception $e) {
                show_json(100000, Yii::$app->params['errorCode'][100000]);
            }

        }
    }
}