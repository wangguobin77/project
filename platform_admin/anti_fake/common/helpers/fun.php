<?php
/**
+------------------------------------------------------------------------------
 * 文件描述： 基础函数封装...
+------------------------------------------------------------------------------
 */
/*
* @todo   UTF-8转GB2312
* @param  字符串
* @return 转换后的字符
*/
function str_u2g($str){
    return iconv('UTF-8', 'GB2312//IGNORE', $str);
}
/*
* @todo   GB2312转UTF-8
* @param  字符串
* @return 转换后的字符
*/
function str_g2u($str){
    return iconv('GB2312', 'UTF-8//IGNORE', $str);
}

/*
* @todo   格式化打印变量
* @param  变量 标签 输出返回
* @return 变量信息
*/
function str_dump($var, $label='', $echo=true){
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    $output = '<pre>' . $label .' '. htmlspecialchars($output, ENT_QUOTES) . '</pre>';
    if ($echo){
        echo($output);
    } else{
        return $output;
    }
}
/*
* @todo   返回json数据
* @param  编码 信息 数据
* @return 格式化的json
*/
function show_json($code, $mess='', $data=array()) {
    header('Content-Type: application/json; charset=utf-8');
    $json = array('code'=>$code, 'message'=>$mess, 'data'=>$data);
    $json = json_encode($json);
    exit($json);
}

/*
 * 页面跳转 获取值基于session
* @todo   返回json数据
* @param  编码 信息 数据
* @return 格式化的json
*/
function set_ses_data($code,$data='') {
    $json = array('code'=>$code, 'data'=>$data);
    // $json = json_encode($json);
    if(!session_id()){
        @session_start();//start session
    }
    $_SESSION['global_status'] = $json;
}

/**
 * 获取session 用一次就删除
 * @param $code
 * @param string $mess
 * @param array $data
 */
function get_ses_data($key) {
    if(!session_id()){
        @session_start();//start session
    }

    if(!in_array($key,['code','data'])){
        return false;
    }
    if(isset($_SESSION['global_status']) && !empty($_SESSION['global_status'])){
        $data = $_SESSION['global_status'][$key];
        unset($_SESSION['global_status']);
        return $data;
    }

    return false;
}
/**
 * [encryptedPwd description]
 * @param  [type] $pwd    [description]
 * @param  [type] $string [description]
 * @return [type]         [description]
 * @descripe对密码进行加密 都转小写
 */
function encryptedPwd ($pwd = NULL, $string = NULL)
{
    return strtolower(md5($pwd.$string));
}
/**
 * [delData description]
 * @param  [type] $value [description]
 * @return [type]        [description]
 * @descripe 过滤数据
 */
function filterData ($data,$type='string',$Maxlength=32,$MinLength=0)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {

            checkLengthParams($data,$Maxlength,$MinLength);
            checkTypeParams($data,$type);

//            $value = input_str_check($value);

            $value = trim($value);//移除字符串两侧的字符
            //   $value = htmlspecialchars($value);//过滤字符 单引号等
            //    $data[$key] = strip_tags($value);//剥去字符串中的 HTML、XML 以及 PHP 的标签
        }
    } else {

        checkLengthParams($data,$Maxlength,$MinLength);
        checkTypeParams($data,$type);

        $data = trim($data);
        //  $data = htmlspecialchars($data);
        //  $data = strip_tags($data);

//        $data = input_str_check($data);

    }

    return $data;
}

/**
 * 转义字符串，同时支持验证长度
 * @param $data
 * @param $Maxlength
 * @param $MinLength
 * @param $character
 * @return string
 */
function escape($data, $Maxlength=false, $MinLength=false, $character='utf-8')
{
    $data = htmlspecialchars($data);//过滤字符 单引号等
    $data = strip_tags($data);//剥去字符串中的 HTML、XML 以及 PHP 的标签
    if ($Maxlength !== false) {
        if (mb_strlen($data, $character) > $Maxlength) {
            return false;
        }
    }
    if ($MinLength !== false) {
        if(mb_strlen($data, $character) < $MinLength){
            return false;
        }
    }
    return $data;
}

/**
 * 验证是否正数(已经最大最小值)
 * @param $number
 * @param bool $min
 * @param bool $max
 * @return bool
 */
