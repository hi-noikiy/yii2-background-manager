<?php
/**
 * User: SeaReef
 * Date: 2018/6/8 18:42
 */
return [
//    平台主配置redis -- 禁止使用select指向database
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 0,
        'password' => 'sahkshd32ds',
    ],
    'redis_1' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 1,
        'password' => 'sahkshd32ds',
    ],
    'redis_2' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 2,
        'password' => 'sahkshd32ds',
    ],
    'redis_3' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 3,
        'password' => 'sahkshd32ds',
    ],
    'redis_4' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 4,
        'password' => 'sahkshd32ds',
    ],
    'redis_5' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 5,
        'password' => 'sahkshd32ds',
    ],
    'redis_6' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 6,
        'password' => 'sahkshd32ds',
    ],
    'redis_7' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 7,
        'password' => 'sahkshd32ds',
    ],
    'redis_8' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 8,
        'password' => 'sahkshd32ds',
    ],

    'platform_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '39.105.45.50',
        'port' => 6379,
        'database' => 0,
        'password' => 'sahkshd32ds',
    ],
    'platform_redis_3' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '39.105.45.50',
        'port' => 6379,
        'database' => 3,
        'password' => 'sahkshd32ds',
    ],

//    游戏redis设置
    'game_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '39.105.45.50',
        'port' => 6379,
        'database' => 0,
        'password' => 'sahkshd32ds'
    ],
    'game_dev_redis' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 0,
        'password' => 'sahkshd32ds',
    ],
    'game_dev_redis_2' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 2,
        'password' => 'sahkshd32ds',
    ],

    // 平台代理相关计算类型的写redis配置
    'daili_redis_write' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 6,
        'password' => 'sahkshd32ds'
     ],

     // 平台代理相关计算类型的写redis配置
     'daili_redis_read_00' => [
        'class' => 'yii\redis\Connection',
        'hostname' => '10.0.4.134',
        'port' => 6379,
        'database' => 6,
        'password' => 'sahkshd32ds'
     ],
];