<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>主页</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />
    <link href="css/css/icons-extra.css" rel="stylesheet" />
    <link href="css/css/mui.picker.min.css" rel="stylesheet" />
    <link href="css/css/mui.poppicker.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">
    <link rel="stylesheet" href="css/css/index.css">

</head>
<body>
<!-- 顶部标题  -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title" v-html="userData.user_info.web_daili_title"></h1>
    <p class="game_info">
        <span v-html="userData.user_info.current_game"></span>
        <span v-html="userData.user_info.play_name"></span>
        <span v-html="userData.user_info.cur_level"></span>
    </p>
</header>

<!--内容区域-->
<div class="mui-content">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <!-- 轮播图
    <div id="slider" class="mui-slider" v-show="userData.user_info.daili_banner.length!=0">
        <div class="mui-slider-group mui-slider-loop">
            <!-- 额外增加的一个节点(循环轮播：第一个节点是最后一张轮播)
            <div class="mui-slider-item mui-slider-item-duplicate" v-show="userData.user_info.daili_banner.length>1">
                <a href="#">
                    <img v-bind:src="userData.imgL">
                </a>
            </div>
            <!-- img 
            <div class="mui-slider-item" v-for="img in userData.user_info.daili_banner">
                <a href="#">
                    <img v-bind:src="img">
                </a>
            </div>
            <!-- 额外增加的一个节点(循环轮播：最后一个节点是第一张轮播) 
            <div class="mui-slider-item mui-slider-item-duplicate" v-show="userData.user_info.daili_banner.length>1">
                <a href="#">
                    <img v-bind:src="userData.imgF">
                </a>
            </div>
        </div>
        <div class="mui-slider-indicator" v-show="userData.user_info.daili_banner.length>1">
            <div class="mui-indicator mui-active"></div>
            <div class="mui-indicator"></div>
        </div>
    </div>-->
    <div id="home" class="mui-control-content mui-active">
        <!-- 九宫格 -->
        <ul class="mui-table-view mui-grid-view mui-grid-9">
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a href="#rank" id="rank_btn" v-on:tap="rank()">
                    <span class="mui-icon-extra mui-icon-extra-class" style="color:#FF7360"></span>
                    <div class="mui-media-body">排行榜</div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">-->
                <!--&lt;!&ndash;<a href="#task" id="task_btn" v-on:tap="task()">&ndash;&gt;-->
                <!--<a class="skip_btn" id="mytask">-->
                    <!--<span class="mui-icon-extra mui-icon-extra-dictionary" style="color:#5B96F6"></span>-->
                    <!--&lt;!&ndash;<span class="mui-icon-extra mui-icon-extra-dictionary" style="color:#5B96F6">&ndash;&gt;-->
                        <!--&lt;!&ndash;<span class="noread noread_task"></span>&ndash;&gt;-->
                    <!--&lt;!&ndash;</span>&ndash;&gt;-->
                    <!--<div class="mui-media-body">任务</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="dataAnalysis_n">
                    <span class="mui-icon-extra  mui-icon-extra-order" style="color:#4CBCF7"></span>
                    <div class="mui-media-body">数据分析</div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="toCash_n">
                    <span class="mui-icon-extra mui-icon-extra-prech" style="color:#FED030"></span>
                    <div class="mui-media-body">提现</div>
                </a>
            </li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="score">
                    <span class="mui-icon-extra mui-icon-extra-trend" style="color:#CD3353"></span>
                    <div class="mui-media-body">一键诊断</div>
                </a>
            </li>

            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#mail" id="mail_btn" v-on:tap="mail()">-->
                    <!--<span class="mui-icon-extra mui-icon-extra-comment" style="color:#A8DD99">-->
							<!--<span class="noread noread_mail"></span>-->
						<!--</span>-->
                    <!--<div class="mui-media-body">邮箱</div>-->
                <!--</a>-->
            <!--</li>-->

            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="nextDetail_n">
                    <span class="mui-icon-extra mui-icon-extra-peoples" style="color:#A8DD99"></span>
                    <div class="mui-media-body">下级明细</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn" id="billDetail_n">
                    <span class="mui-icon-extra mui-icon-extra-gold" style="color:#FFAB1F"></span>
                    <div class="mui-media-body">账单明细</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn zt" id="toNext_n">
                    <span class="mui-icon-extra mui-icon-extra-addpeople" style="color:#4DC6EE"></span>
                    <div class="mui-media-body">授权下级</div>
                </a>
            </li>
             <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3">
                <a class="skip_btn zt" id="index_all">
                    <span class="mui-icon-extra mui-icon-extra-find" style="color:#4DC6EE"></span>
                    <div class="mui-media-body">返回首页</div>
                </a>
            </li>

            mui-icon mui-icon-undo
        </ul>
        <!-- 数据展示 -->
        <ul class="mui-table-view mui-grid-view mui-grid-9" id="data_show">
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body">余额(元)</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body">会员</div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body">桌数</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="toCash_n">
                    <div class="mui-media-body show1" id="pay_back_monney" v-html="userData.user_info.pay_back_monney"></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="nextDetail_n">
                    <div class="mui-media-body show1" id="member_num" v-html="userData.user_info.member_num"></div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body show1" v-html="">0</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="billDetail_n">
                    <div class="mui-media-body">今日收益</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="nextDetail_n">
                    <div class="mui-media-body">今日绑定</div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body">环比昨日</div>-->
                <!--</a>-->
            <!--</li>-->
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body show2">+<span v-html="userData.user_info.today_new_profit_num"></span></div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#">
                    <div class="mui-media-body show2">+<span v-html="userData.user_info.today_new_member_num"></span></div>
                </a>
            </li>
            <!--<li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-4">-->
                <!--<a href="#">-->
                    <!--<div class="mui-media-body show2"><span>+</span><span v-html="">0</span></div>-->
                <!--</a>-->
            <!--</li>-->
        </ul>
        <!-- 等级显示 -->
        <ul class="mui-table-view" id="level_des">
            <li class="mui-table-view-cell mui-media">
                <h5 class="mui-text-center">
                    <span>当前等级：<strong v-html="userData.user_info.cur_level"></strong></span>
                    <span v-show="userData.user_info.daili_level!=1">下一等级：<strong v-html="userData.user_info.next_level" style="color:#FF3232;"></strong></span>
                </h5>
            </li>
        </ul>
        <!--进度条-->
        <ul class="mui-table-view" id="show_progress" v-show="userData.user_info.current_game!='昭通麻将'">
            <li class="mui-table-view-cell mui-media" id="icon-help">
                <a href="#tips"><span class="mui-icon mui-icon-help" style="color:#007aff;"></span></a>
            </li>
            <li class="mui-table-view-cell mui-media" v-for="val in dailiUpData" v-show="dailiUpData.length!=0">
                <div class="mui-text-center">
                    <p>
                        <span v-html="val.content"></span>:
                        <span v-html="val.done" style="color:#FF3232;"></span>
                        <span v-show="userData.user_info.daili_level!=1">/<span v-html="val.target"></span></span>
                    </p>
                    <p class="mui-progressbar mui-progressbar-in" data-progress="{{(val.done)/(val.target)*100}}">
                        <span></span>
                        <strong><i v-html="(val.done)/(val.target)*100 | currency '' ''"></i>%</strong>
                    </p>
                </div>
            </li>
            <li class="mui-text-center" v-show="dailiUpData.length==0">
                暂未开放，详情请联系客服咨询！
            </li>
            <li class="mui-table-view-cell mui-media">
                <h5 class="mui-text-center">
                    <span>当前分成：<strong v-html="userData.cur_per"></strong>%</span>
                    <span v-show="userData.user_info.daili_level!='1'">升级后分成：<strong v-html="userData.target_per" style="color:#FF3232;"></strong>%</span>
                </h5>
            </li>
            <li class="mui-table-view-cell mui-media mui-text-center" style="color:#ddd;" v-show="userData.user_info.special_game_desc!=''">
                <span v-html="userData.user_info.special_game_desc"></span>
            </li>
        </ul>
    </div>
    <div id="daili" class="mui-control-content">
        <ul class="mui-table-view">
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="nextDetail_n">
                    <span class="mui-icon mui-icon-download"></span>
                    <span>下级明细</span>
                </a>
            </li>
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn zt" id="toNext_n">
                    <span class="mui-icon mui-icon-person"></span>
                    <span>授权下级</span>
                </a>
            </li>
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="billDetail_n">
                    <span class="mui-icon mui-icon-bars"></span>
                    <span>账单明细</span>
                </a>
            </li>
        </ul>
    </div>
    <div id="fuwu" class="mui-control-content">
        <ul class="mui-table-view mui-grid-view mui-grid-9">
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="imgdiy">
                    <span class="mui-icon-extra mui-icon-extra-gift" style="color:#5B96F6"></span>
                    <div class="mui-media-body">素材定制</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="dataAnalysis_n">
                    <span class="mui-icon-extra mui-icon-extra-calc" style="color:#FFAB1F"></span>
                    <div class="mui-media-body">数据分析</div>
                </a>
            </li>
            <li class="mui-table-view-cell mui-media mui-col-xs-6 mui-col-sm-6">
                <a href="#" class="skip_btn" id="score">
                    <span class="mui-icon-extra mui-icon-extra-trend" style="color:#CD3353"></span>
                    <div class="mui-media-body">一键诊断</div>
                </a>
            </li>
        </ul>
    </div>
    <div id="my" class="mui-control-content">
        <ul class="mui-table-view">
            <!--<li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="myInfo_n">
                    <span class="mui-icon mui-icon-person"></span>
                    <span>我的信息</span>
                </a>
            </li>-->
            <!--<li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="toCash_n">
                    <span class="mui-icon mui-icon-refresh"></span>
                    <span>余额提现</span>
                </a>
            </li>-->
            <li class="mui-table-view-cell">
                <a class="mui-navigate-right skip_btn" id="mail_n" v-on:tap="editMailStatus()">
                    <span class="mui-icon mui-icon-email"></span>
                    <span class="mymail">我的邮箱<span class="mui-badge mui-badge-danger mui-text-center mailNum" v-html="mailNum"></span></span>
                </a>
            </li>
            <!--<li class="mui-table-view-cell">-->
                <!--<a class="mui-navigate-right skip_btn" id="feedback_n">-->
                    <!--<span class="mui-icon mui-icon-compose"></span>-->
                    <!--<span>问题反馈</span>-->
                <!--</a>-->
            <!--</li>-->
            <!--<li class="mui-table-view-cell">-->
                <!--<a class="mui-navigate-right skip_btn" id="more_n">-->
                    <!--<span class="mui-icon mui-icon-plusempty"></span>-->
                    <!--<span>更多产品</span>-->
                <!--</a>-->
            <!--</li>-->
        </ul>
    </div>
    <div id="customer" class="mui-control-content" style="margin:0 10px;">
        <p class="mui-text-center">跟进代理列表</p>
        <!--<ul class="mui-table-view mui-text-left">-->
            <!--<li class="mui-table-view-cell">-->
                <!--<span class="mui-col-xs-2 mui-col-sm-2">I&nbsp;D&nbsp;&nbsp;搜索：</span>-->
                <!--<input type="tel" id="sy_id" class="mui-col-xs-4 mui-col-sm-4 mui-text-left" style="margin-bottom:0px;width:50%;height:30px;">-->
            <!--</li>-->
            <!--<li class="mui-table-view-cell">-->
                <!--<span class="mui-col-xs-2 mui-col-sm-2">代理级别：</span>-->
                <!--<select name="" id="sy_l" class="mui-col-xs-6 mui-col-sm-6" style="border:1px solid rgba(0,0,0,.2) !important;margin-bottom: 0px;height:30px;padding:5px 10px;">-->
                    <!--<option value="">all</option>-->
                    <!--<option value="1">1</option>-->
                    <!--<option value="2">2</option>-->
                    <!--<option value="3">3</option>-->
                <!--</select>-->
            <!--</li>-->
            <!--<li class="mui-table-view-cell">-->
                <!--<span class="mui-col-xs-2 mui-col-sm-2">状态筛选：</span>-->
                <!--<select name="" id="sy_n" class="mui-col-xs-6 mui-col-sm-6" style="border:1px solid rgba(0,0,0,.2) !important;margin-bottom: 0px;height:30px;padding:5px 10px;">-->
                    <!--<option value="">all</option>-->
                    <!--<option value="1">红灯</option>-->
                    <!--<option value="2">黄灯</option>-->
                    <!--<option value="3">绿灯</option>-->
                <!--</select>-->
                <!--<button class="mui-col-xs-2 mui-col-sm-2" v-on:tap="cusBtn()">搜索</button>-->
            <!--</li>-->
        <!--</ul>-->
        <div class="mui-segmented-control" style="margin-bottom:10px;">
            <a class="mui-control-item mui-active" href="#sy">收益</a>
            <a class="mui-control-item" href="#hy">名下会员</a>
            <a class="mui-control-item" href="#dl">名下代理</a>
            <a class="mui-control-item" href="#zs">桌数</a>
        </div>
        <div id="sy" class="mui-control-content mui-active">
            <table class="mui-table">
                <tr>
                    <td v-on:tap="sort_cus('daili_level')">
                        <span>代理ID</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>代理姓名</td>
                    <td v-on:tap="sort_cus('daili_sy')">
                        <span>昨日收益</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>日/周增长</td>
                    <td>切换身份</td>
                </tr>
                <tr v-show="syList.length==0">
                    <td colspan="5">暂无数据...</td>
                </tr>
                <tr v-for="list in syList" v-show="syList.length!=0">
                    <td v-bind:class="list.TRAFFIC_LIGHTS==1? 'bg_red':(list.TRAFFIC_LIGHTS==2?'bg_yellow':(list.TRAFFIC_LIGHTS==3?'bg_green':'bg_white'))">
                        <span v-html="list.DAILI_INDEX" style="color:#000;"></span>
                        <span class="mui-badge badge_id" v-html="list.DAILI_LEVEL"></span>
                    </td>
                    <td v-html="list.TRUE_NAME"></td>
                    <td v-html="list.PROFIT_YESTERDAY/100"></td>
                    <td>
                        <span v-bind:class="color(list.PROFIT_YESTERDAY_RATE)" v-html="list.PROFIT_YESTERDAY_RATE/100"></span>/<span v-bind:class="color(list.PROFIT_SEVENDAY_RATE)" v-html="list.PROFIT_SEVENDAY_RATE/100"></span>
                    </td>
                    <td>
                        <button class="btn mui-btn mui-btn-primary" v-on:tap="change(list.DAILI_INDEX)" style="padding:3px 5px;">切换</button>
                    </td>
                </tr>
            </table>
        </div>
        <div id="hy" class="mui-control-content">
            <table class="mui-table">
                <tr>
                    <td v-on:tap="sort_cus('daili_level')">
                        <span>代理ID</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>代理姓名</td>
                    <td v-on:tap="sort_cus('hy_num')">
                        <span>名下会员总数</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>日/周增长</td>
                    <td v-on:tap="sort_cus('hy_profit')">
                        <span>名下会员贡献收益</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>日/周增长</td>
                    <td>切换身份</td>
                </tr>
                <tr v-show="syList.length==0">
                    <td colspan="7">暂无数据...</td>
                </tr>
                <tr v-for="list in syList" v-show="syList.length!=0">
                    <td v-bind:class="list.TRAFFIC_LIGHTS==1? 'bg_red':(list.TRAFFIC_LIGHTS==2?'bg_yellow':(list.TRAFFIC_LIGHTS==3?'bg_green':'bg_white'))">
                        <span v-html="list.DAILI_INDEX" style="color:#000;"></span>
                        <span class="mui-badge badge_id" v-html="list.DAILI_LEVEL"></span>
                    </td>
                    <td v-html="list.TRUE_NAME"></td>
                    <td v-html="list.ALL_MEMBER_NUM"></td>
                    <td>
                        <span v-bind:class="color(list.NEW_MEMBER_YESTERDAY_RATE)" v-html="list.NEW_MEMBER_YESTERDAY_RATE"></span>/<span v-bind:class="color(list.NEW_MEMBER_SEVENDAY_RATE)" v-html="list.NEW_MEMBER_SEVENDAY_RATE"></span>
                    </td>
                    <td v-html="list.MEMBER_PROFIT_YESTERDAY/100"></td>
                    <td>
                        <span v-bind:class="color(list.MEMBER_PROFIT_YESTERDAY_RATE)" v-html="list.MEMBER_PROFIT_YESTERDAY_RATE/100"></span>/<span v-bind:class="color(list.MEMBER_PROFIT_SEVENDAY_RATE)" v-html="list.MEMBER_PROFIT_SEVENDAY_RATE/100"></span>
                    </td>
                    <td>
                        <button class="btn mui-btn mui-btn-primary" v-on:tap="change(list.DAILI_INDEX)" style="padding:3px 5px;">切换</button>
                    </td>
                </tr>
            </table>
        </div>
        <div id="dl" class="mui-control-content">
            <table class="mui-table">
                <tr>
                    <td v-on:tap="sort_cus('daili_level')">
                        <span>代理ID</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>代理姓名</td>
                    <td v-on:tap="sort_cus('dl_num')">
                        <span>名下代理总数</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>日/周增长</td>
                    <td v-on:tap="sort_cus('dl_profit')">
                        <span>名下代理贡献收益</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>日/周增长</td>
                    <td>切换身份</td>
                </tr>
                <tr v-show="syList.length==0">
                    <td colspan="7">暂无数据...</td>
                </tr>
                <tr v-for="list in syList" v-show="syList.length!=0">
                    <td v-bind:class="list.TRAFFIC_LIGHTS==1? 'bg_red':(list.TRAFFIC_LIGHTS==2?'bg_yellow':(list.TRAFFIC_LIGHTS==3?'bg_green':'bg_white'))">
                        <span v-html="list.DAILI_INDEX" style="color:#000;"></span>
                        <span class="mui-badge badge_id" v-html="list.DAILI_LEVEL"></span>
                    </td>
                    <td v-html="list.TRUE_NAME"></td>
                    <td v-html="list.ALL_DAILI_NUM"></td>
                    <td>
                        <span v-bind:class="color(list.NEW_DAILI_YESTERDAY_RATE)" v-html="list.NEW_DAILI_YESTERDAY_RATE"></span>/<span v-bind:class="color(list.NEW_DAILI_SEVENDAY_RATE)" v-html="list.NEW_DAILI_SEVENDAY_RATE"></span>
                    </td>
                    <td v-html="list.DAILI_PROFIT_YESTERDAY/100"></td>
                    <td>
                        <span v-bind:class="color(list.DAILI_PROFIT_YESTERDAY_RATE)" v-html="list.DAILI_PROFIT_YESTERDAY_RATE/100"></span>/<span v-bind:class="color(list.DAILI_PROFIT_SEVENDAY_RATE)" v-html="list.DAILI_PROFIT_SEVENDAY_RATE/100"></span>
                    </td>
                    <td>
                        <button class="btn mui-btn mui-btn-primary" v-on:tap="change(list.DAILI_INDEX)" style="padding:3px 5px;">切换</button>
                    </td>
                </tr>
            </table>
        </div>
        <div id="zs" class="mui-control-content">
            <table class="mui-table">
                <tr>
                    <td v-on:tap="sort_cus('daili_level')">
                        <span>代理ID</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>代理姓名</td>
                    <td v-on:tap="sort_cus('hy_room')">
                        <span>名下会员开桌数</span>
                        <img src="img/sort.png" class="sortbox">
                    </td>
                    <td>日/周增长</td>
                    <!--<td>名下代理名下开桌数</td>-->
                    <!--<td>日/周/月环比</td>-->
                    <td>切换身份</td>
                </tr>
                <tr v-show="syList.length==0">
                    <td colspan="5">暂无数据...</td>
                </tr>
                <tr v-for="list in syList" v-show="syList.length!=0">
                    <td v-bind:class="list.TRAFFIC_LIGHTS==1? 'bg_red':(list.TRAFFIC_LIGHTS==2?'bg_yellow':(list.TRAFFIC_LIGHTS==3?'bg_green':'bg_white'))">
                        <span v-html="list.DAILI_INDEX" style="color:#000;"></span>
                        <span class="mui-badge badge_id" v-html="list.DAILI_LEVEL"></span>
                    </td>
                    <td v-html="list.TRUE_NAME"></td>
                    <td v-html="list.OPEN_ROOM_YESTERDAY/4"></td>
                    <td>
                        <span v-bind:class="color(list.OPEN_ROOM_YESTERDAY_RATE)" v-html="list.OPEN_ROOM_YESTERDAY_RATE"></span>/<span v-bind:class="color(list.OPEN_ROOM_SEVENDAY_RATE)" v-html="list.OPEN_ROOM_SEVENDAY_RATE"></span>
                    </td>
                    <!--<td></td>-->
                    <!--<td></td>-->
                    <td>
                        <button class="btn mui-btn mui-btn-primary" v-on:tap="change(list.DAILI_INDEX)" style="padding:3px 5px;">切换</button>
                    </td>
                </tr>
            </table>
        </div>
        <div class="mui-pagination" id="sy_pager" v-show="syList.length!=0">
            <div class="mui-text-center">
                <span class="mui-btn first" v-on:tap="sy_showPage(1,$event)">
                    首页
                </span>
                <span class="mui-btn previous" v-on:tap="sy_showPage(sy_pageCurrent-1,$event)">
                    上一页
                </span>
                <input class="pageIndex" type="text" v-model="sy_pageCurrent | onlyNumeric" v-on:blur="sy_showPage(sy_pageCurrent,$event,true)" />
                <span class="mui-btn next" v-on:tap="sy_showPage(sy_pageCurrent+1,$event)">
                    下一页
                </span>
                <span class="mui-btn last" v-on:tap="sy_showPage(sy_pageCount,$event)">
                    尾页
                </span>
            </div>
            <div class="mui-text-center">
                <span>当前第{{sy_pageCurrent}}页，共{{sy_pageCount}}页</span>
            </div>
        </div>
    </div>
