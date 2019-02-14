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
<div class="panel panel-information">
    <div class="nav-wrap">
        <div class="nav">
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>基本信息</h1>
        </div>
    </div>
    <div class="information-wrap">
        <div class="information-basic">
            <div class="item item-tel">
                <label>昵称</label>
                <p><?=$user_info['play_name']?></p>
            </div>
            <div class="item item-tel">
                <label>用户ID</label>
                <p><?=$user_info['user_id']?></p>
            </div>
            
            <div class="item item-lvl">
                <label>返利比例</label>
                <p>35%</p>
            </div>
            <div class="item item-lvl">
                <label>上级代理</label>
                <p><?=$user_info['parent_index']?></p>
            </div>
            <div class="item item-username">
                <label>下级玩家</label>
                <p><?=$user_info['member_num']?></p>
            </div>
            <div class="item item-username">
                <label>下级代理</label>
                <p><?=$user_info['daili_num']?></p>
            </div>
            <div class="item item-username">
                <label>手机号</label>
                <p><span id="page_tel"><?=$user_info['tel']?></span>
                <a href="javascript:showmsg2(1)" style="color:#f60;padding-right:0.2rem;float: right">绑定手机号</a></p>
            </div>
            <div class="item item-username">
                <label>真实姓名</label>
                <p><span id="real_name"><?=$user_info['true_name']?></span>
                   <a href="javascript:showmsg()" style="color:#f60;padding-right:0.2rem;float: right">修改</a></p>
            </div>
            <div class="item item-username">
                <label>创建时间</label>
                <p><?=$user_info['regis_time']?></p>
            </div>
        </div>
    </div>
    <div class="index-wrap">
        <div class="footer-note">
            <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
        </div>
    </div>
</div>
<!--绑定电话号码-->
<div class="popup-mask" id="tipdiv2" style="display: none;z-index:985"></div>
<div class="popup popup-agency-confirm" id="tipbody2" style="display: none;">
    <a href="javascript:;" class="popup-btn-close" onclick="closemsg2();"></a>
    <div class="main">
        <div class="tip">
            <div class="mipt">
                <h5 class="title red">您正在进行手机号的绑定</h5>
                <input type="number" id="mobile" placeholder="请输入您的手机号">
                <input type="number" id="code" placeholder="请输入验证码">
                <a href="javascript:;" onclick="sendmobile()" id="btn-code1" class="btn-code red">获取验证码</a>
            </div>
            <div class="dbtn"><span class="btn" onclick="bindMobile()" >绑定</span></div>
        </div>
    </div>
</div>
<!---->
<!--修改真实姓名-->
<div class="popup-mask" id="tipdiv" style="display: none;"></div>
<div class="popup popup-agency-confirm" id="tipbody" style="display: none;z-index: 1000;">
    <a href="javascript:;" class="popup-btn-close" onclick="closemsg();"></a>
    <div class="main">
        <div class="tip">
            <div class="mipt">
                <h5 class="title red">修改真实姓名</h5>
                <input type="text" id="name" placeholder="请输入您的真实姓名">
            </div>
            <p class="red" style="font-weight: 600;font-size: 0.2rem;margin-bottom: 0.2rem;text-align: center">
                注：该项很重要，谨慎填写</p>
        </div>
        <div class="dbtn"><span class="btn" onclick="bindName()" >确认</span></div>
    </div>
</div>
<!---->
<!--alert弹框-->
<div class="alert-mask popup-mask" id="alerttip" style="display: none;z-index: 1001;"></div>
<div class="alert-box popup popup-agency-confirm" id="alertbody" style="display: none;z-index: 1002;">
    <div class="main">
        <div class="alert-tip" id="alerttitle" style="text-align: center;line-height: 0.5rem;padding:0.2rem;">
            请填写正确的数据
        </div>
        <div class="dbtn"><span class="btn" onclick="closealert();">确定</span></div>
    </div>
</div>
<!---->


<!--loading组件-->
<div class="loading-box" id="loading">
    <img src="static/mobile/agent/images/loading.gif" alt="" class="img">
</div>
<!---->

<script src="static/mobile/script/ajax.js"></script>
<script>
    //    绑定手机号弹框出现
    function showmsg2(id) {
        if (id == 2) {
            $('#tipbody2').fadeIn();
            $('#tipdiv2').fadeIn();
        }
        else {
            //$("#tipcontent").html($msg);
            $('#tipbody2').fadeIn();
            $('#tipdiv2').fadeIn();
        }
    }
    //    关闭绑定手机号弹框
    function closemsg2() {
        $('#tipbody2').fadeOut();
        $('#tipdiv2').fadeOut();
    }
    //    修改真实姓名弹框出现
    function showmsg($msg) {
        $("#tipcontent").html($msg);
        $('#tipbody').fadeIn();
        $('#tipdiv').fadeIn();
    }
    //    关闭真实姓名弹框
    function closemsg() {
        $('#tipbody').fadeOut();
        $('#tipdiv').fadeOut();
    }
    //    修改真实姓名的展示
    function changename() {
        var name = $('#name').val()
        $('#real_name').html(name)
        closemsg()
    }
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


    function sendmobile(){
        var mobile = $("#mobile").val();
        if(!(/^1[34578]\d{9}$/.test(mobile))){
            showalert("手机号格式不正确！");
            return ;
        }
        $("#loading").show();
        $.ajax({
            url : base_url + '&r=liuliang/sent-sms' + sign,
            type : 'post',
            data : {mobile:mobile},
            success : function(res){
                $("#loading").hide();
                showalert(res.ret_msg);
            }
        });
    }


    function bindMobile(){
       
        var mobile = $("#mobile").val();
        mobile.replace(/\s+/g, "");
        if(!(/^1[34578]\d{9}$/.test(mobile))){
            showalert("手机号格式不正确！");
            return ;
        }
        $("#loading").show();
        closemsg2();
        $.ajax({
            url : base_url + '&r=liuliang/set-agent-tel' + sign,
            type : 'post',
            data : {mobile:mobile},
            success : function(res){
                $("#loading").hide();
                if(res.ret_code == 0){
                    $("#page_tel").html(mobile);
                }
                showalert(res.ret_msg);
            },
            error:function(){
                 $("#loading").hide();
                showalert("页面访问出错！");
            }
        });
    }

    function bindName(){

        var name = $("#name").val();
        name.replace(/\s+/g, "");
        if(!( /^[\u4e00-\u9fa5]+(·[\u4e00-\u9fa5]+)*$/.test(name))){
            showalert("请输入正确的中文名字");
            return ;
        }
        closemsg();
        $("#loading").show();
        $.ajax({
            url : base_url + '&r=liuliang/set-agent-info' + sign,
            type : 'post',
            data : {name:name},
            success : function(res){
                $("#loading").hide();
                 if(res.ret_code == 0){
                    $("#real_name").html(name);
                }
                showalert(res.ret_msg);
            },
            error:function(){
                 $("#loading").hide();
                showalert("页面访问出错！");
            }
        });
    }
</script>
</body>
</html>
