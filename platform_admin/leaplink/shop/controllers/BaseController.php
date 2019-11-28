<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-03-20
 * Time: 17:58
 */

namespace app\controllers;

use app\controllers\bus\shopBus;
use common\helpers\RedisHelper;
use common\menu\MenuCore;
use yii\helpers\Url;
use yii\web\Controller;
use app\controllers\bus\appBus;


abstract class BaseController extends Controller
{
    /** 登录用户基础信息(shop 表的) */
    public $loginUserInfo;

    /** 商户 id */
    public $shopId = null;

    /** 渲染视图不使用布局 */
    public $layout = false;

    public $token_tmp;

    public function beforeAction($action)
    {
        //登录验证
        if(!$this->loginStatusCheck()){
            header("Location:" . Url::toRoute('/app/login'));
            exit;
        }
        parent::beforeAction($action);

        //临时使用:初始化菜单
        MenuCore::init();
        return true;
    }

    /** 登录状态检测 */
    protected function loginStatusCheck()
    {
        $app = new appBus();

        if(!$app->loginStatusCheck()) {
            return false;
        }

        $this->token_tmp  = \Yii::$app->request->cookies->getValue(\Yii::$app->params['sessionName']);

        $this->shopId = $app->getLoginShopInfo('shop_id')['shop_id'];

        //用户已登录
        $this->loginUserInfo = (new shopBus())->getShopFromCache($this->shopId);

        return true;
    }


}