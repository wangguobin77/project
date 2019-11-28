<?php
/**
 * 导出 csv文件
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/8/22
 * Time: 下午1:37
 */

namespace common\library;

use Yii;
use app\models\Sn;

class exportCsv
{
    /**
     * 根据批次号id 导出当前批次号下的所有sn号
     */
    public function rankedexport($bid)
    {

        $title = 'SN,激活码,校验状态(校验状态 1 校验中 2 可使用 3 废弃)'."\n";
        $fileName = 'empty.csv';

        //根据批次号id 查询批次信息
        $Sn = new Sn;
        $data = $Sn->getSnBatchInfoSelectOne($bid);//批次号信息


        //验证 查找缩写
        if(!$data){
            show_json(100000,'Data is not legal cannot be audited');
        }

        //只有被审核通过的文件才能下载
        if($data['check_status'] != 3){
            show_json(100000,'Only approved files can be downloaded');
        }


        //根据批次id 获取所有sn相关数据 getSnInfoFromBid
        $sn_info_list = $Sn->getSnInfoFromBid($bid);//sninfo信息,新数据sn_info.bid存bid而不是sn前13位

        $wrstr = '';
        if(!empty($sn_info_list)){
            //文件名使用sn前13位
            $snOne = current($sn_info_list);
            $fileName = substr($snOne['sn'], 0, 13) . '.csv';

            foreach ($sn_info_list as $key => $value) {
                $wrstr .= $value['sn'] .','.
                    $value['rand_str'] . ',' .
                    $value['check_status'];
                $wrstr .= "\n";
            }
        }

        $this->Csvexport( $fileName, $title, $wrstr);
        insert_db_log('select', "批次下载:批次id-{$bid}");
    }

    /**
     * 导出csv
     * @param string $file
     * @param string $title
     * @param $data
     */
    public function Csvexport($file = '', $title = '', $data)
    {
        header("Content-Disposition:attachment;filename=".$file);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        // ob_start();

        //表头
        $wrstr = $title;

        //内容
        $wrstr .= $data;

        $wrstr = iconv("utf-8", "GBK//ignore", $wrstr);

        // ob_end_clean();

        echo $wrstr;

    }
}