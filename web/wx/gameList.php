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
    <style>
        .mui-table-view.mui-grid-view .mui-table-view-cell .mui-media-body{
            font-size: 12px;
        }
        ul{
            padding:0px;
            margin: 10px;
        }
        ul li{
            list-style:none;
        }
        ul li label{
            font-size: 12px;
        }
        .mui-grid-view.mui-grid-9 .mui-media {
            padding: 0px;
        }
    </style>
</head>
<body>
<!-- 顶部标题  -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title">千喜山西棋牌推广联盟</h1>
</header>

<!--内容区域-->
<div class="mui-content">
    <!-- 九宫格 -->
    <!--<ul class="mui-table-view mui-grid-view mui-grid-9" v-show="flag">
       <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" v-on:tap="mymodal('shisanshui')">
            <a>
                <span class=""><img src="img/shisanshui.png" alt="" width="24" height="24"></span>
                <div class="mui-media-body">十三水</div>
            </a>
        </li>
    </ul>-->
    <ul class="mui-table-view mui-grid-view mui-grid-9" v-show="!flag">
        <li class="mui-table-view-cell mui-media mui-col-xs-3 mui-col-sm-3" v-for="val in gameListAll" v-on:tap="mymodal(val.gid)">
            <a>
                <span class="mui-icon-extra mui-icon-extra-class"></span>
                <div class="mui-media-body" v-html="val.name"></div>
            </a>
        </li>
    </ul>
</div>

<!--modal-->
<div id="modal" class="mui-modal">
    <header class="mui-bar mui-bar-nav">
        <a class="mui-icon mui-icon-close mui-pull-right" href="#modal"></a>
        <h1 class="mui-title">信息核对</h1>
    </header>
    <div class="mui-content" style="height: 100%;">
        <p class="mui-content-padded">
            注意：此处需要重新核对您的信息，请认真填写：
        </p>
        <ul>
            <li>

                <label style="display:inline-block;width:100%;">您的游戏ID：</label>
                <input type="tel" id="id" >
            </li>
            <!--<li>-->
                <!--<label>您的手机号：</label>-->
                <!--<input type="tel" id="phone">-->
            <!--</li>-->
            <li>
                <label style="display:inline-block;width:100%;">验证码：</label>
                <div style="width:100%;">
                    <input type="tel" id="checkCode" style="width:50%">
                    <button id='btnSendCode' class="mui-btn mui-btn-primary" v-on:tap="sendCode()" style="width:48%;height:40px;">获取验证码</button>
                </div>
            </li>
            <li>
                <button type="button" class="mui-btn mui-btn-primary" v-on:tap="submitBtn()" style="width:100%;">提交</button>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<!--<script type="text/javascript" src="js/js/dataClient.js?v=2"></script>-->
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/gameList.js?v=2"></script>
</body>
</html>
