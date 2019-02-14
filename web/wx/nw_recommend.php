<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>百万棋牌室代理平台</title>
    <link rel="stylesheet" href="static/mobile/agent/css/common.min.css">
    <link rel="stylesheet" href="static/mobile/agent/css/information.min.css?2">
    <script type="text/javascript" src="static/mobile/agent/lib/jquery.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/sky.min.js"></script>
    <script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
</head>
<body>

<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a href="javascript:;" onclick="history.go(-1)"></a>
            <h1>推荐下级代理</h1>
        </div>
    </div>

    <!-- 跑马灯 -->
    <?php include 'base_run.php' ?>
    <!-- 头部信息 -->
    <?php include 'base_head.php' ?>

    <div class="search-wrap">
        <div class="search-condition">
            <div class="player-header" style="height:auto;line-height: 0.5rem;padding-top:0.1rem;">
                <div class="box">
            <span class="name">
                <img src="static/mobile/agent/images/index/icon-title.png" alt="" class="img-l img">
                推荐下级代理
                <img src="static/mobile/agent/images/index/icon-title.png" alt="" class="img-r img">
            </span>
                </div>
                <p class="red">返利：下级代理玩家桌费的7%</p>
            </div>
            <div class="information-wrap">
                <div class="information-basic agent-input">
                    <div class="item">
                        <label>玩家姓名</label>
                        <p><input value="" id="name" style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.64rem; line-height: 0.64rem; padding-left: 0.2rem; width: 130px;" type="text">
                        </p>
                    </div>
                    <div class="item">
                        <label>游戏ID</label>
                        <p><input id="game_id" style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.64rem; line-height: 0.64rem; padding-left: 0.2rem; width: 130px;" type="number">
                        </p>
                    </div>
                    <div class="item">
                        <label>微信号</label>
                        <p><input id="wechart" style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.64rem; line-height: 0.64rem; padding-left: 0.2rem; width: 130px;" type="text">
                        </p>
                    </div>
                    <div class="item">
                        <label>手机号</label>
                        <p><input id="mobile" style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.64rem; line-height: 0.64rem; padding-left: 0.2rem; width: 130px;" type="number">
                        </p>
                    </div>
                    <div class="item">
                        <label>上级ID</label>
                        <p><input id="leader_id" style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.64rem; line-height: 0.64rem; padding-left: 0.2rem; width: 130px;" type="number">
                        </p>
                    </div>
                </div>
                <div class="form-btn" style="padding-bottom:10px; width:80%;margin:0.6rem auto;" onclick="apply()">
                    <span class="btn-submit" style="background-color: #fb9c07; border-radius: 0.1rem; color: #fff; display: block; font-size: 0.3rem; height: 0.8rem; line-height: 0.8rem; text-align: center; width: 100%;">申请</span>
                </div>
            </div>
        </div>
    </div>
    <p class="red" style="text-align: center; margin-bottom:0.6rem;">注：联系客服以加快申请速度 <br>客服微信号xxxxx</p>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>
<!--loading组件-->
<div class="loading-box" id="loading">
    <img src="static/mobile/agent/images/loading.gif" alt="" class="img">
</div>
<!---->
<!--alert弹框-->
<div class="alert-mask popup-mask" id="alerttip" style="display: none;z-index: 1001;"></div>
<div class="alert-box popup popup-agency-confirm" id="alertbody" style="display: none;z-index: 1002;">
    <div class="main">
        <div class="alert-tip" id="alerttitle" style="text-align: center;line-height: 0.5rem;padding:0.2rem;">
            请填写正确的数据
        </div>
        <div class="dbtn"><input type="button" value="确定" onclick="closealert();"></div>
    </div>
</div>
<!---->
<script src="static/mobile/script/ajax.js"></script>
<script>
    //    alert弹框出现
    function showalert($msg) {
        $("#alerttitle").html($msg);
        $('#alerttip').fadeIn();
        $('#alertbody').fadeIn();
    }
    //    关闭alert弹框
    function closealert() {
        $('#alerttip').fadeOut();
        $('#alertbody').fadeOut();
    }
    // 申请按钮点击后执行
    function apply() {
        var name      = $('#name').val();
        var wechart   = $('#wechart').val();
        var game_id   = $('#game_id').val();
        var mobile    = $('#mobile').val();
        var leader_id = $('#leader_id').val();
        $("#loading").show();
        
        $.ajax({
            url : base_url + '&r=liuliang/add-recommand' + sign,
            type : 'post',
            data : {name:name,wechart:wechart,gameId:game_id,mobile:mobile,leaderId:leader_id},
            success : function(res){
                $("#loading").hide();
                showalert(res.ret_msg);
            }
        });
    }
</script>
</body>
</html>