<?php
header("Content-Type: text/html;charset=utf-8");

header("Cache-Control:no-cache");

include 'main.load.inc';

//if(!isset($_SESSION['token']) || $_SERVER['HTTP_TOKEN'] != $_SESSION['token']){
//    en_json('-1', 'token wrong');
//}

$check_status = "false";

//开启视频
if(isset($_GET['open']) && $_GET['open']){
    // 验证ipc开启是否超过配置限制
    // 规则: 1.ipc开启不超过配置
    $count = $_SESSION['sp_count'];
    if ($count >= NODE_NUM_MAX){
        en_json('90001', '告警！请关闭一路摄像头来点播alarm摄像头！');
    }

    $ipc = $open = $_GET['open'];
    $status = IPC_STATUS_OPEN; //开启的状态
    $action = '关闭'; //操作

    //发起开启请求
    $ret = open_ipc($open);
    $check_status = $ret[0];

    if($check_status === "false"){
        en_json('-1', $action.'失败，请稍后重试！');
    }
}elseif(isset($_GET['close']) && $_GET['close']){//关闭视频
    $ipc = $close = $_GET['close'];

    $status = IPC_STATUS_CLOSE; //关闭的状态
    $action = '关闭'; //操作

    //发起关闭请求
    $ret = close_ipc($close);
    $check_status = $ret[0];

    if($check_status === "false"){
        en_json('-1', $action.'失败，请稍后重试！');
    }
}

//验证开启/关闭
if($check_status === "true"){
    $num = 1;
    while ($num <= CHECK_STATUS_NUM){
        $check_res = check_ip_status_changed($ipc, $status);

        if($check_res){
            break;
        }
        $num++;
        sleep(CHECK_STATUS_TIME);
    }

    if(!$check_res){
        en_json('-2', $action.'失败，请稍后重试！');
    }
}

//获取列表信息
$str = get_ipc_info();

//解析字符串
$info = strToJson2($str);
//已开启的视频数量
$_SESSION['sp_count'] = $info[0];

en_json('0', 'ok', $info[1]);