function isInteger($number, $min=false, $max=false)
{
    $number = trim($number);
    if (floor($number) != $number) {
        return false;
    }
    if ($min) {
        if ($number<$min) {
            return false;
        }
    }
    if ($max) {
        if ($number > $max) {
            return false;
        }
    }
    return true;
}

/**
 * @param $data
 * @param int $Maxlength 最大长度
 * @param int $Min 最小长度
 * @return string
 */
function checkLengthParams($data,$Maxlength,$Min)//默认长度32位
{
    $data = trim($data);
    if(strlen($data) > $Maxlength){
        show_json(100000,'The parameters are log');//参数长度超长
    }

    if(strlen($data) < $Min){
        show_json(100000,'The parameters are short');//参数长度过短
    }

    return $data;
}

/**
 * @integer 整形
 * @string  字符串
 * @double   双精度
 * @boolean  布尔
 * @NULL    空值
 *
 * @param $data
 * @param string $type
 * @return mixed
 */
function checkTypeParams($data,$type = 'string')//默认字符串
{
    $i_type = gettype($data);

    $type = strtolower($type);

    if(is_numeric($data)){
        return $data;
    }

    if(strtolower($i_type) != $type){
        show_json(100000,'Data type error');//参数长度过短
    }

    return $data;
}
/**
 *
 * @param  int  随机数
 * @return string
 * @descripe 生成唯一的32位字符串 截取四位
 */
function uuid ($limit = 4)
{
    $str = time();
    $str = md5($str . mt_rand());
    $str = md5(substr($str . time() . md5(mt_rand()), mt_rand(0, 30)));
    $str = substr($str, 0, intval($limit));
    return $str;
}

/**
 * 随机获取四位整数
 * @param int $limit
 * @return bool|string
 */
function getIntCode($limit=4)
{
    $str = str_shuffle(time());
    $str = substr($str, 0, intval($limit));
    return $str;
}
/**
 *
 * @param url 获取数据的地址
 * @return array 服务器返回的结果
 * @descripe 使用post方式 到指定的url 下采集数据
 */
function postXml ($xml_data, $url)
{
    $header[] = "Content-type: text/xml";  //定义content-type为xml,注意是数组
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
    $result = curl_exec($ch);
    return $result;
}
/**
 *
 * @param url 获取数据的地址
 * @return array 服务器返回的结果
 * @descripe 使用get方式 到指定的url 下采集数据
 */
