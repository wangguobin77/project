<?php

namespace app\controllers;

use app\controllers\base\SnBaseController;
class SnController extends SnBaseController
{
    public $layout = false;

    public function actionList()
    {
        $data = $this->snList();
        return $this->render('list', $data);
    }

    public function actionDownload()
    {
        $data = $this->snData();
        if ($data) {
            $batch = $this->batchInfo();

            $month = $this->ChrToInt(substr($batch['batch_num'],2,1));
            $fileName = '20'.substr($batch['batch_num'],0,2).'年'.$month.'月 第'.substr($batch['batch_num'],3).'批'.$batch['category'].'.csv';

            // 头部标题
            $csv_header = array('batch流水号', 'sn', 'key');

            $header = implode(',', $csv_header) . PHP_EOL;
            $content = '';
            foreach ($data as $k => $v) {
                $content .= implode(',', [$v['batch_serial'], $v['sn'], $v['key']]) . PHP_EOL;
            }
            $csvData = iconv("utf-8", "GBK//ignore",$header . $content);
            header("Content-Disposition:attachment;filename=".$fileName);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Transfer-Encoding: binary');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            echo $csvData;
        }
    }

    protected function ChrToInt($index)
    {
        $ten = 0;
        $len = strlen($index);
        for($i=1;$i<=$len;$i++){
            $char = substr($index,0-$i,1);//反向获取单个字符

            $int = ord($char);
            $ten += ($int-65)*pow(26,$i-1);
        }

        return $ten;
    }
}