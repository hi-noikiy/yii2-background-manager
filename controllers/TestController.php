<?php
/**
 * User: SeaReef
 * Date: 2018/6/8 16:12
 */
namespace app\controllers;

use app\common\Tool;
use app\controllers\api\RechargeController;
use linslin\yii2\curl\Curl;
use Yii;
use yii\db\Query;
use app\models\User;
use yii\log\Logger;
use yii\log\Target;
use yii\web\Response;

class TestController extends BaseController
{
    public $enableCsrfValidation = false;

    /**
     * 测试jquery ajax功能
     */
    public function actionT1()
    {
        return $this->render('t1');
    }

    public function actionT2()
    {
//        echo 't2';
        echo json_encode($_REQUEST);
    }

    public function actionT3()
    {
        return $this->render('t3');
    }

    public function actionT4()
    {
        return $this->render('t4');
    }

    public function actionT5()
    {
//        echo json_encode($_REQUEST);
        var_dump($_REQUEST);
    }

    public function actionT6()
    {
        return $this->render('t6');
    }

    public function actionT7()
    {
        var_dump($_POST);
    }

    /**
     * 原生js进行ajax操作
     */
    public function actionT8()
    {
        return $this->render('t8');
    }

    public function actionT9()
    {
        return $this->render('t9');
    }

    public function actionT10()
    {
        return $this->render('t10');
    }

    public function actionT11()
    {
        var_dump($_POST);
    }

    public function actionT12()
    {
        echo 't12';
    }

    public function actionT13()
    {
        $redis = Yii::$app->mdwl_activity;
        echo '<pre>';
        var_dump($redis);
        die();
    }

    public function actionT14()
    {
        $this->render('t14');
    }

    /**
     * 测试充值
     */
    public function actionPay()
    {
        $config = [
            'app_id' => 'e9b0dd9a-5666-41be-8c3c-d41d15a2157a',
            'app_secret' => '8239d116-d67a-469b-a9a9-d46fdfa07138',
            'time' => time(),
        ];

        $arr = [
            'app_id' => $config['app_id'],
            'timestamp' => $config['time'],
            'app_sign' => md5($config['app_id'] . $config['time'] . $config['app_secret']),
            'channel' => '	BC_EXPRESS',
            'total_fee' => 1,
            'bill_no' => time() . rand(0, 100000),
            'title' => '白开水',
            'return_url' => 'http://recharge.daili-pk.dropgame.cn',
            'notify_url' => 'http://recharge.daili-pk.dropgame.cn',
//            'analysis' => [
//                'product' => [
//                    [
//                    'name' => 'productA',
//                    'count' => 2,
//                    'price' => 1000,
//                    ],
//                    'ip' => '111.121.1.10',
//                ]
//            ],
//                '{"product":[{"name":"product A", "count": 2, "price": 1000}, {"name": "product B", "count":1, "price": 2000}], "ip":"111.121.1.10"}'
        ];

        $data = json_encode($arr);
        echo $data;
        die();

        $curl = new Curl();

        $url = 'https://api.beecloud.cn/2/rest/bill';
        $response = $curl->setRawPostData($data)
            ->post($url);
//        var_dump($data, $url);
        var_dump($response, $curl->errorText);
    }

    public function actionT100()
    {
        if (Yii::$app->request->isAjax) {
            $get = Yii::$app->request->get();
            $page = $get['page'];
            $limit = $get['limit'];

            $count = (new Query())->from('t_daili_player')->count();
            $data = (new Query())->select(['DAILI_ID', 'PLAYER_INDEX', 'PASSWORD', 'NAME', 'TEL', 'ADDRESS', 'TYPE', 'PARENT_INDEX', 'CREATE_TIME'])->from('t_daili_player')->limit($limit)->offset(($page -1) * 10)->all();

            $res['code'] = 0;
            $res['msg'] = '';
            $res['count'] = $count;
            $res['data'] = $data;

            $this->asJson($res);
        } else {
            return $this->render('t100');
        }
    }

    public function actionT101()
    {
        return $this->render('t101.html');
    }

