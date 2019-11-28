<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/16
 * Time: 下午1:58
 */

namespace app\controllers;

use app\controllers\bis\FeedbackBis;
use app\models\Feedback;
use common\helpers\Exception;
use common\Result;
use Yii;
use yii\data\Pagination;
use app\controllers\base\BaseController;

class FeedbackController extends BaseController
{

    public function init()
    {
        $this->layout = false;
        $this->enableCsrfValidation = false;
    }


    /**
     * 售后服务申请列表
     */
    public function actionList()
    {
        $bis = new FeedbackBis();


        //获取总条数
        $totalCount = $bis->getCountFeedback();

        $pages = new Pagination(['totalCount'=>$totalCount,'defaultPageSize'=>Yii::$app->params['default_page_size']] );  //传入页面的总页数格式

        $models = $bis->getData($pages);

        return $this->render('list', [
            'data'  =>  $models,
            'pages' =>  $pages
        ]);

    }

    /**
     * 查看详细信息
     */
    public function actionView()
    {
        $bis = new FeedbackBis();

        $id = filterData(Yii::$app->request->get('id'), 'integer', 11);
        $data = $bis->getByFeedbackId($id);

        return $this->render('view', [
            'data'  =>  $data
        ]);
    }

    public function actionChange_status()
    {
        if(Yii::$app->request->isAjax){
            $ret = new Result();
            try{
                $id = (int)Yii::$app->request->post('id');
                $new_status = (int)Yii::$app->request->post('contact_status');

                $res = FeedbackBis::updateStatus($id, $new_status);
                if(!$res){
                    throw new Exception('修改失败', 900031);
                }
            }catch (Exception $e){
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
            return $ret;
        }
    }

}