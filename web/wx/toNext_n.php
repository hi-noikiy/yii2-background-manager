<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>授权下级</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">
    <style>
        .mui-table td{
            border: 0 none;
        }
        #applyList td{
            border:1px solid #ddd;
        }
        body{
            -webkit-overflow-scrolling: touch;
        }
        #toNext , #applyList{
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
<div class="mui-content" style="overflow:scroll">
    <!-- 跑马灯 -->
    <div class="light">
        <marquee  height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <ul class="marquee">
                <li></li>
            </ul>
        </marquee>
    </div>
    <div id="toNext" class="mui-control-content mui-active">
        <table class="mui-table">
            <tr>
                <th>填写推广员信息</th>
            </tr>
            <tr>
                <td>
                    <label for="username">真实姓名：</label>
                    <input type="text" id="username" placeholder="">
                    <p class="">
                        <span style="font-size:12px;"><span style="color:darkred;">请务必填写真实姓名</span> ，否则无法提现到账！</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="usertel">手机号：</label>
                    <input type="tel" id="usertel" placeholder="必填项">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="userid">游戏ID：</label>
                    <input type="tel" id="userid" placeholder="必填项">
                </td>
            </tr>
            <tr>
                <td>
                    <label><span v-html="member_num"></span>名群成员ID：</label>
                    <textarea name="" id="member_num" cols="30" rows="7" placeholder="要求:未绑定他人推荐码(格式:逗号或空格隔开)"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label><span v-html="room_num"></span>局桌数房号：</label>
                    <textarea name="" id="room_num" cols="30" rows="4" v-bind:placeholder="room_num_ph"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="">群截图：</label>
                    <a href="#" id="a_multiple">
                        <span>选择图片</span>
                        <input type="file" multiple="multiple" id="multiple" />
                    </a>
                    <div id="box"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="other">拥有什么资源/&优势（选填）：</label>
                    <textarea  id="other" cols="30" rows="2"></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="button" value="提交审核" v-on:tap="submit()">
                </td>
            </tr>
        </table>
    </div>
    <div id="applyList" class="mui-control-content">
        <table class="mui-table" style="margin-bottom: 60px;">
            <tr>
                <th colspan="4">申请列表</th>
            </tr>
            <tr>
                <td>申请玩家ID</td>
                <td>真实姓名</td>
                <td>申请状态</td>
                <td>申请意见</td>
            </tr>
            <tr v-show="applyList.length==0">
                <td colspan="4">暂无数据...</td>
            </tr>
            <tr v-for="list in applyList" v-show="applyList.length!=0">
                <td v-html="list.PLAYER_INDEX"></td>
                <td v-html="list.TRUE_NAME"></td>
                <td v-html="list.STATUS | status"></td>
                <td v-html="list.REMARK | remark"></td>
            </tr>
        </table>
    </div>
</div>

<!-- 底部导航 -->
<nav class="mui-bar mui-bar-tab">
    <a class="mui-tab-item mui-active" href="#toNext">
        <span class="mui-tab-label">授权下级</span>
    </a>
    <a class="mui-tab-item" href="#applyList">
        <span class="mui-tab-label">申请列表</span>
    </a>
</nav>

<div class="loading">
    <div class="loading_inner">
        <img src="img/loading1.gif" style="width:30px;height:30px;">
    </div>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/html5ImgCompress.min.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/toNext.js?v=2"></script>
</body>
</html>