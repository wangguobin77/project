<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');
Yii::setAlias('@common', '../common');
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
   /* 'language' => 'zh',*/
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zheng',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',

            ],

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
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'db' => $db['db'],
        'rbacDb' => $db['rbacDb'],
        'redis_3' => $db['redis_3'],
        /*'urlManager' => [
             'enablePrettyUrl' => true,
             'showScriptName' => false,
             'rules' => [
                 'test' => 'test/test',
                 'check_ver' => 'ota/check_ver',
                 'PUT,PATCH test/<id>' => 'user/update',
                 'DELETE test/<id>' => 'user/delete',
                 'GET,HEAD test/<id>' => 'user/view',
                 'POST test' => 'user/create',
                 'GET,HEAD test' => 'user/index',
                 'test/<id>' => 'user/options',
                 'test' => 'user/options',
            ],
        ],*/
        //todo 多语言
       /* 'i18n' => [
            'translations' => [
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    // 'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],

                'db' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    // 'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'db' => 'db.php',
                        'db/error' => 'error.php',
                    ],
                ],

            ],
        ],*/

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
