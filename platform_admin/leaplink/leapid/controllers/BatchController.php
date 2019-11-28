<?php

/**
 * 批次控制器 -- 仅做数据接收和验证，业务逻辑请在business逻辑类中完成
 */
namespace app\controllers;

use app\console\jobs\TestJob;
use app\models\batch\ARBatch;
use common\consequence\ErrorCode;
use common\consequence\Result;
use common\exception\GanWuException;
use common\helpers\ValidateHelper;
use Yii;
use common\helpers\Utils;
use app\controllers\bus\batchBus;
use app\controllers\base\BaseController;

class BatchController extends BaseController
{
    public $layout = false;
    public $enableCsrfValidation = false;

    //列表
    public function actionList()
    {
        $bus = new batchBus();
        $params = Utils::getHttpInput();
        $ret = $bus->getList($params);

        return $this->render('list', ['datas' => $ret['datas'], 'pages' => $ret['pages']]);
    }

    //创建
    public function actionAdd()
    {
        if(Yii::$app->request->isAjax) {
            $data = Utils::getHttpInput();
            $ret = new Result();
            try {
                //参数验证
                //芯片型号
                if(strlen($data['chip_type']) != 4 ){
                    throw new GanWuException('请输入 4 位芯片型号', ErrorCode::ERROR);
                }
                //固件升级秘钥
                $data['key_update'] = str_replace(' ', '', $data['key_update']);
                if (strlen($data['key_update']) != 32) {
                    throw new GanWuException('32位,请检查长固件升级秘钥长度', ErrorCode::ERROR);
                }
                //批次日期
                if ($data['batch_date'] == '') {
                    throw new GanWuException('请选择批次日期', ErrorCode::ERROR);
                }
                //批次流水
                if((int)$data['batch_no'] > 100 || (int)$data['batch_no'] <= 0) {
                    throw new GanWuException('批次流水取值 1-99', ErrorCode::ERROR);
                }
                //生产数量
                if((int)$data['batch_count'] >= 1000000) {
                    throw new GanWuException('每批次最多生产 999999', ErrorCode::ERROR);
                }
                $bus = new batchBus();
                $ret = $bus->add([$data]);

            } catch (GanWuException $e) {
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
            return $ret;
        }
        return $this->render('add', []);
    }

    //审核
    public function actionCheckPass()
    {
        date_default_timezone_set('Asia/ShangHai');

        if (Yii::$app->request->isAjax) {
            $params = Utils::getHttpInput();
            $ret = new Result();

            try {
                $bus = new batchBus();
                $id = (int)$params['id'];
                $status = (int)$params['status'];

                $ret->data = $bus->checkPass($id, $status);

            } catch (GanWuException $e) {
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    //刷缓存
    public function actionCache()
    {
        if (Yii::$app->request->isAjax) {
            $params = Utils::getHttpInput();
            $ret = new Result();

            try {
                $bus = new batchBus();
                $id = (int)$params['id'];
                $type = $params['type'];

                $bus->frushCache($id, $type);

            } catch (GanWuException $e) {
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }
            return $ret;
        }
        return false;
    }

    public function actionDownload()
    {
        $batch_id = Yii::$app->request->get('batch_id');
        if(!$batch_id) {
            echo '参数错误';die;
        }

        $batch = ARBatch::findOne(['id' => $batch_id]);

        $bin_root = '/leapid/%s/'; //bin 文件路径

        $filePath = Yii::$app->params['bin_root'].sprintf($bin_root, $batch->batch_date . str_pad($batch->batch_no, 2,0, STR_PAD_LEFT)). $batch->batch_date . str_pad($batch->batch_no, 2,0, STR_PAD_LEFT) . '.zip';

        ob_clean();//清除一下缓冲区
        //获得文件名称
        $filename = basename($filePath);
        //检查文件是否可读
        if(!is_file($filePath) || !is_readable($filePath)) exit('Can not access file '.$filename);
        /**
         * 这里应该加上安全验证之类的代码，例如：检测请求来源、验证UA标识等等
         */
        //以只读方式打开文件，并强制使用二进制模式
        $fileHandle=fopen($filePath,"rb");
        if($fileHandle===false){
            exit("文件不存在,请生成bin: $filename");
        }
        //文件类型是二进制流。设置为utf8编码（支持中文文件名称）
        header('Content-type:application/octet-stream; charset=utf-8');
        header("Content-Transfer-Encoding: binary");
        header("Accept-Ranges: bytes");
        //文件大小
        header("Content-Length: ".filesize($filePath));
        //触发浏览器文件下载功能
        header('Content-Disposition:attachment;filename="'.urlencode($filename).'"');
        //循环读取文件内容，并输出
        while(!feof($fileHandle)) {
            //从文件指针 handle 读取最多 length 个字节（每次输出10k）
            echo fread($fileHandle, 10240);
        }
        //关闭文件流
        fclose($fileHandle);
    }
}