</div>

<!-- 底部导航 -->
<nav class="mui-bar mui-bar-tab">
    <a class="mui-tab-item mui-active" href="#home">
        <span class="mui-icon mui-icon-home"></span>
        <span class="mui-tab-label">首页</span>
    </a>
    <a class="mui-tab-item" href="#daili">
        <span class="mui-icon mui-icon-personadd"></span>
        <span class="mui-tab-label">代理</span>
    </a>
    <a class="mui-tab-item" href="#fuwu">
        <span class="mui-icon mui-icon-info"></span>
        <span class="mui-tab-label">服务</span>
    </a>
    <a class="mui-tab-item" href="#my">
        <span class="mui-icon mui-icon-gear"><span class="mui-badge mui-badge-danger"></span></span>
        <span class="mui-tab-label">我的</span>
    </a>
    <a class="mui-tab-item" href="#customer" id="nav_customer" style="display: none;">
        <span class="mui-icon mui-icon-person"></span>
        <span class="mui-tab-label">客户经理</span>
    </a>
</nav>

<!-- 排行榜 -->
<div id="rank" class="mui-modal">
    <div class="inner_box">
        <a class="mui-icon mui-icon-close mui-pull-right" href="#rank"></a>
        <button id='rank_type' class="mui-btn mui-btn-block" type='button' style="padding-bottom:5px;width:93%;">
            <span v-html="rankList.cur_rank_txt">收入排行榜</span>
            <!--<a class="mui-icon mui-icon-arrowdown"></a>-->
            <a class="mui-icon rankdrop"></a>
        </button>
        <div class="rank rank1">
            <div class="mui-segmented-control">
                <a class="mui-control-item mui-active" href="#item1">
                    日榜
                </a>
                <a class="mui-control-item" href="#item2">
                    周榜
                </a>
                <a class="mui-control-item" href="#item3">
                    月榜
                </a>
            </div>
            <div style="height:180px;">
                <div id="item1" class="mui-control-content mui-active">
                    <p style="position: absolute;width:100%;top:-45px;text-align:center;">日榜：<span v-html="dateObj.day.start"></span></p>
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <li class="mui-table-view-cell">
                                    <span>序号</span>
                                    <span>ID</span>
                                    <span>金额(元)</span>
                                </li>
                                <li class="mui-table-view-cell" v-for="day_p in rankList.list_d">
                                    <span v-html="$index+1"></span>
                                    <span v-html="day_p.PLAYER_INDEX | idHide"></span>
                                    <span v-html="day_p.optotal/100"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.profit.Yesterday "></span>
                        <span v-show="myRank.profit.Yesterday">|
                            <span v-html="myRank.profit.Yesterday_data/100"></span>(元)
                        </span>
                    </p>
                </div>
                <div id="item2" class="mui-control-content">
                    <p style="position: absolute;width:100%;top:-45px;text-align:center;">周榜：<span v-html="dateObj.week.start"></span>-<span v-html="dateObj.week.end"></span></p>
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <ul class="mui-table-view">
                                    <li class="mui-table-view-cell">
                                        <span>序号</span>
                                        <span>ID</span>
                                        <span>金额(元)</span>
                                    </li>
                                    <li class="mui-table-view-cell" v-for="week_p in rankList.list_w">
                                        <span v-html="$index+1"></span>
                                        <span v-html="week_p.PLAYER_INDEX | idHide"></span>
                                        <span v-html="week_p.optotal/100"></span>
                                    </li>
                                </ul>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.profit.Lastweek "></span>
                        <span v-show="myRank.profit.Lastweek">|
                            <span v-html="myRank.profit.Lastweek_data/100 "></span>(元)
                        </span>
                    </p>
                </div>
                <div id="item3" class="mui-control-content">
                    <p style="position: absolute;width:100%;top:-45px;text-align:center;">月榜：<span v-html="dateObj.month.start"></span>-<span v-html="dateObj.month.end"></span></p>
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <li class="mui-table-view-cell">
                                    <span>序号</span>
                                    <span>ID</span>
                                    <span>金额(元)</span>
                                </li>
                                <li class="mui-table-view-cell" v-for="month_p in rankList.list_m">
                                    <span v-html="$index+1"></span>
                                    <span v-html="month_p.PLAYER_INDEX | idHide"></span>
                                    <span v-html="month_p.optotal/100"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.profit.Thismonth "></span>
                        <span v-show="myRank.profit.Thismonth">|
                            <span v-html="myRank.profit.Thismonth_data/100 "></span>(元)
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="rank rank2">
            <div class="mui-segmented-control">
                <a class="mui-control-item mui-active" href="#item4">
                    日榜
                </a>
                <a class="mui-control-item" href="#item5">
                    周榜
                </a>
                <a class="mui-control-item" href="#item6">
                    月榜
                </a>
            </div>
            <div style="height:180px;">
                <div id="item4" class="mui-control-content mui-active">
                    <p style="position: absolute;width:100%;top:-45px;text-align:center;">日榜：<span v-html="dateObj.day.start"></span></p>
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <li class="mui-table-view-cell">
                                    <span>序号</span>
                                    <span>ID</span>
                                    <span>人数(人)</span>
                                </li>
                                <li class="mui-table-view-cell" v-for="day_m in rankList.list_d">
                                    <span v-html="$index+1"></span>
                                    <span v-html="day_m.PLAYER_INDEX | idHide"></span>
                                    <span v-html="day_m.PLAYER_COUNT"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.member.Yesterday "></span>
                        <span v-show="myRank.member.Yesterday">|
                            <span v-html="myRank.member.Yesterday_data "></span>(人)
                        </span>
                    </p>
                </div>
                <div id="item5" class="mui-control-content">
                    <p style="position: absolute;width:100%;top:-45px;text-align:center;">周榜：<span v-html="dateObj.week.start"></span>-<span v-html="dateObj.week.end"></span></p>
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <ul class="mui-table-view">
                                    <li class="mui-table-view-cell">
                                        <span>序号</span>
                                        <span>ID</span>
                                        <span>人数(人)</span>
                                    </li>
                                    <li class="mui-table-view-cell" v-for="week_m in rankList.list_w">
                                        <span v-html="$index+1"></span>
                                        <span v-html="week_m.PLAYER_INDEX | idHide"></span>
                                        <span v-html="week_m.PLAYER_COUNT"></span>
                                    </li>
                                </ul>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.member.Lastweek "></span>
                        <span v-show="myRank.member.Lastweek">|
                            <span v-html="myRank.member.Lastweek_data "></span>(人)
                        </span>
                    </p>
                </div>
                <div id="item6" class="mui-control-content">
                    <p style="position: absolute;width:100%;top:-45px;text-align:center;">月榜：<span v-html="dateObj.month.start"></span>-<span v-html="dateObj.month.end"></span></p>
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <li class="mui-table-view-cell">
                                    <span>序号</span>
                                    <span>ID</span>
                                    <span>人数(人)</span>
                                </li>
                                <li class="mui-table-view-cell" v-for="month_m in rankList.list_m">
                                    <span v-html="$index+1"></span>
                                    <span v-html="month_m.PLAYER_INDEX | idHide"></span>
                                    <span v-html="month_m.PLAYER_COUNT"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.member.Thismonth "></span>
                        <span v-show="myRank.member.Thismonth">|
                            <span v-html="myRank.member.Thismonth_data "></span>(人)
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="rank rank3">
            <div style="height:230px;">
                <div id="item7" class="mui-control-content mui-active">
                    <div class="mui-scroll-wrapper">
                        <div class="mui-scroll">
                            <ul class="mui-table-view">
                                <li class="mui-table-view-cell">
                                    <span>序号</span>
                                    <span>ID</span>
                                    <span>人数(人)</span>
                                </li>
                                <li class="mui-table-view-cell" v-for="all in rankList.all">
                                    <span v-html="$index+1"></span>
                                    <span v-html="all.PLAYER_INDEX | idHide"></span>
                                    <span v-html="all.PLAYER_COUNT"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <p class="mui-text-center" style="position:absolute;bottom:5px;width:100%;">
                        我的排名：<span v-html="myRank.member.All "></span>
                        <span v-show="myRank.member.All">|
                            <span v-html="myRank.member.All_data "></span>(人)
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 任务 -->
<div id="task" class="mui-modal">
    <div class="inner_box">
        <a class="mui-icon mui-icon-close mui-pull-right" href="#task"></a>
        <h4 class="">代理成长任务</h4>
        <div style="height:180px;position:relative;">
            <div class="mui-scroll-wrapper">
                <div class="mui-scroll">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell mui-media" v-for="task in taskList">
                            <a href="#">
                                <button class="mui-pull-right" v-on:click="getGift(task.daili_task_id)" v-html="getRes(task.reward_desc,task.reward_logs)">
                                </button>
                                <div class="mui-media-body">
                                    <span v-html="task.name"></span>
                                    <small><span v-html="task.schedule_index"></span>/<span v-html="task.schedule_count"></span></small>
                                    <h6 class='mui-ellipsis' v-html="task.description"></h6>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 邮箱 -->