function getCurl ($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
/**
 *
 * @param url 推送数据的地址
 * @return array  服务器返回的结果
 * @descripe  使用post方式 发送数据到指定的url下  采集数据
 */
function postCurl ($url, $data)
{
    $header = array();
    $header[] = 'Accept:application/json';
    $header[] = 'Content-Type:application/json;charset=utf-8';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $back_info = curl_exec($ch);
    if (curl_errno($ch)) {
        $back_info = curl_error($ch);
        writeLog($back_info);
        $back_info = json_encode(array('status' => false, 'msg' => $back_info));
    }
    curl_close($ch);
    return $back_info;
}


/**
 * curl post 第2种方式
 * @param $url
 * @param $post_data
 * @return mixed
 */
function postCurl_1($url, $post_data){
    $o="";
    foreach ($post_data as $k=>$v)
    {
        $o.= "$k=".urlencode($v)."&";
    }
    $post_data=substr($o,0,-1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 * @param data 导出的数据
 * @param fileNme  导出文件的文件名
 */
function exportCsv ($filename, $data)
{
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=" . $filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

/**
 * 程序中 非重要的数据 调试写log
 * @param string $str需要写入日志的内容
 * @return string
 */
function writeLog ($str = 'is null',$dir='public')
{
    $trace = current(debug_backtrace());
    /* $log_dir = __DIR__ . '/../Public/Log';//日志文件目录*/
    $log_dir = '/log/'.$dir;//绝对地址
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $day_path = $log_dir . '/' . date('Y-m-d');
    if (!is_dir($day_path)) {
        mkdir($day_path, 0755, true);
    }
    $log_path = $day_path . '/' . date('Y-m-d H') . '.txt';//sql 日志文件路径
    $time = date('Y-m-d H:i:s');
    if (is_array($str)) {
        $str = var_export($str, true);
    }
    $msg = "In {$trace['file']},Line:{$trace['line']},Output:" . $str;
    // $msg = "{$time}:message:" . PHP_EOL . $str . PHP_EOL;
    $handel = fopen($log_path, 'a+');
    fwrite($handel, $msg);
    fclose($handel);
    return 'msg' . $str;
}

/**
 * 记录重要数据 要正对数据库做比对
 * @param string $c controller
 * @param string $a  function
 * @param array $data 数据
 * @param string $dir 目录
 * @return string
 */
function var_log($data=array(),$filename=''){

    $trace = current(debug_backtrace());

    $log_dir = LOG_PATH;//绝对地址目录
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $day_path = $log_dir . '/' . date('Y-m-d');
    if (!is_dir($day_path)) {
        mkdir($day_path, 0755, true);
    }
    $log_path = $day_path . '/' . ($filename)? $filename.'.txt':date('Y-m-d H'). '.txt';//sql 日志文件路径

    $msg = json_encode(array('data'=>$data,'ip'=>getIp(),'time'=>date('Y-m-d H:i:s')));

    $msg = "In {$trace['file']},Line:{$trace['line']},Output:" . $msg."\n";
    //$msg = "{$time}:message:" . PHP_EOL . $msg . PHP_EOL;
    $handel = fopen($log_path, 'a+');
    fwrite($handel, $msg);
    fclose($handel);
    return 'msg' . $msg;
}

/**
 * 仅适用dblog记录
 * @param $uid 操作者 uid
 * @param $act  操作事件 如delete update...
 * @param $table 表名
 * @param array $data  操作的记录数据
 * @return string
 */
function var_db_log($uid,$act,$table,$data=array()){

    $trace = current(debug_backtrace());
    $log_dir = '/data/logs/a';//绝对地址目录
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    $day_path = $log_dir . '/' . date('Y-m');
    if (!is_dir($day_path)) {
        mkdir($day_path, 0755, true);
    }
    $log_path = $day_path . '/' .$table. '.log';//sql 日志文件路径

    $msg = json_encode(array('uid'=>$uid,'ts'=>time(),'ip'=>getIp(),'act'=>$act,'table'=>$table,'data'=>$data,));

    $r_msg = "Output:" . $msg.PHP_EOL;
    //$msg = "{$time}:message:" . PHP_EOL . $msg . PHP_EOL;
    $handel = fopen($log_path, 'a+');
    fwrite($handel, $r_msg);
    fclose($handel);
    return $msg;
}

/**
 * [getIp description]
 * @return [type] [description]
 * @descripe 获取用户的ip
 */
function getIp ()
{
    $onlineip = '';
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $onlineip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $onlineip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $onlineip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $onlineip = $_SERVER['REMOTE_ADDR'];
    }
    return $onlineip;
}

/**
 * 时间规定格式
 * @return string
 */
function setDateTime()
{
    //return date('Y-m-d').date('H:i:s');
    return date('Y-m-d H:i:s');
}

/**
 * 返回毫秒级的时间戳
 */
function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

/**
 * 生成GUID（UUID）
 * @access public
 * @return string
 * @author knight
 */
function createGuid()
{

    mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
    $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
//    $hyphen = chr ( 45 ); // "-"
    $uuid = '' . //chr(123)// "{"
        substr ( $charid, 0, 8 )  . substr ( $charid, 8, 4 )  . substr ( $charid, 12, 4 )  . substr ( $charid, 16, 4 )  . substr ( $charid, 20, 12 );
    //.chr(125);// "}"
    return $uuid;

}

/**
 * 函数名称：inject_check()
 * 函数作用：检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
 * 参　　数：$sql_str: 提交的变量
 * 返 回 值：返回检测结果，ture or false
 */
function inject_check($sql_str) {
    return preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str); // 进行过滤
}

/**
 * 过滤单引号及其他敏感符号
 * @param $sql_str
 * @return int
 */
function v_check($sql_str) {
    return preg_match('/\'|\/\*|\*|\.\.\/|\.\/|/', $sql_str); // 进行过滤
}
/**
 * 函数名称：verify_id()
 * 函数作用：校验提交的ID类值是否合法
 * 参　　数：$id: 提交的ID值
 * 返 回 值：返回处理后的ID
 */
function verify_id($id=null) {
    if (!$id) { exit('没有提交参数！'); } // 是否为空判断
    elseif (inject_check($id)) { exit('提交的参数非法！'); } // 注射判断
    elseif (!is_numeric($id)) { exit('提交的参数非法！'); } // 数字判断
    $id = intval($id); // 整型化

    return $id;
}

/**
 * 函数名称：str_check()
 * 函数作用：对提交的字符串进行过滤
 * 参　　数：$var: 要处理的字符串
 * 返 回 值：返回过滤后的字符串
 */
function str_check( $str ) {
    if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否打开
        $str = addslashes($str); // 进行过滤
    }
    $str = str_replace("_", "\_", $str); // 把 '_'过滤掉
    $str = str_replace("%", "\%", $str); // 把 '%'过滤掉

    return $str;
}

/**
 * 函数名称：input_str_check()
 * 函数作用：对提交的字符串进行过滤
 * 参　　数：$var: 要处理的字符串
 * 返 回 值：返回过滤后的字符串
 */
function input_str_check( $str ) {
    $str = str_replace("%", "", $str); // 把 '%'过滤掉
    $str = str_replace("/", "", $str); // 把 '%'过滤掉
    if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否打开
        $str = addslashes($str); // 进行过滤
        // return $str;
    }
//    $str = str_replace("\'", "\\\\\'", $str); // 把 '_'过滤掉

    //$str = str_replace("\"", "", $str); // 把 '"'过滤掉
    $str = str_replace("*", "", $str); // 把 '*'过滤掉*/
    return $str;
}

