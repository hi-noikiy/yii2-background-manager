<?php
/**
 * User: SeaReef
 * Date: 2018/8/6 11:49
 *
 * 内网测试服配置
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');
//是否测试服
defined('IS_TEST') or define('IS_TEST', true);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/intranet.php';

(new yii\web\Application($config))->run();
