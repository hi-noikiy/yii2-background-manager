<?php
namespace app\common\wxpay\Lib;

/** 
 * 配置账号信息
 */

class WxPayConfig
{
    private $config = [];
    
    protected static $_instance = null;

    protected static $Type = "";

    protected  static $conf;

    public static function init($con){
        self::$conf = $con;
    }
    protected  function __construct(){
        $conf = self::$conf;
        //APPID：绑定支付的APPID（必须配置，开户邮件中可查看）
        $this->config['appid'] =  isset($conf['appid']) ? $conf['appid'] : '';
        
        //MCHID：商户号（必须配置，开户邮件中可查看）
        $this->config['mchid'] =  isset($conf['mchid']) ? $conf['reserve1'] : '';
        
        //KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）
        $this->config['mchkey'] =  isset($conf['mchkey']) ? $conf['appkey'] : '';
        
        // APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），
        $this->config['appsecret'] =  isset($conf['appsecret']) ? $conf['appsecret'] : '';
        
        //=======【证书路径设置】=====================================
        /**
         * TODO：设置商户证书路径
         * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，
         * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）
         * @var path
         */
        
        $this->config['sslcert_path'] =  isset($conf['sslcert_path']) ? $conf['sslcert_path'] : '';
        $this->config['sslkey_path'] =  isset($conf['sslkey_path']) ? $conf['sslkey_path'] : '';
        
        //=======【curl代理设置】===================================
        /**
         * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
         * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
         * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
         * @var unknown_type
         */
        
        $this->config['curl_proxy_host'] =  isset($conf['curl_proxy_host']) ? $conf['curl_proxy_host'] : '0.0.0.0';
        $this->config['curl_proxy_port'] =  isset($conf['curl_proxy_port']) ? $conf['curl_proxy_port'] : 0;
        
        //=======【上报信息配置】===================================
        /**
         * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，
         * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少
         * 开启错误上报。
         * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报
         * @var int
         */
        $this->config['report_levenl'] =  isset($conf['report_levenl']) ? $conf['report_levenl'] : 2;
        
        //=======【网页授权代理配置】===================================
        /**
         * TODO：微信WAP支付测试使用正式公众号，受网页授权域名限制，测试或开发环境下，
         * 需借用正式环境网页授权代理机制。请设置正式环境网页授权代理地址
         * @var string
         */
        isset($conf['oauth_proxy']) && $this->config['oauth_proxy'] = $conf['oauth_proxy'];
        
    }
    
    /**
     * 单例模式，唯一入口
     */
    public static  function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        
        return self::$_instance->config;
    }
    
    
}
