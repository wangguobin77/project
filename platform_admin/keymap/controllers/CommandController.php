<?php
namespace app\controllers;

use Yii;
use app\models\Command;
use common\library\Manufacture;
use yii\web\Controller;
use app\controllers\base\BaseController;

class CommandController extends BaseController
//class CommandController extends Controller
{
    public $layout = false;

    public function actionAdd()
    {
        if( Yii::$app->request->isPost ) {
            try {
                $model = new Command();
                if ($model->load(['Command'=>Yii::$app->request->post()]) && $model->save()) {
                    show_json(0, Yii::$app->params['errorCode'][0]);
                }
                $msg = current($model->getErrors()) ? : Yii::$app->params['errorCode'][100000];
                show_json(100000, $msg);
            } catch (\Exception $e) {
                show_json(100000, Yii::$app->params['errorCode'][100000]);
            }
        }

        $category = Manufacture::getCategoryAll();

        return $this->render('add', ['category'=>$category]);
    }

    /**
     * command select all
     * @return
     */
    public function actionList()
    {
        $data = Command::find('id,key,code,version,analog_params,normal_params')->orderBy('code')->asArray()->all();

        return $this->render('list', ['data'=>$data]);
    }

    /*
     * command edit
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = Command::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'Command is not exists.');
                }
                if ($model->load(['Command'=>Yii::$app->request->post()]) && $model->save()) {
                    show_json(0, Yii::$app->params['errorCode'][0]);
                }
                show_json(100000, current($model->getErrors())? : Yii::$app->params['errorCode'][100000]);
            } catch (\Exception $e) {
                show_json(100000, Yii::$app->params['errorCode'][100000]);
            }
        }

        $info = Command::findOne(trim(Yii::$app->request->get('id')));

        if ($info) {
            $info['category_id'] = explode(',',$info['category_id']);
            $category = Manufacture::getCategoryAll();

            return $this->render('edit', [
                'info'  =>  $info,
                'category'  =>  $category,
            ]);
        }
    }

    /**
     * command delete
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            try {
                if (Command::findOne(trim(Yii::$app->request->post('id')))->delete()) {
                    show_json(0, Yii::$app->params['errorCode'][0]);
                }
                throw new \Exception();
            } catch (\Exception $e) {
                show_json(100000, Yii::$app->params['errorCode'][100000]);
            }
        }
    }

}