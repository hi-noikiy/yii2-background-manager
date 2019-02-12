<?php
/**
 * User: SeaReef
 * Date: 2018/6/8 18:42
 * 内网测试服reids配置
 */
return [
    //    平台主配置redis -- 禁止使用select指向database
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',//10.10.20.77
        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],
    'redis_1' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',//10.10.20.77
        'port' => 6379,
        'database' => 1,
        'password' => '123456',
    ],
    // 平台主配置redis
    'redis_2' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 2,
        'password' => '123456'
    ],
    // 平台主配置redis
    'redis_3' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 3,
        'password' => '123456'
    ],
    // 平台主配置redis
    'redis_4' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 4,
        'password' => '123456'
    ],
    // 平台主配置redis
    'redis_5' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 5,
        'password' => '123456'
    ],
    // 平台主配置redis
    'redis_6' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 5,
        'password' => '123456'
    ],

    //    游戏redis设置
    'game_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 0,
        'password' => '123456'
    ],

    'platform_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.107',
        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],
    'platform_redis_3' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.107',
        'port' => 6379,
        'database' => 3,
        'password' => '123456',
    ],

    //    游戏gate服务器redis
    'gate_redis1' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],

    'gate_redis2' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],
    //游戏开发redis
    'game_dev_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],
    'game_dev_redis_2' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 2,
        'password' => '123456',
    ],

    // 平台代理相关计算类型的写redis配置
    'daili_redis_write' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 6,
        'password' => '123456'
    ],

    // 平台代理相关计算类型的写redis配置
    'daili_redis_read_00' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.10.20.77',
        'port' => 6379,
        'database' => 6,
        'password' => '123456'
    ],

];