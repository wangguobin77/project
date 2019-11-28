<?php

/** -------------------------
 * 解析数据
 * @param $str
 * the str likes:
 * node_id=3:21-0,
 * node_id=5:33-1,44-0,
 * node_id=8:55-0,
 * node_id=4:66-0,77-0,
 * ...
 * return likes:
 * { key: "5", parent: "1",geo: "Node4",color: colors["blue"],level:2  },
 * { key: "21", parent: "2",color: colors["orange"],geo: "tree1",select:false, originColor: colors["orange"],level:3 },
----------------------------*/
function strToJson($str){
    $arr = explode(",\n", $str);
    $ret_str = '[';
    $count = 0;
    foreach ($arr as $value){
        if(!empty($value)){
            ereg('node_id=([0-9]{1,3}):(.*)', $value, $arr1);
            if(isset($arr1[1])) {
                $ret_str .= '{"key":"'.$arr1[1].'","parent":"1","geo":"Node'.$arr1[1].'","color":"colors[\\"blue\\"]","level":2},';

                if (isset($arr1[2])) {
                    $arr2 = explode(',', $arr1[2]);
                    foreach ($arr2 as $v2) {
                        $arr3 = explode('-', $v2);
                        if (isset($arr3[0]) && isset($arr3[1])) {
                            if($arr3[1] == 1){
                                $select = 'true';
                                $count++;
                            }else{
                                $select = 'false';
                            }

                            $ret_str .= '{"key":"'.$arr3[0].'","parent":"'.$arr1[1].'","color":"colors[\\"orange\\"]","geo":"sp'.$arr3[0].'","select":'.$select.',"originColor":"colors[\\"orange\\"]","level":3},';
                        }
                    }
                }
            }
        }
    }
    $ret_str = substr($ret_str, 0, -1) . "]";
    return array($count, $ret_str);
}


/** -------------------------
 * 解析数据
 * @param $str
 * the str likes:
 * node_id=3:21-0,
 * node_id=5:33-1,44-0,
 * node_id=8:55-0,
 * node_id=4:66-0,77-0,
 * ...
 * return likes:
 * { key: "5", parent: "1",geo: "Node4",color: colors["blue"],level:2  },
 * { key: "21", parent: "2",color: colors["orange"],geo: "tree1",select:false, originColor: colors["orange"],level:3 },
----------------------------*/
function strToJson2($str){
    $arr = explode(",\n", $str);
    $ret_str = '[';
    $count = 0;
    foreach ($arr as $value){
        if(!empty($value)){
            ereg('node_id=([0-9]{1,3}):(.*)', $value, $arr1);
            if(isset($arr1[1])) {
                $ret_str .= '[{"key":"'.$arr1[1].'","parent":"1","geo":"Node'.$arr1[1].'","color":"colors[\\"blue\\"]","level":2},';

                if (isset($arr1[2])) {
                    $arr2 = explode(',', $arr1[2]);
                    foreach ($arr2 as $v2) {
                        $arr3 = explode('-', $v2);
                        if (isset($arr3[0]) && isset($arr3[1])) {
                            if($arr3[1] == 1){
                                $select = 'true';
                                $count++;
                            }else{
                                $select = 'false';
                            }

                            $ret_str .= '{"key":"'.$arr3[0].'","parent":"'.$arr1[1].'","color":"colors[\\"orange\\"]","geo":"sp'.$arr3[0].'","select":'.$select.',"originColor":"colors[\\"orange\\"]","level":3},';
                        }
                    }
                    $ret_str = substr($ret_str, 0, -1) . "],";
                }
            }
        }
    }
    $ret_str = substr($ret_str, 0, -1) . "]";
    return array($count, $ret_str);
}

//拼接返回json
function en_json($code, $msg = '', $data = ''){
    if($data){
        $json = "{\"code\":{$code},\"message\":\"{$msg}\", \"data\":{$data}}";
    }else{
        $json = "{\"code\":{$code},\"message\":\"{$msg}\"}";
    }
    die($json);
}

