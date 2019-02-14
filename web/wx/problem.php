<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="content-language" content="zh-CN" />
    <meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->
    <title>问题反馈</title>
    <link rel="stylesheet" href="css/mui.min.css">

    <link rel="stylesheet" type="text/css" href="css/mui.public.css">
    <script type="text/javascript" src="js/jquery.min.js?v=2"></script>
    <script src="js/html5ImgCompress.min.js?v=2"></script>
</head>
<body>
    <header class="mui-bar mui-bar-nav" id="index_header">
        <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">千喜山西棋牌推广联盟</h1>
        <p>
            <span id="game">未来云南麻将</span>
            <span id="level" style="margin-left:10px"></span>
            <span id="user"></span>
        </p>
    </header>
    <div class="light">
        <marquee height="20" behavior="scroll" direction="left" scrollamount="2"  onMouseOut="this.start()">
            <!--<a><span class="mui-icon-extra mui-icon-extra-prech"></span></a>-->
            <ul class="marquee">

            </ul>
        </marquee>
    </div>
    <div class="mui-content problem">
        <p>
            您可在此反馈遇到的问题以及建议
        </p>
        <div class="row mui-input-row">
            <textarea id='question' class="mui-input-clear question"></textarea>
        </div>
        <p>上传图片</p>
        <a href="#" id="a_multiple">
            <span>选择图片</span>
            <input type="file" multiple="multiple" id="multiple" />
        </a>
        <div id="box"></div>
        <p><input type="button" value="提交" class="mui-btn"></p>
    </div>
    <script type="text/javascript" src="js/mui.min.js?v=2"></script>
    <script type="text/javascript" src="js/public.js?v=2"></script>
    <script type="text/javascript" src="js/base.js?v=2"></script>
</body>
</html>