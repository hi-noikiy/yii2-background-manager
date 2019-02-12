<?php
/**
 * User: SeaReef
 * Date: 2018/6/8 18:42
 *
 * 外网测试服redis配置
 */
return [
//    平台主配置redis
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],
    'redis_1' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 1,
        'password' => '123456',
    ],
    'redis_2' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 2,
        'password' => '123456',
    ],
    'redis_3' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 3,
        'password' => '123456',
    ],
    'redis_4' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 4,
        'password' => '123456',
    ],
    'redis_5' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 5,
        'password' => '123456',
    ],
    'redis_6' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 6,
        'password' => '123456',
    ],
    'redis_7' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 7,
        'password' => '123456',
    ],
    'redis_8' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 8,
        'password' => '123456',
    ],

//    游戏redis设置
    'game_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
        'password' => '123456'
    ],

    //游戏开发redis
    'game_dev_redis' => [
        'class' => 'yii\redis\Connection',
        //'hostname' => '192.168.1.237',
        'hostname' => '127.0.0.1',

        'port' => 6379,
        'database' => 0,
        'password' => '123456',
    ],
    //游戏开发redis
    'game_dev_redis_2' => [
        'class' => 'yii\redis\Connection',
        //'hostname' => '192.168.1.237',
        'hostname' => '127.0.0.1',

        'port' => 6379,
        'database' => 2,
        'password' => '123456',
    ],

    // 平台代理相关计算类型的写redis配置
    'daili_redis_write' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 6,
        'password' => '123456'
    ],

    // 平台代理相关计算类型的写redis配置
    'daili_redis_read_00' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'database' => 6,
        'password' => '123456'
    ],

];