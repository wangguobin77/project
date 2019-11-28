<?php
namespace app\controllers;

use Yii;
class ParamController extends base\ParamBaseController
{
    public $layout = false;

    /*
     * 小车分类列表
     */
    public function actionList()
    {
        $result = $this->getListDataLogic();

        $data = [
            'data' => $result
        ];
        return $this->render('list', $data);
    }

    /*
     * 添加小车参数分类
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $this->addCategory();
        }

        $data = array();
        $pid = trim(Yii::$app->request->get('pid'));
        if ($pid) {
            $data['parent'] = $this->getParamOne($pid);
        }

        return $this->render('add', $data);
    }

    /**
     * 修改小车参数
     */
    public function actionEdit()
    {
        $info = $this->getParamOne(trim(Yii::$app->request->get('id')));
        if ($info) {
            $relation = $this->getExistsRelationByCategoryId($info['id']);
//            var_dump($relation);die;
            return $this->render('edit', ['info'=>$info, 'relation'=>$relation]);
        }
    }

    /*
     * 删除小车参数分类
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            $code = $this->deleteCategoryLogic(trim(Yii::$app->request->post('id')));

            show_json($code, $this->getErrMessage($code));
        }
    }
}