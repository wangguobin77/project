<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-01
 * Time: 09:49
 */

namespace app\controllers;

use common\consequence\Result;
use common\exception\FixedException;
use Yii;
use common\helpers\UtilsHelper;
use app\controllers\bus\couponBus;

/**
 * 商户优惠券管理控制器
 * Class CouponController
 * @package app\controllers
 */
class CouponController extends BaseController
{
    public function actionList()
    {
        $couponBus = new couponBus();

        $params = UtilsHelper::getHttpInput();

        list($datas, $pages) = $couponBus->getList($this->shopId, $params);

        return $this->render('list', [
            'params' => $params,
            'datas' => $datas,
            'pages' => $pages,
        ]);
    }

    public function actionDetail(){
        $params = UtilsHelper::getHttpInput();
        $couponBus = new couponBus();
        $data = $couponBus->getCouponById($this->shopId, $params['id']);
        return $this->render('detail', ['data'=>$data]);
    }

    /**
     * 优惠券 创建|修改
     * @return string
     */
    public function actionEdit(){

        $params = UtilsHelper::getHttpInput();
        $couponBus = new couponBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                if($params['id']){
                    $couponBus->updateCoupon($this->shopId, $params);
                }else{
                    $ret->data = $couponBus->createCoupon($this->shopId, [$params]);
                }
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        $data = [];
        if(isset($params['id'])){
            $data = $couponBus->getCouponById($this->shopId, $params['id']);
        }


        return $this->render('edit', ['data'=>$data]);
    }

    /**
     * 优惠券删除
     */
    public function actionDel()
    {
        $params = UtilsHelper::getHttpInput();
        $couponBus = new couponBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                $couponBus->del($params['id']);
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    /**
     * 优惠券提交审核
     */
    public function actionCheck_pass()
    {
        $params = UtilsHelper::getHttpInput();
        $couponBus = new couponBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                $couponBus->check_pass($params['id']);
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    public function actionStatus_rm()
    {
        $params = UtilsHelper::getHttpInput();
        $couponBus = new couponBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                $couponBus->status_rm($params['id']);
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

}