<?php

return [
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
    'app_id' => '523D3B1F64BE05AFD50DE46FCE34297A',//项目的appid
    'app_key' => '1737F9FB40AFDB0F9F309104B1CA77C0',//项目的key
    'sso_login_guid_limittime' => 2592000,//有效期 3天 sso 登陆 redis缓存用户信息 30天
    'prix_email_pad_key' => 'pst_pad_email:',// + userid 填补邮箱时 发送邮箱验证链接 必须缓存邮箱 前端会更新邮箱的值
    'prix_email_pad_key_limittime' => 259200,//有效期 3天 填补邮箱时 发送邮箱验证链接 必须缓 存邮箱 前端会更新邮箱的值
    'prixuseremailguid' => 'pst_emailverifycode:',//邮箱验证guid key + userid 18位
    'prixuseremailcode_limittime' => 120,//有效期 30分钟 //邮箱激活的code 存活时间
    'regverifycodekey' => 'global_imgverifycode:',//图形验证码前缀+sessionid
    'prixusermobilekey' => 'global_smsverifycode:',// 手机短信验证码 缓存值 key 设置有效期 + 手机号码
    'regverifycodelimittime' => 900,//默认图形验证码存活时间15分钟
    /*每个时间段只允许相同ip操作次数 ip限制*/
    'limitipnum' => 100,
    'limitTime' => 3600,//注册限制时间段 以秒计算
    'regloglimittime' => 86400,//注册ip脏数据 过期时间
    'mobileauthcode' => 300, //手机验证码过期时间
    'mobileauthqm' => '感悟科技',//短信验证码签名
    //手机注册 start 短信验证码限制条件配置
    'authcodeconf'=>[
        'mobilelimit' => 3,//在规定时间内 同一个手机号码 接受 验证码次数
        'timelimit' => 3600,//限制规定时间 秒计算 默认 1小时 可以接受3次
    ],
    //调用用户信息的权限设置 仅供内部使用 限制ip
    'get_userinfo_limit_ip' =>[
        '192.168.90.240',
        '192.168.90.241',
        '127.0.0.1'
    ],
    //redis key 前缀 需要 + 用户手机号码
    'userauthcodekey' => 'global_smslimit:',//每个用户 接受验证码记录的log key 此key 设置有限期 判断同一个手机限制条件 会用到 此key
    'userauthcodekey_expire_time' => 86400,//key 过期时间 秒计算 每个用户接受验证码的 log记录
    // 验证码 end
];
