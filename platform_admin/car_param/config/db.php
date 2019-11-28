<?php

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=car_param',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
    //redis
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname'=>'redis.db',
        'port' => 6379,
        'database' => 3,
        'password'=>'123456',
    ],
];
