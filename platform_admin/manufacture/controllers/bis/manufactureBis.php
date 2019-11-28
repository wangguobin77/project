<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-17
 * Time: 10:09
 */

/**
 * 厂商逻辑类
 */

namespace app\controllers\bis;

use common\ErrorCode;
use common\helpers\Exception;
use Yii;
use app\models\Manufacture;
use common\MvcResult;
use common\Result;
use yii\data\Pagination;

class manufactureBis
{
    /**
     * 添加厂商
     * @param $data
     * @throws Exception
     */
    public function add($data){
        $ret = new Result();
        //name,name_en,login_name,email,mobile 不能重复
        $tmp_info = Manufacture::find()
            ->select('name,name_en,login_name,email,mobile')
            ->where(['name'=>$data['name']])
            ->orWhere(['name_en'=>$data['name_en']])
            ->orWhere(['login_name'=>$data['login_name']])
            ->orWhere(['email'=>$data['email']])
            ->orWhere(['mobile'=>$data['mobile']])
            ->asArray()->One();
        if (!empty($tmp_info)) {
            if($tmp_info['name'] == $data['name']){
                throw new Exception('该中文名称已被使用', ErrorCode::ERROR);
            }
            if($tmp_info['name_en'] == $data['name_en']){
                throw new Exception('该英文名称已被使用', ErrorCode::ERROR);
            }
            if($tmp_info['login_name'] == $data['login_name']){
                throw new Exception('该登录名称已被使用', ErrorCode::ERROR);
            }
            if($tmp_info['email'] == $data['email']){
                throw new Exception('该邮箱已被使用', ErrorCode::ERROR);
            }
            if($tmp_info['mobile'] == $data['mobile']){
                throw new Exception('该手机号已被使用', ErrorCode::ERROR);
            }
        }

        $data['id'] = createGuid(32);
        //防止添加的时候覆盖之前的记录(guid有可能会重复)
        if (Manufacture::findOne($data['id'])) {
            throw new Exception('添加失败，请重新尝试', ErrorCode::ERROR);
        }
        $data['salt'] = uuid(4);//随机四位字符
        $data['password'] = encryptedPwd(trim($data['password']),$data['salt']);
        $model = new Manufacture();
        try {
            $model->load(['Manufacture'=>$data]);
            if(!$model->save()){
                throw new Exception('添加失败，请重新尝试', ErrorCode::ERROR);
            }
        } catch (\yii\db\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        //记录日志
        insert_db_log('insert', '添加厂商');

        return $ret;
    }

    /**
     * 获取厂商列表
     */
    public function getList(){
        $ret = MvcResult::getInstance();

        $manufacture = new Manufacture();
        $ret->pages = new Pagination(['defaultPageSize'=>10, 'validatePage'=>false]);
        $data = $manufacture->getList($ret->pages->offset, $ret->pages->limit);
        $ret->pages->totalCount = $data['totalCount'];
        $ret->data = $data['data'];

        return $ret;
    }


    public function getInfo($mid){
        $ret = MvcResult::getInstance();

        $manufacture = new Manufacture();
        $ret->info = $manufacture->getInfo($mid);

        return $ret;
    }

    /**
     * 修改厂商
     * @param $data
     * @return Result
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function edit($data){
        $ret = new Result();
        $model = Manufacture::findOne($data['id']);

        if ($data['password']) {
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

        try {
            $model->save();
        } catch (\yii\db\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        insert_db_log('update', "修改厂商:{$data['id']}");

        return $ret;
    }

    /**
     * 软删除厂商
     * @param $id
     * @return Result
     * @throws Exception
     */
    public function delete($id){
        $ret = new Result();
        try{
            Manufacture::updateAll(['is_deleted'=>1], ['id'=>$id]);
        }catch (\yii\db\Exception $e){
            throw new Exception($e->getMessage(), $e->getCode());
        }
        insert_db_log('update', "软删除厂商{$id}");
        return $ret;
    }

    /**
     * 彻底删除厂商
     * @param $id
     * @return Result
     * @throws Exception
     */
    public function deleteTrue($id){
        $ret = new Result();
        try{
            $info = Manufacture::findOne($id);
            if (!$info) {
                throw new Exception('厂商不存在', ErrorCode::NON_EXISTENT_INFORMATION);
            }
            if ($info['is_deleted'] != 1) {
                throw new Exception('删除之后才能彻底删除', ErrorCode::ERROR);
            }
            if (\app\models\RemoteType::find()->where(['manufacture_id'=>$id])->exists()) {
                throw new Exception('厂商下面存在遥控器，不能删除', ErrorCode::ERROR);
            }
            if (\app\models\DeviceType::find()->where(['manufacture_id'=>$id])->exists()) {
                throw new Exception('厂商下面存在device，不能删除', ErrorCode::ERROR);
            }

            //已经有批次的厂商不能删除
            $man = new \common\library\Manufacture();
            if ($man->checkManufactureExistsSn($id)) {
                throw new Exception('厂商下面存在存在批次，不能删除', ErrorCode::ERROR);
            }
            //执行删除
            $info->delete();

        }catch (\yii\db\Exception $e){
            throw new Exception($e->getMessage(), $e->getCode());
        }

        insert_db_log('delete', "删除厂商{$id}");
        return $ret;
    }
}