<?php
return [
    //redis使用模式  1:单例模式 ...todo 留下扩展-- 2:哨兵模式 3:集群模式
    'redis_mod' => 1,

    //单例模式 redis 配置
    'singleton_redis' => [
        'host'=>'106.75.122.206',
        'port' => 6379,
        'db' => 11,
        'pwd'=>'HelloSenseThink',
    ],
];