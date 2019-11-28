<?php

namespace app\controllers\base;

use Yii;
use app\models\Sn;
use app\models\Batch;
use yii\data\Pagination;
class SnBaseController extends BaseController
{
    protected function snList()
    {
        $query = Sn::find()->where(['batch_id'=>trim(Yii::$app->request->get('id'))]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return [
            'data'  =>  $models,
            'pages' =>  $pages,
        ];
    }

    public function snData()
    {
        return Sn::find()->where(['batch_id'=>trim(Yii::$app->request->get('id'))])
            ->orderBy('id desc')
            ->asArray()
            ->all();
    }

    public function batchInfo()
    {
        return Batch::find()
            ->select('batch.*,category.name category')
            ->leftJoin('category', 'batch.category_id = category.id')
            ->where(['batch.id' =>trim(Yii::$app->request->get('id'))])
            ->asArray()
            ->one();
    }
}