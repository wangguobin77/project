<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/18
 * Time: 下午7:44
 */

namespace backend\controllers\api;

use yii;
use yii\web\Controller;
use common\library\SmsService;
use backend\models\AdminUser;

class FindpwdController extends Controller
{
    /**
     * SDk
     * 重置密码
     * @return string
     * 重置密码
     */
    public function actionReset_phone(){

        $SmsService = new SmsService();

        if(Yii::$app->request->post()){

            $mobile = Yii::$app->request->post('mobile');//手机号码

            //验证表单
            if(checkmobile($mobile)){//验证手机号码

                $userInfo = AdminUser::find()->where(['username'=>$mobile])->asArray()->one();//获取符合条件的所有值

                if(!$userInfo){
                    show_json(100000,'User info not exist');//用户信息不存在
                }

                $SmsService->isLimitIp(); //限制ip 短时间内 访问次数 判断是否合法

                if($SmsService->sendAuthCodeToQueue2($mobile)){//推送手机验证码
                    // if(true){//推送手机验证码
                    show_json(0,'success',['mobile'=>$mobile]);
                }

            }

        }else{
            return $this->render('/reset_mobile_pwd');
        }

    }

    /**
     * 重置密码
     * 重置密码流程第二部 验证手机验证码 通过则跳转设置密码页面
     */
    public function actionReset_phone_2()
    {

        if(Yii::$app->request->post()){
            $mobile = Yii::$app->request->post('mobile');//手机号码

            $authCode = Yii::$app->request->post('authCode');//获取手机验证码

            if(checkmobile($mobile)) {//验证手机号码
                //if(true) {//验证手机号码

                $SmsService = new SmsService;

                $SmsService->disUsermobileAuthcode($mobile,$authCode);//处理手机验证码验证

                show_json(0,'success',['mobile'=>$mobile]);
            }

        }else{
            show_json(100014,Yii::$app->params['errorCode'][100014]);
        }

    }

    /**
     * SDK
     * 重置密码流程第三部 验证两次密码的合法性
     */
    public function actionReset_phone_3()
    {

        if(Yii::$app->request->isPost){
            /*判断两次密码是否相同*/
            if(checkLengthParams(Yii::$app->request->post('pwd'),20,6) !== checkLengthParams(Yii::$app->request->post('repeatpwd'),20,6)){
                show_json(100025, Yii::$app->params['errorCode'][100025]);
            }

            $usermobile = Yii::$app->request->post('usermobile');//手机号码
            $pwd = Yii::$app->request->post('pwd');//手机号码


            if(checkmobile($usermobile)) {//验证手机号码
                //更改用户密码
                $model = AdminUser::findOne($_SESSION['info']['uid']);

                $model->password_hash = $model->setPassword($pwd);//加密后的密码

                if ($model->save()) {
                    show_json(0, 'reset password success');
                }
            }


        }else{
            show_json(100014,Yii::$app->params['errorCode'][100014]);
        }


    }
}