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
    .agentID{color:red}
    .todayNum{color:red}
    .todayAgent{color:red}
    .todayPlayer{color:red}
    .totalNum{color:red}
    .backNum{color:red}

    .titleinfo{font-size: 16px;font-weight:600;}
    span{display:inline-block;margin:5px;}
    .customers
    {
        width:100%;
        border-collapse:collapse;
    }
</style>
<body>

<div class="agentInfo" >
    <div style="width:100%;"><h2 style="text-align: center;"><span class="agentName"></span><span class="titleinfo">代理信息</span></h2></div>
    <div style="width: 100%;">
        <table class = "customers">
            <tr>
                <td>☆代理ID：<span class="agentID"></span></td>
                <td>☆今日业绩：<span class="todayNum"></span></td>
            </tr>
            <tr>
                <td>☆今日直属新增代理：<span class="todayAgent"></span></td>
                <td>☆今日直属新增玩家：<span class="todayPlayer"></span></td>
            </tr>
            <tr>
                <td>☆历史收益：<span class="totalNum"></span></td>
                <td>☆可提现：<span class="backNum"></span></td>
            </tr>
        </table>
    </div>
</div>

<div style="background-color: #F0F0F0;width:100%;height: 10px;"></div>
</body>
</html>
<script>
    $.ajax({
        url:'/wechat-web/direct',
        success:function (res) {
            res = eval('('+res+')');
            console.log(res);
            if (res.code == 0) {
                var data = res.data;
                $('.agentName').html('('+data.name+')');
                $('.agentID').html(data.player_id);
                $('.todayNum').html(data.todayConsume);
                $('.todayAgent').html(data.today_direct_agent);
                $('.todayPlayer').html(data.today_direct_user);
                $('.totalNum').html(data.all_pay_back_gold);
                $('.backNum').html(data.pay_back_gold);
            }
        }
    })
</script>