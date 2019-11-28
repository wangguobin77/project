<?php

/**
 * 厂商控制器 -- 仅做数据接收和验证，业务逻辑请在business逻辑类中完成
 */
namespace app\controllers;

use app\controllers\bis\manufactureBis;
use common\ErrorCode;
use common\helpers\Exception;
use common\helpers\Utils;
use common\helpers\ValidateHelper;
use common\MvcResult;
use common\Result;
use Yii;
use common\library\UploadImage;
use app\controllers\base\BaseController;

class ManufactureController extends BaseController
{
    public $layout = false;
    public $enableCsrfValidation = false;

    /**
     * 添加厂商
     */
    public function actionAdd()
    {
        $params = Utils::getHttpInput();
        try{
            if (Yii::$app->request->isPost) {
                $ret = new Result();

                $rules = [
                    [['logo', 'name', 'name_en', 'login_name', 'linkman', 'password', 'repeat_password', 'email', 'mobile'], 'required'],
                    [['logo', 'name', 'name_en', 'login_name', 'linkman', 'password', 'repeat_password', 'email', 'mobile'], 'string' ],
                    [['password', 'repeat_password'], 'string', 'max'=>6, 'min'=>20],
                    [['email'], 'email'],
                    [['home_page'], 'url']
                ];
                $ret = ValidateHelper::validate($params, $rules);
                if($ret->code === ErrorCode::SUCCEED){
                    $data = $this->manufactureValidate();   //验证表单数据
                    $bis = new manufactureBis();
                    $ret = $bis->add($data);
                }
            }else{
                $ret = MvcResult::getInstance($this);
                $ret->view = 'add';
            }
        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }

        return $ret;
    }

