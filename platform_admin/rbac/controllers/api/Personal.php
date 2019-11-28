<?php
/**
 * 移动端个人中心
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/18
 * Time: 下午5:50
 */

namespace backend\controllers\api;

use yii;
use  backend\controllers\base\BaseCoreController;
use backend\models\AdminUserProfile;
use backend\models\AdminUser;
class Personal extends  BaseCoreController
{
    /**
     * 用户登陆的情况下 可以重新设置密码
     */
    public function actionReset_pwd()
    {
        $newpwd = filterData(Yii::$app->request->post('newpwd'), 'string', 20, 6);//新密码密码

        $repeat_pwd = filterData(Yii::$app->request->post('repeat_pwd'), 'string', 20, 6);//重复确认密码

        if ($newpwd != $repeat_pwd) {
            show_json(100000, 'The passwords do not match');//两次输入的密码不一致
        }

        if ($this->isValidationPwd()) {
            $model = AdminUser::findOne($_SESSION['info']['uid']);

            $model->password_hash = $model->setPassword($newpwd);//加密后的密码

            if ($model->save()) {
                show_json(0, 'change password success');
            }

            show_json(100000, 'change password error');
        }

    }

    /**
     * 验证当前密码是否与原始密码一致 不能一致
     * @param $pwd
     * @return bool
     */
    protected function isValidationPwd($pwd)
    {
        $userProfileInfo = AdminUser::find()->where(['uid' => $_SESSION['info']['uid']])->one();

        if ($userProfileInfo['password_hash'] == $pwd) {//验证重置的密码不能与原始密码一致
            show_json(100000, 'The current modified password cannot be consistent with the original password');//当前修改密码不能与原始密码一致
        }

        return true;
    }

    /**
     * 修改手机api
     * @return string
     */
    public function actionChange_mobile()
    {
        if(Yii::$app->request->isPost){
            $mobile = Yii::$app->request->post('mobile');

            checkmobile($mobile);//验证手机格式是否正确


            $user_list = AdminUser::find()->where(['username'=>$mobile])->all();//获取符合条件的所有值

            if($user_list){
                show_json(100000,'The mobile phone has been used, please change it');//根改的手机号码已经被使用，请换一个
            }


            $this->updateMobile($mobile);//修改手机号码

        }else{
            return $this->render('change_mobile');
        }
    }

    /**
     * 根据uid 修改用户手机号码
     */
    protected function updateMobile($mobile)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try{
            $model = AdminUser::findOne($_SESSION['info']['uid']);

            $model->username = $mobile;

            $model1 = AdminUserProfile::findOne($_SESSION['info']['uid']);

            $model1->mobile = $mobile;

            $model->save();

            $model1->save();

            $transaction->commit();

            show_json(0,'The phone number was modified success');

        }catch (\Exception $e){

            $transaction->rollBack();

            show_json(100000,'The phone number was modified error');
        }

    }

}