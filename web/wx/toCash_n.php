<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>申请提现</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />
    <link href="css/css/mui.picker.min.css" rel="stylesheet" />
    <link href="css/css/mui.dtpicker.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">

    <style>
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

        .bankAccount select{
            margin-bottom:0px;
        }
        .bankAccount input{
            margin-bottom: 0px;
            border:0 none;
        }
        #bank_btn{
            position:fixed;
            bottom:15px;
            right:15px;
        }
        #toCash_apply ,#toCash_list{
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
        <!--<span v-html="userData.current_game"></span>-->
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
    <div id="toCash_apply"  class="mui-control-content mui-active">
        <table class="mui-table cashInfo">
            <tr>
                <th colspan="2" class="f16">金额信息</th>
            </tr>
            <tr>
                <td>可用金额</td>
                <td><span"><?php echo $_SESSION['user_info']['other']['all']['pay_back_monney']  ?></span>元</td>
            </tr>
            <tr>
                <td>待入账金额</td>
                <td><span"><?php echo $_SESSION['user_info']['forzen_money']  ?></span>元</td>
            </tr>
            <tr>
                <td>累计收益</td>
                <td><span"><?php echo $_SESSION['user_info']['other']['all']['all_pay_back_monney']  ?></span>元</td>
            </tr>
            <tr>
                <td style="width:35%;">提现金额</td>
                <td style="width:65%;padding: 10px 0;">
                    <input type="tel" id="cash" placeholder="0" style="width:50%;padding:5px 10px;height:30px;margin-bottom: 0px;">
                    <input type="button" value="提现" v-on:tap="tocash()">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="mui-text-left">
                    <ul style="font-size:12px;color:darkred;">
                        <li>提现须知：</li>
                        <li>(1)每周最多成功提现两次;</li>
                        <li>(2)单次提现金额范围50~5000元;</li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>
    <div id="toCash_list"  class="mui-control-content timechoose">
        <ul class="mui-table-view mui-text-center">
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">起始时间：</span>
                <button id='cash_startTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="cash_startTime" style="width:75%"></button>
            </li>
            <li class="mui-table-view-cell">
                <span class="mui-col-xs-3 mui-col-sm-3">终止时间：</span>
                <button id='cash_endTime' class="mui-col-xs-9 mui-col-sm-9 mui-text-left" v-html="cash_endTime" style="width:75%"></button>
            </li>
            <li class="mui-table-view-cell">
                <input type="button" value="查询" v-on:tap="cash_search()">
            </li>
        </ul>
        <div id="toCash_order_list">
            <p class="mui-text-center">提现订单</p>
            <p class="mui-text-center" v-show="cashData.length==0">
                暂无数据
            </p>
            <table class="mui-table" v-for="list in cashData" v-show="cashData.length!=0" style="margin-bottom:10px;">
                <tr>
                    <td>订单号</td>
                    <td v-html="list.ORDER_ID" colspan="3"></td>
                </tr>
                <tr>
                    <td>订单金额</td>
                    <td v-html="list.PAY_MONEY/100 | currency ''"></td>
                    <td>手续费</td>
                    <td v-html="list.PAY_FEE/100 | currency ''"></td>
                </tr>
                <tr>
                    <td>创建时间</td>
                    <td v-html="list.CREATE_TIME" colspan="3"></td>
                </tr>
                <tr>
                    <td>订单状态</td>
                    <td v-html="list.PAY_STATUS | status" colspan="3"></td>
                </tr>
                <tr v-show="list.PAY_STATUS==2 || list.PAY_STATUS==3">
                    <td>失败原因</td>
                    <td v-html="list.API_DESC | des" colspan="3"></td>
                </tr>
            </table>
        </div>
        <div class="mui-pagination" id="cash_pager" v-show="cashData.length!=0">
            <div class="mui-text-center">
                <span class="mui-btn first" v-on:tap="cash_showPage(1,$event)">
                    首页
                </span>
                <span class="mui-btn previous" v-on:tap="cash_showPage(cash_pageCurrent-1,$event)">
                    上一页
                </span>
                <input class="pageIndex" type="text" v-model="cash_pageCurrent | onlyNumeric" v-on:blur="cash_showPage(cash_pageCurrent,$event,true)" />
                <span class="mui-btn next" v-on:tap="cash_showPage(cash_pageCurrent+1,$event)">
                    下一页
                </span>
                <span class="mui-btn next" v-on:tap="cash_showPage(cash_pageCount,$event)">
                    尾页
                </span>
            </div>
            <div class="mui-text-center">
                <span>当前第{{cash_pageCurrent}}页，共{{cash_pageCount}}页</span>
            </div>
        </div>
    </div>
</div>

<!-- 底部导航 -->
<nav class="mui-bar mui-bar-tab">
    <a class="mui-tab-item mui-active" href="#toCash_apply">
        <span class="mui-tab-label">申请提现</span>
    </a>
    <a class="mui-tab-item" href="#toCash_list">
        <span class="mui-tab-label">提现订单</span>
    </a>
</nav>

<div class="loading">
    <div class="loading_inner">
        <img src="img/loading1.gif" style="width:30px;height:30px;">
    </div>
</div>

<div class="img" style="display:none;position:absolute;width:100%;height:100%;top:0px;z-index:999;">
    <span v-on:click="hideImg()" style="position:absolute;top:0px;right:0px;width:20px;height:20px;background:#ddd;color:#000;text-align:center;line-height:20px;font-size:20px;">&times;</span>
    <img src="img/xinyang.jpg" alt="">
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.picker.min.js?v=2"></script>
<script type="text/javascript" src="js/js/mui.dtpicker.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/toCash.js?v=2"></script>
</body>
</html>