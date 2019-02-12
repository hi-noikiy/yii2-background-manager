<?php
/**
 * User: SeaReef
 * Date: 2018/11/9 14:56
 *
 * 全局错误码
 */
namespace app\common;

class Code
{
    /**
     * 成功
     */
    const CODE_OK = 1;

    /**
     * 失败
     */
    const CODE_ERROR = -1;

    /**
     * -1xx、基础错误
     */

    /**
     * ip禁止访问
     */
    const CODE_IP_NOT_ALLOW = -101;

    /**
     * 子游戏ID不正确
     */
    const CODE_GID_NOT_ALLOW = -102;

    /**
     * mac地址错误
     */
    const CODE_MAC_NOT_ALLOW = -103;

    /**
     * uid不存在
     */
    const CODE_UID_NOT_FOUND = -104;




    /**
     * -2**、充值相关错误
     */

    /**
     * 充值参数错误
     */
    const CODE_PARAMS_ERROR = -201;

    /**
     * 充值黑名单
     */
    const CODE_PAY_BLACK_LIST = -202;

    /**
     * 充值用户未找到
     */
    const CODE_PAY_USER_NOT_FOUND = -203;

    /**
     * 充值商品未找到
     */
    const CODE_PAY_GOODS_NOT_FOUND = -204;

    /**
     * 充值商品未找到
     */
    const CODE_INVALID_RECHARGE_ACTIVITY = -205;

    /**
     * 已经领取活动
     */
    const CODE_ACTIVITY_HAS_COMPLETED = -206;



    /**
     * 添加临时订单失败
     */
    const CODE_ADD_ORDER_ERROR = -207;

    /**
     * 订单处理中
     */
    const CODE_ORDER_PROCCESS = -208;

    /**
     * 订单已完成
     */
    const CODE_ORDER_FINISHED = -209;

    /**
     * 订单返回结果失败
     */
    const CODE_ORDER_RETURN_ERROR = -210;

    /**
     * 订单签名错误
     */
    const CODE_ORDER_SIGN_ERROR = -211;

    /**
     * 订单为找到
     */
    const CODE_ORDER_NOT_FOUND = -212;

    /**
     * 订单金额不正确
     */
    const CODE_ORDER_MONEY_ERROR = -213;

    /**
     * 活动已失效
     */
    const CODE_ACTIVITY_HAS_INVALID = -214;









    /*************************直兑api开始************************/
    /** 成功返回 */
    const OK                = 0;

    /** 通用失败返回 */
    const ERROR             = 500;

    /** 请求方式错误 */
    const REQUEST_ERROR     = 200;

    /** 参数错误 */
    const PARAM_ERROR       = 101;

    /** 参数值不合法 */
    const PARAM_VALUE_ERROR = 102;

    /** 未绑定账号 */
    const NO_BIND           = 103;

    /** 用户不存在 */
    const NO_PLAYER         = 104;

    /** 提现类型错误 */
    const EXCHANGE_TYPE_ERROR = 105;

    /** 提现低于最低限制*/
    const DOWN_LINE_ERROR = 106;

    /** 提现高于最高限制*/
    const UP_LINE_ERROR = 107;

    /** 自身货币不足 */
    const AMOUNT_ERROR = 108;

    /** 订单创建失败 */
    const ORDER_CREATE_ERROR = 109;

    /** 处理玩家金币失败 */
    const DISPOSE_GOLD_ERROR = 110;

    /** 直兑到玩家第三方账户失败 */
    const EXCHANGR_ERROR = 120;

    /** 银行卡号不合法 */
    const BANK_NUMBER_ERROR = 121;

    /** 订单不存在 */
    const ORDER_NOT_EXTENDS = 122;

    /** 网页授权返回失败 */
    const ACCESS_RETURN_ERROR = 123;

    /** 订单预处理失败，参数有误！ */
    const PRETREATMENT_ORDER_ERROR = 124;


    /** 超出每日限额 */
    const OVER_ALL_LINE = 125;

