<?php

return [
    'yar_server_address' => 'http://test.adminsso.senseplay.cn/',//服务器地址
    'access_token_name' => 'global_access_token',//cookie 存储的token名称
    'app_keymap_name' => 'icloud',//当前项目的名称 用生成json文件的路径使用 注意不同环境 名称会不同，要替换
    'mobileauthqm' => '感悟科技',//短信验证码签名
    //rbac 权限放行路由
    'allowUrl' =>
        [
//            'index/main'
        ],
    'language_all' => [
        'en',   //英文
        'zh',   //中文
    ],
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'OAUTH_REDIRECT_URI'=> 'http://test.a.senseplay.cn/rbac/web/index.php?r=client/oauth',//回掉地址
    'UCENTER_OAUTH_URL' => 'http://test.adminsso.senseplay.cn/oauth/token',//验证服务器 token
    'app_id' => '523D3B1F64BE05AFD50DE46FCE34297A',//项目的appid
    'app_key' => '1737F9FB40AFDB0F9F309104B1CA77C0',//项目的key
    'oauth_login_url' => 'http://test.adminsso.senseplay.cn/oauth/authorize',//登陆地址
];
