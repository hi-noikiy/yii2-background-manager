<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>我的玩家</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="../static/js/style.js"></script>
</head>
<style>
    .layui-table-cell{padding:0!important;}
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return" style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">我的玩家</h2>
</div>
<iframe id="agentinfo" align="center" width="100%" height="160" src="agentinfo"  frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>

<div class="layui-container" style="margin-top:10px;" >
    <p style="text-align: center;font-weight: 550">我的玩家</p>
    <hr>

    <div class="layui-row" >
        <form action="" class="layui-form">
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                <input type="text" class="layui-input" style="border-radius: 20px" placeholder="开始时间" id="startTime" name="startTime">
            </div>
            <div class="layui-input-inline layui-col-xs4" style="margin:5px;">
                <input type="text" class="layui-input" style="border-radius: 20px" placeholder="结束时间" id="endTime" name="endTime">
            </div>
            <div class="layui-btn layui-btn-warm layui-col-xs2" style="margin:5px;background-color: #00CCFF;border-radius: 20px" lay-submit="" lay-filter="search1">查询</div>
        </form>
    </div>
    <div class="main">
        <table class="layui-table" id="player"lay-even lay-skin="line" lay-size="lg">
            <caption>所选日期总业绩：<span id="total" style="color:red;"></span></caption>
        </table>
    </div>
</div>
</body>
<script>
    layui.use(['table','form','laydate'],function () {
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
            elem:"#player"
            ,url:"/wechat-web/member-list"
            ,page:true
	    ,method:'post'
            ,size: 'lg'
            ,cols:[[
                {field:"u_id",title:'ID', align:'center', width:'18%'}
                ,{field:"weixin_nickname",title:'昵称', align:'center', width:'18%'}
                ,{field:"consume",title:'业绩(直属)',value:0, align:'center', width:'20%'}
                ,{field:"gold_bar",title:'剩余元宝', align:'center', width:'15%'}
                ,{field:"last_login_time",title:'最后登录', align:'center', width:'29%'}
            ]]
            ,where:{
                start_time: $('#startTime').val(),
                end_time:$('#endTime').val()
            }
            ,done:function (res, curr, count) {
                $.ajax({
                    url:"/wechat-web/money-in-time"
                    ,data:{
                        start_time:$('#startTime').val(),
                        end_time:$('#endTime').val()
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
            table.reload('player',{
                url:'/wechat-web/member-list'
                ,page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    "start_time": $('#startTime').val(),
                    "end_time":$('#endTime').val(),
                    "user_id":$('#userId').val()
                }
            })
        });
    })
</script>
</html>
