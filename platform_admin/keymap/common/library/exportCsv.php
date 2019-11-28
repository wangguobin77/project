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
use manufacture\models\Sn;

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

        $sn_manufacture_category_short_info = $Sn->getSnSFRSelectAll($data['m_id']);

        if(!$sn_manufacture_category_short_info){
            show_json(100000,'lack of specific information audit failed');//缺少具体信息 不能通过审核
        }

        if($data['h_type'] == 1){//终端
            $sn_category_device_short_info = $Sn->getSnDeviceAndCategoryShortInfo($data['h_id']);

            if(!$sn_category_device_short_info){
                show_json(100000,'lack of hid specific information audit failed');//缺少具体信息 不能通过审核
            }
            $c_short = $sn_category_device_short_info['category_short'];//大类缩写

            $d_short = $sn_category_device_short_info['device_type_short'];//终端缩写
        }else if($data['h_type'] == 2){//遥控器
            /* $sn_remote_short_info = $Sn->getSnRemoteShortInfo($data['r_id']);*/
            $sn_remote_short_info = $Sn->getSnRemoteShortInfo($data['h_id']);

            if(!$sn_remote_short_info){
                show_json(100000,'lack of rid specific information audit failed');//缺少具体信息 不能通过审核
            }
            $c_short = 'RC';
            $d_short = $sn_remote_short_info['remote_type_short'];//大类缩写
        }else{
            show_json(100000,'device type unknown');//设备类型不详 不能通过审批
        }
        //end

        $batch_number_str = $sn_manufacture_category_short_info['manufacture_short'].$c_short.$d_short.$data['batch_year'].$data['batch_no'];//批次序列号

        //根据批次id 获取所有sn相关数据 getSnInfoFromBid
        $sn_info_list = $Sn->getSnInfoFromBid($batch_number_str);//sninfo信息

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