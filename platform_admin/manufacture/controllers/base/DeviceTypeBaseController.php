<?php
namespace app\controllers\base;

use Yii;
use yii\data\Pagination;
use common\library\Manufacture;
use app\models\DeviceType;
use app\models\DeviceTypeRemoteType;
class DeviceTypeBaseController extends BaseController
{
    /*
     * @添加设备类型
     */
    protected function deviceTypeAdd($data)
    {
        $data['id'] = createGuid(32);
        $data['type_en'] = $data['type'];
        //防止添加的时候覆盖之前的记录(guid有可能会重复)
        if (DeviceType::findOne($data['id'])) {
            show_json(100000, $this->getErrMessage(100000));
        }

        $model = new DeviceType();
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(['DeviceType'=>$data]) && $model->save()) {
//                var_db_log($this->userid, 'add', 'device_type', $data);
                $tr->commit();
                insert_db_log('insert', "添加设备类型");
                show_json(0, $this->getErrMessage(0), $data['id']);
            }
            $tr->rollBack();
            $msg = $model->getErrors() ? current($model->getErrors()) : $this->getErrMessage(100000);
            show_json(100000, $msg);
        } catch (\Exception $e) {
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /*
     * 修改设备类型
     */
    protected function deviceTypeEdit($data)
    {
        $id = trim(Yii::$app->request->post('id'));
        $model = DeviceType::findOne($id);

        //验证status
        $data['status'] = trim(Yii::$app->request->post('status'));
        if (!in_array($data['status'], ['0','1','2'])) {
            show_json(102000,Yii::$app->params['errorCode'][102000]);
        }

        $model->type = $data['type'];
        $model->name_en = $data['name_en'];
        $model->name = $data['name'];
        $model->category_id = $data['category_id'];
        $model->manufacture_id = $data['manufacture_id'];
        $model->description = $data['description'];
        $model->status = $data['status'];

        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
//                var_db_log($this->userid, 'update', 'device_type', $data);
                $tr->commit();
                insert_db_log('update', "修改设备类型 id-{$id}");
                show_json(0, $this->getErrMessage(0));
            }
            $tr->rollBack();
            $msg = $model->getErrors() ? current($model->getErrors()) : $this->getErrMessage(100000);
            show_json(100000, $msg);
        } catch (\Exception $e) {
            $tr->rollBack();

            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /*
     * DEVICE TYPE 列表
     */
    protected function deviceTypeList()
    {
        $mid = trim(Yii::$app->request->get('mid'));
        if ($mid) {
            $where = ['=','a.manufacture_id',$mid];
        }else{
            $where = [];
        }
        $query = DeviceType::find()
            ->select('a.*,b.name m_name, b.name manufacture_name,c.name category_name')
            ->from('device_type a')
            ->join('left join', 'manufacture b', 'a.manufacture_id = b.id')
            ->join('left join', 'category c', 'a.category_id = c.id')
            ->where($where);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->orderBy('add_time desc')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return [
            'data'  =>  $models,
            'pages' =>  $pages,
            'mid'    =>  $mid,
        ];
    }

    /*
     * 删除 DEVICE TYPE
     */
    protected function deviceTypeDelete()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $id = trim(Yii::$app->request->post('id'));

            if (DeviceType::updateAll(['is_deleted'=>1], ['id'=>$id])) {
//                var_db_log($this->userid, 'update', 'device_type', ['id'=>$id, 'status'=>$deleted]);
                $tr->commit();
                insert_db_log('update', "软删除设备类型 id-{$id}");
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /**
     * 彻底删除device type
     */
    protected function deviceTypeDeleteTrue()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $id = trim(Yii::$app->request->post('id'));
            $info = DeviceType::findOne($id);
            if (!$info) {
                show_json(100000, $this->getErrMessage(100000));
            }

            if ($info['is_deleted'] != 1) {
                show_json(100000, '删除之后才能彻底删除');
            }

            //关联批次的设备不能删除
            $man = new Manufacture();
            if ($man->checkManufactureExistsSn($info['id'], 2)) {
                show_json(100000, '该设备关联了批次,不能删除!');
            }

            if ($info->delete()) {
                DeviceTypeRemoteType::deleteAll(['device_type_id' => $info['id']]);
                $tr->commit();
                insert_db_log('delete', "删除设备类型 id-{$id}");
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /*
     * DEVICE TYPE 详细信息
     * @param $device_id int id
     * @return array
     */
    protected function deviceOne($device_id)
    {
        $deviceInfo = $this->getDeviceType($device_id);  //device信息

        //没有数据
        if (!$deviceInfo) {
            show_json(102000, $this->getErrMessage(102000));
        }

        $remoteData = $this->getDeviceTypeRemoteType($device_id);  //适用遥控器系列

        //没有适用的遥控器系列
        if (!$remoteData) {
            $deviceInfo['remote_serial'] = array();
            $deviceInfo['remote_serial_ids'] = array();
            return $deviceInfo;
        }
        //有适用的遥控器系列
        foreach ($remoteData as $val) {
            $deviceInfo['remote_serial'][] = $val;
            if ($val['is_deleted'] == 0) {
                $deviceInfo['remote_serial_ids'][] = $val['remote_type_id'];
            }
        }
        //没有适用的要空袭系列，给空数组
        if (!isset($deviceInfo['remote_serial_ids'])) {
            $deviceInfo['remote_serial_ids'] = array();
        }

        return $deviceInfo;
    }

    /*
     * 获取category
     */
    protected function categoryList()
    {
        return Manufacture::getCategoryAll();
    }

    /*
     * 获取manufacture
     */
    protected function manufactureList()
    {
        return Manufacture::getManufactureALl();
    }

    /**
     * 获取remote type
     */
    protected function remoteTypeList()
    {
        return Manufacture::getRemoteTypeAll();
    }

    /*
     * 验证 DEVICE TYPE
     * @return array
     */
    protected function deviceValidate()
    {
        $data['type'] = filterData(Yii::$app->request->post('type'), 'string', 128, 2);   //转义type，并验证长度

        $data['name'] = filterData(Yii::$app->request->post('name'), 'string', 128, 2);   //转义name，并验证长度

        $data['name_en'] = filterData(Yii::$app->request->post('name_en'), 'string', 128, 2);   //转义name_en，并验证长度

        $data['description'] = filterData(Yii::$app->request->post('description'), 'string', 4*1024, 0);   //验证description

        //验证kemanufacture(必须是32位)
        $data['manufacture_id'] = trim(Yii::$app->request->post('manufacture_id'));
        if (!preg_match( "/^[a-zA-Z0-9]{1,32}$/u",$data['manufacture_id'])) {
            show_json(102000, $this->getErrMessage(102000));
        }

        //验证category(必须是32位)
        $data['category_id'] = trim(Yii::$app->request->post('category_id'));
        if (!preg_match( "/^[a-zA-Z0-9]{1,32}$/u",$data['category_id'])) {
            show_json(102000, $this->getErrMessage(102000));
        }
        return $data;
    }

    /**
     * 根据主键和状态查找device
     * @param $id
     * @param int $deleted
     * @return mixed
     */
    protected function getDeviceType($id)
    {
        try {
            return DeviceType::find()->where(['id'=>$id])->asArray()->one();
        } catch (\Exception $e) {
            return false;
        }
    }

    /*
     * @获取终端适用遥控器
     */
    protected function getDeviceTypeRemoteType($device_id)
    {
        $data = array();
        try {
            $remoteType = DeviceTypeRemoteType::find()->select('remote_type_id')->where(['is_deleted' => 0,'device_type_id'=>$device_id])->asArray()->all();
            if ($remoteType) {
                return array_column($remoteType, 'remote_type_id');
            }
            return $data;
        } catch (\Exception $e) {
            return $data;
        }

    }

    //适配遥控器
    protected function setRemoteType()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $id = trim(Yii::$app->request->post('id'));

            $info = $this->getDeviceType($id);
            if (!$info) {
                show_json(100000, $this->getErrMessage(100000));
            }
            $remote = trim(Yii::$app->request->post('remote_type_id'));

            //$checked为真认为是添加，为否认为是取消
            $checked = trim(Yii::$app->request->post('checked'));
            if ($checked) {
                $model = new DeviceTypeRemoteType();
                $model->device_type_id = $id;
                $model->remote_type_id = $remote;
                if (!$model->save()) {
                    throw new \Exception('fail');
                }
            } else {
                DeviceTypeRemoteType::deleteAll(['and',['=', 'device_type_id', $id], ['=', 'remote_type_id', $remote]]);
            }
            $tr->commit();
            insert_db_log($checked?'insert':'delete', ($checked?'添加':'删除')."终端遥控器映射:终端id-{$id} 遥控器id-{$remote}");
            show_json(0, $this->getErrMessage(0));
        } catch (\Exception $e) {
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }



/*        //修改之前适配的遥控器系列
        $now = is_array($remote) ? $remote : array();

        //修改之后适配的遥控器系列
        $brfore = $this->getDeviceTypeRemoteType($id);
        //不在适配的遥控器
        $delete = $brfore ? array_diff($brfore, $now) :  array();
        //新适配的遥控器
        $insert = $brfore ? array_diff($now, $brfore) :  $now;

        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($delete) {
                DeviceTypeRemoteType::deleteAll(['and',['=', 'device_type_id', $id], ['in', 'remote_type_id', $delete]]);
            }
            if ($insert) {
                foreach ($insert as $item) {
                    $model = new DeviceTypeRemoteType();
                    $model->device_type_id = $id;
                    $model->remote_type_id = $item;
                    if (!$model->save()) {
                        throw new \Exception(current($model->getErrors()));
                    }
                }
            }
            $tr->commit();
            show_json(0, $this->getErrMessage(0));
        } catch (\Exception $e) {
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }*/
    }
}