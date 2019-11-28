<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-16
 * Time: 16:00
 */

namespace app\controllers;

use common\consequence\Result;
use common\exception\FixedException;
use Yii;
use app\controllers\bus\goodsBus;
use common\helpers\UtilsHelper;

/**
 * 临期商品控制器
 * Class GoodsController
 * @package app\controllers
 */
class GoodsController extends BaseController
{
    /**
     * 列表
     */
    public function actionList()
    {
        $goodsBus = new goodsBus();

        $params = UtilsHelper::getHttpInput();

        list($datas, $pages) = $goodsBus->getList($this->shopId, $params);

        return $this->render('list', [
            'params' => $params,
            'datas' => $datas,
            'pages' => $pages,
        ]);
    }

    /**
     * 创建|修改
     * @return string
     */
    public function actionEdit(){

        $params = UtilsHelper::getHttpInput();
        $goodsBus = new goodsBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                if($params['id']){
                    $goodsBus->updateGoods($this->shopId, $params);
                }else{
                    $ret->data = $goodsBus->createGoods($this->shopId, [$params]);
                }
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        $data = [];
        if(isset($params['id'])){
            $data = $goodsBus->getGoodsById($this->shopId, $params['id']);
        }


        return $this->render('edit', ['data'=>$data]);
    }

    /**
     * 删除
     */
    public function actionDel()
    {
        $params = UtilsHelper::getHttpInput();
        $goodsBus = new goodsBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                $goodsBus->del($params['id']);
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    /**
     * 提交审核
     */
    public function actionCheck_pass()
    {
        $params = UtilsHelper::getHttpInput();
        $goodsBus = new goodsBus();
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                $goodsBus->check_pass($params['id']);
            }catch (FixedException $e){
                $ret->code = $e->getCode();
                $ret->msg = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }
}