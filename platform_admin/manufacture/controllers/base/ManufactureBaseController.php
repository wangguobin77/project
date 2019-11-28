<?php
namespace app\controllers\base;

use Yii;
use common\library;
use yii\data\Pagination;
use app\models\Manufacture;
class ManufactureBaseController extends BaseController
{
    /*
     * @ 添加厂商
     */
    protected function manufactureAdd($data)
    {
        if (strlen($data['password']) > 20 || strlen($data['password']) < 6) {
            show_json(102000, $this->getErrMessage(102000));
        }
        $data['id'] = createGuid(32);
        //防止添加的时候覆盖之前的记录(guid有可能会重复)
        if (Manufacture::findOne($data['id'])) {
            show_json(100000, $this->getErrMessage(100000));
        }
        $data['salt'] = uuid(4);//随机四位字符
        $data['password'] = encryptedPwd(trim($data['password']),$data['salt']);
        $model = new Manufacture();
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(['Manufacture'=>$data]) && $model->save()) {
                $tr->commit();
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
     * 修改厂商
     */
    protected function manufactureEdit($data)
    {
        $model = Manufacture::findOne(trim(Yii::$app->request->post('id')));

        if ($data['password']) {
            if (strlen($data['password']) > 20 || strlen($data['password']) < 6) {
                show_json(102000, Yii::$app->params['errorCode'][102000]);
            }
            $data['salt'] = uuid(4);//随机四位字符
            $data['password'] = encryptedPwd(trim($data['password']),$data['salt']);
            $model->salt = $data['salt'];
            $model->password = $data['password'];
        }
        $model->name = $data['name'];
        $model->name_en = $data['name_en'];
        $model->login_name = $data['login_name'];
        $model->email = $data['email'];
        $model->mobile = $data['mobile'];
        $model->logo = $data['logo'];
        $model->address = $data['address'];
        $model->contact_info = $data['contact_info'];
        $model->home_page = $data['home_page'];
        $model->linkman = $data['linkman'];
        $model->common = $data['common'];

        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
//                var_db_log($this->userid, 'update', 'manufacture', $data);
                $tr->commit();
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
     *  删除厂商
     */
    protected function manufactureDelete()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $id = trim(Yii::$app->request->post('id'));

            if (Manufacture::updateAll(['is_deleted'=>1], ['id'=>$id])) {
//                var_db_log($this->userid, 'update', 'manufacture', ['id'=>$id, 'status'=>$deleted]);
                $tr->commit();
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception();
        } catch (\Exception $e) {
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }

    }

    /**
     * 彻底删除厂商
     */
    protected function manufactureDeleteTrue()
    {
        $tr = Yii::$app->db->beginTransaction();
        try {
            $info = Manufacture::findOne(trim(Yii::$app->request->post('id')));
            if (!$info) {
                show_json(100000, $this->getErrMessage(100000));
            }
            if ($info['is_deleted'] != 1) {
                show_json(100000, '删除之后才能彻底删除');
            }
            if (\app\models\RemoteType::find()->where(['manufacture_id'=>$info['id']])->exists()) {
                show_json(100000, '厂商下面存在遥控器，不能删除');
            }
            if (\app\models\DeviceType::find()->where(['manufacture_id'=>$info['id']])->exists()) {
                show_json(100000, '厂商下面存在device，不能删除');
            }

            //已经有批次的厂商不能删除
            $man = new library\Manufacture();
            if ($man->checkManufactureExistsSn($info['id'])) {
                show_json(100000, 'The manufacture has already batch,can`t be deleted.');
            }
            if ($info->delete()) {
//                //删除遥控器相关
//                $sql = 'delete a,b,c,d from remote_type a,remote_keyset b,remote_analog c,device_type_remote_type d where a.id = b.remote_type_id and a.id = c.remote_type_id and a.id=d.remote_type_id and a.manufacture_id=\''.$info['id'].'\'';
//                Yii::$app->db->createCommand($sql)->execute();
//                //删除device相关
//                $sql = 'delete a,b from device_type a,device_type_remote_type b where a.id = b.device_type_id and a.manufacture_id=\''.$info['id'].'\'';
//                Yii::$app->db->createCommand($sql)->execute();
                $tr->commit();
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $tr->rollback();
            show_json(100000, $this->getErrMessage(100000));
        }
    }
    /*
     *  验证字段
     * @return array
     */
    protected function manufactureValidate()
    {
        $data['name'] = filterData(Yii::$app->request->post('name'), 'string', 128, 2); //验证name

        $data['name_en'] = filterData(Yii::$app->request->post('name_en'), 'string', 128, 2);   //验证name_en

        $data['linkman'] = filterData(Yii::$app->request->post('linkman'), 'string', 128, 1);   //验证linkman=

        $data['address'] = filterData(Yii::$app->request->post('address'), 'string', 128, 0);   //验证address

        $data['common'] = filterData(Yii::$app->request->post('description'), 'string', 64*1024, 0);   //验证description

        $data['password'] = trim(Yii::$app->request->post('password'));
        if ($data['password'] !== trim(Yii::$app->request->post('repeat_password'))) {
            show_json(102000, Yii::$app->params['errorCode'][102000]);
        }
        //登录名验证
        $data['login_name'] = trim( Yii::$app->request->post('login_name') );    //验证login_name
        if (!preg_match("/^[a-zA-Z0-9_\-\.]{4,32}$/u", $data['login_name'])) {
            show_json(102000, Yii::$app->params['errorCode'][102000]);
        }

        //邮箱验证
        $data['email'] = trim(Yii::$app->request->post('email'));
        if (!preg_match(Yii::$app->params['email_preg_match_pattern'], $data['email'])) {
            show_json(102000,Yii::$app->params['errorCode'][102000]);
        }

        //验证联系人电话
        $data['contact_info'] = filterData(trim(Yii::$app->request->post('contact_info')), 'string', 128, 0);

        //验证手机
        $data['mobile'] = trim(Yii::$app->request->post('mobile'));
        if (!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/u", $data['mobile']) && $data['mobile']) {
            show_json(102000, Yii::$app->params['errorCode'][102000]);
        }

        //验证网址
        $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|([\s()<>]+|(\([\s()<>]+))*\))+(?:([\s()<>]+|(\([\s()<>]+))*\)|[^\s`!(){};:\'".,<>???“”‘’]))@';
        $data['home_page'] = trim(Yii::$app->request->post('home_page'));
        if (!preg_match($regex,$data['home_page']) && $data['home_page']) {
            show_json(102000, Yii::$app->params['errorCode'][102000]);
        }

        $data['logo'] = trim(Yii::$app->request->post('logo'));    //logo

        return $data;

    }

    /**
     * @param $field
     * @param $value
     */
    protected function selectByPrimaryKey($id, $delete=0)
    {
        try {
//            return Manufacture::find()->where(['id'=>$id, 'is_deleted'=>$delete])->asArray()->one();
            return Manufacture::find()->where(['id'=>$id])->asArray()->one();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 厂商列表
     * @return array
     */
    protected function manufactureList()
    {
        $keywords = trim(Yii::$app->request->get('keywords'));
        $status = trim(Yii::$app->request->get('status'));
//        $where = ['and',['=','is_deleted',0]];
//        if ($keywords != '') {
//            $where[] = ['or',['like','name',$keywords],['like','name_en',$keywords]];
//        }
//        if ($status != '') {
//            $where[] = ['=','status',$status];
//        }
//        $query = Manufacture::find()->select('id,name,linkman,mobile,status')->where($where);
        $query = Manufacture::find()->select('id,name,linkman,mobile,status,is_deleted');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('add_time desc')
            ->asArray()
            ->all();
        return [
            'data'  =>  $models,
            'pages' =>  $pages,
            'keywords'    =>  $keywords,
            'status'    =>  $status,
        ];
    }
}