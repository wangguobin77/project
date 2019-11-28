<?php
namespace app\controllers;

use Yii;
use app\controllers\base\DeviceTypeBaseController;
class Device_typeController extends DeviceTypeBaseController
{
    public $layout = false;

    /**
     * @添加DEVICE
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $data = $this->deviceValidate();    //验证表单数据，并过滤空格

            $this->deviceTypeAdd($data);
        }

        return $this->render('add', [
            'category'    =>  $this->categoryList(),  //所有的device大类
            'manufacture'    =>  $this->manufactureList(),   //所有的厂商
        ]);
    }

    /**
     * DEVICE TYPE 列表
     */
    public function actionList()
    {
        $data = $this->deviceTypeList();

        return $this->render('list', $data);
    }

    /**
     * @修改 DEVICE TYPE
     */
    public function actionEdit()
    {
        if (Yii::$app->request->isPost) {
            $data = $this->deviceValidate();   //验证表单穿过来的信息

            $this->deviceTypeEdit($data);
        }

        $info = $this->getDeviceType(trim(Yii::$app->request->get('id')));

        if ($info) {
            return $this->render('edit', [
                'info'  =>  $info,
                'category'  =>  $this->categoryList(),  //所有的device大类
                'manufacture'   =>  $this->manufactureList(),   //所有的厂商
            ]);
        }
    }

    /**
     * 适配遥控器
     */
    public function actionSet()
    {
        if (Yii::$app->request->isPost) {
            $this->setRemoteType();
        }

        $id = trim(Yii::$app->request->get('id'));
        $info = $this->getDeviceType($id);
        if ($info) {
            $info['remote_type'] = $this->getDeviceTypeRemoteType($id);
            return $this->render('set', [
                'info' => $info,
                'remotetype' => $this->remoteTypeList(),
            ]);
        }

    }

    /**
     * 删除 DEVICE TYPE(软删除)
     */
    public function actionDelete()
    {
        if (Yii::$app->request->isPost) {
            $this->deviceTypeDelete();
        }
    }

    /**
     * 车堵删除
     */
    public function actionDelete_true()
    {
        if (Yii::$app->request->isPost) {
            $this->deviceTypeDeleteTrue();
        }
    }

    //修改数据库结构
    public function actionAlter()
    {
        $data = Yii::$app->db->createCommand('select * from `device_type_remote_type`')->queryAll();
        $index = 1;
        foreach ($data as $key=>$value) {
            Yii::$app->db->createCommand()->update('device_type_remote_type',['id'=>$index],['id'=>$value['id']])->execute();
            $index++;
        }
        Yii::$app->db->createCommand('alter table device_type_remote_type add unique index(device_type_id,remote_type_id)')->execute();
        Yii::$app->db->createCommand('alter table device_type_remote_type modify column id int auto_increment')->execute();
        echo 'ok';
    }
}



