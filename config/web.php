<?php
/**
 * 外网正式服主配置文件
 */

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$redis = require __DIR__ . '/redis.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'kYiUNJmAOMTzhWhxez75sYLKzxqx7gXY',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
            'enableSession' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logVars' => ['_GET', '_POST'],
                    'exportInterval' => 100,
                    'maxFileSize' => 10240 * 5,
                    'logFile' => '@app/runtime/logs/error.' . date('Y-m-d-H') . '.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info', 'profile'],
                    'logVars' => [],
                    'exportInterval' => 100,
                    'maxFileSize' => 10240 * 5,
                    'logFile' => '@app/runtime/logs/info.' . date('Y-m-d-H') . '.log',
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        ],
        'wechat' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wx5223c60abfaaf719',
            'appSecret' => 'dadc7a70094d29082bb82b8706d7dba5',
            'token' => 'b0277f92861137b5e3b28fe7aa780cda'
        ],

//        竣付通公共号支付信息
        'wechat_jpay' => [
            'class' => 'callmez\wechat\sdk\Wechat',
            'appId' => 'wxdefb3685bf94df40',
            'appSecret' => '339be887d8caec12b64255a93c0bcdda',
            'token' => '',
        ],
    ],
    'params' => $params,

//    、设置时区
    'timeZone' => 'Asia/Shanghai',
    'defaultRoute' => 'user/login',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

$config['components'] = array_merge($config['components'], $db);
$config['components'] = array_merge($config['components'], $redis);

return $config;
