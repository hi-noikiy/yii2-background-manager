<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>百万棋牌室代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/invite.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/agency.min.css">
    <script type="text/javascript" src="static/mobile/agent/lib/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a href="javascript:void(0);" onclick="history.go(-1)"></a>
            <h1>代理学堂</h1>
        </div>
    </div>

    <!-- 头部信息 -->
    <?php // include 'base_head.php'; ?>
    <div class="agency-wrap">
        <div class="tab-container">
            <div class="tab-main">
                <style>
                    .dhelp{padding: 10px; line-height: 0.43rem}
                    .dhelp h2{text-align:center; font-size: 0.5rem; padding-top:10px; }
                    .dhelp .spin{text-align:right; color:#5F5959;text-align: center;padding: 16px 0}
                    .dhelp .dcont{color:#240E0F; line-height: 0.6rem; padding: 0 0.2rem}
                    .dhelp .dcont p{margin-bottom:20px;}
                    .dhelp .dcont strong{font-weight:700}
                    .info a{font-weight:700; font-size: 0.29rem; display: block; width: 100%}
                </style>
                <div class="dhelp">
                    <h2>
                        <?php
                        $my_title_cfg = array(
                                1 => '制度简介',
                                2 => '产品优势',
                                3 => '开通代理后怎么赚钱？',
                                4 => '怎么开通下级代理？',
                                5 => '一些不外传的小秘籍',
                                6 => '代理收益模拟',
                        );
                        echo $my_title_cfg[$_GET['wf']];
                        ?>
                    </h2>
                    <div class="spin"> 2018.1.26 修订</div>
                    <div class="dcont">
                        <?php
                            if($_GET['wf'] == 1) {
                                ?>
                                <p>
                                    <!--<strong>制度简介</strong> <br>-->
                                    1.百万棋牌室是基于线上真人竞技模式的网上棋牌室，绿色安全、公平稳定，只要会使用手机，无需任何成本，人人皆可创业；
                                    <br>
                                    2.成为代理后，可以通过游戏内分享、代理后台保存推广二维码等方式，宣传您专属的推广二维码广告图，邀请玩家扫码下载游戏；
                                    <br>
                                    3.玩家一旦通过扫描某位代理的二维码进入游戏，则将终身被绑定为该代理的下级玩家，代理可以根据情况，酌情为自己的玩家从代理后台为其开通代理，成为自己的下级代理；
                                    <br>
                                    4.百万棋牌实行服务费消耗即返的模式，自己下级的玩家在游戏中消耗的桌费（包含下级代理的所有玩家和下下级代理的所有玩家），都会即时计算返利给各级代理，并且可以随时通过代理后台将返利收益提现；
                                    <br>
                                    5.返利制度分为三级，任何人都可成为代理，代理可享受自己下级玩家桌费消耗价值的35%，自己下级代理的玩家的10%，还有下下级代理的玩家的5%；
                                    <br>
                                    6.成为代理后，要积极拓展下级玩家和下级代理，否则一段时间后将被取消代理资格喔！
                                </p>
                                <?php
                            } else if($_GET['wf'] == 2) {
                                ?>
                                <p>
                                    <!--<strong>产品优势</strong> <br>-->
                                    1.百万棋牌室采用棋牌类产品最先进简单的3.0模式，可私密建房，亦可随时加入陌生人房间，随时开局，不用等人，不用频繁换群，不封号，更没有任何风险；
                                    <br>
                                    2.百万棋牌室是由前腾讯团队倾力打造的一款高品质风格化棋牌游戏产品，秉承业内最安全、最稳定、最公平的研发原则，加上公司强大的资金、推广运营实力，必将成为国内真人竞技品类最强大的平台之一，早加入早赚钱；
                                    <br>
                                    3.针对目前市场上现存相同玩法的房卡类游戏法律风险过大而且容易被封群封号的问题，成为百万棋牌室的代理完全不用担心这些，百万棋牌室线上充值购买元宝，公司收入及代理返利合法纳税，不会有任何法律风险，更不会被封群封号；
                                    <br>
                                    4.百万棋牌室代理模式操作简单，管理成本极低，但利润绝对会是您现在所代理游戏的十倍以上，可以兼职代理百万棋牌室，顺手发发朋友圈即可将现有资源变成现金！越早行动，机会越多哟！
                                </p>
                                <?php
                            } else if($_GET['wf'] == 3) {
                                ?>
                                <p>
                                    <!--<strong>开通代理后怎么赚钱？</strong> <br>-->
                                    1.发展下级玩家：因为下级玩家可以为您直接提供35%的高额桌费消耗返利，所以成为代理后，在游戏内通过“分享”功能将自己专属的推广二维码发到朋友圈，或者进入代理后台-推广二维码中将二维码广告图保存下来发给亲朋好友，都是最直接可以获取下级玩家的方法；
                                    <br>
                                    2.发展下级代理：一个人的力量是有限的，想要获取最高利润最有效的方法就是把身边更有人脉和能量的人发展成自己的下级代理了。下级代理可以继续发展他的玩家，而这些人将仍然会给你带来桌费消耗10%的返利！同时下级代理还可以再继续发展他自己的下级代理，你将可以从下下级代理的玩家桌费消耗获得5%的返利！所以，真正的躺赚，从寻找优质下级代理开始吧！
                                </p>
                                <?php
                            } else if($_GET['wf'] == 4) {
                                ?>
                                <p>
                                    <!--<strong>怎么开通下级代理？</strong> <br>-->
                                    1.您可以自助在“百万同城约局”公众号集成的代理后台-开通代理功能中，将您的下级玩家（必须下载并登陆过游戏）开通为下级代理；
                                    <br>
                                    2.可开通的下级代理数量是有限的，最初只有5个，如收益达到一定水平，可联系客服申请提高名额数量；
                                    <br>
                                    3.上级代理有权将0收益的下级代理的代理资格收回，释放可开通代理数量名额。
                                </p>
                                <?php
                            } else if($_GET['wf'] == 5) {
                                ?>
                                <p>
                                    <!--<strong>一些不外传的小秘籍</strong> <br>-->
                                    1.好友扫码后即会绑定成为你的永久下级，但他们往往会忘记下载登陆游戏，您可以在代理后台-我的玩家功能中观察他们的登陆状态，如果一直显示未登陆，可以尝试联系他们尽快下载游戏登陆哟；
                                    <br>
                                    2.刚被开通成为代理的下级代理，往往不知道怎么进入代理后台开启他的代理扩张之路，这时候需要把我们“百万同城约局”的公众号分享给他，告知点击右下角代理后台即可；
                                    <br>
                                    3.对下级代理进行一些必要的业务指导能大大加速这些新司机上路；
                                    <br>
                                    4.坚持不懈，积少成多，是我们见到的最容易成功的代理品质。
                                </p>
                                <?php
                            } else if($_GET['wf'] == 6) {
                                ?>
                                <p>
                                   <!-- <strong><A name="top6">服务费收取规则及代理收益模拟</A></strong> <br>-->
                                    多数产品我们以底注的一定比例收取固定的元宝数作为每局的服务费（桌费），
                                    例如麻将类产品收取底注额度50%-100%作为桌费（随着底注加大比例降低）；<br>
                                    三张牌则收取底注的30%-50%作为桌费（随着底注加大比例降低）；<br>
                                    如代理名下有30名玩家在线游戏1小时（约等于80局）底注为100的三张牌游戏，代理的收益=30x80x100x50%x35%=42000元宝，也就是420元，平均每天玩5小时：5x420=每天收益2100元！
                                </p>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>
</body>
</html>