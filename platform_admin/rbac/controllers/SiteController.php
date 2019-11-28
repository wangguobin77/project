<?php
namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use common\library\SmsService;
use app\models\AdminUser;
use yii\web\Controller;
use yii\helpers\Url;
/**
 * Class SiteController
 * @package controllers
 */
class SiteController extends Controller
{

    public $layout = 'public_login';

    public function init()
    {
        $this->layout = false;

    }    /**
 * @inheritdoc
 */
    public function behaviors()
    {
        $behaviors=  [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
        return array_merge( parent::behaviors(),$behaviors);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Login action.
     *
     * @return string
     */
    /*public function actionLogin()*/
    public function Login()
    {
        if (!Yii::$app->user->isGuest) {
            //new 新增
            $_SESSION['user'] = [
                'uid' => Yii::$app->user->identity->id,//用户id
                'username' => Yii::$app->user->identity->username,//用户登陆用户名
            ];
            //$url =  Url::toRoute(['index/index']);

            // header("location:".$url);exit;
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()){
            //new 新增
            $_SESSION['user'] = [
                'uid' => Yii::$app->user->identity->id,//用户id
                'username' => Yii::$app->user->identity->username,//用户登陆用户名
            ];
            return $this->goBack();
        }else{
            return $this->render('login',['model'=>$model]);
        }

    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        //Yii::$app->user->logout();
        unset($_SESSION);//释放session的缓存数据
        unset($_COOKIE);
        $url = Url::toRoute('site/login');
        header("localtion:$url");
    }

    /***************************处理运功找回密码**********************************/
    /**
     * 获取手机验证码
     */
    public function actionGetCode()
    {
        $mobile = Yii::$app->request->post('mobile');

        $SmsService = new SmsService;

        if(checkmobile($mobile)){//验证手机
            $SmsService->isLimitIp(); //限制ip 短时间内 访问次数 判断是否合法

            if($SmsService->sendAuthCodeToQueue2($mobile)){//推送手机验证码
                show_json(0,'success');
            }
        }

        show_json(100000,'Get validation code interface error');//获取验证吗接口错误
    }

    /**
     * SDK
     * 手机注册流程第二部 验证手机验证码 通过则跳转设置密码页面
     */
    public function actionValidationCode()
    {

        if(Yii::$app->request->post()){
            $usermobile = Yii::$app->request->post('mobile');//手机号码

            $authCode = Yii::$app->request->post('authCode');//获取手机验证码

            if(checkmobile($usermobile)) {//验证手机号码

                $SmsService = new SmsService;

                $SmsService->disUsermobileAuthcode($usermobile,$authCode);//处理手机验证码验证

                show_json(0,'success',['usermobile'=>$usermobile]);
            }

        }else{
            show_json(100000,'The parameter format is incorrect');
        }

    }

    /**
     * 输入密码 并确认密码
     * 找回密码第二部
     */
    public function actionDisPwd()
    {

        if(Yii::$app->request->isPost){
            /*判断两次密码是否相同*/
            if(checkLengthParams(Yii::$app->request->post('pwd'),20,6) !== checkLengthParams(Yii::$app->request->post('repeatpwd'),20,6)){
                show_json(100000, 'The passwords do not match');//两次密码不一致
            }

            $mobile = Yii::$app->request->post('mobile');//手机号码
            $pwd = Yii::$app->request->post('pwd');//密码


            if(checkmobile($mobile)) {//验证手机号码
                $userInfo = AdminUser::find()->where(['username'=>$mobile])->one();//获取用户详细信息

                $AdminUser = AdminUser::findOne($userInfo['id']);

                $AdminUser->setPassword($pwd);//加密后的密码

                if($AdminUser->save()){
                    show_json(0,'Password reset successfully');
                }
            }

            show_json(0,'Password reset error');
        }

        show_json(100000,'The parameter format is incorrect');

    }

}
