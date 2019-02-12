<?php
return [
    'adminEmail' => 'admin@example.com',

    'change_bind_modile' => '13146149339',//改绑手机号
	'channel_rule_name' => '渠道合伙人',//平台登陆账号角色 渠道合伙人的角色名称

	'wechat_config' => [
		'API_KEY' => '7293edb63c09a81dbc6b2f6e3aacd9fc',//直兑api秘钥
		'MCH_APPID' => 'wx5223c60abfaaf719',//商户appid
		'MCHID' => '1515631881',//商户号
		'RECHARGE_API' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
		'SEE_ORDER_API' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo',
		'PAY_EXCHANGE_MONEY' => 'pay_exchange_money',
		'FAIL_PAY_EXCHANGE_MONEY' => 'fail_pay_exchange_money',
	],
    'wechat_config2' => [
        'API_KEY' => 'd5669a6e9a3805aa40d9d33cc33ce127',//直兑api秘钥
        'MCH_APPID' => 'wx0df1e20f682e8a39',//商户appid
        'MCHID' => '1518539401',//商户号
        'RECHARGE_API' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers',
        'SEE_ORDER_API' => 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo',
        'PAY_EXCHANGE_MONEY' => 'pay_exchange_money',
        'FAIL_PAY_EXCHANGE_MONEY' => 'fail_pay_exchange_money',
    ],
    'wechat_web_black_switch'=>1,//微信代理后台黑名单开关1开 2关
    'wechat_web_black_list'=>[30001417,30006534,30039877,30047704,30048140,30048960,30060619,30071428,30073965,30075452,30077614,30084067,30103752,30104949,30115788,30124376,30124741,30125616,30135267,30149832,30151069,30162614,30173979,30178941,30182120,30207921,30209054,30213278,30232411,30233882,30234602,30246036,30262158,30285989,30287425,30291115,30291338,30301279,30301355,30303346,30312528,30315923,30320772,30330012,30350793,30355756,30358594,30361528,30381268,30402393,30410542,30410976,30414372,30418494,30435321,30440952,30444082,30450552,30484116,30489101,30492209,30492858,30499694,30503341,30508848,30509239,30509408,30510936,30511186,30538184,30547795,30549342,30554314,30569227,30579075,30583502,30584215,30591127,30598021,30612278,30613828,30646517,30653224,30658532,30659938,30662393,30663056,30670964,30676567,30682389,30687281,30689867,30701524,30712286,30713128,30729366,30760375,30773244,30778364,30778712,30779385,30785534,30795203,30807594,30831773,30834066,30836386,30836587,30841499,30842533,30846826,30849854,30870852,30872280,30874011,30875881,30887478,30892723,30896731,30897772,30959805,30961363,30965475,30981893,30988291,30997053,30498486],
    //提现的上限
    'withdraw_deposit_up_line' => "5000",

    //提现的下限
    'withdraw_deposit_down_line' => "20",

    //提现渠道关闭时，可提现人员id
    'can_withdraw_deposit_id'=>'30773882',

    /*******************yii2-easy-wechat start*********************/
    'wechat' => [
        'class' => 'jianyan\easywechat\Wechat',
        'userOptions' => [],  # 用户身份类参数
        'sessionParam' => '', # 微信用户信息将存储在会话在这个密钥
        'returnUrlParam' => '', # returnUrl 存储在会话中
    ],

    
    // 微信配置 具体可参考EasyWechat
    'wechatConfig' => [],

    // 微信支付配置 具体可参考EasyWechat
    'wechatPaymentConfig' => [],

    // 微信小程序配置 具体可参考EasyWechat
    'wechatMiniProgramConfig' => [],

    // 微信开放平台第三方平台配置 具体可参考EasyWechat
    'wechatOpenPlatformConfig' => [],

    // 微信企业微信配置 具体可参考EasyWechat
    'wechatWorkConfig' => [],
    /*******************yii2-easy-wechat end*********************/

    /** 直兑白名单 */
    'exchange_white_list_switch'=>1,//1打开 2关闭
    'exchange_white_list'=>['30444308','50773780'],

    /** 直兑绑定类型 */
    'bind_type'=>[
        1=>'支付宝',
        2=>'银行卡'
    ],
    'VIP_RECHARGE_TYPE'=>[
        1=>'微信',
        2=>'QQ'
    ],
    /** 直兑限制条件 */
    'bind_condition'=>[
        'ali'=>[
            'is_use'=>0,//是否启用 1启用
            'up_line'=>1000000,//直兑上线
            'down_line'=>10000,//直兑下线
            'minimum_reserve'=>1000,//直兑最低保留
            'service_charge'=>'0',//手续费计算比例
        ],
        'bank'=>[
            'is_use'=>0,//是否启用 1启用
            'over_all_line'=>2000000,//每日上限
            'up_line'=>2000000,//每笔直兑上线
            'down_line'=>10000,//每笔直兑下线
            'minimum_reserve'=>1000,//直兑最低保留
            'service_charge'=>'0.02',//手续费计算比例
        ],
        'wechat'=>[
            'is_use'=>1,//是否启用 1启用
            'over_all_line'=>2000000,//每日上限
            'up_line'=>2000000,//每笔直兑上线
            'down_line'=>10000,//每笔直兑下线
            'minimum_reserve'=>1000,//直兑最低保留
            'service_charge'=>'0.02',//手续费计算比例
            'time_out' => 5,//订单超时时间 分
        ]
    ],
    'exchange_config'=>[
      'ali'=>[],
      'bank_hfb'=>[
          'request_url'=>'https://Pay.heepay.com/API/PayTransit/PayTransferWithSmallAll.aspx',
          'agent_id'=>'2117605',//2117096
          'md5_key'=>'93CAA0BE91F44256912F6C71',//md5加密密钥//87DBED4E08494A8CACAA6EC6
          'des_key'=>'30FDB37F75754D32AC8104AA',//des加密密钥//F496EB8EA51E4EB9B148B534
          'notify_url'=>'http://www.yqmaster.com/api/notify/exchange-notify',
          'BIN_chekc_url'=>'https://Pay.heepay.com/API/PayTransit/QueryBankCardInfo.aspx',
          'check_order_url'=>'https://Pay.heepay.com/API/PayTransit/QueryTransfer.aspx',
          'answer'=>'上游厂商结算款',//付款理由，第三方平台设置//上游结算款
      ]
    ],

    /** 输出金额:充值金额 */
    'gold_transition_proportion'=>'100/1',

    /** vip充值间隔时间 */
    'vip_recharge_time_out'=>'2',

    /** 提现比例 */
    'gold_withdraw_deposit'=>'110',//1:100

    //游戏id列表
    'games' => [
        '524817'=>"填大坑",
        '524818'=>"新牛牛",
        '524815'=>"新推筒子",
        '524816'=>"新扎金花",
        '524822'=>"斗地主",
        '524821' => '百人推筒子',
        '524813' => '三公',
//        '1114112'=>"大厅"
    ],
    //游戏战绩
    'games_record_id'=>[
        '524815',
        '524816',
        '524817',
        '524818',
        '524822',
        '524813',
    ],
    'default_table_fee_game_id'=>"524803",//台费默认显示游戏id
    //需要转换牌型的游戏id（战绩）
    'player_card_convert'=>[
        '524817',
        '524818',
        '524816',
        '524822',
    ],
    'gameForShort'=>[
        '524803'=>'zjh',
        '524560'=>'sxmj',
        '524802'=>'neimeng',
        '524510'=>'dda',
        '524807'=>'niuniu',
        '524899'=>'pdk',
        '524813'=>'sangong_sg',

        '524817'=>'tdk',//新填大坑
        '524818'=>'nn',//新牛牛
        '524815'=>'ttz',//新推筒子
        '524816'=>'zhajinhua',//新扎金花
        '524822'=>'doudizhu',//斗地主
    ],

    //充值地址
    'recharge_Url' => 'http://10.10.20.107:9966/recharge',

    //发送短信
    'api-key' => 'a0eefbaf740c1770baf1db7606b5453c',

    //短信过期时间
    'game_email_sms_timeout' => 60,
    //kicking_url封停账号
    'kicking_url' => 'http://10.10.20.107:9966/player_downline?',
    //跑马灯
    'pm_url' => 'http://10.10.20.107:9966/paomadeng',
    //公告邮件
    'email_url' => 'http://10.10.20.107:9966/mail',
    //天降红包
    'sky_drop_hongbao_url' => 'http://10.10.20.107:9966/hongbao',
    //百人场踢出房间
    'hundreds_kick' => 'http://10.10.20.69:20033',
    //百人场游戏列表
    'hundreds_games' => [
        524821,//百人推筒子
        524823,//百人牛牛
        524826,//龙虎斗
        524827,//红黑大战
    ],
    //代理提现页面开关：0打开提现，1关闭提现(只有公司人可以看)
    'pay_daili_forbid_switch' => 0,
    /**微信后台对外关闭状态下，可登陆用户id */
    'can_login_userId'=>'30694460,30963116,30705020,30820923,30905300,30555182,30701842,30633059,30312528,30174505,30826102,30011607,30979597',

    /** 代理后台黑名单开关 1开 */
    'agent_activity_switch'=>0,
    'agent_activity_white_list'=>['30444308'],

    /** 微信代理后台收益显示黑名单 1开 */
    'wechat_web_rebate_switch'=>1,
    'wechat_web_rebate_back_list'=>['30444308','30773780','30118876'],

    //提现按钮开关:0关闭开关(只有东洋可提)，1打开开关
    'pay_button_switch' => 1,

    'gid_alies' => [
        524803 => '三张牌',
        524560 => '山西麻将',
        524802 => '内蒙麻将',
        524510 => '内蒙大A王',
        524807 => '牛牛',
        524899 => '跑的快',
        524812 => '推筒子',
        524813 => '三公',
        524822 => '斗地主'
    ],
    //版本
    'V_1.0' => [
    ],
    // 晋级条件--云南合集
    'yunnan_heji' => [],
    // 晋级条件--湖北合集
    'hubei_heji' => [],
    'mobile' => '13147070211',
    'current_game' => '',
    //'sms_key' => 'd1337b52a3ed31880aadce371c7805f5',
    'sms_key' => '830aa24a9af58f769beaabb21212cf4c',
    'wx_redirect' => '',
    'return_enctrypt_option' => false,//返回值加密开关

    'daili_rule' => [
        '1' =>
            "0.35",
        '2' =>
            "0.1",
        '3' =>
            "0.05",
        'brokerage' =>
            "0.2",
    ],
    'up_daili_rule' => [
        2 => [
            'member' => 500,
            'profit' => 30000,
        ],
        3 => [
            'member' => 80,
            'profit' => 4000,
        ]
    ],
    'oss' => [
        'bucket' => 'xy-sh-llylqp',
        'accessKeyId' => 'LTAIr5QJq6FPLxIS',
        'accessKeySecret' => 'QR7y2zYwvvU4KVSsTceX86MqK43U11',
        'endPoint' => 'llylqp.cdn.xianyugame.com',
    ],
    'taskRules' => [
        [
            'name' => 'N个会员完成绑定，奖励人民币',
            'identity' => 'member-bind',
            'subscribes' => ['login', 'member-bind'],
        ],
        [
            'name' => 'N个会员完成首冲，奖励人民币',
            'identity' => 'member-first-recharge',
            'subscribes' => ['login', 'member-recharge'],
        ],
        [
            'name' => '会员充值总额在指定范围内，按新的比率计算提成',
            'identity' => 'member-recharge-range',
            'subscribes' => ['login', 'member-recharge'],
        ],
    ],
    'redisKeys' => [
        'userEvent' => '%s_daili_event',
        'rule_statis' => '%s_statis_daili_task_rule_%d',
        'FreeDarw' => 'xxx-FreeDraw-',//免费抽奖记录
        'user_gold_draw' => 'user_gold_draw', //钻石抽
        'decrypt_web_view_token' => '_webview_token_',//web_view 用户加解密后比较用的 token
        'webview_private_key' => '_webview_private_key',//web_view 加解密用key  解xor_enc
        'key_recharge_info' => '_key_recharge_info',//充值流水key;
        'profit_notice' => '_profit_notice_time',//充值流水key;
        'dealt_key_recharge_info_key' => 'dealt_key_recharge_info_key',//处理过的充值流水 已弃用 ！
//        'enshi_daili_rule' => 'enshi_daili_rule', //代理提成规则
        'daili_rule' => '_daili_rule',
        'gold_daili_rule' => '_gold_daili_rule',
        'web_all_wxcompay_money' => 'web_all_wxcompay_money',//提现总额
        'web_all_wxcompay_money_time' => 'web_all_wxcompay_money_time',//提现总额、
        'daili_relationship' => '_daili_relationship',//代理关系
        'pay_user_money_list' => 'pay_user_money_list',//代理申请支付订单队列
        'fail_pay_money_list' => 'fail_pay_money_list', //支付失败重查队列
        'sent_pay_error_time' => '-sent_pay_error_time',//记录发送支付错误短信时间
        'compay_times' => '-compay_times-',//用户提现次数;
        'daili_relation_list' => '_daili_relation_list_',//获取代理用户所有上级  //todo 需要核对是否弃用
        'web_all_player_room_data' => 'web_all_player_room_data_',//所有玩家开房数据
        'web_check_down_daili' => 'web_check_down_daili',//每个代理降级条件数据
        'web_check_up_daili' => 'web_check_up_daili',//可以升级的代理
        'member_Yesterday_ranking' => '_member_Yesterday_ranking',//昨日新增会员排名
        'member_Yesterday_ranking_data' => '_member_Yesterday_ranking_data',//昨日新增会员排名
        'member_Lastweek_ranking' => '_member_Lastweek_ranking',//上周新增会员排名
        'member_Lastweek_ranking_data' => '_member_Lastweek_ranking_data',//上周新增会员排名
        'member_Thismonth_ranking' => '_member_Thismonth_ranking',//这个月
        'member_Thismonth_ranking_data' => '_member_Thismonth_ranking_Data',//这个月
        'member_All_ranking' => '_member_All_ranking',//会员总排名
        'member_All_ranking_data' => '_member_All_ranking_data',//会员总排名
        'profit_Yesterday_ranking' => '_Profit_Yesterday_ranking',//昨日新增会员排名
        'profit_Yesterday_ranking_data' => '_Profit_Yesterday_ranking_data',//昨日新增会员排名
        'profit_Lastweek_ranking' => '_Profit_Lastweek_ranking',//上周新增会员排名
        'profit_Lastweek_ranking_data' => '_Profit_Lastweek_ranking_data',//上周新增会员排名
        'profit_Thismonth_ranking' => '_Profit_Thismonth_ranking',//这个月
        'profit_Thismonth_ranking_data' => '_Profit_Thismonth_ranking_data',//这个月
        'profit_All_ranking' => '_Profit_All_ranking',//会员总排名
        'profit_All_ranking_data' => '_Profit_All_ranking_data',//会员总排名
        'daili_all_index' => '_daili_all_index',//代理
        'player_info' => '_web_player_info',//玩家信息缓存
        'open_room_data' => '_web_open_room_data_',//玩家开房数
        'join_room_data' => '_web_join_room_data_',//玩家玩房数
        'marquee' => '_web_marquee',//玩家玩房数
        'event_key' => 'daili_dispatcher_list',
        'ReturnCenterList' => 'daili_ReturnCenterList_list',
        'MsgPushCenterList' => 'daili_msg_accept_event_list',
        'player_api_session' => 'sign_',//玩家代理后台SESSION,
        'machine_code' => 'machine_code',//机器人注册用
        'user_info' => 'user_info',//机器人注册用 / 用户信息redis
        //wkein新增
        'daili_manage_list' => 'daili_manage_list', //代理管理相关信息
        'gold_business_player' => 'gold_business_player',//币商信息
        'crazy_bet_switch' => 'crazy_bet_switch',//疯狂下注开关

        /**************************台费****************************/
        'table_fee_rate_zjh' => 'table_fee_rate_zjh_524803', // zjh台费设置
        'table_fee_rate_sxmj' => 'table_fee_rate_sxmj_524560', //sxmj台费设置
        'table_fee_rate_dda' => 'table_fee_rate_dda_524510',//dda台费设置
        'table_fee_rate_niuniu' => 'table_fee_rate_niuniu_524807',//牛牛台费设置
        'table_fee_rate_neimeng' => 'table_fee_rate_sxmj_524802',//内蒙台费设置
        'table_fee_rate_pdk' => 'table_fee_rate_zjh_524899', //跑的快
        'table_fee_rate_phz' => 'table_fee_rate_pdk_524805', //跑胡子
        'table_fee_rate_ttz' => 'table_fee_rate_tuitongzi_524815', //推筒子
        'table_fee_rate_tdk_old' => 'table_fee_rate_tdk_524808', //填大坑old
        'table_fee_rate_sangong_sg' => 'table_fee_rate_sangong_524813', //三公
        'table_fee_rate_tdk'=> 'table_fee_rate_tianDaKeng_524817',//填大坑new
        'table_fee_rate_nn'=> 'table_fee_rate_nn_524818',//填大坑new
        'table_fee_rate_zhajinhua'=>'table_fee_rate_zhajinhua_524816',
        'table_fee_rate_doudizhu'=>'table_fee_rate_douDiZhu_524822',


        'daili_open_info' => 'daili_open_info', //代理自行开启下级代理设置
        'gold_rate_key' => 'gold_rate_key',// 金币转钱的利率
        'black_id_list' => 'black_id_list', //账户封停列表
        'current_players_count' => 'current_players_count', //获取游戏当前时间在线玩家KEY
        'gm_paoma_time' => 'gm_paoma_time', //平台发布的跑马灯（id-开始时间）的一个有序集合
        'robot_common_info' => 'robot_common_info',//机器人
        'lunbo_interval' => 'lunbo_interval',//播放时间
        'downtime' => 'downtime',//停机维护信息
        'br_table_config' => 'br_table_config_',//百人场游戏配置
        //'br_table_log' => 'br_table_log_524821',//百人场游戏日志
        'robot_player' => 'robot_player_',//百人场机器人列表
        'br_robot_info' => 'br_robot_info_',//百人场当天实时统计
        'general_robot_config' => 'robot_robot_config',//通用机器人列表
        'general_robot_property' => 'robot_CharacterProperty',//通用机器人属性
        'robot_switch' => 'robot_switch',//通用机器人开关
        'general_robot_now_gold_pool' => 'general_robot_now_gold_pool',//通用机器人当日的额度
        'robot_match_waittime' => 'robot_match_waittime',//斗地主机器人进场时间
        'daili_income_rank' => 'daili_income_rank',//代理昨天收入排行
        'robot_add_match_base' => 'robot_add_match_base',//通用机器人入场底注限制


        'game_log'=>'platform_game_log_',//战绩前缀


        'money_client_config' => 'money_client_config',//充值限额设置

        'inf_agent'=>'inf_agent',//无限代理，代理信息键

        'platform_skydrop_switch' => 'platform_skydrop_switch',//红包开关

    ],
    //允许修改的会员上级id
    'systemPlayerIndex' => [999, 123456],

    'qiniu' => [
        'sKey' => 'kqXLqeV25S_1_DCUoWm-nkjq1mYJNcF0YCCZVTAI',
//        'sKey' => 'ZoW50J4tb1jZgwC18qDLS5Dw31I2wq9mxqt0K8Yc',
        'aKey' => 'ZUvT9KjB6sWk5vRfZXwc5ig77cIfFPc8SwCWpriS',
//        'aKey' => 'FWMbXjCajZhN4j8SWl2fpSryi-yfVBo7kwwN_UBW',
        'bucket' => 'webdaili',
        'url' => '',
    ],

    'BankPay' => [  //银行卡支付
        // 'PaySingle'         =>'http://139.199.191.240/pay-web/guocai/api/paySingle', //支付
        // 'balanceQuery'      =>'http://139.199.191.240/pay-web/guocai/api/balanceQuery',//查询余额
        // 'paySingleQuery'    => 'http://139.199.191.240/pay-web/guocai/api/paySingleQuery',//支付结果
    ],

    // 授权下级--提交代理申请限制（代理级别不为3 ）
    'applyToDailiIsLimit' => ['zhaotong'],
    'manage_tel' => '13147070211',
    // 代理晋级条件
    'dailiUp' => [
        // 规则别名
        'rule_alies' => [
            'memberNum' => '名下总会员数',
            'dailiNum' => '名下总代理数',
            'monthProfitNum' => '本月利润分成',
            'monthRechargeNum' => '名下本月充值流水',
            'weekMemberOpenRoomNum' => '名下一周会员总开桌数',
            'weekProfitNum' => '一周总收益',
        ],

        // 初级代理-> 高级代理
        3 => [
            // 山西合集
            524803 => 'rule', 524560 => 'rule', 524561 => '', 524802 => 'rule', 524510 => 'rule',
            524807 => 'rule',
            524563 => 'rule',

            'rule' => [
                ['content' => 'memberNum', 'target' => 60],
                ['content' => 'dailiNum', 'target' => 3],
                ['content' => 'monthProfitNum', 'target' => 6000],
            ],
            'rule2' => [
                ['content' => 'monthProfitNum', 'target' => 1400],
            ],
            'rule3' => [
                ['content' => 'weekMemberOpenRoomNum', 'target' => 350],
                ['content' => 'weekProfitNum', 'target' => 500],
            ],
            'rule4' => [
                ['content' => 'memberNum', 'target' => 60],
                ['content' => 'dailiNum', 'target' => 3],
                ['content' => 'monthRechargeNum', 'target' => 6000],
            ],
        ],
        // 高级代理-> 超级代理
        2 => [
            // 湖北合集
            327940 => 'rule',
//            262402 => 'rule',
//            262407 => 'rule',
            'rule' => [
                ['content' => 'monthProfitNum', 'target' => 30000],
            ],
        ],
    ],
    /**
     * 实习代理相关
     */
    // 实习代理招募申请
    'shixiDailiZhaoMu' => [262402],
    // 实习代理申请短信验证码有效期分钟数 默认15分钟
    'shixiDailiSmsTokenExpireTime' => 14,
    // 实习代理权限有效天数
    'shixiDailiValidDay' => [
        'huanggang' => 1,
        262402 => 0,
    ],
    // 实习代理转正会员所需达标数
    'shixiDailiMemberTaskNum' => [
        262402 => 0,
    ],

    //接口例外游戏
    'EXCEPT_GAME' => [
        262413,
    ],
    /**
     * 平台redis键
     */
    'prk' => [
        'junior_list' => 'pk_junioragent_list',
        'junior_relation' => 'pk_junioragent_relation',
        'junior_params' => 'pk_junioragent_params',
    ],
    /**
     * 存人人代scan_code的redis键
     */
    'people_scan_code'=>'people_scan_code',

    // 游戏底注配置
    'stake_524803' => [
        // '三张牌',
        11 => '10底注（匹配）',
        12 => '100底注（匹配）',
        13 => '500底注（匹配）',
        1 => '50底注',
        2 => '100底注',
        3 => '200底注',
        4 => '500底注',
        5 => '1000底注',
        6 => '2000底注',
        7 => '3500底注',
        8 => '5000底注',
        14 => '1元匹配',
    ],
    'stake_524560' => [
        // '山西麻将'
        1 => '50底注',
        2 => '100底注',
        3 => '200底注',
        4 => '500底注',
        5 => '1000底注',
        6 => '2000底注',
        7 => '5000底注'
    ],
    'stake_524802' => [
        // '内蒙麻将'
        1 => '50底注',
        2 => '100底注',
        3 => '200底注',
        4 => '500底注',
        5 => '1000底注',
        6 => '2000底注',
        7 => '5000底注'
    ],
    'stake_524510' => [
        // '内蒙大A王'
        1 => '50底注',
        2 => '100底注',
        3 => '200底注',
        4 => '500底注',
        5 => '1000底注',
        6 => '2000底注',
        7 => '5000底注'
    ],
    'stake_524807' => [
        // '牛牛'
        // 1 => '20底注',
        2 => '50底注',
        3 => '100底注',
        4 => '200底注',
        5 => '500底注',
        6 => '1000底注',
        7 => '2000底注',
        8 => '5000底注',
        2046 => '50底注（匹配）',
        2047 => '100底注（匹配）',
        2048 => '200底注（匹配）',
        2049 => '一元匹配',
    ],
    'stake_524899' => [
        // '跑得快'
        1 => '10底注',
        2 => '50底注',
        3 => '100底注',
        4 => '200底注',
        5 => '500底注',
        6 => '1000底注',
        7 => '2000底注',
        8 => '5000底注'
    ],
    'stake_524813' => [
        //'三公'
        1 => '20三公',
        2 => '50三公',
        3 => '100三公',
        4 => '200三公',
        5 => '500三公',
        6 => '1000三公',
        11 => '20金花',
        12 => '50金花',
        13 => '100金花',
        14 => '200金花',
        15 => '500金花',
        16 => '1000金花',
        21 => '10匹配',
        22 => '50匹配',
        23 => '100匹配',
    ],

    /***********新游戏*************/
    'stake_524815' => [
        // '推筒子'
        1 => '50底注',
        2 => '100底注',
        3 => '200底注',
        4 => '500底注',
        5 => '1000底注',
        6 => '2000底注',
        7 => '3000底注',
        8 => '5000底注',
        9 => '50匹配底注',
        10=> '100匹配底注',
        11=> '200匹配底注'
    ],
    'stake_524817' => [
        //'填大坑'
        1=> '50底注',
        2 => '100底注',
        3 => '200底注',
        4 => '500底注',
        5 => '1000底注',
        6 => '2000底注',
        7 => '3000底注',
        8 => '5000底注',
        9 => '50匹配底注',
        10 => '100匹配底注',
        11 => '200匹配底注',
    ],
    'stake_524818' => [
        //'新牛牛'
        1=>  '(经典抢庄6)50底注',
        2 => '(经典抢庄6)100底注',
        3 => '(经典抢庄6)200底注',
        4 => '(经典抢庄6)500底注',
        5 => '(经典抢庄6)1000底注',
        6 => '(经典抢庄6)2000底注',
        7 => '(经典抢庄6)5000底注',

        8 => '(经典抢庄9)50底注',
        9 => '(经典抢庄9)100底注',
        10 => '(经典抢庄9)200底注',
        11 => '(经典抢庄9)500底注',
        12 => '(经典抢庄9)1000底注',
        13 => '(经典抢庄9)2000底注',
        14 => '(经典抢庄9)5000底注',

        15 => '(通比抢庄6)50底注',
        16 => '(通比抢庄6)100底注',
        17 => '(通比抢庄6)200底注',
        18 => '(通比抢庄6)500底注',
        19 => '(通比抢庄6)1000底注',
        20 => '(通比抢庄6)2000底注',
        21 => '(通比抢庄6)5000底注',

        22 => '(通比抢庄9)50底注',
        23 => '(通比抢庄9)100底注',
        24 => '(通比抢庄9)200底注',
        25 => '(通比抢庄9)500底注',
        26 => '(通比抢庄9)1000底注',
        27 => '(通比抢庄9)2000底注',
        28 => '(通比抢庄9)5000底注',

        29 => '1元匹配',
        30 => '50匹配',
        31 => '100匹配',
        32 => '200匹配',
    ],
    'stake_524816' => [
        //'新扎金花'
        1=> '六人场50底注',
        2=> '六人场100底注',
        3=> '六人场200底注',
        4=> '六人场500底注',
        5=> '六人场1000底注',
        6=> '六人场2000底注',
        7=> '六人场3500底注',
        8=> '六人场5000底注',
        9=> '九人场50底注',
        10=>'九人场100底注',
        11=>'九人场200底注',
        12=>'九人场500底注',
        13=>'九人场1000底注',
        14=>'九人场2000底注',
        15=>'九人场3500底注',
        16=>'九人场5000底注',
        17=>'匹配场1底注',
        18=>'匹配场10底注',
        19=>'匹配场100底注',
        20=>'匹配场500底注'
    ],
    'stake_524822' => [
        //'斗地主'
        1=> '50场底注',
        2=> '100场底注',
        3=> '200场底注',
        4=> '500场底注',
        5=> '1000场底注',
        6=> '2000场底注',
        7=> '3000场底注',
        8=> '5000场底注',
        9=> '匹配场底注',
        10=>'匹配场底注',
        11=>'匹配场底注',
    ],
    //解散房间
    'dissolveTable'=>[
        '524817' =>'http://10.10.20.107:19935/forceDismissTable',//填大坑
        '524818' =>'http://10.10.20.107:20021/forceDismissTable',//新牛牛
        '524815' =>'http://10.10.20.107:19934/forceDismissTable',//新推筒子
        '524816' =>'http://10.10.20.107:19938/forceDismissTable',//新扎金花
        '524822' =>'http://10.10.20.107:19947/forceDismissTable',//斗地主
    ],
    //查询牌桌信息
    'checkTableInfo'=>[
        '524817' => [//填大坑(格式不能变)
            'url'=>'http://10.10.20.107:19935/tableInformation',
            'cardRule'=>[
                'num' => 13,
                'color' => ['黑桃', '红桃', '梅花', '方片','王'],
                'king'  => ['小','大'],
                'value' => ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A']
            ]
        ],
        '524818' => [//新牛牛(格式不能变)
            'url'=>'http://10.10.20.107:20021/tableInformation',
            'cardRule'=>[
                'num' => 13,
                'color' => ["♦", "♣", "♥", "♠", "王"],
                'king'  => ['小','大'],
                'value' => ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"]
            ]
        ],
        '524816' => [//新扎金花(格式不能变)
            'url'=>'http://10.10.20.107:19938/tableInformation',
            'cardRule'=>[
                'num' => 13,
                'color' => ["♦", "♣", "♥", "♠", "王"],
                'king'  => ['小','大'],
                'value' => ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"]
            ]
        ],
        '524815' => [//新牛牛(格式不能变)
            'url'=>'http://10.10.20.107:19934/tableInformation',
            'cardRule'=>[
                'num' => 13,
                'color' => ["♦", "♣", "♥", "♠", "王"],
                'king'  => ['小','大'],
                'value' => ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"]
            ]
        ],
        '524822' => [//斗地主(格式不能变)
            'url'=>'http://10.10.20.107:19947/tableInformation',
            'cardRule'=>[
                'num' => 13,
                'color' => ["♦", "♣", "♥", "♠", "王"],
                'king'  => ['小','大'],
                'value' => ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"]
            ]
        ],
    ],
    'server_whitelist'=>[

    ],
];
