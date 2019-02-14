<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no" />
    <title>下级明细</title>

    <link href="css/css/mui.min.css" rel="stylesheet" />
    <link href="css/css/mui.picker.min.css" rel="stylesheet" />
    <link href="css/css/mui.dtpicker.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">

    <style>
        .mui-table-view:after{
            height:0px;
        }
        .mui-table-view-cell{
            padding:5px 0;
        }
        .mui-table-view-cell button{
            margin-bottom: 0px;
            padding:0 10px;
            height:30px;
            /*width:100%;*/
        }
        .mui-table-view-cell input{
            height:30px;
            padding:0 10px;
            font-size: 14px;
            /*width:100%;*/
        }
        input[type=text]{
            width:auto;
        }
        .mui-table-view:before{
            height:0px;
        }
        .mui-table-view-cell:after{
            height:0px;
        }
        .deepid{
            display:inline-block;width:30%;height:30px;line-height:30px;background:#eee;
        }

        .red{
            background: red;
        }
        .yellow{
            background: yellow;
        }
        .green{
            background: green;
        }
        .white{
            background: white;
        }
        #memberList , #dailiList{
            table-layout: auto;
        }
        #myMember , #myDaili{
            padding:0 10px;
        }
    </style>
</head>
<body>
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
    <div id="myMember" class="mui-control-content mui-active timechoose">
        <ul class="mui-table-view mui-text-center">
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">起始时间：</span>
                <button id='hy_startTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="hy_startTime"></button>
            </li>
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">终止时间：</span>
                <button id='hy_endTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="hy_endTime"></button>
            </li>
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">I&nbsp;D&nbsp;&nbsp;搜索：</span>
                <input type="tel" id="hy_id" class="mui-col-xs-9 mui-col-sm-9 mui-text-left" style="margin-bottom:0px;width:75%">
            </li>
            <li class="mui-table-view-cell">
                <input type="button" class="mui-col-xs-12 mui-col-sm-12 mui-text-center" value="查询" v-on:tap="hy_search()">
            </li>
        </ul>
        <table id="memberList" class="mui-table">
            <tr>
                <th colspan="5">会员列表</th>
            </tr>
            <tr>
                <td colspan="5" class="mui-text-right">会员贡献收入：<span v-html="hy_profit/100"></span></td>
            </tr>
            <tr>
                <td>帐号ID</td>
                <td>帐号名称</td>
                <td v-on:tap="sort_next('time_hy')">
                    <span>绑定时间</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <!--<td>贡献收入</td>-->
                <td>参与桌数</td>
                <td>开桌数</td>
                <!--<td>最后一次登录时间</td>-->
            </tr>
            <tr v-show="hyData.length==0">
                <td colspan="5">暂无数据...</td>
            </tr>
            <tr v-for="hy in hyData" v-show="hyData.length!=0">
                <td v-html="hy.player_index"></td>
                <td v-html="hy.member_name"></td>
                <td v-html="hy.bind_time"></td>
                <!--<td v-html="hy.profit_sum/100"></td>-->
                <td v-html="hy.join_room/1"></td>
                <td v-html="(hy.open_room/4) | numceil"></td>
                <!--<td v-html="hy.last_login_time"></td>-->
            </tr>
        </table>
        <div class="mui-pagination" id="hy_pager" v-show="hyData.length!=0">
            <div class="mui-text-center">
                <span class="mui-btn first" v-on:tap="hy_showPage(1,$event)">
                    首页
                </span>
                <span class="mui-btn previous" v-on:tap="hy_showPage(hy_pageCurrent-1,$event)">
                    上一页
                </span>
                <input class="pageIndex" type="text"  v-model="hy_pageCurrent | onlyNumeric"  v-on:blur="hy_showPage(hy_pageCurrent,$event,true)" id="hy_page"/>
                <span class="mui-btn next" v-on:tap="hy_showPage(hy_pageCurrent+1,$event)">
                    下一页
                </span>
                <span class="mui-btn last" v-on:tap="hy_showPage(hy_pageCount,$event)">
                    尾页
                </span>
            </div>
            <div class="mui-text-center">
                <span>当前第{{hy_pageCurrent}}页，共{{hy_pageCount}}页</span>
            </div>
        </div>
    </div>
    <div id="myDaili" class="mui-control-content timechoose">
        <ul class="mui-table-view mui-text-center">
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">起始时间：</span>
                <button id='dl_startTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="dl_startTime"></button>
            </li>
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">终止时间：</span>
                <button id='dl_endTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="dl_endTime"></button>
            </li>
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">I&nbsp;D&nbsp;&nbsp;搜索：</span>
                <input type="tel" id="dl_id" class="mui-col-xs-9 mui-col-sm-9 mui-text-left" style="margin-bottom:0px;width:75%">
            </li>
            <!--<li class="mui-table-view-cell" v-show="isCus">-->
                <!--<span class="mui-col-xs-3 mui-col-sm-3">代理级别：</span>-->
                <!--<select name="" id="dl_l" class="mui-col-xs-3 mui-col-sm-3" style="border:1px solid rgba(0,0,0,.2) !important;margin-bottom: 0px;height:30px;padding:5px 10px;">-->
                    <!--<option value="0">all</option>-->
                    <!--<option value="1">1</option>-->
                    <!--<option value="2">2</option>-->
                    <!--<option value="3">3</option>-->
                <!--</select>-->
                <!--<span class="mui-col-xs-3 mui-col-sm-3">状态筛选：</span>-->
                <!--<select name="" id="dl_n" class="mui-col-xs-3 mui-col-sm-3" style="border:1px solid rgba(0,0,0,.2) !important;margin-bottom: 0px;height:30px;padding:5px 10px;">-->
                    <!--<option value="0">all</option>-->
                    <!--<option value="1">红灯</option>-->
                    <!--<option value="2">黄灯</option>-->
                    <!--<option value="3">绿灯</option>-->
                <!--</select>-->
            <!--</li>-->
            <li class="mui-table-view-cell">
                <input type="button" class="mui-col-xs-12 mui-col-sm-12 mui-text-center" value="查询" v-on:tap="dl_search()">
            </li>
        </ul>
        <table id="dailiList" class="mui-table">
            <tr>
                <th colspan="5">代理列表</th>
            </tr>
            <tr>
                <td colspan="5" class="mui-text-right">代理贡献收入：<span v-html="dl_profit/100"></span></td>
            </tr>
            <tr>
                <td colspan="5" class="mui-text-left">
                    <span class="mui-text-center deepid customer" style="display:none;" v-html="customer_id" v-on:tap="cusTag(customer_id)"></span>
                    <span class="mui-text-center deepid curuser" style="display:none;" v-html="userData.user_id"  v-on:tap="tag(userData.user_id)"></span>
                    <span class="mui-text-center deepid deepuser" style="display:none;" v-html="curDeepIndex" v-on:tap="tag(curDeepIndex)"></span>
                </td>
            </tr>
            <tr>
                <td style="width:23%">帐号ID</td>
                <td>玩家姓名</td>
                <td v-on:tap="sort_next('time_dl')">
                    <span>绑定时间</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <!--<td>贡献收入</td>-->
                <td>参与桌数</td>
                <td>开桌数</td>
            </tr>
            <tr v-show="dlData.length==0">
                <td colspan="5">暂无数据...</td>
            </tr>
            <tr v-for="dl in dlData" v-show="dlData.length!=0">
                <!--<td v-bind:class="isCus==true && (dl.notice==1? 'red':(dl.notice==2?'yellow':(dl.notice==3?'green':'white')))" v-html="dl.PLAYER_INDEX" v-on:tap="deepShow(dl.PLAYER_INDEX,dl.PARENT_INDEX)">-->

                <!--</td>-->
                <td v-on:tap="deepShow(dl.PLAYER_INDEX,dl.PARENT_INDEX)">
                    <button class="mui-btn mui-btn-primary" v-html="dl.PLAYER_INDEX" style="padding:0px 5px"></button>
                </td>
                <td v-html="dl.TRUE_NAME"></td>
                <td v-html="dl.CREATE_TIME"></td>
                <!--<td v-html="dl.profit_sum/100"></td>-->
                <td v-html="dl.join_room/1"></td>
                <td v-html="(dl.open_room/4) | numceil"></td>
            </tr>
        </table>
        <div class="mui-pagination" id="dl_pager" v-show="dlData.length!=0">
            <div class="mui-text-center">
                <span class="mui-btn first" v-on:tap="dl_showPage(1,$event)">
                    首页
                </span>
                <span class="mui-btn previous" v-on:tap="dl_showPage(dl_pageCurrent-1,$event)">
                    上一页
                </span>
                <input class="pageIndex" type="text" v-model="dl_pageCurrent | onlyNumeric" v-on:blur="dl_showPage(dl_pageCurrent,$event,true)" />
                <span class="mui-btn next" v-on:tap="dl_showPage(dl_pageCurrent+1,$event)">
                    下一页
                </span>
                <span class="mui-btn last" v-on:tap="dl_showPage(dl_pageCount,$event)">
                    尾页
                </span>
            </div>
            <div class="mui-text-center">
                <span>当前第{{dl_pageCurrent}}页，共{{dl_pageCount}}页</span>
            </div>
        </div>
    </div>
</div>

<!-- 底部导航 -->
<nav class="mui-bar mui-bar-tab">
    <a class="mui-tab-item mui-active" href="#myMember" id="navMember">
        <span class="mui-tab-label">我的会员</span>
    </a>
    <a class="mui-tab-item" href="#myDaili" id="navDaili">
        <span class="mui-tab-label">我的代理</span>
    </a>
</nav>

<div class="loading">
    <div class="loading_inner">
        <img src="img/loading1.gif" style="width:30px;height:30px;">
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.picker.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.dtpicker.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/nextDetail.js?v=2"></script>
</body>
</html>