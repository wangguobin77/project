<?php

return [
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=hardware',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
    'adminLog' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=adminLog',
        'username'=>'root',
        'password'=>'root',
        'charset' => 'utf8',
    ],
];
