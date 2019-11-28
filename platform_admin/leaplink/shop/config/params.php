<?php
return [
    'cache_key_prefix' => [ //缓存 key 前缀
        'register_phone_code' => 'leap:shop:register:__code__',  //商户注册手机验证码
        'findpwd_phone_code' => 'leap:shop:findpwd:__code__',  //商户找回密码手机验证码
        'findpwd_phone_token' => 'leap:shop:findpwd:__token__',  //商户找回密码token

        'phone_code_repeat_key' => 'leap:shop:phone:__repeat__', //重复发送验证码时间间隔key

        'shop_category_key' => 'leap:shop:category',  //商户类别缓存

        '__session__' => 'leap:shop:session:__session__',  //商户 session

        'shop_info' => 'leap:shop:info:__cache__',  //商户 cache


        'pending_broadcast_list' => 'likeit:list:shop:', //待发广告队列
    ],
    'cache_code' => [
        'ttl' => 3000, //验证码生存时间5分钟
        'length' => 6, //验证码长度
        'phone_code_repeat_time' => 60, //重复发送验证码时间间隔
    ],

    'redis_session_timeout' => 7*24*3600, //会话生存时间
    'sessionName' => 'appToken',  //sessionId在 cookie 中的键名


    'app_index' => '/shop/welcome',  //网站入口

    /** 手机短信模板 */
    'mobile_template' => [
        1 => '注册验证码：%s。您正在注册来客系统。验证码有效期 5 分钟，请尽快完成注册。【感悟科技】',
        2 => '验证码：%s。您正在找回密码。验证码有效期 5 分钟，为了您的账户安全，请勿泄露给他人。【感悟科技】',
    ],
    'broadcast_status' => [
        '未审核',
        '未推送',
        '已推送',
         '下架'
    ],
    //星期几
    'week_arr' => [
        "周日",
        "周一",
        "周二",
        "周三",
        "周四",
        "周五",
        "周六"
    ]

];
