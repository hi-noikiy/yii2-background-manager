<?php
//内网测试服配置
if (YII_ENV == 'in') {
    $params = require __DIR__ . '/intranet_params.php';
    $db = require __DIR__ . '/intranet_db.php';
    $redis = require __DIR__ . '/intranet_redis.php';
}

//外网测试服
if (YII_ENV == 'out') {
    $params = require __DIR__ . '/test_params.php';
    $db = require __DIR__ . '/test_db.php';
    $redis = require __DIR__ . '/test_redis.php';
}

//正式服配置
if (YII_ENV == 'dev') {
    $params = require __DIR__ . '/params.php';
    $db = require __DIR__ . '/db.php';
    $redis = require __DIR__ . '/redis.php';
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
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

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
//            'itemTable' => 'auth_item',
//            'assignmentTable' => 'auth_assignment',
//            'itemChildTable' => 'auth_item_child',
        ],
    ],
    'params' => $params,

    'timeZone' => 'Asia/Shanghai',
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

$config['components'] = array_merge($config['components'], $db);
$config['components'] = array_merge($config['components'], $redis);

return $config;
