<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>一拳娱乐代理平台</title>
    <!--    <script src="../static/lib/layui/layui.js"></script>-->
    <script src="../static/lib/layuimobile/layui.js"></script>
    <script src="../static/js/style.js"></script>
    <!--    <link rel="stylesheet" href="../wx/static/layui/css/layui.css">-->
    <link rel="stylesheet" href="../wx/static/layuimobile/layui.css">

</head>
<style>
    .c {
        text-align: center;
    }

    .m {
        margin: 20px 0;
    }

    .box {
        width: 80%;
        margin: 0 auto;
    }

    .linktitle {
        font-weight: 600;
        margin-top: 10px;
    }

    p {
        line-height: 2rem;
    }

    .rightIcon {
        font-size: 15px;
        font-weight: 600;
        color: #454545;
        float: right;
        position: relative;
        top: 1px;
    }

    .icon {
        width: 25px;
        height: 25px;
        float: left;
        text-align: center;
        line-height: 25px;
        color: #fff;
        border-radius: 5px;
        margin-right: 5px;
    }

    .remarks {
        color: red;
        font-weight: 400;
        display: inline-block;
        float: right;
    }

    .agentModeInfo {
        color: red;
    }

    .rm {
        display: inline-block;
        margin-right: 7px;
    }

    .lm {
        display: inline-block;
        margin-left: 7px;
    }

    a {
        line-height: 25px;
        display: inline-block;
        width: 100%;
    }

    .agentInfo {
        solid #F4900F;
        padding: 10px;
    }

    .tableStyle {
        width: 100%;
        border-collapse: collapse;
    }

    .buttonfont {
        color: #0C0C0C;
        width: 100%
    }

    .buttonvalue {
        float: left;
        color: #ff0000
    }

    .hrefspace {
        margin: 3% 0;
    }

    html, body {
        width: 100%;
        height: 100%
    }

    body {
        text-align: center;
    }

    hr { /*透明渐变水平线*/
        width: 80%;
        margin: 0 auto;
        border: 0;
        height: 1px;
        background: #333;
        background-image: linear-gradient(to right, #ccc, #333, #ccc);
    }

    .buttonUi {
        height: 50px;
        font-size: 14px;
        font-weight: 600;
        background-color: #00CCFF;
        /*letter-spacing: 5px;*/
        border-radius: 10px;
    }
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <h2 style="line-height: 50px;text-align: center;">
        一拳娱乐代理平台
    </h2>
</div>
<iframe align="center" width="100%" height="160" src="agentinfo" frameborder="no" border="0" marginwidth="0"
        marginheight="0" scrolling="no"></iframe>
<div style="text-align:center" id="agentModeInfo"><a href="https://oss-cdn.601yx.com/wechat/introduce6"><u><font
                    color="red">代理收益模式说明</font></u></a></div>

<div style="width:100%;">
    <div style="width: 50%;float:left;">
        <ul style="margin: 0 10px;">
            <a href="withdrawcash" class="hrefspace withdrawcash">
                <div class="buttonfont" style="border-radius: 20px">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon  layui-icon-rmb" style="font-size: 15px; color: #FFFFFF;"></i>提现
                        </span>
                    </button>
                </div>
            </a>
            <a href="myagent" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-user"
                               style="font-size: 15px; color: #FFFFFF;"></i>我的代理(<?php echo $daili_num; ?>)
                        </span>
                    </button>
                </div>
            </a>
            <a href="twodimensioncode" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-table" style="font-size: 15px; color: #FFFFFF;"></i>推广二维码
                        </span>
                    </button>
                </div>
            </a>

            <a href="agencyschool" class="hrefspace agencyschool">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-survey" style="font-size: 15px; color: #FFFFFF;"></i>代理学堂
                        </span>
                    </button>
                </div>
            </a>

            <a href="yesterdayincome" class="hrefspace yesterdayincome">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-list" style="font-size: 15px; color: #FFFFFF;"></i>上周收入排行榜
                        </span>
                    </button>
                </div>
            </a>
            <a href="createagent" class="hrefspace createagent2 layui-hide">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-user" style="font-size: 15px; color: #FFFFFF;"></i>开通代理
                        </span>
                    </button>
                </div>
            </a>
        </ul>
    </div>

    <div style="width: 50%;float:right">
        <ul style="margin: 0 10px;">
            <a href="week-rebate-detail" class="hrefspace weekRebateDetail">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-rate" style="font-size: 15px; color: #FFFFFF;"></i>周收益详情
                        </span>
                    </button>
                </div>
            </a>
            <a href="results-query" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-list" style="font-size: 15px; color: #FFFFFF;"></i>业绩查询
                        </span>
                    </button>
                </div>
            </a>
            <a href="myplayer" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-form"
                               style="font-size: 15px; color: #FFFFFF;"></i>我的玩家(<?php echo $player_num; ?>)
                        </span>
                    </button>
                </div>
            </a>

            <a href="createagent" class="hrefspace createagent">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-user" style="font-size: 15px; color: #FFFFFF;"></i>开通代理
                        </span>
                    </button>
                </div>
            </a>

            <a href="baseinfo" class="hrefspace">
                <div class="buttonfont">
                    <button class="layui-btn layui-btn-fluid buttonUi">
                        <span style="float: left">
                            <i class="layui-icon layui-icon-username" style="font-size: 15px; color: #FFFFFF;"></i>我的信息
                        </span>
                    </button>
                </div>
            </a>
        </ul>
    </div>
</div>

<!--<div style="width:60%;float: left;margin:0 auto;margin-top: 40%;color: #00CCFF;">-->
<div style="margin:0 auto;margin-top: 100%;width:300px;height:100px;color: #00CCFF;">
    <hr>
    <pre>___  一拳娱乐代理后台  ___</pre>
</div>

<script type="application/javascript">
    layui.use(['table', 'layer', 'form', 'laydate'], function () {
        var $ = layui.$;
        var layer = layui.layer;
        var activity = '<?php echo $activity;?>';

        /*检测是否有活动页 */
        var thisId = 0;
        activityRequest(thisId);

        function activityRequest(thisId) {
            var url = "/wechat/check-activity-page";
            $.ajax({
                type: "POST",
                url: url,
                data: {activity: activity, id: thisId},
                dataType: "json",
                success: function (obj) {
                    var data = obj.data;
                    console.log(data);
                    if (obj.code != 0) {
                        alert(data.msg);
                    }
                    if (data.length == 0) {
                        return;
                    }
                    console.log(data);
                    activityPage(data);
                }
            });
        }

        function activityPage(data) {
            thisId = parseInt(data.num);
            console.log('activityPage当前thisId----' + thisId);
            if (data.type == 1) {//文字
                console.log('活动文字--' + thisId);
                $('.text' + thisId).removeClass('layui-hide');
                console.log('活动文字1111');
                $("#activityPageText" + thisId).html(data.content);
                console.log('活动文字2222');
                layer.open({
                    type: 1
                    , title: [data.title, 'background-color: #00CCFF; color:#fff;padding:0;'] //不显示标题栏
                    , closeBtn: 1
                    , offset: '50px'
                    , area: ['90%', '60%']
                    , shade: 0.5
                    , content: $('.text' + thisId)
                    , cancel: function (index, layero) {
                        console.log('活动文字444');
                        $('.text' + thisId).addClass('layui-hide');
                        thisId = thisId + 1;
                        activityRequest(thisId);
                    }
                });
                console.log('活动文字555');
            }

            if (data.type == 2) {//图片
                console.log('活动图片---' + thisId);
                $('#activityDiv' + thisId).removeClass('layui-hide');
                $("#activityPageImage" + thisId).attr('src', data.img_url);

                layer.open({
                    type: 1
                    , title: false //不显示标题栏
                    , area: '85%'
                    , closeBtn: 1
                    , offset: '50px'
                    , shade: 0.5
                    , content: $('#activityDiv' + thisId)
                    , shadeClose: false
                    , skin: 'layui-layer-nobg' //没有背景色
                    , cancel: function (index, layero) {
                        console.log('图片444');
                        thisId = thisId + 1;
                        activityRequest(thisId);
                    }
                });
            }
        }
    });

    $(function () {
        pushHistory("/wechat/index");

        var rebateSwitch = '<?php echo $rebateSwitch;?>';
        console.log(rebateSwitch);
        if(rebateSwitch == 1){
            $('.weekRebateDetail').addClass('layui-hide');
            $('.withdrawcash').addClass('layui-hide');
            $('.agencyschool').addClass('layui-hide');
            $('.yesterdayincome').addClass('layui-hide');
            $('.createagent').addClass('layui-hide');
            $('.createagent2').removeClass('layui-hide');
        }
    });
</script>
</body>
<!--活动弹窗-->
<div class="layui-form layui-hide" id="activityDiv0" style="display: none;">
    <img style="width: 90%;height90%;" class="layui-form image layui-anim layui-anim-scaleSpring" src=""
         id="activityPageImage0">
</div>
<div class="layui-form layui-hide" id="activityDiv1" style="display: none;">
    <img style="width: 90%;height90%;" class="layui-form image layui-anim layui-anim-scaleSpring" src=""
         id="activityPageImage1">
</div>
<div class="layui-form layui-hide" id="activityDiv2" style="display: none;">
    <img style="width: 90%;height90%;" class="layui-form image layui-anim layui-anim-scaleSpring" src=""
         id="activityPageImage2">
</div>
<div class="layui-form layui-hide" id="activityDiv3" style="display: none;">
    <img style="width: 90%;height90%;" class="layui-form image layui-anim layui-anim-scaleSpring" src=""
         id="activityPageImage3">
</div>

<div class="layui-form text0 layui-hide layui-anim layui-anim-scaleSpring" style="display: none;">
    <p id="activityPageText0"></p>
</div>
<div class="layui-form text1 layui-hide layui-anim layui-anim-scaleSpring" style="display: none;">
    <p id="activityPageText1"></p>
</div>
<div class="layui-form text2 layui-hide layui-anim layui-anim-scaleSpring" style="display: none;">
    <p id="activityPageText2"></p>
</div>
<div class="layui-form text3 layui-hide layui-anim layui-anim-scaleSpring" style="display: none;">
    <p id="activityPageText3"></p>
</div>
</html>