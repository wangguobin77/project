<?php

return [
    'shop' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.90.35;dbname=shop',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
    'coupon' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.90.35;dbname=coupon',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
    'broadcast' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.90.35;dbname=broadcast',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
    'account' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=192.168.90.35;dbname=account',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
    'redis14' => [
        'class' => 'yii\redis\Connection',
        'hostname'=>'redis.db',
        'port' => 6379,
        'database' => 14,
        'password'=>'HelloSenseThink',
    ],
];
