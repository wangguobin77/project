<?php

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=hardware',
        'username'=>'root',
        'password'=>'Aa123.321aA',
        'charset' => 'utf8',
    ],
    //权限管理数据库
    'rbacDb' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=rbac',
        'username'=>'root',
        'password'=>'Aa123.321aA',
        'charset' => 'utf8',
    ],
    //redis
   /* 'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname'=>'redis.db',
        'port' => 6379,
        'database' => 1,
        'password'=>'123456',
    ],*/

];
