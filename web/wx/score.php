<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>一键诊断</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link rel="stylesheet" href="css/css/mui.min.css">

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">

    <style>
        .mui-bar-nav~.mui-content{
            padding-bottom: 0px;
        }
        .mui-content p{
            color:#000;
            line-height: 26px;
            font-weight: bold;
        }
        #chart{
            width: 100%;
            height: 250px;
            text-align: center;
            margin: 0 auto;
        }
        .profit{
            border-top:1px solid #ddd;
            border-bottom:1px solid #ddd;
            padding:10px 0;
        }
        .detail{
            border-bottom:1px dotted #ddd;
            padding:10px 20px;
        }
        .detail div p{
            font-weight: normal;
            font-size:12px;
            line-height: 18px;
        }
        .content{
            position : fixed;
            transition: transform .25s,opacity 1ms .25s;
            transition-timing-function: cubic-bezier(.1,.5,.1,1);
            transform:translate3d(0,100%,0);
            opacity: 0;
        }
        .active{
            transition: transform .25s;
            transition-timing-function: cubic-bezier(.1,.5,.1,1);
            transform: translate3d(0,0,0);
            opacity: 1;
            position:static;
        }
    </style>
</head>
<body class="mui-fullscreen">
<!-- 顶部标题  -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title" v-html="userData.web_daili_title"></h1>
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <p class="game_info">
        <span v-html="userData.current_game"></span>
        <span v-html="userData.play_name"></span>
        <span v-html="userData.cur_level"></span>
    </p>
</header>

<div class="mui-content">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <div id="chart">
        <canvas id="radar" width="320" height="250"></canvas>
    </div>
    <div class="mui-text-center profit">
        <p>
            昨日收益：<span style="color:darkred;font-weight:bold" v-html="profit_yes"></span>元
        </p>
        <p>
            建议收益：<span style="color:darkgreen;font-weight:bold" v-html="profit_sug"></span>元
        </p>
        <p v-html="profit_sug_str">元！</p>
    </div>
    <p class="mui-text-center" v-show="btnisshow">
        <a href="#modal">
            <button class="mui-btn mui-btn-primary" style="margin:10px 0;" v-on:tap="clickBtn()">给点建议吧</button>
        </a>
    </p>
    <div class="content">
        <div class="detail" v-for="val in suggestions">
            <p>
                <span v-html="val.target"></span>
                <span>得分<span v-html="val.score"></span></span>
            </p>
            <div><p v-html="val.suggestion.title"></p></div>
            <div v-for="n in val.suggestion.content">
                <p v-html="n"></p>
            </div>
        </div>
    </div>
</div>

<!--modal-->
<!--<div id="modal" class="mui-modal">-->
    <!--<header class="mui-bar mui-bar-nav" style="height:44px;">-->
        <!--<a class="mui-icon mui-icon-close mui-pull-right" href="#modal"></a>-->
        <!--<h1 class="mui-title" style="line-height:44px;">给您的建议</h1>-->
    <!--</header>-->
    <!--<div class="mui-content">-->

    <!--</div>-->
<!--</div>-->

<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/chart.radar.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/score.js?v=2"></script>
</body>
</html>