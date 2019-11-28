<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-23
 * Time: 11:50
 */

namespace app\controllers;

use common\consequence\ErrorCode;
use Yii;
use common\consequence\Result;
use app\controllers\bus\appBus;
use app\controllers\bus\shopBus;
use app\controllers\traits\appTrait;
use common\exception\UnavailableParamsException;

class ShopController extends BaseController
{
    use appTrait;

    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    //todo 临时,上线前删除获取用户列表
    public function actionUserList()
    {
        $ret = new Result();
        $appBus = new appBus();
        $ret->data = $appBus->getUserList();
        return $ret;
    }

    public function actionSetUserToken()
    {
        $userid = trim(Yii::$app->request->post('id'));
        $token = trim(Yii::$app->request->post('token'));
        $ret = new Result();
        $appBus = new appBus();
        $ret->data = $appBus->setUserToken($userid,$token);
        return $ret;
    }

    /**
     * 商户登出(登出是用户已登录状态才会有登出,不能放入 AppController,会造成循环跳转)
     */
    public function actionLogout()
    {
        $this->delSessionId();
        $this->redirect(['/app/login']);
    }

    /**
     * 商户设置
     */
    public function actionSetMerchants()
    {
        $appBus = new appBus();
        $shopBus = new shopBus();

        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try {
                $params = Yii::$app->request->post();
                if(isset($params['time'])){
                    list($params['open_time'], $params['close_time']) = explode("-", $params['time']);
                    $params['open_time'] = trim($params['open_time']);
                    $params['close_time'] = trim($params['close_time']);
                    unset($params['time']);
                }

                //信息校验
                $shopBus->shopEditCheck($params);

                //修改商户信息
                $shopBus->updateShop($this->loginUserInfo['id'], $params);

            } catch (UnavailableParamsException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }

        //商户类别
        $category = $appBus->getCategory();
        $shopInfo = $shopBus->getShopById($this->loginUserInfo['id']);

        return $this->render('set-merchants', [
            'category' => $category,
            'shopInfo' => $shopInfo,
            ]);
    }

    /**
     * 修改密码
     */
    public function actionUpPassword()
    {
        if(Yii::$app->request->isAjax) {
            $ret = new Result();
            try {
                $params = Yii::$app->request->post();

                $appBus = new appBus();

                //复用登录验证登录密码,去 account
                $shop = $appBus->checkLogin($this->loginUserInfo['phone'] ,$params['old-pwd']);

                if(!empty($shop)){
                    $res = $appBus->disPwd($this->loginUserInfo['phone'], $params['new-pwd'], $params['renew-pwd'], null, 2);
                    if(!$res){
                        $ret->code = ErrorCode::ERROR;
                        $ret->msg = '修改失败';
                    }
                }else{
                    $ret->code = ErrorCode::CORRECT_PASSWORD;
                    $ret->msg = '密码错误';
                }

            } catch (UnavailableParamsException $e) {
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;

        }

        return $this->render('up-password');
    }
}