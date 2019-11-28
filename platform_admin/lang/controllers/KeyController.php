<?php
namespace app\controllers;

use Yii;
use app\models;
class KeyController extends base\LangBaseController
{
    /**
     * 添加key
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost){     //添加key

            $key = filterData(Yii::$app->request->post('key'), 'string', 255, 1);    //key不能为空也不能超过255\

            $file_id = filterData( Yii::$app->request->post('file_id'), 'integer');

            $model = new models\LangAppFileKey();
            try {
                if ($model::find()->where(['file_id'=>$file_id, 'lang_key'=>$key])->one()) {
                    show_json(100000, 'Key is already exists.');
                }
                $model->file_id = $file_id;
                $model->lang_key = $key;
                if ($model->save()) {
                    show_json(0, 'Add key successful.');
                }
                throw new \Exception('');
            } catch (\Exception $e) {
                show_json(100000, 'Add key failed.');
            }
        }
    }

    /**
     * 修改key
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            try {
                $key = Yii::$app->request->post('key');
                $id = trim(Yii::$app->request->post('id'));

                $model = new models\LangAppFileKey();

                $info = $model->findOne($id);
                if (!$info) {
                    show_json(100000, 'Modify failed, not exists Key.');
                }
                if ($model::find()->where(['and', ['=', 'file_id', $info['file_id']], ['=', 'lang_key', $key], ['!=', 'id', $id]])->one()) {
                    show_json(100000, 'Key is already exists.');
                }
                $info->lang_key = $key;
                if ($info->save()) {
                    show_json(0, 'Modify successful.');
                }
                throw new \Exception($info->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Modify failed.');
            }

        }
    }

    /**
     * 删除key
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            if ((new models\LangAppFileKey())->langAppFileKeyDelete(trim(Yii::$app->request->post('id')))) {
                show_json(0, 'Delete successful.');
            }
            show_json(100000, 'Delete failed.');
        }
    }
}