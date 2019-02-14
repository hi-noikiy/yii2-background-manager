<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>素材定制</title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

    <link href="css/css/mui.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="css/css/reset.css">
    <link rel="stylesheet" href="css/css/public.css">
    <link rel="stylesheet" href="css/css/imgdiy.css">

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

<div class="mui-content">
    <div class="mui-text-center">
        <div>
            <img src="img/bgs/thumb.png" alt="">
        </div>
        <a href="#modal" class="mui-btn mui-btn-primary shiyong">使用</a>
    </div>
</div>

<!-- 图片模版modal -->
<div id="modal" class="mui-modal">
    <header class="mui-bar mui-bar-nav">
        <a class="mui-icon mui-icon-close mui-pull-right" href="#modal"></a>
        <h1 class="mui-title">定制图片</h1>
    </header>
    <div class="mui-content" id="ImgBox">
        <img src="img/bgs/bg.jpg" alt="">
        <div class="mui-text-center box titleBox" v-on:tap="titleEdit(title)" v-html="title"></div>
        <div class="mui-text-center box textBox" v-on:tap="txtEdit(text)" v-html="text"></div>
        <div class="mui-text-center box codeImgBox" v-on:tap="codeImgEdit(code)">
            <img v-bind:src="codesrc">
        </div>
    </div>
    <footer v-show="!isimg&&!istap">
        <button type="button" class="mui-btn mui-btn-primary" v-on:tap="publish()">发布</button>
        <button type="button" class="mui-btn mui-btn-danger" v-on:tap="closeBtn()">取消</button>
    </footer>
    <footer v-show="istap">
        <input type="text" v-model="inputText">
        <button type="button" class="mui-btn mui-btn-primary yesBtn" v-on:tap="inputYes()">确认</button>
        <button type="button" class="mui-btn mui-btn-danger noBtn" v-on:tap="inputNo()">取消</button>
    </footer>
    <footer v-show="isimg">
        <button type="button" class="mui-btn mui-btn-primary changeImg">
            <span>更换图片</span>
            <input type="file" accept="image/*" v-on:change="imgChoose($event)">
        </button>
        <button type="button" class="mui-btn mui-btn-danger" v-on:tap="imgNo()">取消</button>
    </footer>
</div>

<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/html2canvas.min.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/imgdiy.js?v=2"></script>

</body>
</html>