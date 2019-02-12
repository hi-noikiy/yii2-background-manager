<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>Title</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
</head>
<style>
    .agentInfo{solid #F4900F;padding:10px;}
    .agentName{font-size: 16px;font-weight:600;color:red}
    .spanFont{color:red}
    .titleinfo{font-size: 16px;font-weight:600;}
    span{display:inline-block;margin:5px;}
    .customers
    {
        width:100%;
        border-collapse:collapse;
        font-size: 16px;
        font-weight: 600;
    }
</style>
<body>
<!--<div class="">-->
<div class="agentInfo" >
    <div style="width:100%;">
        <h2 style="text-align: center;">
            <span class="agentName"></span>
            <span class="titleinfo">
                <a href="javascript:location.replace(location.href);">代理信息</a>
            </span>
        </h2>
    </div>
    <div style="width: 100%;">
        <table class = "customers">
            <tr>
                <td>☆今日业绩：<span class="todayResults spanFont"></span></td>
                <td>☆本周业绩：<span class="weekResults spanFont"></span></td>
            </tr>
            <tr>
                <td>☆伞下代理：<span class="ChannelAgent spanFont"></span></td>
                <td>☆伞下玩家：<span class="ChannelPlayer spanFont"></span></td>
            </tr>
            <tr class="rebate">
                <td>☆历史收益：<span class="totalNum spanFont"></span></td>
                <td>☆上周收益：<span class="weekNum spanFont"></span></td>
            </tr>
        </table>
    </div>
</div>

<!--</div>-->

<div style="background-color: #F0F0F0;width:100%;height: 10px;"></div>

</body>
</html>
<script>
    $.ajax({
        url:'/wechat/results',
        success:function (res) {
            res = eval('('+res+')');
            console.log(res);
            if (res.code == 0) {
                var data = res.data;
                $('.agentName').html('('+data.name+')');
                $('.todayResults').html(data.todayConsume);
                $('.weekResults').html(data.weekConsume);
                $('.ChannelAgent').html(data.channelAgent);
                $('.ChannelPlayer').html(data.channelPlayer);
                $('.totalNum').html(data.all_pay_back_gold);
                $('.weekNum').html(data.weekPayBackGold);

                var rebateSwitch = data.rebateSwitch;
                console.log(rebateSwitch);
                if(rebateSwitch){
                    $('.rebate').addClass('layui-hide');
                }
            }
        }
    })
</script>