 <?php
/**
 * 数据库配置
 *
 */
return [
//    平台主数据库
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=10.10.20.107;dbname=oss',//10.10.20.77
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
        'on afterOpen' => function($event) {
            $event->sender->createCommand("SET NAMES UTF8")->execute();
        },
	    //配置从服务器
//	    'slaveConfig'=>[
//		    'username' => 'slave',
//		    'password' => '123456',
//		    'charset' => 'utf8',
//		    'attributes' => [
//			    // use a smaller connection timeout
//			    PDO::ATTR_TIMEOUT => 10,
//		    ],
//	    ],
//	    // 配置从服务器组
//	    'slaves' => [
//		    ['dsn' => 'dsn for slave server 1'],
//		    ['dsn' => 'dsn for slave server 2'],
//		    ['dsn' => 'dsn for slave server 3'],
//		    ['dsn' => 'dsn for slave server 4'],
//	    ],
    ],

    'center_db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=10.10.20.107;dbname=payment_center',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],

	//游戏登录日志
    'login_db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=10.10.20.107;dbname=login_db',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],
    //游戏日志库
    'player_log' =>  [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=10.10.20.107;dbname=player_log',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ],

	//    游戏综合日志库
    'mdwl_activity' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=10.10.20.107;dbname=mdwl_activity',
        'username' => 'root',
        'password' => '123456',
        'charset' => 'utf8',
    ]
];