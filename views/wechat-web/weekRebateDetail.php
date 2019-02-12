<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>周收益详情</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <style>
        .layui-table-cell {
            padding: 0 !important;
        }

        .leveal,.myAgent,.myAgentWeek{
            text-align: center;
        }
        .leveal span{
            color: red;
        }
        .explain{
            margin-top: -5px;
            font-size: 12px;
            text-align: center;
            color: red;
        }

        .all{
            font-size: 11px;
            height: 8px;
        }
        .all span{
            color: red;
        }

    </style>
</head>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return"
                                           style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">周收益详情</h2>
</div>
<form action="/wechat-web/week-rebate-detail" class="layui-form">
    <div class="layui-container">
        <div class="layui-row">
            <div class="layui-input-inline layui-col-xs3" style="margin-top:5px;margin-left:18%;width: 50%">
                <input type="text" class="layui-input" style="text-align: center;width: 100%" placeholder="开始时间" id="date" name="date">
            </div>
            <div class="layui-input" style="border: hidden">
                <button class="layui-btn" style="margin:5px 0;background-color: #00CCFF;" lay-submit lay-filter="*">查询</button>
            </div>
        </div>
        <div style="margin:0 auto;color: #evel; ?></span>，返利比例00CCFF;"><hr></div>
        <div class="leveal">
            <b>
                <big>
                    <p>您的代理等级：<span><?php echo $data['level'];?></span><span>&nbsp;&nbsp;&nbsp;返利比例：<?php echo $data['radio']; ?></p>
                    <p>业绩：<span><?php echo $data['consume']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;收益：<span><?php echo $data['rebate']; ?></span></p>
                </big>
            </b>
        </div>
        <div style="margin:0 auto;color: #00CCFF;"><hr></div>

        <div style="text-align: center;margin-left: 30px;margin-top: -5px">
            <div class="layui-input" style="border: hidden">
                <p>
                    <input name="week" title="代理返利" lay-filter="redio" type="radio" checked="" value="1">
                    <input name="week" title="直属玩家返利" lay-filter="redio" type="radio" value="2">
                </p>
            </div>
        </div>
        <div style="color: #00CCFF;margin-top: -5px"><hr></div>
        <div class="explain"><span">代理贡献业绩 X 级差 = 贡献返利</span></div>
        <div style="color: #00CCFF;margin-top: -5px"><hr></div>
        <div class="layui tableDiv">
            <!--代理标签下的内容-->
            <div class="layui-form-item myAgent commonDiv">
                <div class="all"><p>&nbsp;&nbsp;&nbsp;代理业绩：<span><?php echo $data['agentData']['consume']?></span>&nbsp;&nbsp;&nbsp;代理返利：<span><?php echo $data['agentData']['rebate']?></span></p></div>
                <table class="layui-table commonTable" id="myAgent" lay-filter="myAgent" lay-even lay-skin="line" lay-size="lg"></table>
            </div>
            <!--代理标签下的内容-->
            <div class="main layui-hide myAgentWeek commonDiv">
                <div class="all"><p>玩家业绩：<span><?php echo $data['playerData']['consume']?></span>&nbsp;&nbsp;&nbsp;玩家返利：<span><?php echo $data['playerData']['rebate']?></span></p></div>
                <table class="layui-table commonTable" id="myAgentWeek" lay-filter="myAgentWeek" lay-even lay-skin="line"
                       lay-size="lg"></table>
            </div>
        </div>
    </div>
    <input type="hidden" value="" id="detailId"/>
</form>
<script>
    layui.use(['table', 'form', 'laydate'], function () {
        var $ = layui.$;
        var laydate = layui.laydate;
        var table = layui.table;
        var form = layui.form;
        laydate.render({elem: "#date",theme: 'molv'});

        //默认看当天数值
        var date = '<?php echo $date;?>';
        var myDate = new Date();
        $('#date').val(date);

        var playerId = '<?php echo $playerId;?>';
        //周查详情--代理
        table.render({
            elem: "#myAgent"
            , url: "/wechat-web/week-details"
            , where: {
                is_agent: 1,
                agent_id: playerId,
                search_date: $('#date').val()
            }
            ,page:{layout:['prev', 'page', 'next', 'limit'], prev:'上一页', next:'下一页', groups:3}
            , cols: [[
                {field: "nicknameId", title: '昵称ID', align: 'center'}
                ,{field: "level", title: "等级", align: 'center',width:80}
                ,{field: "gap", title: '级差',  align: 'center'}
                ,{field: "consume", title: '业绩', align: 'center'}
                ,{field: "rebate", title: '返利', align: 'center'}
            ]]
        });

        //周查详情--玩家
        table.render({
            elem: "#myAgentWeek"
            , url: "/wechat-web/week-details"
            , where: {
                is_agent: 2,
                agent_id: playerId,
                search_date: $('#date').val()
            }
            ,page:{layout:['prev', 'page', 'next', 'limit'], prev:'上一页', next:'下一页', groups:3}
            , cols: [[
                {field: "nicknameId", title: '昵称ID', align: 'center',width:100}
                ,{field: "radio", title: "返利比例", align: 'center',width:100}
                ,{field: "consume", title: '贡献业绩', align: 'center'}
                ,{field: "rebate", title: '贡献返利', align: 'center'}
            ]]
        });


        form.on('radio(redio)', function (data) {
            if (data.value == 1) {
                $('.myAgent').removeClass('layui-hide');
                $('.myAgentWeek').addClass('layui-hide');
            } else {
                $('.myAgent').addClass('layui-hide');
                $('.myAgentWeek').removeClass('layui-hide');
            }
        });

        form.on('submit(*)', function(data){

        });
    });
</script>
</body>
</html>