//生产一个token
function getToken($len = 32, $md5 = true) {
    mt_srand((double) microtime() * 1000000);
    $chars = array (
        'Q','@','8','y','%','^','5','Z','(','G','_','O','`','S','-','N','<','D','{','}','[',
        ']','h',';','W','.','/','|',':','1','E','L','4','&','6','7','#','9','a','A','b','B','~','C',
        'd','>','e','2','f','P','g',')','?','H','i','X','U','J','k','r','l','3','t','M','n','=',
        'o','+','p','F','q','!','K','R','s','c','m','T','v','j','u','V','w',',','x','I','$','Y','z','*');

    # Array indice friendly number of chars;
    $numChars = count($chars) - 1;
    $token = '';
    # Create random token at the specified length
    for ($i = 0; $i < $len; $i++)
        $token .= $chars[mt_rand(0, $numChars)];
    # Should token be run through md5?
    if ($md5) {
        # Number of 32 char chunks
        $chunks = ceil(strlen($token) / 32);
        $md5token = '';
        # Run each chunk through md5
        for ($i = 1; $i <= $chunks; $i++)
            $md5token .= md5(substr($token, $i * 32 - 32, 32));
        # Trim the token
        $token = substr($md5token, 0, $len);
    }
    return $token;
}

if (!function_exists('file_get_contents')){
    //将文件内容读入字符串
    function file_get_contents($path) {
        if(file_exists($path)){
            if($fp = @fopen($path, 'r')){
                $startTime=microtime();
                do{
                    $canWrite = flock($fp,LOCK_EX);
                    if(!$canWrite){
                        usleep(round(rand(0,100)*1000));
                    }
                }while((!$canWrite)&&((microtime()-$startTime)<1000));
                if($canWrite){
                    $str = fread($fp, filesize($path));
                    flock($fp, LOCK_UN);
                }
                fclose($fp);
                return $str;
            }

        }else{
            @mkdir($path);
        }
        return '';
    }
}

/**
 * 替换文件内容
 * @param $message_arr  //数组
 */
function reput_message($message_arr) {
    if($fp = fopen(MESSAGE_TXT_PATH, 'w')){
        $startTime=microtime();
        do{
            $canWrite=flock($fp,LOCK_EX);
            if(!$canWrite){
                usleep(round(rand(0,100)*1000));
            }
        }while((!$canWrite)&&((microtime()-$startTime)<1000));
        if($canWrite){
            fwrite($fp,serialize($message_arr));
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

/**
 * 获取ipc列表信息
 * @return mixed
 */
function get_ipc_info()
{
    exec(BASE_COMMAND.' get_ipc_info', $res);
    $str = '';
    foreach ($res as $v){
        $str .= $v. "\n";
    }
    return $str;
}

/**
 * 发起关闭ipc的请求
 * @param $close int 需要关闭的ip
 * @return mixed
 */
function close_ipc($close){
    exec(BASE_COMMAND.' close_ipc:'. intval($close), $res);
    return $res;
}

/**
 * 发起开启ipc的请求
 * @param $open int 需要打开的ip
 * @return mixed
 */
function open_ipc($open){
    exec(BASE_COMMAND.' open_ipc:'. intval($open), $res);
    return $res;
}

/**
 * 验证ipc状态是否改变
 * @param $ip int 对应的ip
 * @param $new_status int 0/1 新状态
 * @return bool
 */
function check_ip_status_changed($ip, $new_status){
    $str = get_ipc_info();
    $arr = explode(",\n", $str);
    if($arr){
        foreach ($arr as $value){
            ereg('node_id=([0-9]{1,3}):(.*)', $value, $arr1);
            if (isset($arr1[2])) {
                $arr2 = explode(',', $arr1[2]);
                foreach ($arr2 as $v2) {
                    $arr3 = explode('-', $v2);
                    if (isset($arr3[0]) && isset($arr3[1])) {
                        if ($arr3[0] == $ip && $arr3[1] == $new_status) {
                            return true;
                        }
                    }
                }
            }
        }
    }
    return false;
}
