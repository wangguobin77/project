<?php
namespace app\controllers;

use Yii;
use app\models;
use yii\data\Pagination;
class FileController extends base\LangBaseController
{
    /**
     * 列表
     * @return string
     */
    public function actionList()
    {
        $query = models\LangAppFile::find()
            ->select('a.*,b.app_name')
            ->from('lang_app_file a')
            ->join('left join', 'lang_app b', 'a.app_id = b.id');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->orderBy('a.id desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('/app_file/list', [
            'data'=>$models,
            'pages'=>$pages,
        ]);
    }

    /**
     * 添加
     * @return string
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = new models\LangAppFile();
                switch (trim(Yii::$app->request->post('type'))) {
                    case '2':
                        $function_name = 'android_make_file';
                        break;
                    case '3':
                        $function_name = 'u3d_make_file';
                        break;
                    default:
                        $function_name = 'make_file';
                }
                $model->app_id = trim(Yii::$app->request->post('app_id'));
                $model->file_path = trim(Yii::$app->request->post('file_path'));
                $model->file_name = trim(Yii::$app->request->post('file_name'));
                $model->function_name = $function_name;
                if ($model->save()) {
                    show_json(0, 'Add successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Add failed.');
            }
        }

        return $this->render('/app_file/add',[
            'app' => models\LangApp::find()->asArray()->all(),
        ]);
    }

    /**
     * 修改
     * @return string
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = models\LangAppFile::findOne(trim(Yii::$app->request->post('id')));
                switch (trim(Yii::$app->request->post('type'))) {
                    case '2':
                        $function_name = 'android_make_file';
                        break;
                    case '3':
                        $function_name = 'u3d_make_file';
                        break;
                    default:
                        $function_name = 'make_file';
                }
                if (!$model) {
                    show_json(100000, 'App File not exists.');
                }
                $model->file_path = trim(Yii::$app->request->post('file_path'));
                $model->file_name = trim(Yii::$app->request->post('file_name'));
                $model->function_name = $function_name;
                if ($model->save()) {
                    show_json(0, 'Modify successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Modify failed.');
            }
        }

        $info = models\LangAppFile::find()->select('a.*,b.app_name')
            ->from('lang_app_file a')
            ->join('left join', 'lang_app b', 'a.app_id = b.id')
            ->where(['a.id'=>trim(Yii::$app->request->get('id'))])->asArray()->one();
        if ($info) {
            switch ($info['function_name']) {
                case 'make_file': $info['type'] = 1;
                    break;
                case 'android_make_file': $info['type'] = 2;
                    break;
                case 'u3d_make_file': $info['type'] = 3;
                    break;
            }

            return $this->render('/app_file/edit',[
                'info' => $info
            ]);
        }
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = models\LangAppFile::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'App File not exists.');
                }
                $model->is_delete = 1;
                if ($model->save()) {
                    show_json(0, 'Delete successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Delete failed.');
            }
        }
    }

    /**
     * 彻底删除
     */
    public function actionDelete_true()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = models\LangAppFile::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'App File not exists.');
                }
                if (models\LangAppFileKey::find()->where(['file_id'=>$model['id']])->one()) {
                    show_json(100000, 'App File exists keys, please delete these keys first.');
                }
                $model->is_delete = 1;
                if ($model->save()) {
                    show_json(0, 'Delete successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Delete failed.');
            }
        }
    }

    /**
     * 恢复
     */
    public function actionRecycle()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = models\LangAppFile::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'App File not exists.');
                }
                $model->is_delete = 0;
                if ($model->save()) {
                    show_json(0, 'Recycle successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Recycle failed.');
            }
        }
    }
}