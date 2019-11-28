<?php

return [

    'access_token_name' => 'global_access_token',//cookie 存储的token名称
    'app_keymap_name' => 'icloud',//当前项目的名称 用生成json文件的路径使用 注意不同环境 名称会不同，要替换
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

    'errorCode'=>[//code 码
        0 => 'success',//正确返回统一
        100000 => 'BE_DEFEATED',//web错误返回统一
        100001 => 'USERINFO_NOT_FOUND', //用户信息不存在
        100002 => 'OPENID_NOT_FOUND', //                        | 用户openid不存在
        100003 => 'USER_DEVICE_BIND_EXIST',//               | 用户与device绑定关系已存在
        100004 => 'NO_LOGIN',//                             | 没有登陆过
        100005 => 'PLEASE_LOGIN',//                             | 请登陆
        100010 => 'SN_RELEVANCE_FACILITY_NONENTITY',//      | 设备信息不存在
        100011 => 'GAIN_ACCESSCODE_BE_DEFEATED',//          | device access_code 获取失败
        100012 => ' GAIN_CLIENT_ID_BE_DEFEATED',//          | client_id 获取失败
        100013 => 'GAIN_CLIENT_SECRET_BE_DEFEATED',//       | client_secret 获取失败
        100014 => 'UNBIND_DEVICE_BE_DEFEATED',//            | 用户解绑device 失败
        100015 => 'POST_DATA_NOT_FOUNT',//              | 无post数据
        100016 => 'SET_LANGUAGE_BE_DEFEATED',//             | 设置语言失败
        100017 => 'NO_SELECT_LANGUAGE',//           | 没有可选语言
        //
        100201 => 'PARAM_CATEGORY_ID_DEFICIENCY',//          | device 终端大类id 错误或者缺失
        100202 =>  'REMOTE_TYPE_ID_NOT_FOUND',//             |  remote_type_id错误或者缺失
        100203 =>  'RC_EVENT_TYPE_NOT_FOUND ',//             |  rc_event_type错误或者缺失
        100204 =>  'CATEGORY_ID_NOT_FOUND   ',//             |  category_id错误或者缺失
        100205 =>  'JUDGE_TYPE_NOT_FOUND    ',//             |  judge_type错误或者缺失
        100206 =>  'COMMAND_NOT_FOUND       ' ,//            |  COMMAND错误或者缺失 必传参数 命令作用
        100207 =>  'KEYMAP_TYPE_NOT_FOUND   ',//              |  KEYMAP_TYPE错误或者缺失 必传参数 驱动方式
        100208 =>  'EVENT_NOT_FOUND         ',//             |  EVENT错误或者缺失 必传参数 按键的事件
        100209 =>  'CONDITIONS_BEYOND_CONFIG_MAX_NUM',//              |  keymap 编辑条件超出配置最大数
        100210 =>  'PARAMS_BEYOND_CONFIG_MAX_NUM',//              |  keymap 参数条件超出配置最大数
        100211 =>  'KEYMAP_CONFIG_NOT_FOUND        ',//             |  当前keymapid 下没有配置项
        100212 =>  'KEYNAP_ID_NOT_FOUND        ',//             |  keymap id 参数不存在或者错误

        100213 =>  'ADD_KEYMAPDATA_BE_DEFEATED        ',//             |  添加keymap配置失败
        100214 =>  'GAIN_KEYMAP_VERSION_BE_DEFEATED        ',//             |  创建keymap时获取版本失败
        100215 =>  'MUST_BE_CONFIGURED        ',//             |  必须配置condition条件
        100220 => 'LOGIN ERROR OR PWD ERRER',//登陆错误
        100221 => 'ADMIN USER NO HAVE',//后台用户不存在
        100222 => 'USER INFO HAS BEEN SEALED',//后台用户不存在

        //2000
        102000 =>  'ILLEGAL_PARAMS',                         //  |参数非法
        102001 =>  'CATEGORY NAME_EN IS ALREADY EXISTS',//      |device大类的name_en已存在
        102002 =>  'CATEGORT IS NOT EXISTS',//                  |device大类不存在
        102003 =>  'CATEGORT NAME IS ILLEAGL',//                  |device大类name不合法
        102004 =>  'CATEGORT NAME_EN IS ILLEAGL',//                  |device大类name_en不合法
        102005 =>  'CATEGORT KEY IS ILLEAGL',//                  |device大类key不合法
        102006 =>  'CATEGORT CODE IS ILLEAGL',//                  |device大类code不合法
        102007 =>  'CATEGORT KEY IS ALREADY EXISTS',//                  |device大类key已存在
        102008 =>  'CATEGORT CODE IS ALREADY EXISTS',//                  |device大类code已存在

        102009 =>  'THE TWO PASSWORDS DIFFER',//                  |两次密码输入不一致
        102010 =>  'MANUFACTURE NAME IS ILLEAGL',//                  |厂商名不合法
        102011 =>  'MANUFACTURE NAME_EN IS ILLEAGL',//                  |厂商name_en不合法
        102012 =>  'MANUFACTURE LOGIN_NAME IS ILLEAGL',//                  |厂商登录名不合法
        102013 =>  'MANUFACTURE PASSWORD CAN NOT BE EMPTY',//                  |密码不能为空
        102014 =>  'MANUFACTURE PASSWORD IS ILLEAGL',//                  |密码不合法
        102015 =>  'MANUFACTURE ADDRESS IS TOO LONG',//                  |厂商地址过长
        102016 =>  'MANUFACTURE LOGIN_NAME IS ALREADY EXISTS',//                  |厂商登录名已存在
        102017 =>  'MANUFACTURE NAME_EN IS ALREADY EXISTS',//                  |厂商name_en已存在
        102018 =>  'MANUFACTURE IS NOT EXISTS',//                  |厂商不存在
        102019 =>  'MANUFACTURE TEL_PHONE IS ILLEAGL',//                  |厂商不存在
        102020 =>  'MANUFACTURE LINK_MAN IS ILLEAGL',//                  |厂商不存在

        102060 =>  'REMOTE_TYPE_NAME_EN_IS_ALREADY_EXISTS',//                  |遥控器系列name_en已存在
        102061 =>  'REMOTE_TYPE_IS_NOT_EXISTS',//                  |遥控器系列不存在
        102062 =>  'REMOTE_TYPE_NAME_IS_ILLEAGL',//                  |遥控器系列name不合法
        102063 =>  'REMOTEYPE_NAME_EN_IS_ILLEAGL',//                  |遥控器系列name_en不合法
        102064 =>  'REMOTE_TYPE_TYPE_ILLEAGL',//                  |遥控器系列type不合法
        102065 =>  'REMOTEYPE_TYPE_EN_IS_ILLEAGL',//                  |遥控器系列type_en不合法
        102066 =>  'MANUFACTURE_IS_ILLEAGL',//                  |厂商不合法或未选择
        102067 =>  'CARRY_TYPE_CODE_IS_ILLEAGL',//                  |carry_type_code不合法
        102068 =>  'CARRY_TYPE_IS_ILLEAGL',//                  |carry_type不合法
        102069 =>  'SCREEN_CODE_IS_ILLEAGL',//                  |screen_code不合法
        102070 =>  'SCREEN_IS_ILLEAGL',//                  |screen不合法
        102071 =>  'CODE_IS_ILLEAGL',//                  |code不合法
        102072 =>  'KEY_IS_ILLEAGL',//                  |key不合法

        102080 =>  'DEVICE TYPE TYPE ILLEAGL',//                  |device系列type不合法
        102081 =>  'DEVICE TYPE TYPE_EN ILLEAGL',//                  |device系列type_en不合法
        102082 =>  'DEVICE TYPE NAME ILLEAGL',//                  |device系列name不合法
        102083 =>  'DEVICE TYPE NAME_EN ILLEAGL',//                  |device系列name_en不合法
        102084 =>  'DEVICE TYPE NAME_EN IS ALREADY EXISTS',//        |device系列name_en已存在
        102085 =>  'DEVICE IS NOT EXISTS',//                  |device系列不存在

        //3000
        103001 =>  'EMAIL IS ILLEAGL',      //邮箱不合法
        103002 =>  'MOBILE OR TEL-PHONE IS ILLEAGL',    //手机或者电话验证不合法
        103003 =>  'WEBSITE IS ILLEAGL',    //网站不合法
    ],
    'official_path' => 'keymap/files/keymap/official/',//正式版本 json数据目录
    'remote_official_path' => '/data/www/platform_api/keymap/public/keymap/files/keymap/official/',//远程同步文件目录
    'beta_path' => 'keymap/files/keymap/beta/',//beta目录
    'remote_beta_path' => '/data/www/platform_api/keymap/public/keymap/files/keymap/beta/',//beta 远程目录文件
];
