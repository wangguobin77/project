<?php
namespace app\controllers\base;

use Yii;
use yii\db\Exception;
use app\models\Batch;
use yii\data\Pagination;
use app\models\Category;
class BatchBaseController extends BaseController
{
    protected function batchList()
    {
        $query = Batch::find()->select('batch.*,category.name as category')->leftJoin('category', 'batch.category_id = category.id');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('create_ts desc')
            ->asArray()
            ->all();
        return [
            'data'  =>  $models,
            'pages' =>  $pages,
        ];
    }

    protected function batchAdd()
    {
        $data = $this->batchValidate();
        $model = new Batch();
        $tr = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(['Batch'=>$data]) && $model->save()) {
//                var_db_log($this->userid, 'add', 'manufacture', $data);
                $tr->commit();
                show_json(0, $this->getErrMessage(0));
            }
            $tr->rollBack();
            $msg = $model->getErrors() ? current($model->getErrors()) : $this->getErrMessage(100000);
            show_json(100000, $msg);
        } catch (\Exception $e) {
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    protected function batchReview()
    {
        $status = trim(Yii::$app->request->post('status'));
        if ($status !== '1' && $status !== '2') {
            show_json(100000, 'params error.');
        }
        $id = trim(Yii::$app->request->post('id'));
        $batchInfo = $this->getBatchInfo($id);
        if (!$batchInfo) {
            show_json(100000, 'batch not exists.');
        }
        if ($batchInfo['status'] != 0) {
            show_json(100000, 'batch is already reviewed.');
        }

        $snLength = 32; //sn的长度
        $keyLength = 32;    //key的长度

        try {
            Yii::$app->db->createCommand('call sp_batch_review(:id, :state, :snlen, :keylen, @ret)')
                ->bindValues([':id'=>$id, ':state'=>$status, ':snlen'=>$snLength, ':keylen'=>$keyLength])->execute();
            $res = Yii::$app->db->createCommand("select @ret;")->queryScalar();
            if ($res == 1) {
                show_json(0, 'successful.');
            }
            throw new Exception();
        } catch (\Exception $e) {
            show_json(100000, 'failure.');
        }
    }

    protected function getBatchInfo($id)
    {
        return Batch::find()->select('batch.*,category.name')->leftJoin('category', 'batch.category_id = category.id')->where(['batch.id'=>$id])->asArray()->one();
    }

    protected function batchValidate()
    {
        $data['category_id'] = trim(Yii::$app->request->post('category_id'));
        $data['quantity'] = trim(Yii::$app->request->post('quantity'));
        $data['batch_num'] = substr(date('Y', time()), 2) . $this->IntToChr(date('m', time()));
        $num = '00';
        $batch = Batch::find()->select('batch_num')->where(['category_id'=>$data['category_id']])->orderBy('id desc')->asArray()->one();
        if ($batch) {
            if (substr($batch['batch_num'],0,3) == $data['batch_num']) {
                $num = substr($batch['batch_num'],3) + 1;
                if ($num > 99) {
                    show_json(100000, 'Apply up to 100 times a month.');
                }
                $num = str_pad($num,2,"0",STR_PAD_LEFT);
            }
        }
        $data['batch_num'] .= $num;

        return $data;
    }

    protected function getCategoryAll()
    {
        return Category::find()->asArray()->all();
    }

    protected function IntToChr($index, $start = 64) {
        $str = '';
        if (floor($index / 26) > 0) {
            $str .= $this->IntToChr(floor($index / 26)-1);
        }
        return $str . chr($index % 26 + $start);
    }
}