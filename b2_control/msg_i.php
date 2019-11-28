<?php
header("Content-Type: text/html;charset=utf-8");

header("Cache-Control:no-cache");

include 'main.load.inc';

//if(!isset($_SESSION['token']) || $_SERVER['HTTP_TOKEN'] != $_SESSION['token']){
//    en_json('-1', 'token wrong');
//}

$message_arr = unserialize(file_get_contents(MESSAGE_TXT_PATH));

if(isset($_POST['gid']) && $_POST['gid']){
    $gid = $_POST['gid'];
    if ($message_arr){
        if($gid == 'all'){
            foreach ($message_arr as $key => $value){
                $message_arr[$key]['read'] = 1;
            }
        }else{
            foreach ($message_arr as $key => $value){
                if ($value['gid'] == $gid){
                    $message_arr[$key]['read'] = 1;
                }
            }
        }
        reput_message($message_arr);
    }
}
$msg = 'ok';
if($message_arr){
    $return_str = '[';
    $new_message_num = 0; //未读消息条数
    foreach ($message_arr as $value){
        if($value['read'] == 0){
            $new_message_num++;
        }
        $return_str .= '{"gid":"'. $value['gid'].'","ip":"'. $value['ip'].'", "tip": "'.$value['tip'].'","read":"'.$value['read'].'"},';
    }
    $msg = $new_message_num;
    $return_str = substr($return_str, 0, -1) . "]";
}else{
    $return_str = '';
    $msg = '操作失败！';
}


en_json('0', $msg, $return_str);