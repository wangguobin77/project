<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/5/28
 * Time: 上午11:33
 */

return [
    'yar_server_address' => 'http://test.adminsso.senseplay.cn/',//服务器地址
    'OAUTH_REDIRECT_URI'=> 'http://test.a.senseplay.cn/ota/web/index.php?r=client/oauth',//回掉地址
    'UCENTER_OAUTH_URL' => 'http://test.adminsso.senseplay.cn/oauth/token',//验证服务器 token
    'oauth_login_url' => 'http://test.adminsso.senseplay.cn/oauth/authorize',//登陆地址
    // 定义下载地址及域名
    'ota_url'=>'http://test.cloud.senseplay.cn/',
    'static_ota_json_url' => 'http://static.senseplay.cn',//cdn的反问地址
];