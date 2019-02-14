<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>百万棋牌室代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/wechart.min.css">
    <script type="text/javascript" src="static/mobile/agent/js/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a href="javascript:;" onclick="history.go(-1)"></a>
            <h1>推广二维码</h1>
        </div>
    </div>
    <?php include 'base_run.php' ?>
    <div class="player-header">
        <div class="box">
            <span class="name">
                <img src="static/mobile/agent/images/index/icon-title.png" alt="" class="img-l img">
                推广二维码
                <img src="static/mobile/agent/images/index/icon-title.png" alt="" class="img-r img">
            </span>
        </div>
        <div class="help" id="help"></div>
    </div>
   <style type="text/css">
        .hint{background: #fff;padding-top:1em; }
        .hint .border{width: 90%;margin: 0 auto;border: solid 1px red;padding: 5px 10px; font-size: 15px;line-height: 1.5em;}
    </style>
    <div class="hint">
        <div class="border">
            <p>您可以将下图保存分享给好友，好友识别二维码下载安装游戏后，会自动成为您的下级玩家，为您返利。</p>
            <p>注：如果好友扫码下载后没有成功绑定，请到首页“我的玩家”页，通过“手动绑定”功能主动输入该玩家ID实现手动绑定</p>
         </div>
    </div>

    <div class="wechart-box">
        <h1 class="header">长按图片可保存到手机</h1>
        <div class="wechart-img-box">
            <img id="qrcode" src="" alt="" class="img">
        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>
<script src="static/mobile/agent/js/date.js"></script>
<script src="static/mobile/agent/js/dialog.js"></script>
<script>
    $(function () {
        $('#help').click(function () {
            myAlert('您可以把二维码分享给好友，好友识别二维码下载游戏后，会自动成为您的下级玩家，为您返利。')
        });

        var uid = '<?=$user_info['user_id']?>';
        var img_url = base_url+'r=share/share-qrcode&gid=524803&uid='+uid;
        $("#qrcode").attr('src',img_url); 
    })
</script>
</body>
</html>

