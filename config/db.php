<?php
/**
 * 数据库配置
 */
return [
//    平台主数据库
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=rm-uf6fcc0pq6nfsgk06.mysql.rds.aliyuncs.com;dbname=oss',
        'username' => 'yiquan',
        'password' => 'dsfvs6563#dsf09',
        'charset' => 'utf8',
    ],

//    游戏登录日志
    'login_db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=rm-uf6fcc0pq6nfsgk06.mysql.rds.aliyuncs.com;dbname=login_db',
        'username' => 'yiquan',
        'password' => 'dsfvs6563#dsf09',
        'charset' => 'utf8',
    ],

//    游戏综合日志库
    'mdwl_activity' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=rm-uf6fcc0pq6nfsgk06.mysql.rds.aliyuncs.com;dbname=mdwl_activity',
        'username' => 'yiquan',
        'password' => 'dsfvs6563#dsf09',
        'charset' => 'utf8',
    ],
    //游戏日志库
    'player_log' =>  [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=rm-uf6fcc0pq6nfsgk06.mysql.rds.aliyuncs.com;dbname=player_log',
        'username' => 'yiquan',
        'password' => 'dsfvs6563#dsf09',
        'charset' => 'utf8',
    ],
];