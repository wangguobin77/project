<?php
namespace app\controllers;

use Yii;
use app\controllers\base\RemoteTypeBaseController;
class Remote_typeController extends RemoteTypeBaseController
{
    public $layout = false;

    /**
     * 添加遥控器
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $this->remoteTypeAdd();
        }

        return $this->render('add',['manufacture'=>$this->getManufactureAll()]);
    }

    /**
     * 遥控器列表
     */
    public function actionList()
    {
        $data = $this->remoteTypeList();
        return $this->render('list', $data);
    }

    /**
     * 修改遥控器
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            $this->remoteTypeEdit();
        }
        $info = $this->remoteTypeInfo(trim(Yii::$app->request->get('id')));

        if ($info) {
            return $this->render('edit', [
                'manufacture' => $this->getManufactureAll(),  //厂商信息
                'info' => $info,
            ]);
        }
    }

    /**
     * 删除遥控器(软删除)
     */
    public function actionDelete()
    {
        $this->remoteTypeDelete();
    }

    /**
     * 删除遥控器(彻底删除)
     */
    public function actionDelete_true()
    {
        $this->remoteTypeDeleteTrue();
    }

    /**
     * 遥控器配置按键
     */
    public function actionKeyset()
    {
        if (Yii::$app->request->isPost) {
            $this->keysetAdd();
        }
        $id = trim(Yii::$app->request->get('id'));
        $remoteType = $this->remoteTypeInfo($id);
        if ($remoteType) {
            return $this->render('keyset', [
                'keyset' => $this->selectKeySetAll(),
                'info' => $remoteType,
                'keysetInfo' => $this->selectKeysetById($id),
            ]);
        }
    }
}



