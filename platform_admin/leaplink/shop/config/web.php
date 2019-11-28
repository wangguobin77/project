<?php
$redis = require(__DIR__ . '/param_ex.php');
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$params = array_merge($params, $redis);

$config = [
    'id' => 'shop',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
   /* 'language' => 'zh',*/
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'a1xuWIetD07CiiDLIxzAGaUOFQ8Yuizx',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'shop' => $db['shop'], //商户数据库配置
        'coupon' => $db['coupon'], //优惠券数据库配置
        'broadcast' => $db['broadcast'], //广告数据库配置
        'account' => $db['account'], //用户数据库配置
        'redis14' =>$db['redis14'],//redis13数据库(老大说来客服务端使用 13)
        'redis' => [
            'class' => 'yii\redis\Connection'
        ],

//        'urlManager' => [
//            'enablePrettyUrl' => false,
//            'showScriptName' => false,
//            'rules' => [
//            ],
//        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
