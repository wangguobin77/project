<?php
header("Content-Type: text/html;charset=utf-8");

header("Cache-Control:no-cache");

include 'main.load.inc';

//if(!isset($_SESSION['token']) || $_SERVER['HTTP_TOKEN'] != $_SESSION['token']){
//    en_json('-1', 'token wrong');
//}

//新消息数量
$new_message_num = 0;
//新消息数组
$new_message_arr = array();
//读取告警文件是否有内容
if(file_exists(TIP_FILE_PATH) && filesize(TIP_FILE_PATH) > 0){
    if($fp = @fopen(TIP_FILE_PATH, 'r')){
        $clean = false;
        if (flock($fp, LOCK_EX)){
            $clean = true;
            while (!feof($fp)){
                //读取一行
                $str = fgets($fp, 1024);
                $str = ereg_replace("\r|\n", "", $str);//消息内容,去除空格换行
                list($ip, $tip) = explode(':', $str);
                $ip = intval($ip);
                $tip = $tip ? $tip : 'sp'.$ip.'：发出告警!';
                if($ip && $tip){
                    $new_message_num++;
                    $new_message_arr[] = array(
                        'gid' => rand(100000,999999), //给一个随机数用于标识
                        'ip'=>$ip, //消息内容,去除空格换行
                        'tip'=>$tip, //消息内容,去除空格换行
                        'read' => 0, //是否已读 0 未读 1 已读

                    );
                }
                flock($fp, LOCK_UN);
            }
        }
        fclose($fp);
        //清除提示消息内容
        if($clean){
            exec('cat /dev/null>'.TIP_FILE_PATH);
        }
    }

}else{
    en_json('-1', '没有新消息');
}
$old_message_arr = unserialize(file_get_contents(MESSAGE_TXT_PATH));
if($old_message_arr){
    $message_arr = array_slice(array_merge($new_message_arr, $old_message_arr), 0, MESSAGE_NUM_MAX);
}else{
    $message_arr = $new_message_arr;
}


if($message_arr){
    reput_message($message_arr);
}
$return_str = '[';
foreach ($new_message_arr as $value){
    $return_str .= '{"gid":"'. $value['gid'].'","ip":"'. $value['ip'].'", "tip": "'.$value['tip'].'","read":"'.$value['read'].'"},';
}
$return_str = substr($return_str, 0, -1) . "]";

en_json('0', $new_message_num, $return_str);