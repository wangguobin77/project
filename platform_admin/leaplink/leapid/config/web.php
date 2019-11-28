<?php

$redis = require(__DIR__ . '/param_ex.php');
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/url_config.php')
);
$db = require(__DIR__ . '/db.php');

$params = array_merge($params, $redis);

Yii::setAlias('@common', '../common');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'zheng',
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
        'leapid' => $db['leapid'],
        'redis' => [
            'class' => 'yii\redis\Connection'
        ],
        'redis1' => $params['redis1'],

        //生产队列,需要插入 mysql redis 生成bin文件(老大说分开做,三个插入互相不影响)
        'leapidProductQueue'=> [   //leapid 生产队列
            'class'=> \yii\queue\redis\Queue::class,
            'redis'=>'redis1',// Redis connection component or its config
            'channel'=>'queue:leapid',// Queue channel key queue在redis中key的前缀
            'as log'=> \yii\queue\LogBehavior::class,

        ],

    ],
    'params' => $params,

    'defaultRoute' => 'app'
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