    /** 绑定关系已存在 */
    const BING_RELATION_ERROR = 126;

    /** 该银行卡号已被绑定 */
    const CODE_HAS_BIND = 127;

    /*************************直兑api结束************************/


/******************************直兑定时脚本开始*******************************/
    /** 准备支付状态 */
    const READY_TO_PAY_STATUS = 0;

    /** 支付成功 */
    const SUCCESS_PAY_STATUS = 1;
    const SUCCESS_PAY_MESSAGE = '支付成功';

    const EXCHANGING = 2;//直兑中

    /** 支付失败，待重查 */
    const RECHECK_PAY_STATUS = 3;
    const RECHECK_PAY_MESSAGE = '支付失败 待重新查询';

    const RECHECK_PAY_ERROR_BACK = 101;
    const RECHECK_PAY_ERROR_BACK_MESSAGE = "支付失败,回滚用户金币";
    const RECHECK_PAY_ERROR_BACK_SUCCESS = 1011;
    const RECHECK_PAY_ERROR_BACK_MESSAGE_SUCCESS = "支付失败,回滚用户金币成功";
    const RECHECK_PAY_ERROR_BACK_FALSE = 1012;
    const RECHECK_PAY_ERROR_BACK_MESSAGE_FALSE = "支付失败,回滚用户金币失败";

    const TIME_OUT = 210;//订单超时

    const CODE_NOT_USER_INFO = 220;//玩家不存在

    const EXCHANGE_ERROR = 230;//直兑失败


    /** 支付失败 */
    const FALSE_PAY_MESSAGE = '支付失败 解除冻结金额';

    /** 支付成功 但更新订单状态失败； */
    const ERROR_FALSE_UPDATE_ORDER_MESSAGE = '支付成功 但更新订单状态失败';

    /** 支付成功  但减少用户冻结金额失败; */
    const ERROR_FALSE_MINUS_FORZEN_MESSAGE = '支付成功  但减少用户冻结金额失败';

    /** 转账失败 未成功，todo 具体处理待定 */
    const TRANSFER_FALSE_MINUS_FORZEN_MESSAGE = '转账失败 未成功';

    /** 处理中订单 */
    const PROCESSING_FALSE_MINUS_FORZEN_MESSAGE = '处理中订单 需要重试';

    /** 余额不足 单独处理 */
    const NOTENOUGH_FALSE_MINUS_FORZEN_MSEEAGE = '余额不足 单独处理 人工跟进中';

    /** 和微信交互失败 */
    const MUTUAL_FILE_WITH_WECHAT = '和微信通信失败！';

    /** 直兑失败，第三方返回打款失败 */
    const THIRD_RETURN_ERROR = '第三方返回打款失败,核对打款信息！';

    /******************************直兑定时脚本结束*******************************/

    /**
     * 具体功能状态码
     */
    const CODE_NOT_ENOUGH = -2;

    /**
     * 充值功能
     */
    const CODE_GAMEID_NOT_FOUND = -21;

    const CODE_PLAYID_NOT_FOUND = -22;

    const CODE_RECHARGE_TIMEOUT = -23;

    const  CODE_VIP_RECHARGE_ERROR = '-10001';

    /**
     * LAYUI使用
     */
    const CODE_LAYUI_OK = 1;

    /**
     * 支付使用的code值
     */
    const CODE_PAY_PARAMS_ERROR = -31;

    const CODE_PAY_TYPE_ERROR = -33;

    const CODE_PAY_CHANNEL_ERROR = -34;

    const CODE_PAY_WRONGFUL = -36;

    const USER_HAS_FIRST_RECHARGE = -37;


    /**
     * 登录黑名单
     */

    /**
     * 游戏邮件
     */
    const CODE_GAME_EMAIL_MORE_THAN_99 = -40;

    const CODE_GAME_EMAIL_NOT_FOUND = -41;

    const CODE_EXCEL_TYPE_ERROR = -42;

    const CODE_SIZE_TOO_LARGE = -43;