    /**
     * 厂商列表
     */
    public function actionList()
    {
        $params = Utils::getHttpInput();
        try{
            $ret = MvcResult::getInstance($this);
            $ret->view = 'list';

            $rules = [];
            $ret = ValidateHelper::mvcValidate($params, $rules);
            if($ret->code === ErrorCode::SUCCEED){
                $bis = new manufactureBis();
                $ret = $bis->getList();
            }

        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 厂商编辑页面/编辑
     */
    public function actionEdit()
    {
        $params = Utils::getHttpInput();
        try{
            if (Yii::$app->request->isPost) {
                $ret = new Result();
                $data = $this->manufactureValidate();   //验证表单数据
                $data['id'] = filterData(trim(Yii::$app->request->post('id')), 'string', 32, 32); // 厂商 id

                $bis = new manufactureBis();
                $ret = $bis->edit($data);
            }else{
                $ret = MvcResult::getInstance($this);
                $ret->view = 'edit';
                $rules = [
                    [['id'], 'required'],
                ];
                $ret = ValidateHelper::mvcValidate($params, $rules);

                if($ret->code === ErrorCode::SUCCEED){
                    $bis = new manufactureBis();
                    $ret = $bis->getInfo($params['id']);
                }
            }
        }catch (Exception $e){
            $ret->code = $e->getCode();
            $ret->message = $e->getMessage();
        }
        return $ret;
    }

    /**
     * 验证字段
     * @return mixed
     * @throws Exception
     */
    private function manufactureValidate()
    {
        $data['name'] = filterData(Yii::$app->request->post('name'), 'string', 128, 2); //验证name

        $data['name_en'] = filterData(Yii::$app->request->post('name_en'), 'string', 128, 2);   //验证name_en

        $data['linkman'] = filterData(Yii::$app->request->post('linkman'), 'string', 128, 1);   //验证linkman=

        $data['address'] = filterData(Yii::$app->request->post('address'), 'string', 128, 0);   //验证address

        $data['common'] = filterData(Yii::$app->request->post('description'), 'string', 64*1024, 0);   //验证description

        $data['password'] = trim(Yii::$app->request->post('password'));
        if ($data['password'] !== trim(Yii::$app->request->post('repeat_password'))) {
            throw new Exception("两次输入密码不一致", ErrorCode::TWO_INCONSISTENT_PASSWORDS);
        }

        if (strlen($data['password']) > 20 || strlen($data['password']) < 6) {
            throw new Exception("密码长度必须在6-20之间", ErrorCode::BAD_PARAMS);
        }

        //登录名验证
        $data['login_name'] = trim( Yii::$app->request->post('login_name') );    //验证login_name
        if (!preg_match("/^[a-zA-Z0-9_\-\.]{4,32}$/u", $data['login_name'])) {
            throw new Exception("登录名只能使用a-zA-Z0-9_-.的组合", ErrorCode::BAD_PARAMS);
        }

        //邮箱验证
        $data['email'] = trim(Yii::$app->request->post('email'));
        if (!preg_match(Yii::$app->params['email_preg_match_pattern'], $data['email'])) {
            throw new Exception("邮箱错误", ErrorCode::BAD_PARAMS);
        }

        //验证联系人电话
        $data['contact_info'] = filterData(trim(Yii::$app->request->post('contact_info')), 'string', 128, 0);

        //验证手机
        $data['mobile'] = trim(Yii::$app->request->post('mobile'));
        if (!preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/u", $data['mobile']) && $data['mobile']) {
            throw new Exception("手机格式不正确", ErrorCode::BAD_PARAMS);
        }

        //验证网址
        $regex = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|([\s()<>]+|(\([\s()<>]+))*\))+(?:([\s()<>]+|(\([\s()<>]+))*\)|[^\s`!(){};:\'".,<>???“”‘’]))@';
        $data['home_page'] = trim(Yii::$app->request->post('home_page'));
        if (!preg_match($regex,$data['home_page']) && $data['home_page']) {
            throw new Exception("网址错误", ErrorCode::BAD_PARAMS);
        }

        $data['logo'] = trim(Yii::$app->request->post('logo'));    //logo

        return $data;

    }


    /**
     * 删除厂商（软删除）
     */
    public function actionDelete()
    {
        $params = Utils::getHttpInput();
        $ret = new Result();
        if (Yii::$app->request->isPost) {
            try{
                $rules = [[['id'], 'string', 'length'=> 32]];
                $ret = ValidateHelper::validate($params, $rules);
                if($ret->code === ErrorCode::SUCCEED){
                    $bis = new manufactureBis();
                    $ret = $bis->delete($params['id']);
                }

            }catch (Exception $e){
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
        }
        return $ret;
    }

    /**
     * 删除厂商（彻底删除）
     */
    public function actionDelete_true()
    {
        $params = Utils::getHttpInput();
        $ret = new Result();
        if (Yii::$app->request->isPost) {
            try{
                $rules = [[['id'], 'string', 'length'=> 32]];
                $ret = ValidateHelper::validate($params, $rules);
                if($ret->code === ErrorCode::SUCCEED){
                    $bis = new manufactureBis();
                    $ret = $bis->deleteTrue($params['id']);
                }

            }catch (Exception $e){
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
        }
        return $ret;
    }

    /**
     * 厂商上传logo
     */
    public function actionLogo()
    {
        if (Yii::$app->request->isPost) {
            $img = trim(Yii::$app->request->post('file'));
            if (!$img) {
                show_json(100000, '请先选择要上传的图片.');
            }
            $base64 = str_replace('data:image/jpeg;base64,', '' ,$img);
            $base64 = str_replace('=', '',$base64);
            $img_len = strlen($base64);
            $file_size = $img_len - ($img_len/8)*2;
            $file_size = number_format(($file_size/1024),2);
            if ($file_size > (1024*1024*2)) {
                show_json(100000, '图片过大.');
            }

            $up = new UploadImage;

            $result = $up->upload($img,'/web/uploads/logo');
            //上传失败
            if (!isset($result['status']) || $result['status'] != 1) {
                $msg = isset($result['info']) ? $result['info'] : '上传失败.';
                show_json(100000, $msg);
            }

            //只是上传图片不需要写入数据库
            show_json(0, '/manufacture'.$result['http_url']);
        }
    }

}