    public function actionYbdetail()
    {
        return $this->render('YBDetail.html');
    }

    public function actionReported()
    {
        return $this->render('reported.html');
    }
    public function actionBereported()
    {
        return $this->render('beReported.html');
    }
    public function actionPayplatfrom()
    {
        return $this->render('payPlatfrom.html');
    }
    public function actionWelcome()
    {
        return $this->render('welcome.html');
    }
    public function actionSearchagent()
    {
        return $this->render('searchAgent.html');
    }
    public function actionAddagent()
    {
        return $this->render('addAgent.html');
    }
    public function actionAgentlist()
    {
        return $this->render('agentList.html');
    }
    public function actionMemberlist()
    {
        return $this->render('memberList.html');
    }
    public function actionApplyauditing()
    {
        return $this->render('applyAuditing.html');
    }
    public function actionPartnerlist()
    {
        return $this->render('partnerList.html');
    }
    public function actionEveryoneagentlist()
    {
        return $this->render('everyoneAgentList.html');
    }
    public function actionAgenttree()
    {
        return $this->render('agentTree.html');
    }
    public function actionAddplatfrom()
    {
        return $this->render('addPlatfrom.html');
    }
    public function actionRevise()
    {
        return $this->render('revise.html');
    }
    public function actionAddpay()
    {
        return $this->render('addPay.html');
    }
    public function actionReportedcheck()
    {
        return $this->render('reportedCheck.html');
    }
    public function actionSealoff()
    {
        return $this->render('sealOff.html');
    }

    public function actionActivityinformation()
    {
        return $this->render('activityInformation.html');
    }
    public function actionMaillist()
    {
        return $this->render('mailList.html');
    }
    //创建邮件
    public function actionCreatemail()
    {
        return $this->render('createMail.html');
    }
    //日志查询
    public function actionLogquery()
    {
        return $this->render('logQuery.html');
    }
    //提现订单
    public function actionCashorder()
    {
        return $this->render('cashOrder.html');
    }
    //充值查询
    public function actionRechargequery()
    {
        return $this->render('rechargeQuery.html');
    }
    //人人代理提现
    public function actionProxiesforeveryone()
    {
        return $this->render('proxiesForEveryone.html');
    }
    //绑定客户经理
    public function actionBoundaccountmanager()
    {
        return $this->render('boundAccountManager.html');
    }
    //修改密码
    public function actionModifythepassword()
    {
        return $this->render('modifyThePassword.html');
    }
    //redis相关设置
    public function actionRedis()
    {
        return $this->render('redis.html');
    }
    //白菜设置
    public function actionCabbage()
    {
        return $this->render('cabbage.html');
    }
    //官方充值
    public function actionOfficialcharge()
    {
        return $this->render('officialCharge.html');
    }
    //货币价格
    public function actionMoneyprice()
    {
        return $this->render('moneyPrice.html');
    }
    //解散房间
    public function actionDisbandedroom()
    {
        return $this->render('disbandedRoom.html');
    }
    //封停账号
    public function actionClosedaccount()
    {
        return $this->render('closedAccount.html');
    }
    //登陆IP黑名单
    public function actionIpblacklist()
    {
        return $this->render('IPblacklist.html');
    }
    //登陆MAC黑名单
    public function actionMacblacklist()
    {
        return $this->render('MACblacklist.html');
    }

    //历史信息库
    public function actionHistoryinfo()
    {
        return $this->render('historyInfo.html');
    }
    //日志查询
    public function actionSearchlog()
    {
        return $this->render('searchLog.html');
    }
    //新增货币
    public function actionAddcurrency()
    {
        return $this->render('addCurrency.html');
    }
    //添加IP黑名单
    public function actionAddipblack()
    {
        return $this->render('addIPBlack.html');
    }
    //添加IP黑名单
    public function actionAddmacblack()
    {
        return $this->render('addMacBlack.html');
    }
    //修改白菜列表
    public function actionRevisecabbagelist()
    {
        return $this->render('reviseCabbageList.html');
    }
    //删除白菜
    public function actionDelcabbagelist()
    {
        return $this->render('delCabbageList.html');
    }
    //增加白菜
    public function actionAddcabbage()
    {
        return $this->render('addCabbage.html');
    }

