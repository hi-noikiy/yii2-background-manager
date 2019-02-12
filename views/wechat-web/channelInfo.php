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
    .agentInfo{border:2px solid #F4900F;border-radius: 5px;margin:10px 15px 0 15px;padding:10px;}
    .agentName{font-size: 16px;font-weight:600;}
    .agentID{color:red}
    .title{color:#A7A7A7}
    .titleNum{color:red;}
    span{display:inline-block;margin:5px;}
    .layui-container{padding: 10px 15px;}
</style>
<body>
<!--<div class="">-->
<div class="agentInfo" >
    <div style="text-align: center">
        <span class="agentName" >渠道合伙人伞下数据</span>
    </div>
    <hr>
    <div>
        <span class="title">总消耗(元)：</span><span class="titleNum" id="all_cost">0.00</span><span class="title">当月消耗(元)：</span><span class="titleNum" id="month_cost">0.00</span>
    </div>
    <div>
        <span class="title">代理数量：</span><span class="titleNum" id="daili_count">0</span><span class="title">今日新增代理：</span><span class="titleNum" id="add_daili">0</span>
    </div>
    <div>
        <span class="title">玩家数量：</span><span class="titleNum" id="player_count">0</span><span class="title">今日新增玩家：</span><span class="titleNum" id="add_player">0</span>
    </div>
</div>

<!--</div>-->

<!--<div style="background-color: #F0F0F0;width:100%;height: 10px;"></div>-->

</body>
</html>
<script>
    $.ajax({
        url:'/wechat/get-channel-info',
        success:function (res) {
            res = eval('('+res+')');
            if (res.code == 200) {
                var data = res.data;
                /*$('.agentID').html('('+data.player_id+')');
                $('.agentName').html(data.name);*/
                $('#all_cost').html(data.all_cost);
                $('#month_cost').html(data.month_cost);
                $('#daili_count').html(data.daili_count);
                $('#add_daili').html(data.add_daili);
                $('#player_count').html(data.player_count);
                $('#add_player').html(data.add_player);
            }
        }
    })
</script>