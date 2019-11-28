<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-17
 * Time: 16:40
 */

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\shop\ARShopEvent;
use common\consequence\Result;
use app\controllers\bus\appBus;
use common\consequence\ErrorCode;
use common\exception\FixedException;
use common\exception\UnavailableParamsException;

/**
 * 网站控制器
 */
class AppController extends BaseController
{
    /**
     * 重载用户登录状态验证方法
     * 如果用户已登录,跳转到redirect_url页或首页
     */
    protected function loginStatusCheck()
    {
        if((new appBus())->loginStatusCheck()){
            if($redirect_url = Yii::$app->request->get('redirect_url')){
                header($redirect_url);
            }else{
                header("location:" . Url::toRoute(Yii::$app->params['app_index']));
            }
            exit;
        }
        return true;
    }

    /**
     * 商户登录页
     */
    public function actionLogin()
    {
        $params = [
            'account' => '',
            'password' => '',
        ];
        if(Yii::$app->request->isPost){//商户登录
            $params = Yii::$app->request->post();
            try {
                $bus = new appBus();
                //商户登录账户密码验证
                $bus->login($params);

                //登录成功直接跳转
                if($redirect_url = Yii::$app->request->get('redirect_url')){
                    return $this->redirect($redirect_url);
                }else{
                    return $this->redirect(Url::toRoute([Yii::$app->params['app_index']]));
                }

            } catch (UnavailableParamsException $e) {
                unset($params['_csrf']);
                $params['code'] = $e->getCode();
                $params['msg'] = $e->getMessage();
            }
        }
        $params['code'] = isset($params['code'])?$params['code']:0;
        $params['msg'] = isset($params['msg'])?$params['msg']:'用户名不能为空';

        return $this->render('login', ['data'=>$params]);
    }

    /**
     * 商户注册页
     */
    public function actionRegister()
    {
        $bus = new appBus();
        //注册商户信息
        if(Yii::$app->request->isAjax){
            $params = json_decode(Yii::$app->request->post()['data'], true);
            $ret = new Result();
            try {
                if(isset($params['time'])){
                    list($params['open_time'], $params['close_time']) = explode("-", $params['time']);
                    $params['open_time'] = trim($params['open_time']);
                    $params['close_time'] = trim($params['close_time']);
                    unset($params['time']);
                }

                //参数校验
                $bus->registerParamsCheck($params);

                //添加商户信息
                $bus->saveShop($params);

            } catch (UnavailableParamsException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }

        return $this->render('register', ['category' => $bus->getCategory()]);
    }

    /**
     * 发送注册手机验证码
     */
    public function actionSendPhoneCode()
    {
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            $phone = Yii::$app->request->post('mobile');
            try {
                $bus = new appBus();

                //限制验证(如 60 秒内只能发送一次等)
                $bus->checkPhoneCode($phone, ARShopEvent::EVENT_LIST['REGISTER']);

                //调用业务类方法发送验证码
                $bus->sendPhoneCode($phone, ARShopEvent::EVENT_LIST['REGISTER']);

            } catch (FixedException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    /**
     * 商户注册成功页
     */
    public function actionRegisterSuccess()
    {
        return $this->render('register-success', []);
    }

    /**
     * 找回密码
     */
    public function actionFindpwd()
    {
        return $this->render('findpwd', []);
    }

    /**
     * 发送找回密码验证码
     */
    public function actionSendFindPwdCode()
    {
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            $phone = Yii::$app->request->post('mobile');
            try {
                $bus = new appBus();

                //限制验证(如 60 秒内只能发送一次等)
                $bus->checkPhoneCode($phone, ARShopEvent::EVENT_LIST['FIND_PASSWORD']);

                //调用业务类方法发送验证码
                $bus->sendPhoneCode($phone, ARShopEvent::EVENT_LIST['FIND_PASSWORD']);

            } catch (FixedException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    /**
     * 找回密码校验
     */
    public function actionFindPwdCheck()
    {
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            $phone = trim(Yii::$app->request->post('mobile'));
            $code = trim(Yii::$app->request->post('code'));
            try {
                $bus = new appBus();

                //验证
                $ret->data['_token'] = $bus->findPwdCheck($phone, $code);

            } catch (UnavailableParamsException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    /**
     * 修改密码
     */
    public function actionDisPwd()
    {
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            $phone = Yii::$app->request->post('mobile');
            $password = Yii::$app->request->post('pwd');
            $rep_password = Yii::$app->request->post('rep_pwd');
            $token = Yii::$app->request->post('_token');

            try {
                $bus = new appBus();

                //修改密码
                $result = $bus->disPwd($phone, $password, $rep_password, $token);
                if(!$result){
                    $ret->code = ErrorCode::ERROR;
                    $ret->msg = '修改密码失败';
                }

            } catch (UnavailableParamsException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

}