    //每日运营统计
    public function actionDailyoperstat()
    {
        return $this->render('dailyOperStat.html');
    }
    //代理开局统计
    public function actionAgencyopenstat()
    {
        return $this->render('agencyOpenStat.html');
    }
    //战绩详情
    public function actionRecorddetails()
    {
        return $this->render('recordDetails.html');
    }
    //玩法参与设计
    public function actionPlaypartstat()
    {
        return $this->render('playPartStat.html');
    }
    //代理经营数据
    public function actionAgentoperdata()
    {
        return $this->render('agentOperData.html');
    }
    //游戏日报
    public function actionGamedaily()
    {
        return $this->render('gameDaily.html');
    }
    //玩家金币日志
    public function actionPlayergoldlog()
    {
        return $this->render('playerGoldLog.html');
    }
    //查询玩家
    public function actionQueryplayer()
    {
        return $this->render('queryPlayer.html');
    }

    //GM列表
    public function actionGmlist()
    {
        return $this->render('GMList.html');
    }
    //玩家列表
    public function actionPlayerlist()
    {
        return $this->render('playerList.html');
    }

    public function actionAuthentication()
    {
        return $this->render('authentication.html');
    }
    //白名单
    public function actionWhitelist()
    {
        return $this->render('whiteList.html');
    }

    //黑名单
    public function actionBlacklist()
    {
        return $this->render('blackList.html');
    }
    //跑马灯配置
    public function actionRunhorselampconf()
    {
        return $this->render('runHorseLampConf.html');
    }

    //渠道合伙人-每日运营统计
    public function actionParnerdailyoper()
    {
        return $this->render('parnerDailyOper.html');
    }

    //渠道合伙人-代理开局统计
    public function actionParneragencyopen()
    {
        return $this->render('parnerAgencyOpen.html');
    }
    //渠道合伙人会员信息
    public function actionParnermemberinfo()
    {
        return $this->render('parnerMemberInfo.html');
    }
    //创建跑马灯
    public function actionCreategm()
    {
        return $this->render('createGM.html');
    }
    //修改跑马灯
    public function actionRevisegm()
    {
        return $this->render('reviseGM.html');
    }
    //跑马灯详情
    public function actionGmdetailsall()
    {
        return $this->render('GMDetailsAll.html');
    }
    //跑马灯详情
    public function actionGmdetailsnotplayed()
    {
        return $this->render('GMDetailsNotPlayed.html');
    }
    //跑马灯详情
    public function actionGmdetailsplayed()
    {
        return $this->render('GMDetailsPlayed.html');
    }
    //代理列表的编辑
    public function actionAgentlistrevise()
    {
        return $this->render('agentListRevise.html');
    }
    //代理列表的跟进记录
    public function actionFollowuprecord()
    {
        return $this->render('followUpRecord.html');
    }
    //会员列表的修改代理ID
    public function actionReviseagentid()
    {
        return $this->render('reviseAgentID.html');
    }
    //活动信息下的新增活动
    public function actionAddactive()
    {
        return $this->render('addActive.html');
    }
    //货币价格下的修改
    public function actionRevisecurrency()
    {
        return $this->render('reviseCurrency.html');
    }
    //经纪人列表
    public function actionBrokerlist()
    {
        return $this->render('brokerList.html');
    }
    //代理信息下的玩家列表
    public function actionPlayerlistagent()
    {
        return $this->render('playerListAgent.html');
    }
    //收益详情
    public function actionIncomedetails()
    {
        return $this->render('incomeDetails.html');
    }
    //下级详情
    public function actionLowerleveldetails()
    {
        return $this->render('lowerLevelDetails.html');
    }
    //伞下输赢情况
    public function actionUmbrellawinorlose()
    {
        return $this->render('umbrellaWinOrLose.html');
    }
    //伞下玩家情况
    public function actionUmbrellaplayer()
    {
        return $this->render('umbrellaPlayer.html');
    }
    //伞下活跃玩家分析
    public function actionUmberactiveplayer()
    {
        return $this->render('umberActivePlayer.html');
    }
    //渠道合伙人列表-消耗详情
    public function actionConsumedetails()
    {
        return $this->render('consumeDetails.html');
    }
    //渠道合伙人列表-下级详情
    public function actionLowerlevel()
    {
        return $this->render('lowerLevel.html');
    }
    //萌新设置
    public function actionNewsetting()
    {
        return $this->render('newsetting.html');
    }
    //打通牌黑名单
    public function actionOpencardblacklist()
    {
        return $this->render('openCardBlackList.html');
    }
    //添加黑名单
    public function actionAddblacklist()
    {
        return $this->render('addBlackList.html');
    }
    //白名单-添加
    public function actionAddwhite()
    {
        return $this->render('addWhite.html');
    }
    //黑名单-添加
    public function actionAddblack()
    {
        return $this->render('addBlack.html');
    }
    //代理开局统计查询
    public function actionAgentopensearch()
    {
        return $this->render('agentOpenSearch.html');
    }
    //玩法与统计
    public function actionPlaymethod()
    {
        return $this->render('playMethod.html');
    }
    //游戏日报统计
    public function actionGamedailycount()
    {
        return $this->render('gameDailyCount.html');
    }

