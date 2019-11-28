<?php
namespace app\controllers;

use app\controllers\bis\BatchBis;
use common\ErrorCode;
use common\helpers\Exception;
use common\helpers\File;
use common\library\resource\BaseResource;
use Yii;
class BatchController extends base\BatchBaseController
{
    public $layout = false;

    /**
     * 批次列表
     */
    public function actionList()
    {
        $data = $this->batchList();
        return $this->render('list', $data);
    }

    /**
     * 申请批次
     */
    public function actionAdd()
    {
        if (Yii::$app->request->isPost) {
            $this->batchAdd();
        }
        return $this->render('add', ['category' => $this->getCategoryAll()]);
    }

    /**
     * 审批批次申请
     */
    public function actionReview()
    {
        if (Yii::$app->request->isPost) {
            $this->batchReview();
        }
        $info = $this->getBatchInfo(trim(Yii::$app->request->get('id')));
        if ($info) {
            return $this->render('edit', ['info' => $info]);
        }
    }

    /**
     * 通过csv获取数据
     */
    public function actionCsv_load()
    {
        ini_set('upload_max_filesize', '10m');

        if(Yii::$app->request->isPost){
//            var_dump($_FILES);die;
            try{
                $category_id = filterData(Yii::$app->request->post('category_id'), 'integer', 11);
                //如果有文件上传，则根据附件来构造素材类
                if (isset($_FILES['csv'])) {
                    $fileData = $_FILES['csv'];

                    //根据上传文件类型，构建文件类实例
                    $mime_type = File::getFileMimeType($fileData['tmp_name']);
                    list($fileType, $tmp) = explode('/', $mime_type);
                    //创建素材类实例
                    $resInst = BaseResource::createInstance($fileType);
                    $ret = $resInst->save($fileData);
                }else{
                    throw new Exception('没有检测到上传文件！', ErrorCode::NO_MATCHED_UPLOADER);
                }
                //数据提取保存
                if($ret->code == ErrorCode::SUCCEED) {
                    $bis = new BatchBis();
                    $bis->load($category_id, $ret->data['url']);

                    //删除上传文件
                    unlink($ret->data['url']);
                    show_json(0, 'multiple save success');//批量上传成功
                }
            } catch (Exception $e){
                //删除上传文件
                isset($ret->data['url']) && unlink($ret->data['url']);

                show_json($e->getCode(), $e->getMessage());
            }
        }

        return $this->render('load', ['category' => $this->getCategoryAll()]);
    }
}