/**
 * 函数名称：post_check()
 * 函数作用：对提交的编辑内容进行处理
 * 参　　数：$post: 要提交的内容
 * 返 回 值：$post: 返回过滤后的内容
 */
function post_check($post) {
    if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否为打开
        $post = addslashes($post); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤
    }
    $post = str_replace("_", "\_", $post); // 把 '_'过滤掉
    $post = str_replace("%", "\%", $post); // 把 '%'过滤掉
    $post = nl2br($post); // 回车转换
    $post = htmlspecialchars($post); // html标记转换

    return $post;
}

/**
 * 检验upc 是否合法
 * @param $code
 * @return bool
 */
function checkUpcCode($code)
{
    if(strlen($code) != 13 ){
        show_json(100000,'code Through the long or Long for short');//长度过长
    }

    $j = 0;//奇数总和
    $o = 0;//偶数总和

    //奇数相加
    for($index=0;$index<strlen(substr($code,0,-1));$index=$index+2){

        if(!is_numeric($code[$index])){
            show_json(100000,'Values must be Numbers');//值必须是数字
        }
        $j = $j+intval($code[$index]);


    }

    //偶数数相加
    for($t=1;$t<strlen(substr($code,0,-1));$t=$t+2){
        if(!is_numeric($code[$t])){
            show_json(100000,'Values must be Numbers');//值必须是数字
        }
        $o = $o+intval($code[$t]);

    }

    $y_code = 10-substr($j+$o*3,-1);//得出校验吗

    if($y_code != substr($code,-1)){
        show_json(100000,'upc Format illegality');//upc 码格式不合法
    }

    return true;
}



/**
 * 验证手机格式
 * @param $mobile
 * @return bool
 */
function checkemail($email)
{

    //$pattern = "/([a-z0-9A-Z\\-_\\.]+@[a-z0-9A-Z]+\\.[a-z0-9A-Z\\-_\\.]+)+/i";//邮箱
    $pattern = Yii::$app->params['preg_match']['email'];

    if(!preg_match( $pattern, $email)){//验证手机格式
        show_json(100000,'Wrong data format');
    }

    return true;
}

/**
 * 验证手机格式
 * @param $mobile
 * @return bool
 */
function checkmobile($mobile)
{

    $pattern = "/^1[0-9]{10}$/";//手机

    if(!preg_match( $pattern, $mobile)){//验证手机格式
        show_json(100000,'Wrong data format');
    }

    return true;
}

/**
 * 加密函数
 * @param $txt
 * @param string $key
 * @return string
 */
function lock_encrypt($txt,$key='senseplay')
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $nh = rand(0,64);
    $ch = $chars[$nh];
    $mdKey = md5($key.$ch);
    $mdKey = substr($mdKey,$nh%8, $nh%8+7);
    $txt = base64_encode($txt);
    $tmp = '';
    $i=0;$j=0;$k = 0;
    for ($i=0; $i<strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = ($nh+strpos($chars,$txt[$i])+ord($mdKey[$k++]))%64;
        $tmp .= $chars[$j];
    }
    return urlencode($ch.$tmp);
}