    //运营统计-今日概况
    public function actionTodayconditionoper()
    {
        return $this->render('todayConditionOper.html');
    }

    //添加收款账号
    public function actionAddreceivableaccount()
    {
        return $this->render('addReceivableAccount.html');
    }
    //中心订单查询
    public function actionCenterordersearch()
    {
        return $this->render('centerOrderSearch.html');
    }

    //机器人统计
    public function actionRobotstatistics()
    {
        return $this->render('robotStatistics.html');
    }
    //机器人设置
    public function actionRobotsetting()
    {
        return $this->render('robotSetting.html');
    }
    //机器人列表
    public function actionRobotlist()
    {
        return $this->render('robotList.html');
    }
    //推筒子数据查询
    public function actionTtzdata()
    {
        return $this->render('TTZData.html');
    }

    //跑马灯列表
    public function actionPmdlist()
    {
        return $this->render('PMDList.html');
    }
    //机器人设置
    public function actionRobotset()
    {
        return $this->render('RobotSet.html');
    }
    //轮播图片设置
    public function actionAct()
    {
        return $this->render('activityInformation.html');
    }
    //轮播图片设置
    public function actionHistoryactiveinfo()
    {
        return $this->render('historyActiveInfo.html');
    }



    //
    //
    public function actionTest()
    {
        return $this->render('test01.html');
    }

     //轮播图片设置
     public function actionRobo()
    {
        return $this->render('robotSetting.html');
    }



    /**
     * 测试支付宝个人充值
     * 账号[imissyoulang@126.com]的授权码是[d9c8dcce2527311ac4e1537f66e3f508]，授权时间[2018-07-01 18:29:21]到[2018-07-02 18:29:21]
     */
    public function actionAlipay()
    {
        file_put_contents('d:/alipay.log', print_r($_REQUEST, 1), FILE_APPEND);
    }

    /**
     * 充值流程假想
     * 1、拉起支付宝转账、
     * 2、转账成功、异步通知发起充值回调
     */
    public function actionD1()
    {
        return $this->renderPartial('d1', ['data' => [
            'name' => 'langhaijia',
            'age' => 'lang',
        ]]);
    }

    public function actionD2()
    {
        return $this->renderPartial('d2');
    }


    public function actionDailitree1()
    {
        return $this->render('dailitree2.html');
    }










    /*public function actionTest01()
       {
          return $this->render('test01.html');
       }*/

