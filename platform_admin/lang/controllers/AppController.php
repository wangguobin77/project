<?php
namespace app\controllers;

use Yii;
use app\models;
use yii\data\Pagination;
class AppController extends base\LangBaseController
{
    private static $app_id;
    /**
     * 列表
     * @return string
     */
    public function actionList()
    {
        $query = models\LangApp::find();
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return $this->render('/app/list', [
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
            $tr = Yii::$app->db->beginTransaction();
            try {
                $language = Yii::$app->request->post('language');
                if (!$language || empty($language)) {
                    show_json(100000, 'Language is required.');
                }
                if (array_intersect($language, array_column(models\Lang::find()->asArray()->all(), 'id')) != $language) {
                    show_json(100000, 'Language not exists');
                }
                $app = new models\LangApp();
                $app->app_name = trim(Yii::$app->request->post('app_name'));
                if ($app->save()) {
                    self::$app_id = $app->getPrimaryKey();
                    $language = array_map('self::map_language', $language);
                    Yii::$app->db->createCommand()->batchInsert('lang_app_lang', ['app_id', 'lang_id'], $language)->execute();
                    $tr->commit();
                    show_json(0, 'Add successful.');
                }
                throw new \Exception($app->getErrors());
            } catch (\Exception $e) {
                $tr->rollBack();
                show_json(100000, 'Add failed.');
            }
        }

        $language = models\Lang::find()->asArray()->all();
        return $this->render('/app/add',[
            'language'=>$language,
        ]);
    }

    /**
     * 删除
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = models\LangApp::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'App is not exists.');
                }
                if ($model['is_delete'] == 1) {
                    show_json(100000, 'App is already deleted.');
                }
                $model->is_delete = 1;
                if ($model->save()) {
                    show_json(0, 'Delete successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Delete falied.');
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
                $model = models\LangApp::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'App is not exists.');
                }
                $model->is_delete = 0;
                if ($model->save()) {
                    show_json(0, 'Recycle successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Recycle falied.');
            }
        }
    }

    /**
     * 修改
     * @return string
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            $tr = Yii::$app->db->beginTransaction();
            try {
                $language = Yii::$app->request->post('language');
                if (!$language || empty($language)) {
                    show_json(100000, 'Language is required.');
                }
                if (array_intersect($language, array_column(models\Lang::find()->asArray()->all(), 'id')) != $language) {
                    show_json(100000, 'Language not exists');
                }
                $app = models\LangApp::findOne(trim(Yii::$app->request->post('id')));
                if (!$app) {
                    show_json(100000, 'App not exists');
                }
                $app->app_name = trim(Yii::$app->request->post('app_name'));
                if ($app->save()) {
                    self::$app_id = $app->getPrimaryKey();
                    $language = array_map('self::map_language', $language);
                    Yii::$app->db->createCommand()->delete('lang_app_lang', ['app_id'=>self::$app_id])->execute();
                    Yii::$app->db->createCommand()->batchInsert('lang_app_lang', ['app_id', 'lang_id'], $language)->execute();
                    $tr->commit();
                    show_json(0, 'Modify successful.');
                }
                throw new \Exception($app->getErrors());
            } catch (\Exception $e) {
                $tr->rollBack();
                show_json(100000, 'Modify falied.');
            }
        }

        $info = models\LangApp::find()->where(['id'=>trim(Yii::$app->request->get('id'))])->asArray()->one();
        if ($info) {
            $info['language'] = array_column(models\LangAppLang::find()->where(['app_id'=>$info['id']])->asArray()->all(), 'lang_id');
            $language = models\Lang::find()->asArray()->all();
            return $this->render('/app/edit',[
                'info' => $info
                ,'language'=>$language,
            ]);
        }
    }

    /**
     * 彻底删除
     */
    public function actionDelete_true()
    {
        if (Yii::$app->request->isPost) {
            try {
                $model = models\LangApp::findOne(trim(Yii::$app->request->post('id')));
                if (!$model) {
                    show_json(100000, 'App is not exists.');
                }
                if (models\LangAppFile::find()->where(['app_id'=>$model['id']])->one()) {
                    show_json(100000, 'App exists file, please delete these file first.');
                }
                if ($model->delete()) {
                    models\LangAppLang::deleteAll(['app_id' => $model['id']]);
                    show_json(0, 'Recycle successful.');
                }
                throw new \Exception($model->getErrors());
            } catch (\Exception $e) {
                show_json(100000, 'Recycle falied.');
            }
        }
    }

    private static function map_language($language)
    {
        return [self::$app_id, $language];
    }
}