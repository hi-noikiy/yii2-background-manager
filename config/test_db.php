<?php
/**
 * 外网测试服、mysql配置
 * 数据库配置
 */
return [
//    平台主数据库
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=oss',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],

    'center_db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=payment_center',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],

//    游戏登录日志
    'login_db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=login_db',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],
    //游戏日志库
    'player_log' =>  [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=player_log',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],

//    游戏综合日志库
    'mdwl_activity' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=mdwl_activity',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],
];