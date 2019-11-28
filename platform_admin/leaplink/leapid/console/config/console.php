<?php
$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/param_ex.php')
);
$db = require __DIR__ . '/db.php';
$config = [
    'id' => 'basic-console',
    'basePath' => dirname(dirname(__DIR__)),
    'bootstrap' => ['log', 'leapidProductQueue'],
    'controllerNamespace' => 'app\console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
    'components' => [
        'log' => [
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

        'leapidProductQueue'=> [
            'class'=> \yii\queue\redis\Queue::class,
            'redis'=>'redis1',// Redis connection component or its config
            'channel'=>'queue:leapid',// Queue channel key queue在redis中key的前缀
            'as log'=> \yii\queue\LogBehavior::class,
        ],

    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}
return $config;