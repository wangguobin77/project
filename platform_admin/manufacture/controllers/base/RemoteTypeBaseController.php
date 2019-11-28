<?php
namespace app\controllers\base;

use Yii;
use app\models;
use yii\data\Pagination;
use common\library\Manufacture;
use common\library\Keymap_json;
use app\models\RemoteType;
class RemoteTypeBaseController extends BaseController
{
    /*
     * 添加遥控器
     */
    protected function remoteTypeAdd()
    {
        $data = $this->remoteTypeValidate();   //验证表单信息

        $data['id'] = createGuid(32);
        $data['type_en'] = $data['type']; //删除的字段，留作备用字段
        $model = new RemoteType;
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(['RemoteType'=>$data]) && $model->save()) {
//                var_db_log($this->userid, 'add', 'remote_type', $data);
                $tr->commit();
                insert_db_log('insert', "添加遥控器");
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
     * 修改遥控器
     */
    protected function remoteTypeEdit()
    {
        $data = $this->remoteTypeValidate();    //验证表单信息
        $id = trim(Yii::$app->request->post('id'));
        $model = RemoteType::findOne($id);
        $data['status'] = trim(Yii::$app->request->post('status'));
        //验证status
        if ($data['status']!=0 && $data['status']!=1 && $data['status']!=2) {
            show_json(102000, Yii::$app->params['errorCode'][102000]);
        }
        $model->name = $data['name'];
        $model->name_en = $data['name_en'];
        $model->type = $data['type'];
//        $model->type_en = $data['type_en'];
        $model->manufacture_id = $data['manufacture_id'];
        $model->description = $data['description'];
        $model->key = $data['key'];
        $model->code = $data['code'];
        $model->screen_code = $data['screen_code'];
        $model->screen = $data['screen'];
        $model->carry_type_code = $data['carry_type_code'];
        $model->carry_type = $data['carry_type'];
        $model->status = $data['status'];
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
//                var_db_log($this->userid, 'update', 'remote_type', $data);
                $tr->commit();
                insert_db_log('update', "修改遥控器 id-{$id}");
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

    /**
     * 遥控器列表
     */
    protected function remoteTypeList()
    {
        $mid = trim(Yii::$app->request->get('mid'));
        $where = array();
        if ($mid) {
            $where = ['a.manufacture_id'=>$mid];
        }

        $query = RemoteType::find()
            ->select('a.*,b.name manufacture_name')
            ->from('remote_type a')
            ->join('left join', 'manufacture b', 'a.manufacture_id = b.id')
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
//            'keywords'    =>  $keywords,
//            'status'    =>  $status,
            'mid'    =>  $mid,
        ];
    }

    /*
     * 遥控器配置按键
     */
    protected function keysetAdd()
    {
        $id = trim(Yii::$app->request->post('id'));
        if (!$this->remoteTypeInfo($id)) {
            show_json(102000, $this->getErrMessage(102000));  //遥控器不存在
        }

        //按键的类型
        $type = trim(Yii::$app->request->post('type'));
        //按键
        $key = trim(Yii::$app->request->post('key'));

        $keycode = $this->selectKeycodeAll();
        $keys = $this->selectKeySetAll();   //所有的按键
        if (!isset($keys[$type])) {
            show_json(102000, $this->getErrMessage(102000));   //没有这个类型的按键
        }

        if (!in_array($key, $this->selectKeySetAll()[$type])) {
            show_json(102000, $this->getErrMessage(102000));   //$type类型下没有这个按键
        }

        $analog = array();  //偏移量
        //这几个类型的按键有偏移量
        if (in_array($type, ['JOYSTICK', 'KEY_JOYSTICK', 'GYRO'])) {
            foreach ($keycode as $item) {
                if ($item['parent'] == $key && $item['keytype'] == 3) {
                    $analog[] = $item['key'];
                }
            }
        }

        //check只要有值就认为是选中
        $checked = trim(Yii::$app->request->post('checked'));

        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($checked) {
                $array = array();
                foreach ($analog as $item) {
                    $array[] = array($id, $item, $item);
                }
                $result = Yii::$app->db->createCommand()->insert('remote_keyset',['remote_type_id'=>$id,'key'=>$key])->execute();
                if (!empty($analog) && $result) {
                    if (!Yii::$app->db->createCommand()->batchInsert('remote_analog', ['remote_type_id', 'analog', 'tag'], $array)->execute()) {
                        throw new \Exception();
                    }
                }
            } else {
                $result = Yii::$app->db->createCommand()->delete('remote_keyset',['remote_type_id'=>$id,'key'=>$key])->execute();
                if (!empty($analog) && $result) {
                    if (!Yii::$app->db->createCommand()->delete('remote_analog', ['and', ['=', 'remote_type_id', $id], ['in', 'analog', $analog]])->execute()) {
                        throw new \Exception();
                    }
                }
            }
            //生成json文件
            if((new Keymap_json)->rc_json_data()){
                $tr->commit();
                insert_db_log($checked?'insert':'delete', ($checked?'添加':'删除')."遥控器配置按键:id-{$id} key-{$key} analog-".json_encode($analog));
                show_json(0,'write data ok');
            }

            show_json(100000, 'no write data');
        } catch (\Exception $e) {
            var_dump($e->getMessage());die;
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /*
     * 删除遥控器（软删除）
     */
    protected function remoteTypeDelete()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $id = trim(Yii::$app->request->post('id'));
            if (RemoteType::updateAll(['is_deleted'=>1], ['id'=>$id])) {
//                var_db_log($this->userid, 'update', 'remote_type', ['id'=>$id, 'status'=>$deleted]);
                $tr->commit();
                insert_db_log('update', "软删除遥控器 id-{$id}");
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /**
     * 彻底删除遥控器
     */
    protected function remoteTypeDeleteTrue()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $id = trim(Yii::$app->request->post('id'));
            $info = RemoteType::findOne($id);
            if (!$info) {
                show_json(100000, $this->getErrMessage(100000));
            }

            if ($info['is_deleted'] != 1) {
                show_json(100000, '删除之后才能彻底删除');
            }

            //已经有批次的厂商不能删除
            $man = new Manufacture();
            if ($man->checkManufactureExistsSn($info['id'], 2)) {
                show_json(100000, '该遥控器关联了批次,不能删除!');
            }
            if ($info->delete()) {
                //删除按键
                models\RemoteKeyset::deleteAll(['remote_type_id'=>$info['id']]);
                //删除device适配遥控器
                models\DeviceTypeRemoteType::deleteAll(['remote_type_id'=>$info['id']]);
                //删除remote_analog
                Yii::$app->db->createCommand()->delete('remote_analog', 'remote_type_id=:id', [':id' => $info['id']])->execute();
                $tr->commit();
                insert_db_log('update', "删除遥控器 id-{$id}");
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception('');
        } catch (\Exception $e) {
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }

    }

    /*
     * 获取所有的按键
     */
    protected function selectKeySetAll()
    {
        return Manufacture::getKeySetAll() ? : array();
    }

    /*
     * 获取遥控器的按键
     */
    protected function selectKeysetById($id)
    {
        $mode = new models\RemoteKeyset();
        return $mode->getRemoteKeysetAll($id);
    }

    protected function selectKeycodeAll()
    {
        return Manufacture::getKeycodeAll();
    }

    /*
     * 验证REMOTE TYPE
     * @return array
     */
    protected function remoteTypeValidate()
    {
        $data['type'] = filterData(Yii::$app->request->post('type'), 'string', 128, 2);   //转义type，并验证长度

//         $data['type_en'] = filterData(trim($data['type_en']), 'string', 128, 4);   //转义type_en，并验证长度

        $data['name'] = filterData(Yii::$app->request->post('name'), 'string', 128, 2);   //转义name，并验证长度

        $data['name_en'] = filterData(Yii::$app->request->post('name_en'), 'string', 128, 2);   //转义name_en，并验证长度

        $data['description'] = filterData(Yii::$app->request->post('description'), 'string', 4*1024, 0);   //转义name_en，并验证长度

        //验证key
        $data['key'] = trim(Yii::$app->request->post('key'));
        if (!preg_match("/^[a-zA-Z0-9]{4}$/", $data['key'])) {
            show_json(102000, $this->getErrMessage(102000));
        }

        //验证code
        $data['code'] = trim(Yii::$app->request->post('code'));
        if (!preg_match("/^[a-zA-Z0-9]{4}$/", $data['code'])) {
            show_json(102000, $this->getErrMessage(102000));
        }
        //验证screen
        $data['screen'] = trim(Yii::$app->request->post('screen'));
        if (!isset($data['screen']) || !isset(Yii::$app->params['screen'][$data['screen']])) {
            show_json(102000, $this->getErrMessage(102000));
        }
        //screen_code
        $data['screen_code'] = Yii::$app->params['screen'][$data['screen']];

        //验证carry_type
        $data['carry_type'] = trim(Yii::$app->request->post('carry_type'));
        if (!isset($data['carry_type']) || !isset(Yii::$app->params['carry_type'][$data['carry_type']])) {
            show_json(102000, $this->getErrMessage(102000));
        }
        //carry_type_code
        $data['carry_type_code'] = Yii::$app->params['carry_type'][$data['carry_type']];

        //验证kemanufacture(必须是32位)
        $data['manufacture_id'] = trim( Yii::$app->request->post('manufacture_id') );
        if (!preg_match( "/^[a-zA-Z0-9]{1,32}$/u", $data['manufacture_id'])) {
            show_json(102000, $this->getErrMessage(102000));
        }

        return $data;

    }
    /*
     * 验证REMOTE TYPE
     * @return array
     */
    protected function keysetvalidate()
    {
        $group = $this->selectKeySetAll();
        //PRESS_BUTTON
        $data['KEY'] = Yii::$app->request->post('KEY');

        if (isset($data['KEY'])) {
            $key = array_intersect($data['KEY'], $group['KEY']);

            if ($key != $data['KEY']) {
                show_json(102000, $this->getErrMessage(102000));
            }
        } else {
            $data['KEY'] = array();
        }

        //GYRO
        $data['GYRO'] = Yii::$app->request->post('GYRO');
        if (isset($data['GYRO'])) {
            $gyro = array_intersect($data['GYRO'], $group['GYRO']);
            if ($data['GYRO'] != $gyro) {
                show_json(102000, $this->getErrMessage(102000));
            }
        } else {
            $data['GYRO'] = array();
        }

        //JOYSTICK
        $data['JOYSTICK'] = Yii::$app->request->post('JOYSTICK');
        if (isset($data['JOYSTICK'])) {
            $joystick = array_intersect($data['JOYSTICK'], $group['JOYSTICK']);
            if ($data['JOYSTICK'] != $joystick) {
                show_json(102000, $this->getErrMessage(102000));
            }
        } else {
            $data['JOYSTICK'] = array();
        }

        //KEY_JOYSTICK
        $data['KEY_JOYSTICK'] = Yii::$app->request->post('KEY_JOYSTICK');
        if (isset($data['KEY_JOYSTICK'])) {
            $joystick = array_intersect($data['KEY_JOYSTICK'], $group['KEY_JOYSTICK']);
            if ($data['KEY_JOYSTICK'] != $joystick) {
                show_json(102000, $this->getErrMessage(102000));
            }
        } else {
            $data['KEY_JOYSTICK'] = array();
        }

        //WHEEL
        $data['WHEEL'] = Yii::$app->request->post('WHEEL');
        if (isset($data['WHEEL'])) {
            $wheel = array_intersect($data['WHEEL'], $group['WHEEL']);
            if ($data['WHEEL'] != $wheel) {
                show_json(102000, $this->getErrMessage(102000));
            }
        } else {
            $data['WHEEL'] = array();
        }

        //验证id
        $data['id'] = filterData(Yii::$app->request->post('id'),'string',32);

        if (!$this->remoteTypeInfo($data['id'])) {
            show_json(102000, $this->getErrMessage(102000));
        }

        $keycode = $this->selectKeycodeAll();

        $data['keyset'] = array();
        $data['analog'] = array();

        foreach ($data['JOYSTICK'] as $j_val) {
            foreach($keycode as $item) {
                if ($item['parent'] == $j_val && $item['keytype'] == 3) {
                    $data['analog'][] = $item['key'];
                }
            }
            $data['keyset'][] = $j_val;
        }
        foreach ($data['KEY_JOYSTICK'] as $kj_val) {
            foreach ($keycode as $item) {
                if ($item['parent'] == $kj_val && $item['keytype'] == 3) {
                    $data['analog'][] = $item['key'];
                }
            }
            $data['keyset'][] = $kj_val;
        }
        foreach ($data['GYRO'] as $g_val) {
            foreach ($keycode as $item) {
                if ($item['parent'] == $g_val && $item['keytype'] == 3) {
                    $data['analog'][] = $item['key'];
                }
            }
            $data['keyset'][] = $g_val;
        }
        foreach ($data['KEY'] as $k_val) {
            $data['keyset'][] = $k_val;
        }
        foreach ($data['WHEEL'] as $w_val) {
            $data['keyset'][] = $w_val;
        }

        $data['keyset'] = implode(',',$data['keyset']);

        $data['analog'] = implode(',',$data['analog']);

        return $data;
    }

    /**
     * 根据主键和状态查找遥控器
     * @param $id
     * @param int $deleted
     */
    protected function remoteTypeInfo($id, $deleted=0)
    {
        try {
            return RemoteType::find()->where(['id'=>$id])->asArray()->one();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取所有未删除的厂商
     */
    protected function getManufactureAll()
    {
        return Manufacture::getManufactureALl();
    }
}