    const CODE_EXCEL_UPLOAD_FAIL = -44;

    const CODE_FILE_UPLOAD_ERROR = -45;

    const CODE_EXCEL_EMPTY = -46;

    const CODE_ATTACHMENT_NUM = -47;

    const CODE_VERIFY_CODE_TIMEOUT = -48;

    const CODE_VERIFY_CODE_ERROR = -49;

    const CODE_PLAYER_EXIST_LIST = -50;
    /**
     * 游戏玩家
     */
    const CODE_PLAYER_NOT_FOUND = -44;

    /**
     * -51~~~~~~~~-100
     * 微信代理后台
     */
    const CODE_NOT_FOUND_DOWN_USER = -51;
    const CODE_NOT_FOUND_DAILI = -52;
    const CODE_NOT_WITHDRAW_TIME = -53;
    const CODE_WITHDRAW_OUT_OF_RANGE = -54;
    const CODE_REAL_NAME_WRONG = -55;
    const CODE_PHONE_VERIFY_CODE_ERROR = -56;
    const CODE_PHONE_VERIFY_CODE_TIME_OUT = -57;
    const CODE_DAILI_NOT_EXISTS = -58;
    const CODE_DAILI_HAS_MONEY = -59;
    const CODE_LOWER_DAILI_HAS_MONEY = -60;
    const CODE_WEIXIN_AUTH_NOT_FOUND = -61;
    const CODE_DAILI_EXISTS = -62;
    const CODE_DAILI_OPEN_LIMIT = -63;
    const CODE_NOT_REAL_NAME = -64;
    const CODE_LOWER_DAILI_EXISTS = -65;

    const CODE_WITHDRAW_LOW_RANGE = -66;
    const CODE_WITHDRAW_UP_RANGE = -67;
    const CODE_WITHDRAW_NO_ENOUGH = -68;
    const CODE_NOT_LOGIN = -69;
    const CODE_NOT_BIND = -70;

