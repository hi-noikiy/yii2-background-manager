<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>我的代理</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="../static/js/style.js"></script>
    <style>
        .layui-table-cell{padding:0!important;}
    </style>
</head>
<style>
    .search-button{
        margin-top:8px;
        width:20%;
        border-radius: 20px;
        margin-left: 2%;
        background-color: #00CCFF
    }
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">我的代理</h2>
</div>
<iframe id="agentinfo" align="center" width="100%" height="160" src="agentinfo"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>

<div class="layui-container">
    <div class="layui-row" >
        <form action="" class="layui-form">
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                <input type="text" class="layui-input" style="border-radius: 20px" placeholder="开始时间" id="startTime" name="startTime">
            </div>
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;border-radius: 20px">
                <input type="text" class="layui-input" style="border-radius: 20px" placeholder="结束时间" id="endTime" name="endTime">
            </div>
            <div class="layui-btn layui-btn-warm layui-btn-sm search-button" lay-submit="" lay-filter="search1">查询</div>
        </form>
    </div>

    <div class="main">
        <table class="layui-table" id="myAgent" lay-even lay-skin="line" lay-size="lg">
            <caption>所选日期总业绩：<span id="total" style="color:red;">0</span></caption>
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
        //默认看当天数值
        var myDate = new Date();
        $('#startTime').val(myDate.getFullYear()+'-'+(myDate.getMonth()+1)+'-'+myDate.getDate());
        $('#endTime').val(myDate.getFullYear()+'-'+(myDate.getMonth()+1)+'-'+myDate.getDate());
        table.render({
            elem:"#myAgent"
            ,url:"/wechat-web/member-daili-list"
            ,page:true
	    ,method:'post'
            ,size: 'lg'
            ,where:{
                start_time:$('#startTime').val(),
                end_time:$('#endTime').val()
            }
            ,cols:[[
                {field:"nickname",title:'昵称',width:'30%',align:'center'}
                ,{field:"player_id",title:'ID',width:'30%',align:'center'}
                ,{field:"consume",title:'业绩(伞下)',width: '40%',align:'center'}
            ]]
            ,done:function (res, curr, count) {
                $.ajax({
                    url:"/wechat-web/daili-money-in-time"
                    ,data:{
                        start_time:$('#startTime').val(),
                        end_time:$('#endTime').val(),
                    }
                    ,success:function (res) {
                        res  = eval('('+res+')');
                        if (res.code == 1) {
                            $('#total').html(res.data);
                        }
                    }
                });

            }
        });
        form.on('submit(search1)',function (data) {
            reloadIframeWeb('agentinfo');
            table.reload('myAgent',{
                url:'/wechat-web/member-daili-list'
                ,page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    "start_time": $('#startTime').val(),
                    "end_time":$('#endTime').val(),
                    "user_id":$('#agentID').val()
                }
            })
        });
        form.on('select(fasttips)',function (data) {
            var myDate = new Date();
            $('#endTime').val(myDate.getFullYear()+'-'+(myDate.getMonth()+1)+'-'+myDate.getDate());
            if ($('#quickSearch').val() == 1) {//三天
                var myDate_ = new Date((Date.parse(new Date()))-(86400000*2));
                $('#startTime').val(myDate_.getFullYear()+'-'+(myDate_.getMonth()+1)+'-'+myDate_.getDate());
            } else if ($('#quickSearch').val() == 2) {//一周
                var myDate_ = new Date((Date.parse(new Date()))-(86400000*6));
                $('#startTime').val(myDate_.getFullYear()+'-'+(myDate_.getMonth()+1)+'-'+myDate_.getDate());
            }
            table.reload('myAgent',{
                url:'/wechat-web/member-daili-list'
                ,page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    "start_time": $('#startTime').val(),
                    "end_time":$('#endTime').val(),
                    "user_id":$('#agentID').val()
                }
            })
        })


    })
</script>
</body>
</html>
