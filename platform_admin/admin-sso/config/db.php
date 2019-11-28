<?php

return [
    'db'=>[
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=rbac',
        'username'=>'root',
        'password'=>'Aa123.321aA',
        'charset'=>'utf8'
    ],
    'rbacDb'=>[
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=rbac',
        'username'=>'root',
        'password'=>'Aa123.321aA',
        'charset'=>'utf8'
    ],
    //redis
    //13库用作管理后台授权使用  1 是sdk授权
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname'=>'redis.db',
        'port' => 6379,
        'database' => 13,
        'password'=>'123456',
    ],

];