/**
 * 解密函数
 * @param $txt
 * @param string $key
 * @return string
 */
function unlock_encrypt($txt,$key='senseplay')
{
    $txt = urldecode($txt);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
    $ch = $txt[0];
    $nh = strpos($chars,$ch);
    $mdKey = md5($key.$ch);
    $mdKey = substr($mdKey,$nh%8, $nh%8+7);
    $txt = substr($txt,1);
    $tmp = '';
    $i=0;$j=0; $k = 0;
    for ($i=0; $i<strlen($txt); $i++) {
        $k = $k == strlen($mdKey) ? 0 : $k;
        $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
        while ($j<0) $j+=64;
        $tmp .= $chars[$j];
    }
    return base64_decode($tmp);
}


/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20','',$string);
    $string = str_replace('%27','',$string);
    $string = str_replace('%2527','',$string);
    $string = str_replace('*','',$string);
    $string = str_replace('"','',$string);
    $string = str_replace("'",'',$string);
    $string = str_replace(';','',$string);
    $string = str_replace('<','&lt;',$string);
    $string = str_replace('>','&gt;',$string);
    $string = str_replace("{",'',$string);
    $string = str_replace('}','',$string);
    $string = str_replace('\\','',$string);
    return $string;
}

/**
 * 将数组转换为字符串
 *
 * @param	array	$data		数组
 * @param	bool	$isformdata	如果为0，则不使用new_stripslashes处理，可选参数，默认为1
 * @return	string	返回字符串，如果，data为空，则返回空
 */
function array2string($data, $isformdata = 1) {
    if($data == '' || empty($data)) return '';

    if($isformdata) $data = new_stripslashes($data);
    if (version_compare(PHP_VERSION,'5.3.0','<')){
        return addslashes(json_encode($data));
    }else{
        return addslashes(json_encode($data,JSON_FORCE_OBJECT));
    }
}

/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string) {
    if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,'utf-8');
    foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
    return $string;
}


/**
 * 获取远程图片并把它保存到本地, 确定您有把文件写入本地服务器的权限
 * @param string $content 文章内容
 * @param string $targeturl 可选参数，对方网站的网址，防止对方网站的图片使用"/upload/1.jpg"这样的情况
 * @return string $content 处理后的内容
 */
function grab_image($content, $targeturl = ''){
    preg_match_all('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/', $content, $img_array);
    $img_array = isset($img_array[1]) ? array_unique($img_array[1]) : array();

    if($img_array) {
        $path =  Yii::$app->params['upload_file'].'/'.date('Ym/d');
        $urlpath = $path;
        $imgpath =  ROOT__PATH.$path;
        if(!is_dir($imgpath)) @mkdir($imgpath, 0777, true);
    }

    foreach($img_array as $key=>$value){
        $val = $value;
        if(strpos($value, 'http') === false){
            if(!$targeturl) return $content;
            $value = $targeturl.$value;
        }
        $ext = strrchr($value, '.');
        if($ext!='.png' && $ext!='.jpg' && $ext!='.gif' && $ext!='.jpeg') return false;
        $imgname = date("YmdHis").rand(1,9999).$ext;
        $filename = $imgpath.'/'.$imgname;
        $urlname = $urlpath.'/'.$imgname;

        ob_start();
        readfile($value);
        $data = ob_get_contents();
        ob_end_clean();
        file_put_contents($filename, $data);

        if(is_file($filename)){
            $content = str_replace($val, $urlname, $content);
        }else{
            return $content;
        }
    }
    return $content;
}

/**
 * 获取内容中的图片
 * @param string $content 内容
 * @return string
 */
function match_img($content){
    preg_match('/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/', $content, $match);
    return !empty($match) ? $match[1] : '';
}

/**
 * 字符截取
 * @param $string 要截取的字符串
 * @param $length 截取长度
 * @param $dot	  截取之后用什么表示
 * @param $code	  编码格式，支持UTF8/GBK
 */
