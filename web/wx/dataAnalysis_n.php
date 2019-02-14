<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>数据分析</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />
    <link href="css/css/mui.picker.min.css" rel="stylesheet" />
    <link href="css/css/mui.dtpicker.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">

    <style>
        .mui-bar-nav~.mui-content{
            padding-bottom: 0px;
        }
        .mui-table-view:before{
            height:0px;
        }
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
            width:100%;
        }
        .mui-table-view-cell input{
            height:30px;
            padding:0 10px;
            font-size: 14px;
            width:100%;
        }
        .mui-table-view-cell:after{
            height:0px;
        }
        .mui-table-view , .dataBox{
            padding: 0 10px;
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
<div class="mui-content timechoose">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <ul class="mui-table-view mui-text-center">
        <li class="mui-table-view-cell">
            <span class="mui-col-xs-3 mui-col-sm-3">起始时间：</span>
            <button id='dt_startTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="dt_startTime" style="width:75%"></button>
        </li>
        <li class="mui-table-view-cell">
            <span class="mui-col-xs-3 mui-col-sm-3">终止时间：</span>
            <button id='dt_endTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="dt_endTime" style="width:75%"></button>
        </li>
        <li class="mui-table-view-cell">
            <input type="button" value="查询" v-on:tap="dt_search()">
        </li>
    </ul>
    <div class="dataBox">
        <table id="dataList" class="mui-table">
            <tr>
                <th colspan="6">数据分析</th>
            </tr>
            <tr>
                <td v-on:tap="sort_data('time')">
                    <span>时间</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <td v-on:tap="sort_data('member_num')">
                    <span>会员数</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <td v-on:tap="sort_data('daili_num')">
                    <span>代理数</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <td v-on:tap="sort_data('member_profit')">
                    <span>会员利润</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <td v-on:tap="sort_data('daili_profit')">
                    <span>代理利润</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
                <td v-on:tap="sort_data('open_room')">
                    <span>桌数</span>
                    <img src="img/sort.png" class="sortbox">
                </td>
            </tr>
            <tr v-show="dtList.length==0">
                <td colspan="6">暂无数据...</td>
            </tr>
            <tr v-for="dt in dtList" v-show="dtList.length!=0">
                <td v-html="dt.create_time"></td>
                <td v-html=" dt.member_num ? dt.member_num : 0 "></td>
                <td v-html=" dt.daili_num ? dt.daili_num : 0 "></td>
                <td v-html=" dt.member_profit ? (dt.member_profit/100) : 0"></td>
                <td v-html=" dt.daili_profit ? (dt.daili_profit/100) : 0"></td>
                <td v-html=" dt.open_room ? (dt.open_room/4) : 0 "></td>
            </tr>
        </table>
    </div>
</div>
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
<script type="text/javascript" src="js/js/dataAnalysis.js?v=2"></script>
</body>
</html>