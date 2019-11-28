<?php

return [
    'oauth_login_url' => 'http://test.adminsso.senseplay.cn/oauth/authorize',//登陆页面访问地址
    'adminEmail' => 'admin@example.com',
    'language_all' => [
        'en',//英文
        'zh'//中文
    ],
    'errorCode' => [
        100001 => 'please again login',//请重新登陆
        100002 => 'oauth state parameter not legal',//oauth state传值为空或者不合法
        100003 => 'appId parameter not legal',//申请的appid 传值为空活着不合法
        100004 => 'appSecret parameter not legal',//申请的appSecret 传值为空活着不合法
        100005 => 'user information is not legal',//用户信息不合法
        100006 => 'please apply for APP_ID',//缺少参数 appId
        100007 => 'please apply for APP_SECRET',//缺少参数 APP_SECRET 
        100008 => 'APP_ID and APP_SECRET information illegal',//信息不合法 请确认信息 没有请者申请
        100009 => 'refresh access_token be defeated',//信息不合法 请确认信息 没有请者申请
        100010 => 'please log in again if you log in',//登陆异常请重新登陆
        100011 => 'missing parameter access_token',//缺少参数
        100012 => 'access_token invalid can\'t get openid',//缺少参数
        100013 => 'user openid can not be empty',//openid 不能为空
        100014 => 'user information does not exists',//db 用户信息不存在
        100015 => 'refresh token missing parameter',//调用刷新 token 接口时缺少必要参数
        100016 => 'app-config parametric discordance',//申请的 app配置有关信息不合法
        100017 => 'access_token past due Or illegal',//access_token 过期或者不合法
        100018 => 'code invalid char',//授权code码有误
    ],
    //加载定义的模版
    'viewType' => [
        1 => 'default',//默认
    ],
    //目前支持的多语言配置
    'language' => [
        'en'=>'english',//英文
        'zh'=>'简体中文'//中文
    ],
    //验证正则
    'preg_match' => [
        'email' => '/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,8})$/',
        'mobile' => '/^1[0-9]{10}$/'
    ],
    /*每个时间段只允许相同ip操作次数 ip限制*/
    'limitipnum' => 100,
    'limitTime' => 3600,//注册限制时间段 以秒计算
    'regloglimittime' => 86400,//注册ip脏数据 过期时间
    'rpc'=>[//rpc 模式
        'secretKey' => 'senseplay',// 加密key值
    ],
    //rbac 权限放行路由
    'allowUrl' =>
        [
//            'index/main'
        ],
    'find_pwd_url'=>'http://test.a.senseplay.cn'

];
