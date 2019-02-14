<?php

// NOTE: Make sure this file is not accessible when deployed to production
//if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
//    die('You are not allowed to access this file.');
//}
date_default_timezone_set('PRC');
/**
 * 外网测试服入口文件
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
//是否测试服
defined('IS_TEST') or define('IS_TEST', true);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/test.php';

(new yii\web\Application($config))->run();
