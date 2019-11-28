<?php

return [
    'yar_server_address' => 'http://test.adminsso.senseplay.cn',//服务器地址
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
    'OAUTH_REDIRECT_URI'=> 'http://test.a.senseplay.cn/car_param/web/index.php?r=client/oauth',//回掉地址
    'UCENTER_OAUTH_URL' => 'http://test.adminsso.senseplay.cn/oauth/token',//验证服务器 token
    'app_id' => '0DEEBF5F9570F5AC07DF7F82C8836EA9',//项目的appid
    'app_key' => '0B9D494D5818DE8358530156F40CE735',//项目的key
    'oauth_login_url' => 'http://test.adminsso.senseplay.cn/oauth/authorize',//登陆地址
    'default_page_size' =>  10,

    'errorCode'=>[
        0 => 'success',//正确返回统一
        100000  => 'BE_DEFEATED',//web错误返回统一
        100001  => 'Network error. Please try again.', // 网络出错，请重试', //
        100002  =>  'PARAMETER_CNNAME_ERROR', //参数中文名称要在1-128个字符之间
        100003  =>  'The parameter name cannot be empty.', // 参数名不能为空',
        100004  =>  '', // 参数中文名称不能超过100个字符',
        100005  =>  'The parameter name should not exceed 128 characters.', // 参数名不能超过100个字符',
        100006  =>  'PARAMETER_NAME_ERROR', // 参数名不能为空',
        100007  =>  'PARAMETER_VALUE_ERROR', // 参数值不能超过100个字符',

        100008  =>  'The parameter name currently submitted already exists.', // 当前提交的参数名已存在',
        100009  =>  'EXISTING_FILE_CANNOT_BE_DELETED', // 该分类下存在已经关联的 ini 文件,不能删除',
        100014  =>  'SUBCATEGORIES_CANNOT_BE_DELETED', // 该分类下存在子分类,不能删除',
        100010  =>  'PARENT_CATEGORY_NOT_EXISTS', // 该分类的父级分类不存在',
        100011  =>  'Classification names should not be repeated.', // 分类名不能重复',
        100012  =>  'ID can not be empty.', // Id不能为空',
        100013  =>  'CATEGORY_NOT_EXISTS', // 分类不存在',
        100015  =>  'INI_FILE_CATEGORY_ERROR', //ini文件必须关联顶级分类,

        120002  =>  'INI_FILE_NAME_ERROR', // 文件名称不能超过100个字符',
        120004  =>  'INI_FILE_DESC_ERROR', // 文件名称不能超过200个字符',
        120005  =>  'INI_FILE_CONTENT_ERROR', // ini文件内容不能为空',
        120007  =>  'INI_FILE_NOT_EXISTS', // ini文件不存在',

        120001  =>  'The name of the file can not be empty.', // 文件名称不能为空',
        120003  =>  'INI file description can not be empty.', // ini文件描述不能为空',
        120006  =>  'INI file ID can not be empty.', // ini文件ID不能为空',
        120008  =>  'Please select parameters first.', // 请先选择参数',
        120009  =>  'Option can not be empty.', // xxx 选项不能为空
//        120010  =>  'INI files are associated with categories and cannot be modified or deleted.', // ini文件跟分类存在关联，不能删除',
        120010  =>  'ini文件与分类相关联，无法修改或删除', // ini文件跟分类存在关联，不能删除',
        120011  =>  'There is already the same INI file association, and can not be added repeatedly.', // 已经存在相同ini文件关联，不能重复添加',
        120012  =>  'HASH_EXISTS', //hash值已存在
        120013  =>  'RELATION_NOT_EXISTS', //关联不存在
        120014  =>  'PARAM_REQUIRED', //参数必选


        110001  =>  'Add fail', // 添加失败',
        110003  =>  'Edit fail', // 修改失败',
        110005  =>  'Delete fail', // 删除失败',
        110007  =>  'operation failed', // 操作失败',

    ]
];
