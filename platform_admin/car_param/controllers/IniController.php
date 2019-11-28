<?php
namespace app\controllers;

use Yii;
class IniController extends base\IniBaseController
{
    public $layout = false;

    /*
     * ini 文件列表
     */
    public function actionList()
    {
        return $this->render('list', $this->getListDataLogic());
    }

    /*
     * ini add
     */
    public function actionAdd()
    {
        return $this->render('add', ['category'=>$this->getFirstCategory()]);
    }

    /*
     * ini add ajax
     */
    public function actionAdd_ajax()
    {
        if (Yii::$app->request->isPost) {
            $this->addIniAjaxLogic();
        }
    }

    /*
     * ini edit
     */
    public function actionEdit()
    {
        $info = $this->getIniInfo(trim(Yii::$app->request->get('id')));
        if ($info) {
            return $this->render('edit', ['info'=>$info, 'category'=>$this->getFirstCategory()]);
        }
    }

    /*
     * ini edit ajax
     */
    public function actionEdit_ajax()
    {
        if (Yii::$app->request->isPost) {
            $this->editIniInfoAjaxLogic();
        }
    }

    /*
     * 删除 ini
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            $this->deleteIni();
        }
    }

    /*
     * 删除 relation
     */
    public function actionDelete_relation()
    {
        if (Yii::$app->request->isPost) {
            $this->deleteIniRelation();
        }
    }

    /*
     * ini 添加关联小车参数
     */
    public function actionRelation()
    {
        $id = trim(Yii::$app->request->get('id'));
        $info = $this->getIniInfo($id);
        if ($info) {
            $category = $this->getCategoury($info['category_id']);

            $relations = $this->getExistsRelation($id);

            $data = [
                'info'    =>  $info,
                'category'  =>  $category['info'],
                'relation' => $relations,
                'categoryList'  =>  $category['children'],
            ];

            return $this->render('relation', $data);
        }
    }

    /*
     * ini 添加关联小车参数
     */
    public function actionRelation_ajax()
    {
        if (Yii::$app->request->isPost) {
            $this->addIniRelation();
        }
    }
}