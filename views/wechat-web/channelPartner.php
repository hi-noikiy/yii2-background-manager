<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>渠道合伙人</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <style>
        .layui-table-cell{padding:0!important;}
    </style>
</head>
<body>
<div style="background-color: #F4900F;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">渠道合伙人</h2>
</div>
<iframe align="center" width="100%" height="200" src="channel-info"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>

<div class="layui-container">
    <div class="layui-row" >
        <form action="" class="layui-form" >
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;visibility:hidden;">
                <input type="text" class="layui-input" >
            </div>
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;visibility:hidden;">
                <input type="text" class="layui-input" >
            </div>
            <div class="layui-btn layui-btn-warm layui-btn-sm layui-col-sm2 " style="margin:8px 0;" id="createAgent" >开通代理</div>

        </form>
    </div>

    <div class="layui-row">
        <form action="" class="layui-form">
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                <input type="text" class="layui-input" placeholder="开始时间" id="startTime" name="startTime" lay-key="1">
            </div>
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                <input type="text" class="layui-input" placeholder="结束时间" id="endTime" name="endTime" lay-key="2">
            </div>
            <div class="layui-btn layui-btn-warm layui-btn-sm layui-col-sm2 " style="margin:8px 0;" id="search" lay-submit="" lay-filter="search1" >查询</div>
        </form>
    </div>

    <div class="main">
        <table class="layui-table" id="myAgent"lay-even lay-skin="line" lay-size="lg">
            <caption>上月消耗(元宝)：<span id="lastMonth" style="color:red;">0</span>&nbsp;&nbsp;当月消耗(元宝)：<span id="sameMonth" style="color:red;">0</span></caption>
            <caption></caption>
        </table>
    </div>
</div>
<script>
    layui.use(['table','form','laydate'],function () {
        var $ = layui.$;
        var laydate = layui.laydate;
        var table = layui.table;
        var form = layui.form;
        laydate.render({elem:"#startTime"});
        laydate.render({elem:"#endTime"});
        table.render({
            elem:"#myAgent"
            ,url:"/wechat/get-cost-list"
            ,page:true
            ,cols:[[
                {field:"day",title:'日期',align:'center'}
                ,{field:"sum",title:'消耗数量(元)',align:'center'}
            ]]
        });
        $('#search').on('click',function () {
            var startTime = $('#startTime').val();
            var endTime = $('#endTime').val();
            table.reload('myAgent',{
                url:'/wechat/get-cost-list',
                method: 'get',
                page:{
                    curr:1
                },
                where:{
                    start_time:startTime,
                    end_time:endTime
                }
            })

        });

        //获取当月和上月元宝消耗
        function getGoldCost() {
            $.ajax({
                url:"/wechat/get-same-last"
                ,success:function (res) {
                    res  = eval('('+res+')');
                    if (res.code == 200) {
                        $('#lastMonth').html(res.data.last_cost);
                        $('#sameMonth').html(res.data.same_cost);
                    }
                }
            });
        }
        getGoldCost();

        $('#createAgent').on('click',function () {
            window.location.href='/wechat/channel-create-agent';
        });
    })
</script>
</body>
</html>