function str_cut($string, $length, $dot = '...', $code = 'utf-8') {
    $strlen = strlen($string);
    if($strlen <= $length) return $string;
    $string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
    $strcut = '';
    if($code == 'utf-8') {
        $length = intval($length-strlen($dot)-$length/3);
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif(194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif(224 <= $t && $t <= 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif(240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif(248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }
            if($noc >= $length) {
                break;
            }
        }
        if($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
        $strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
    } else {
        $dotlen = strlen($dot);
        $maxi = $length - $dotlen - 1;
        $current_str = '';
        $search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
        $replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
        $search_flip = array_flip($search_arr);
        for ($i = 0; $i < $maxi; $i++) {
            $current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            if (in_array($current_str, $search_arr)) {
                $key = $search_flip[$current_str];
                $current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
            }
            $strcut .= $current_str;
        }
    }
    return $strcut.$dot;
}



/**
 * 设置 cookie
 * @param string $name     变量名
 * @param string $value    变量值
 * @param int $time    过期时间
 */
function set_cookie($name, $value = '', $time = 0) {
    $time = $time > 0 ? SYS_TIME + $time : $time;
    $name = Yii::$app->params['cookie_pre'].$name;
    $value = is_array($value) ? 'in_senseplayphp'.string_auth(json_encode($value)) : string_auth($value);
    setcookie($name, $value, $time, Yii::$app->params['cookie_path'], Yii::$app->params['cookie_domain'], Yii::$app->params['cookie_secure']);
    $_COOKIE[$name] = $value;
}


/**
 * 字符串加密/解密函数
 * @param	string	$txt		字符串
 * @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
 * @param	string	$key		密钥：数字、字母、下划线
 * @param	string	$expiry		过期时间
 * @return	string
 */
function string_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key != '' ? $key : Yii::$app->params['auth_key']);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(strtr(substr($string, $ckey_length), '-_', '+/')) : sprintf('%010d', $expiry ? $expiry + SYS_TIME : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - SYS_TIME > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.rtrim(strtr(base64_encode($result), '+/', '-_'), '=');
    }
}



/**
 * 获取 cookie
 * @param string $name     	  变量名，如果没有传参，则获取所有cookie
 * @param string $default     默认值，当值不存在时，获取该值
 */
function get_cookie($name = '', $default = '') {
    if(!$name) return $_COOKIE;
    $name = Yii::$app->params['cookie_pre'].$name;
    if(isset($_COOKIE[$name])){
        if(strpos($_COOKIE[$name],'in_senseplayphp')===0){
            $_COOKIE[$name] = substr($_COOKIE[$name],9);
            return json_decode(MAGIC_QUOTES_GPC?stripslashes(string_auth($_COOKIE[$name],'DECODE')):string_auth($_COOKIE[$name],'DECODE'), true);
        }
        return string_auth(safe_replace($_COOKIE[$name]),'DECODE');
    }else{
        return $default;
    }
}


/**
 * 删除 cookie
 * @param string $name     变量名，如果没有传参，则删除所有cookie
 */
function del_cookie($name = '') {
    if(!$name){
        foreach($_COOKIE as $key => $val) {
            setcookie($key, '', SYS_TIME - 3600, Yii::$app->params['cookie_path'], Yii::$app->params['cookie_domain'], Yii::$app->params['cookie_secure']);
            unset($_COOKIE[$key]);
        }
    }else{
        $name = Yii::$app->params['cookie_pre'].$name;
        if(!isset($_COOKIE[$name])) return true;
        setcookie($name, '', SYS_TIME - 3600, Yii::$app->params['cookie_path'], Yii::$app->params['cookie_domain'], Yii::$app->params['cookie_secure']);
        unset($_COOKIE[$name]);
    }
}

/**
 * 将字符串转换为数组
 *
 * @param	string	$data	字符串
 * @return	array	返回数组格式，如果，data为空，则返回空数组
 */
function string2array($data) {
    $data = trim($data);
    if($data == '') return array();

    if(strpos($data, '{\\')===0) $data = stripslashes($data);
    $array=json_decode($data,true);
    return $array;
}

/**
 *  提示信息页面跳转
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址,stop为停止
 * @param     int     $limittime  限制时间
 * @return    void
 */
function showmsg($msg, $gourl='', $limittime=3) {
    $gourl = empty($gourl) ? HTTP_REFERER : $gourl;
    $stop = $gourl!='stop' ? false : true;
    include(ROOT_PATH.'tpl'.DIRECTORY_SEPARATOR.'message.tpl');
}

/**
 * 设置导航html输出内容
 * @param $p_title
 * @param string $d_title
 * @param string $o_text  注意三级内容 可以自己拼接更多的自定义标签，用／分开
 */
function setNavHtml($p_title,$d_title = '',$o_text = '')
{
    Yii::$app->params['p_title'] = $p_title;

    if($d_title){
        Yii::$app->params['d_title'] = $d_title;
    }

    if($o_text){
        Yii::$app->params['o_text'] = $o_text;
    }
}

/**
 * 输出导航标签内容
 * @return string
 */
function getNavHtml()
{
    $str = '';

    $p_title = isset(Yii::$app->params['p_title'])?Yii::$app->params['p_title']:'';//一级标签

    if($p_title) $str .= $p_title;

    $d_title = isset(Yii::$app->params['d_title'])?Yii::$app->params['d_title']:'';//二级标签

    if($d_title) $str .= $d_title;

    $o_text = isset(Yii::$app->params['o_text'])?Yii::$app->params['o_text']:'';//三级标签

    if($o_text) $str .= $o_text;

    return $str;

}


/**
 * 返回数组中指定多列
 *
 * @param  Array  $input       需要取出数组列的多维数组
 * @param  String $column_keys 要取出的列名，逗号分隔，如不传则返回所有列
 * @param  String $index_key   作为返回数组的索引的列
 * @return Array
 */
function array_columns($input, $column_keys=null, $index_key=null){
    $result = array();

    $keys =isset($column_keys)? explode(',', $column_keys) : array();

    if($input){
        foreach($input as $k=>$v){

            // 指定返回列
            if($keys){
                $tmp = array();
                foreach($keys as $key){
                    $tmp[$key] = $v[$key];
                }
            }else{
                $tmp = $v;
            }

            // 指定索引列
            if(isset($index_key)){
                $result[$v[$index_key]] = $tmp;
            }else{
                $result[] = $tmp;
            }

        }
    }

    return $result;
}

/**
 * 数组分页函数  核心函数  array_slice
 * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
 * $count   每页多少条数据
 * $page   当前第几页
 * $array   查询出来的所有数组
 * order 0 - 不变     1- 反序
 */

function page_array($page,$count,$array,$order){

    global $countpage; #定全局变量
    $page=(empty($page))?'1':$page; #判断当前页面是否为空 如果为空就表示为第一页面

    $start=($page-1)*$count; #计算每次分页的开始位置
    if($order==1){
        $array=array_reverse($array);
    }
    $totals=count($array);

    $countpage=ceil($totals/$count); #计算总页面数

    $pagedata=array();

    $pagedata=array_slice($array,$start,$count);

    return $pagedata;  #返回查询数据
}

/**
 * 只记录token
 * 设置全局cookie 维持客户端用户登陆状态
 */
function setCookie_token($token)
{
    setcookie(Yii::$app->params['access_token_name'],$token,time()+3600*24*30,'/');
}

function getCookie_token()
{
    return isset($_COOKIE[Yii::$app->params['access_token_name']]) ? $_COOKIE[Yii::$app->params['access_token_name']] : '';
}

/**
 * combineURL
 * 拼接url
 * @param string $baseURL   基于的url
 * @param array  $keysArr   参数列表数组
 * @return string           返回拼接的url
 */
function combineURL($baseURL,$keysArr){
    /*$combined = $baseURL."&";*/
    $combined = $baseURL."?";
    $valueArr = array();

    foreach($keysArr as $key => $val){
        $valueArr[] = "$key=$val";
    }

    $keyStr = implode("&",$valueArr);
    $combined .= ($keyStr);

    return $combined;
}

function str_rand($length=32,$characters='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
    if(!is_int($length)||$length<0){
        return false;
    }
    $characters_length=strlen($characters)-1;//因为字符串的顺序是从0开始的，下面的mt_rand()从0开始取，所以最后一个字符串要取到就只能减一
    $string='';
    for($i=$length;$i>0;$i--){
        $string.=$characters[mt_rand(0,$characters_length)];
    }

    return $string;
}

function session_to()
{
    if(!session_id()){
        @session_start();//start session
    }
}
?>