    /**
     * 默认操作码返回值
     */
    public static $CODE_MESSAGES = [
        self::OK => 'OK',
        self::CODE_OK => 'OK',
        self::CODE_ERROR => 'ERROR',
        self::CODE_IP_NOT_ALLOW => 'IP NOT ALLOW',
        self::CODE_GID_NOT_ALLOW => 'GID NOT ALLOW',
        self::CODE_MAC_NOT_ALLOW => 'MAC NOT ALLOW',
        self::CODE_UID_NOT_FOUND => 'UID NOT FOUND',

        self::CODE_PARAMS_ERROR => 'PARAMS ERROR',
        self::CODE_PAY_BLACK_LIST => '商户异常，暂时无法支付！',
        self::CODE_PAY_USER_NOT_FOUND => 'PAY USER NOT FOUND',
        self::CODE_PAY_GOODS_NOT_FOUND => 'PAY GOODS NOT FOUND',
        self::CODE_INVALID_RECHARGE_ACTIVITY => 'INVALID RECHARGE ACTIVITY',
        self::CODE_ACTIVITY_HAS_COMPLETED => 'ACTIVITY HAS COMPLETED',
        self::CODE_ADD_ORDER_ERROR => 'ADD TMP ORDER ERROR',
        self::CODE_ORDER_PROCCESS => 'ORDER PROCESSING',
        self::CODE_ORDER_FINISHED => 'ORDER HAS BEEN COMPLETED',
        self::CODE_ORDER_SIGN_ERROR => 'ORDER SIGN ERROR',
        self::CODE_ORDER_NOT_FOUND => 'ORDER NOT FOUND',
        self::CODE_ORDER_MONEY_ERROR => 'ORDER MONEY ERROR',

        self::CODE_ORDER_RETURN_ERROR => 'ORDER RETURN ERROR',
        self::CODE_ORDER_SIGN_ERROR => 'ORDER SIGN ERROR',
        self::CODE_ORDER_NOT_FOUND => 'ORDER NOT FOUND',
        self::CODE_ORDER_MONEY_ERROR => 'ORDER MONEY ERROR',

//        self::CODE_ACTIVITY_HAS_INVALID = 'ACTIVITY HAS INVALID',
        self::CODE_ACTIVITY_HAS_INVALID => 'ACTIVITY HAS INVALID',

        self::CODE_OK => 'ok',
        self::CODE_ERROR => 'error',
        self::CODE_NOT_ENOUGH => 'params not enough',
        self::CODE_GAMEID_NOT_FOUND => 'gameid not found',
        self::CODE_LAYUI_OK => '',
        self::CODE_UID_NOT_FOUND => 'UID NOT FOUND',

        self::CODE_PAY_PARAMS_ERROR => 'pay url params error',
//        self::CODE_PAY_BLACK_LIST => 'pay system error',
        self::CODE_PAY_TYPE_ERROR => 'pay type error',
        self::CODE_PAY_CHANNEL_ERROR => 'channel pay error',
        self::CODE_PAY_USER_NOT_FOUND => 'pay user not found',
        self::CODE_PAY_WRONGFUL => 'pay wrongful',
        self::USER_HAS_FIRST_RECHARGE => 'user has first recharge',
        //self::CODE_PAY_PLACE_FAIL => 'user place fail',

        self::CODE_PLAYID_NOT_FOUND => 'play_id not found',

        self::CODE_GAME_EMAIL_MORE_THAN_99 => 'game email num more than 99',
        self::CODE_PARAMS_ERROR => 'params error',
        self::CODE_GAME_EMAIL_NOT_FOUND => 'game email not found',
        self::CODE_EXCEL_TYPE_ERROR => 'excel file type error',
        self::CODE_SIZE_TOO_LARGE => 'file size too large',
        self::CODE_EXCEL_UPLOAD_FAIL => 'upload fail',
        self::CODE_FILE_UPLOAD_ERROR => 'file upload error',
        self::CODE_EXCEL_EMPTY => 'play id empty',
        self::CODE_ATTACHMENT_NUM => 'num is between 1 and 500000',
        self::CODE_VERIFY_CODE_TIMEOUT => 'verify code timeout',
        self::CODE_VERIFY_CODE_ERROR => 'verify code error',
        self::CODE_PLAYER_NOT_FOUND => 'player not found',

        self::CODE_RECHARGE_TIMEOUT => 'recharge time out',
        self::CODE_NOT_FOUND_DOWN_USER =>'not found down user',
        self::CODE_NOT_FOUND_DAILI =>'not found daili',
        self::CODE_NOT_WITHDRAW_TIME => 'not withdraw time',
        self::CODE_WITHDRAW_OUT_OF_RANGE => 'out of withdraw range ',
        self::CODE_REAL_NAME_WRONG => 'real name wrong for withdraw',
        self::CODE_PHONE_VERIFY_CODE_ERROR => 'phone verify code error',
        self::CODE_PHONE_VERIFY_CODE_TIME_OUT => 'verify code time out',
        self::CODE_DAILI_NOT_EXISTS => 'daili not exists',
        self::CODE_DAILI_HAS_MONEY => 'daili has money',
        self::CODE_LOWER_DAILI_HAS_MONEY => 'lower daili has money',
        self::CODE_WEIXIN_AUTH_NOT_FOUND => 'weixin auth not found',
        self::CODE_DAILI_EXISTS => 'daili exists',
        self::CODE_DAILI_OPEN_LIMIT => 'daili open limit',
        self::CODE_NOT_REAL_NAME => 'daili no real name',
        self::CODE_LOWER_DAILI_EXISTS => 'lower daili exists',

        self::CODE_WITHDRAW_LOW_RANGE => 'withdraw low range',
        self::CODE_WITHDRAW_UP_RANGE => 'withdraw up range',

        self::CODE_WITHDRAW_NO_ENOUGH => 'money not enough',
        self::CODE_NOT_LOGIN => 'player not login',
        self::CODE_NOT_USER_INFO => 'player not exist',
    ];
}