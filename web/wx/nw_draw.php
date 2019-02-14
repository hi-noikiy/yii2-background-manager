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
</head>
<body>
<div class="panel panel-index">
    <div class="nav-wrap">
        <div class="nav">
            <a onclick="urlto('wx/route&url=index_all')"></a>
            <h1>提现</h1>
        </div>
    </div>
    
   
    <!-- 头部信息 -->
    <?php include 'base_head.php' ?>

    <div class="information-wrap">
        <div class="tab-container">
            <div class="tab-nav" style="border:none;">
                <a  onclick="urlto('wx/route&url=nw_draw')" class="first on">提现</a>
                <a  onclick="urlto('wx/route&url=nw_drawsearch')" class="last" >提现查询</a>
            </div>
        </div>


        <p style="background: #fff; color: red; text-align: center; padding-top: 5px; padding-bottom: 10px; font-size: 16px;">可提现时间为每日早2：00到晚22：00</p>


        <div class="information-basic" id="wechat_div">
            <div class="item item-tel">
                <label style="width:2.3rem">可提现金额：</label>
                <p class="red">￥ <span id="amount"><?php echo number_format($user_info['pay_back_money'],2); ?></span></p>
            </div>
            <div class="item item-code" style="padding-bottom:10px; margin-top:10px; position: relative;">
                <label style="width:2.3rem">提现金额：</label>
                <p>￥<input value="<?php echo number_format($user_info['pay_back_money'],2); ?>" id="draw_amount"
                style=" border: 1px solid #ccc; border-radius: 0.1rem; height: 0.78rem; line-height: 0.78rem; padding-left: 0.2rem; width: 100px !important;"
                        type="number" min="0.00" max="5000">
                </p>
                <div class="form-btn" style="position:absolute; right: 0.25rem; width: 1rem; top:-5px;">
                    <span class="btn-submit" onclick="draw_all()" 
                      style="background-color: #fb9c07; border-radius: 0.1rem; color: #fff; display: block; font-size: 0.3rem; height: 0.8rem; line-height: 0.8rem; text-align: center; width: 100%;">全部</span>
                </div>
            </div>
            <div class="item item-code" style="padding-bottom:10px; margin:10px 0 20px">
                <label style="width:2.3rem">真实姓名：</label>
                <p id="real_name" >
                    <input type=""  style="width: 50% !important;" id="name" <?php if($user_info['true_name']){echo 'value='.$user_info['true_name'].' disabled';} ?> />
                </p>
            </div>
        
              <p class="red font-size_s font-weight_b text-align_c">注：请您务必正确填写真实姓名，否则提现会失败</p>
           
            <div class="form-btn" style="padding-bottom:10px; width:80%;margin:0 auto;margin-top:28px;">
                <span class="btn-submit" id="btn_submit"
                      style="background-color: #fb9c07; border-radius: 0.1rem; color: #fff; display: block; font-size: 0.3rem; height: 0.8rem; line-height: 0.8rem; text-align: center; width: 100%;">提现</span>
            </div>
            <div class="item item-code" style="padding:30px 20px 200px 20px">
                <p class="red" style="text-align: center">注：最低提现额度要20元</p>
            </div>
        </div>
    </div>

</div>
<div class="index-wrap">
    <div class="footer-note">
        <p style="text-align:center"><span>百万棋牌室代理平台</span></p>
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
        <div class="dbtn"><span class="btn" onclick="closealert();">确定</span></div>
    </div>
</div>
<!---->


<!-- 弹框:提现页面判断未绑定手机号码时的提示页面 BEGIN-->
<?php
if(empty($_SESSION['user_info']['tel'])) {
    ?>
    <div class="popup-mask" id="confirm_mobile_tip" style="display: block;z-index:999"></div>
    <div class="popup popup-agency-confirm" id="confirm_mobile_body" style="display: block;z-index: 1000;">
        <a href="javascript:void(0);" class="popup-btn-close" onclick="closemsg2();"></a>
        <br />
        <div class="main">
            <div class="tip">
                <div class="mipt">
                    <h5 class="title graya" style="font-size: 0.3rem;color: #9C9C9C;">为了您的资金安全，需要绑定手机号</h5>
                    <input type="number" id="mobile" placeholder="请输入您的手机号">
                    <input type="number" id="code" placeholder="请输入验证码">
                    <a href="javascript:void(0);" onclick="sendmobile();" class="btn-code red">获取验证码</a><!--id="btn-code1"-->
                </div>
                <div class="dbtn"><span class="btn" onclick="bindMobile();" >绑定</span></div>
            </div>
        </div>
    </div>
    <?php
}
?>
<!-- END -->

<script type="text/javascript" src="static/mobile/agent/js/common.js"></script>
<script src="static/mobile/script/ajax.js"></script>
<script type="text/javascript">
    // 标识手机验证码弹窗不刷新页面
    var if_reload_page = 1;
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
        if(if_reload_page == 1) {
            location.reload();
        } else {
            if_reload_page = 1;
        }

    }
    // 提现按钮点击后执行
    var idx = 1;
    var btn_submit = $('#btn_submit');
    btn_submit.click(function () {
        if(!idx) {
            return false
        }
        draw()
    });

    function draw_all(){
        var num = '<?=$user_info['pay_back_money']?$user_info['pay_back_money']:'0.00'?>';
        $("#draw_amount").val(num);
    }

    function draw() {

        var amount = $('#amount').html();
        var draw_amount = $('#draw_amount').val();
        var real_name = $('#name').val();
   /*     if (parseFloat(amount) < parseFloat(draw_amount) || parseFloat(draw_amount) < 1){
            showalert('输入金额不符合规范，请重新输入');
            return;
        }*/

        $("#loading").show();
        $.ajax({
            url : base_url +'&r=wxdaili/ '+sign,
            type : 'post',
            data : {num : draw_amount*100,name:real_name},
            success : function(json){
               $("#loading").hide();
               if(json.ret_code ==0){
                 showalert("提现成功，两小时后到账！");
               }else{
                 showalert(json.ret_msg);
               }
            },
            error:function(){
                $("#loading").hide();
                showalert("请求出错,请稍后！");
            }    
        });
    }


    <?php
    if(empty($_SESSION['user_info']['tel'])) {
    ?>
    // ------- 提现页面判断未绑定手机号码时的提示页面 BEGIN
    function closemsg2()
    {
        $('#confirm_mobile_tip').fadeOut();
        $('#confirm_mobile_body').fadeOut();
    }
    /**
     * 获取手机验证码 方法同nw_info
     */
    function sendmobile(){
        if_reload_page = 2;
        var mobile = $("#mobile").val();
        if(mobile == '') {
            showalert("请输入手机号码！");
            return false;
        }
        if(!(/^1[34578]\d{9}$/.test(mobile))){
            showalert("手机号格式不正确！");
            return false;
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
    /**
     * 绑定手机 方法同nw_info
     */
    function bindMobile(){
        if_reload_page = 2;
        var mobile = $("#mobile").val();
        mobile.replace(/\s+/g, "");
        if(!(/^1[34578]\d{9}$/.test(mobile))){
            showalert("手机号格式不正确！");
            return false;
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
                    showalert('绑定手机成功！');
                } else {
                    showalert(res.ret_msg);
                }

            },
            error:function(){
                $("#loading").hide();
                showalert("页面访问出错！");
            }
        });
    }
    // ------- END
    <?php
    }
    ?>
</script>
</body>
</html>
