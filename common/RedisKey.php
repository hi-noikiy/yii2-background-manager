<?php
/**
 * User: SeaReef
 * Date: 2018/11/9 14:47
 *
 * redis键记录
 */
namespace app\common;

class RedisKey
{


    /**********************缓存key************************/
    /**
     * 轮播图缓存
     */
    const CACHE_LUNBO = 'cache_conf_lunbo';

    /**
     * 商城列表缓存
     */
    const CACHE_GOODS_LIST = 'cache_goods_list';





    /**********************功能性数据缓存************************/
    /**
     * 无限代玩家每日消耗汇总、hash
     */
    const INF_DAY_CONSUME = 'inf_day_consume_';

    /**
     * 无限代每周消耗汇总、hash
     */
    const INF_WEEK_CONSUME = 'inf_week_consume_';

    /**
     * 无限代所有人消耗、hash
     */
    const INF_ALL_CONSUME = 'inf_all_consume';

    /**
     * 无限代代理信息、hash
     */
    const INF_AGNET = 'inf_agent';

    /**
     * 无限代代理关系、有序集合
     */
    const INF_AGENT_RELATION = 'inf_agent_relation';

    /**
     * 无限代日伞下消耗、hash
     */
    const INF_UNDER_DAY_CONSUME = 'inf_under_day_consume_';

    /**
     * 无限代周伞下消耗、hash
     */
    const INF_UNDER_WEEK_CONSUME = 'inf_under_week_consume_';

    /**
     * 无限代汇总伞下消耗、hash
     */
    const INF_UNDER_ALL_CONSUME = 'inf_under_all_consume';

    /**
     * 无限代每周等级评定
     */
    const INF_LEVEL = 'inf_level_';


    /**
     * 轮播图缓存
     */
    const LUNBO_PLAY_INTERVAL = 'lunbo_interval';


    /**
     * IP、MAC黑名单redis配置
     */
    const MAC_BLACK = 'pk_mac_black';

    const ONE_IP_BLACK = 'pk_oneip_black';

    const MANY_IP_BLACK = 'pk_manyip_black';

    /**
     * 举报缓存键前缀
     */
    const REPORT_KEY = 'pk_report_';

    /**
     * 登录判断
     */
    const KEY_LOGIN_CHECK = 'pk_login_check';

    /**
     * 开服判断
     */
    const KEY_LOGIN_IP_BLACK = 'pk_login_ip_white';

    /**
     * 充值限额
     */
    const MONEY_CLIENT_CONFIG = 'money_client_config';

    /**
     * 在线人数
     */
    const REAL_ONLINE = 'online_user_info';

    /**
     * 设置页面微信的玩家ID
     */
    const WEB_PLAYER_ID = 'web_player_id';

    /**
     * 设置当前渠道id
     */
    const CHANNEL_ID = 'this_channel_id_';

    /**
     * 玩家ID对应的渠道ID
     */
    const CHANNEL_PLAYER = 'channel_player';
}