    public function actionT200()
    {echo '{"code":0,"msg":"","count":16,"data":[
    {"date1":"20180506","userID":"01","userName":"八个字八个字八个","current":"999999999","count":"999999999","serviceCharge":"999999999","win":"999999999","fail":"999999999","grossroceeds":"999999999","winFail":"300%","parentInfo":"八个字八个字八个","IP":"57","grandInfo":"八个字八个字八个"}
    ,{"date1":"20180506","userID":"01","userName":"八个字八个字八个","current":"999999999","count":"999999999","serviceCharge":"999999999","win":"999999999","fail":"999999999","grossroceeds":"999999999","winFail":"300%","parentInfo":"八个字八个字八个","IP":"57","grandInfo":"八个字八个字八个"}
    ]}';
    }

    public function actionT201()
    {echo '{"code":0,"msg":"","count":1000,"data":[
    {"index":10000,"ID":"user-0","agenID":"1111","wechat":"签名-0","reportedType":255,"reportContent":24,"failOrWin":"作家","userId":57,"name":57}
    ,{"index":10000,"ID":"user-0","wechat":"签名-0","reportedType":255,"reportContent":24,"failOrWin":"作家","userId":57,"name":57}
    ,{"index":10000,"ID":"user-0","wechat":"签名-0","reportedType":255,"reportContent":24,"failOrWin":"作家","userId":57,"name":57}
    ]}';
    }

    public function actionT202()
    {echo '{"code":0,"msg":"","count":1000,"data":[
    {"index":10000,"ID":"user-0","bereportTime":"女","beReportPN":"城市-0","wechat":"签名-0","surplus":255,"report1":24,"report2":82830700,"report3":"作家","report4":57,"reportcontent":57,"IP":57,"equipment":57,"equipment1":57,"equipment2":57,"equipment3":57,"equipment4":57,"equipment5":57}
    ,{"index":10000,"ID":"user-0","bereportTime":"女","beReportPN":"城市-0","wechat":"签名-0","surplus":255,"report1":24,"report2":82830700,"report3":"作家","report4":57,"reportcontent":57,"IP":57,"equipment":57}
    ]}';
    }

    public function actionT203()
    {echo '{"code":0,"msg":"","count":16,"data":[
    {"DAILI_ID":"20180506","PLAYER_INDEX":"01","PASSWORD":"八个字八个字八个","NAME":"999999999","TEL":"999999999","ADDRESS":"999999999","CREATE_TIME":"999999999"}
    ]}';
    }
    public function actionT204()
    {echo '{"code":0,"msg":"","count":16,"data":[
    {"DAILI_ID":"20180506","PLAYER_INDEX":"01","PASSWORD":"八个字八个字八个","NAME":"999999999","TEL":"999999999","ADDRESS":"999999999","CREATE_TIME":"999999999"}
    ,{"DAILI_ID":"20180506","PLAYER_INDEX":"01","PASSWORD":"八个字八个字八个","NAME":"999999999","TEL":"999999999","ADDRESS":"999999999","CREATE_TIME":"999999999"}
    ]}';
    }


    public function actionT205()
    {echo '{"code":0,"msg":"","count":10,"data":[
    {"playerID":"2222","gameID":"1111","platName":"10000","platInfo":"user-0","feilv":"签名-0","limitTotle":"255","peopleTotle":"24","Alipay":"支持","weightAli":"82830700","wechat":"作家","weightWechat":"57"}
    ,{"platName":"10000","platInfo":"user-0","feilv":"签名-0","limitTotle":"255","peopleTotle":"24","Alipay":"支持","weightAli":"82830700","wechat":"作家","weightWechat":"57"}
    ]}';
    }
    public function actionT206()
    {echo '{"code":0,"msg":"","count":10,"data":[
    {"white":"0","sex":"女","public1":"是","TypeV":"0","typeT":"元宝","num":"23","publicDate":"2013-02-01 00:00:00","sendDate":"2013-02-01 00:00:00","objectID1":"objectID","announcement1":"是","status":"待发送","title":"啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊","agenID":"1234","userID":"1111","tel":"123456","currencyID":"001","number":"10000","ID":"user-0","creater":"签名-0","creationTime":"255","content":"24","public":"是","address":"82830700","startTime":"2018-01-12","terminalTime":"2017-02-18","totalRevenue":"57","functionary":"57"}
    ,{"white":"1","index":"index","number":"number","title":"title","content":"啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊","appendix":"appendix","publishObject":"多用户","sendDate":"2018-02-12 12:23:21","isPublic":"是","publicDate":"2018-03-12 13:23:34","public1":"否","status":"已发送","title":"啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊","number":"10001","ID":"user-1","higherLevel":"签名-0","nickName":"255","userName":"24","phone":"支持","address":"82830700","startData":"作家","overplus":"57","totalRevenue":"57","functionary":"57"}
    ,{"white":"1","public1":"是","status":"准备发送","title":"啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊","number":"10002","ID":"user-2","higherLevel":"签名-0","nickName":"255","userName":"27","phone":"支持","address":"82830700","startData":"作家","overplus":"57","totalRevenue":"57","functionary":"57"}

    ]}';
    }


    public function actionT207()
    {echo '{"code":0,"msg":"","count":1000,"data":[
    {
    "series":[
        "05-01",
        "05-08",
        "05-15",
        "05-22",
        "05-29"
    ],
    "rows":[
        {
            "values":[
                [13174],
                [13486],
                [13816],
                [13832],
                [11374]
            ]
        }
    ]
}
    ]}';
    }

    /**
     * 测试支付宝到账通知
     * 支付平台的角色
     */
    public function actionNotify()
    {
//        1、生成平台唯一订单号
        $order_id = time() . rand(1, 100);
//        var_dump($order_id);

//        传入玩家信息、uid、订单号、
        $request = Yii::$app->request;
        $uid = $request->get('uid');

//        2、调起具体支付宝账号、具体金额的支付链接

//        3、保障唯一性的判断方法、发起充值的账号id和回调账号一致、金额、时间
    }

    public function actionT208()
    {
        echo '{"code":0,"msg":"","count":10,"data":[
    {"a":[1,2,3,4,5,6,7,8,9,10,11,12,13,14],"b":[1,2,3,4,5,6,7,8,9,10,11,12,13,14],"c":[1,2,3,4,5,6,7,8,9,10,11,12,13,14],"d":[1,2,3,4,5,6,7,8,9,10,11,12,13,14],"e":[1,2,3,4,5,6,7,8,9,10,11,12,13,14],"f":[1,2,3,4,5,6,7,8,9,10,11,12,13,14],"g":[1,2,3,4,5,6,7,8,9,10,11,12,13,14]}
    ]}';
    }

    public function actionT209()
    {echo '{"code":0,"msg":"","count":10,"data":[
    {"character":"1234","operationInterval":"1111","readyWait":"123456","randomEvent":"001","departureRate":"10000","timeInterval":"user-0","expressionTrigger":"签名-0","SMSTrigger":"255","enduranceTrigger":"24","messageProbability":"是","cordon0":"82830700","advance":"2018-01-12","cordon1":"2017-02-18","reduce":"57","highest":"57","kibitzing1":"11111"}
    ,{"character":"1234","operationInterval":"1111","readyWait":"123456","randomEvent":"001","departureRate":"10000","timeInterval":"user-0","expressionTrigger":"签名-0","SMSTrigger":"255","enduranceTrigger":"24","messageProbability":"是","cordon0":"82830700","advance":"2018-01-12","cordon1":"2017-02-18","reduce":"57","highest":"57"}
    ,{"number":"003","character":"1234","operationInterval":"1111","readyWait":"123456","randomEvent":"001","departureRate":"10000","timeInterval":"user-0","expressionTrigger":"签名-0","SMSTrigger":"255","enduranceTrigger":"24","messageProbability":"是","cordon0":"82830700","advance":"2018-01-12","cordon1":"2017-02-18","reduce":"57","highest":"57"}
    ,{"number":"004","character":"1234","operationInterval":"1111","readyWait":"123456","randomEvent":"001","departureRate":"10000","timeInterval":"user-0","expressionTrigger":"签名-0","SMSTrigger":"255","enduranceTrigger":"24","messageProbability":"是","cordon0":"82830700","advance":"2018-01-12","cordon1":"2017-02-18","reduce":"57","highest":"57"}
    ,{"number":"005","character":"1234","operationInterval":"1111","readyWait":"123456","randomEvent":"001","departureRate":"10000","timeInterval":"user-0","expressionTrigger":"签名-0","SMSTrigger":"255","enduranceTrigger":"24","messageProbability":"是","cordon0":"82830700","advance":"2018-01-12","cordon1":"2017-02-18","reduce":"57","highest":"57"}

    ]}';
    }

    public function actionT500()
    {
        return $this->render('t500');
    }

    public function actionT501()
    {
        if (Yii::$app->request->isPost) {
            $db = Yii::$app->db;
            $count = (new Query())
                ->select('stat_mengxin')
                ->count();
            $data = (new Query())
                ->select('*')
                ->from('stat_mengxin')
                ->all();

            $this->writeLayui(self::CODE_LAYUI_OK, '', $count, $data);
        } else {
            return $this->render('t501');
        }
    }

    public function actionT502()
    {

    }

    public function actionT503()
    {
        $info = Tool::sendGold(2, 3, 5000, 1, '30705020', 910, 1114112, 'iappy');
        var_dump($info);
        $info = Tool::sendGold(Tool::RECHARGE_GAME, Tool::PROPS_TYPE, 5000, Tool::GOLD_INCR, '30705020');
        var_dump($info);
    }

    /**
     * 测试session数据
     */
    public function actionT601()
    {
        $session = Yii::$app->session;
//        var_dump($session);
        $info = $session->set('username', 'langhaijiao');
        $info = $session->get('username');
        $info = $session->getName();

        var_dump($info);
    }

    /**
     * 测试db链接
     */
    public function actionT602()
    {
        Yii::trace('start lang');
        Target::className();
        Logger::className();
    }

    public function actionT603()
    {
        $db = new \yii\db\Connection([
            'dsn' => 'mysql:host=192.168.1.158;dbname=oss',
            'username' => 'root',
            'password' => 123456,
            'charset' => 'utf8',
        ]);

        $db2 = Yii::$app->db;
        $record = $db2->createCommand("SELECT * FROM auto_config")->queryAll();
        $record = $db2->createCommand("SELECT * FROM auto_config WHERE id = 30")->queryOne();
        $record = $db2->createCommand("SELECT name FROM auto_config")->queryColumn();
        $record = $db2->createCommand("SELECT COUNT(*) FROM auto_config")->queryScalar();

//        绑定参数
        $record = $db2->createCommand("SELECT * FROM auto_config WHERE id = :id ")->getSql();
        $params = [':id' => 30, ':game_id' => 0];
        $record = $db2->createCommand("SELECT * FROM auto_config WHERE id = :id AND game_id = :game_id")
            ->bindValues($params)
            ->queryOne();

        $record = $db2->createCommand("SELECT * FROM auto_config WHERE id = :id")
            ->bindParam(':id', $id);

        $record = $db2->createCommand("SELECT * FROM auto_config")->query();
//        $record = $db2->createCommand()->upsert('')
    }

    /**
     * layui与query-builder的整理
     */
    public function actionT604()
    {
        return $this->render('t604');
    }

    public function actionT605()
    {
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_XML,
            'formatters' => [
                \yii\web\Response::FORMAT_XML => [
                    'class' => 'yii\web\XmlResponseFormatter',
                    'rootTag' => 'urlset', //根节点
                    'itemTag' => 'url', //单元
                ],
            ],
            'data' => [ //要输出的数据
                [
                    'loc' => 'http://********',
                ],
            ],
        ]);
    }

    public function actionT606()
    {
        $arr = [
            'aaa' => 1,
            'bbb' => 2,
        ];
        echo Tool::buildXml($arr);
    }

    /**
     * 商户入驻、只请求一次、多次执行可能处罚微信风控
     */
    public function actionCheckin()
    {
        $merNo = 47135;
        $version = '1.0';
        $payType = 'WXZF_ONLINE';
        $randomStr = $this->generate_str(10);
        $appkey = '';
        $sign = 'MerNo=' . $merNo . '&PayType=' . $payType . '&RandomStr=' . $randomStr;
        $mername = '广州诗迪科技有限公司';
        $shortname = '广州诗迪';
        $servicePhone = '15036581447';
        $private_key = RechargeController::ecpss_info($merNo)['private_key'];
        $sign = Tool::genSign($sign, $private_key);

        $params['ScanMerchantInRequest'] = [
            'MerNo' => $merNo,
            'Version' => $version,
            'SignInfo' => $sign,
            'PayType' => $payType,
            'RandomStr' => $randomStr,
            'ChannelNo' => '235009412',
            'MerchantInfo' => [
                'MerName' => $mername,
                'ShortName' => $shortname,
                'ServicePhone' => $servicePhone,
                'Business' => 501
            ]
        ];


        $xml = Tool::buildXml($params, 'xml', 'version="1.0" encoding="utf-8"');
        $url = 'https://gwapi.yemadai.com/scanpay/merchantIn/';

        $curl = new \yii\base\Curl();
        $res = $curl->setPostParams([
            'requestDomain' => base64_encode($xml),
        ])->post($url);
//        $res = $curl->post($url, base64_encode($xml));
        var_dump($res);
        var_dump(base64_decode($res));
    }

    /**
     * 商户入驻查询
     */
    public function actionCheckInfo()
    {
        $merNo = 46814;
        $version = '1.0';
        $payType = 'WXZF_ONLINE';
        $randomStr = $this->generate_str(10);
        $appkey = '';
        $companyno = 'sweep-c278f9bc490143beb0a2833b680c2d91';
        $sign = 'MerNo=' . $merNo. '&CompanyNo=' . $companyno . '&PayType=' . $payType . '&RandomStr=' . $randomStr;
        $mername = '广州诗迪科技有限公司';
        $shortname = '广州诗迪';
        $servicePhone = '15036581447';

        $private_key = RechargeController::ecpss_info($merNo)['private_key'];
        $sign = Tool::genSign($sign, $private_key);

        $params['ScanMerchantInQueryRequest'] = [
            'MerNo' => $merNo,
            'Version' => $version,
            'CompanyNo' => $companyno,
            'PayType' => 'WXZF_ONLINE',
            'RandomStr' => $randomStr,
            'SignInfo' => $sign,
        ];
        $xml = Tool::buildXml($params, 'xml', 'version="1.0" encoding="utf-8"');
        $url = 'https://gwapi.yemadai.com/scanpay/merchantInQuery';

        $curl = new \yii\base\Curl();
        $res = $curl->setPostParams([
            'requestDomain' => base64_encode($xml),
        ])->post($url);
        var_dump($res);
        var_dump(base64_decode($res));
    }

    /**
     * 生成随机字符串
     */
    public function generate_str($length)
    {
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= chr(mt_rand(33, 126));
        }

        return md5($str);
    }

    public function actionT1000()
    {
        $params = [
            'MerNo=46659',
            'BillNo=2019012319551694796',
            'OrderNo=0253612092',
            'Amount=10.01',
            'Succeed=88',
//            'Result=SUCCESS',
        ];
//        拼接加密串
        $sign_str = implode('&', $params);

//        echo '<hr/>';
//        echo $sign_str;
//        echo '<hr/>';
//        生成加密串
//        $sign = Tool::genSign($sign_str, RechargeController::ecpss_info(46659)['private_key']);
//        echo $sign;
//        echo '<hr/>';


        $sign = 'GhLjjF+BCBvpGtL9tqoxc3AsMrHPXUFk31rTrprNgvZnwzeS2oEqYCpaaiwIHHfZzg0UCkiXpIpMqyw45Ewr66jg9gfXDUje/e6JLQ01/vQW0vQgZ1wF6jszxzNNdqhoYesdntijFqXyHmVXnn/jUzk2OzNzKofOU6Vu3YvWmfQ=';
        var_dump($sign_str, $sign);
//        die();
//        验证加密串
        $result = Tool::verifySign($sign_str, $sign, RechargeController::ecpss_info(46659)['public_key']);
        var_dump($result);
    }

//MerNo=46659&BillNo=2019012317033560037&OrderNo=0253487207&Amount=10.01&Succeed=88
}