<div id="mail" class="mui-modal">
    <div class="inner_box">
        <a class="mui-icon mui-icon-close mui-pull-right" href="#mail"></a>
        <h4 class="">邮箱</h4>
        <div style="height:180px;position:relative;">
            <div class="mui-scroll-wrapper">
                <div class="mui-scroll">
                    <ul class="mui-table-view">
                        <li class="mui-table-view-cell" v-for="mail in mailList">
                            <div class="mui-slider-right mui-disabled">
                                <a class="mui-btn mui-btn-red" v-on:click="delMail(mail.MAIL_ID)">删除</a>
                            </div>
                            <div class="mui-slider-handle">
                                <span v-html="mail.CONTENT"></span>
                                <small class="mui-pull-right" v-html="mail.CREATE_TIME | blank"></small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 代理升级提示 -->
<div id="tips" class="mui-modal">
    <div class="inner_box">
        <a class="mui-icon mui-icon-close mui-pull-right" href="#tips"></a>
        <h4 class="">代理升级小贴士</h4>
        <ul class="mui-table-view">
            <li class="mui-table-view-cell">1.代理收入：</li>
            <li class="mui-table-view-cell">
                <span>*</span>
                初级代理：群员充值额*<span v-html="userData.per3"></span>%
            </li>
            <li class="mui-table-view-cell">
                <span>*</span>
                高级代理：群员充值额*<span v-html="userData.per2"></span>%
            </li>
            <li class="mui-table-view-cell">
                <span>*</span>
                超级代理：群员充值额*<span v-html="userData.per1"></span>%
            </li>
            <li class="mui-table-view-cell">2.升级条件：</li>
            <li class="mui-table-view-cell">(1).初级->高级：</li>
            <li class="mui-table-view-cell" v-for="val in (userData.r3)">
                <span>*</span>
                <span v-html="val"></span>
            </li>
            <li class="mui-table-view-cell" v-show="(userData.r3).length==0">
                暂未开放
            </li>
            <li class="mui-table-view-cell">(2).高级->超级：</li>
            <li class="mui-table-view-cell" v-for="val in (userData.r2)" v-show="(userData.r2).length!=0">
                <span>*</span>
                <span v-html="val"></span>
            </li>
            <li class="mui-table-view-cell" v-show="(userData.r2).length==0">
                暂未开放
            </li>
        </ul>
    </div>
</div>

<div class="loading">
    <div class="loading_inner">
        <img src="img/loading1.gif" style="width:30px;height:30px;">
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.picker.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.poppicker.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jQuery.autoIMG.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/countUp.min.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/index.js?v=2"></script>
</body>
</html>