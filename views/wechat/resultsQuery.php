<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>业绩查询</title>
    <link rel="stylesheet" href="../static/lib/layui/css/layui.css">
    <script src="../static/lib/layui/layui.js"></script>
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="../static/js/style.js"></script>
    <style>
        .layui-table-cell {
            padding: 0 !important;
        }
    </style>
</head>
<style>
    .myAgentWeek, .myAgent{
        width: 100%;
        position: absolute;
    }
    .weekTable{
        width: 100%;
    }

    .myAgent {
        width: 100%;
        position: absolute;
        text-align: center;
    }
    .layui-table-body{overflow-x: hidden;}
    .detailTime{
        margin:5px;
        border-radius: 20px;
        width: 100px;
    }
    .commonTable tr{
        width: 100%;
    }
</style>
<body>
<div style="background-color: #00CCFF;color:#fff;height:50px;width:100%;">
    <a href="index" style="color:#fff;"><i class="layui-icon layui-icon-return"
                                           style="float:left;position: relative;left:15px;top:17px;"></i></a>
    <h2 style="line-height: 50px;text-align: center;">业绩查询</h2>
</div>
<iframe id="resultsInfo" align="center" width="100%" height="160" src="results-info" frameborder="no" border="0" marginwidth="0"
        marginheight="0" scrolling="no"></iframe>

<form action="" class="layui-form">
    <div class="layui-container">
        <div class="layui-row searchDiv">
            <div class="layui-input-inline layui-col-xs3" style="margin:5px;">
                <input type="text" class="layui-input" style="border-radius: 20px" placeholder="开始时间" id="startTime"
                       name="startTime">
            </div>
            <div class="layui-input-inline layui-col-xs3" style="margin:5px;border-radius: 20px">
                <input type="text" class="layui-input" style="border-radius: 20px" placeholder="结束时间" id="endTime"
                       name="endTime">
            </div>
            <div class="layui-btn layui-btn-warm layui-btn-sm layui-col-sm2" id="search1"
                 style="margin:8px 0;border-radius: 20px;background-color: #00CCFF" data-type="search1">查询
            </div>
        </div>

        <div class="layui-form-item" style="text-align: center;margin-left: -20%">
            <div class="layui-input-block">
                <input name="sex" title="日查" lay-filter="redio" type="radio" checked="" value="1">
                <input name="sex" title="周查" lay-filter="redio" type="radio" value="2">
            </div>
        </div>

        <!--日查-->
        <div class="layui-form-item myAgent" style="margin-left: -4%">
            <table class="layui-table commonTable" id="myAgent" lay-filter="myAgent" lay-even lay-skin="line" lay-size="lg"></table>
        </div>
        <!--日查详情-->
        <div class="layui-hide myAgentDetail" style="text-align: center">
            <div class="layui-container" style="margin-top:10px;">
                <div class="layui-tab layui-tab-card" lay-filter="tabs">
                    <ul class="layui-tab-title" style="text-align: center">
                        <li class="layui-this" lay-id="withdrawCash">代理(伞下)</li>
                        <li lay-id="search">玩家(直属)</li>
                    </ul>

                    <div class="layui-tab-content">
                        <!--代理标签下的内容-->
                        <div class="layui-tab-item layui-show">
                            <table class="layui-table commonTable" id="AgentDetail" lay-filter="AgentDetail" lay-even
                                   lay-skin="line" lay-size="lg"></table>
                        </div>

                        <!--玩家标签下的内容-->
                        <div class="layui-tab-item">
                            <!--代理标签下的内容-->
                            <div class="layui-tab-item layui-show">
                                <table class="layui-table commonTable" id="PlayerDetail" lay-filter="PlayerDetail" lay-even
                                       lay-skin="line" lay-size="lg"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--周查-->
        <div class="main layui-hide myAgentWeek">
            <table class="layui-table commonTable" id="myAgentWeek" lay-filter="myAgentWeek" lay-even lay-skin="line"
                   lay-size="lg"></table>
        </div>
        <!--周查详情-->
        <div class="main layui-hide myAgentWeekDetail">
            <div class="layui-container" style="margin-top:10px;">
                <div class="layui-tab layui-tab-card" lay-filter="tabs">
                    <ul class="layui-tab-title" style="text-align: center">
                        <li class="layui-this" lay-id="withdrawCash">代理(伞下)</li>
                        <li lay-id="search">玩家(直属)</li>
                    </ul>

                    <div class="layui-tab-content">
                        <!--代理标签下的内容-->
                        <div class="layui-tab-item layui-show">
                            <table class="layui-table commonTable" id="AgentWeekDetail" lay-filter="AgentWeekDetail" lay-even
                                   lay-skin="line" lay-size="lg"></table>
                        </div>

                        <!--玩家标签下的内容-->
                        <div class="layui-tab-item">
                            <!--代理标签下的内容-->
                            <div class="layui-tab-item layui-show">
                                <table class="layui-table commonTable" id="PlayerWeekDetail" lay-filter="PlayerWeekDetail" lay-even
                                       lay-skin="line" lay-size="lg"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="" id="detailId" />
</form>

<!--详情弹出层-->
<script type="text/html" id="myAgentDetail">
    <div id="detail">
        <div class="layui-btn layui-btn-xs" lay-event="myAgentDetail" title="详情">详情</div>
    </div>
</script>

<!--<script type="text/html" id="myAgentWeekDetail">-->
<!--    <div id="detailWeek">-->
<!--        <div class="layui-btn layui-btn-xs" lay-event="myAgentWeekDetail" title="详情">详情</div>-->
<!--    </div>-->
<!--</script>-->

<script>
    layui.use(['table', 'form', 'laydate'], function () {
        var $ = layui.$;
        var laydate = layui.laydate;
        var table = layui.table;
        var form = layui.form;
        laydate.render({elem: "#startTime"});
        laydate.render({elem: "#endTime"});
        laydate.render({elem: "#startTimeDetail"});
        laydate.render({elem: "#endTimeDetail"});
        //默认看当天数值
        var myDate = new Date();
        $('#startTime').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());
        $('#endTime').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());
        $('#startTimeDetail').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());
        $('#endTimeDetail').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());

        //日查
        table.render({
            elem: "#myAgent"
            , url: "/wechat/results-day"
            , method: 'post'
            , where: {
                start_time: $('#startTime').val(),
                end_time: $('#endTime').val()
            }
            , cols: [[
                {field: "date", title: '日期', align: 'center'}
                , {field: "consume", title: '业绩(元)', align: 'center'}
                , {field: "操作", title: "操作", toolbar: "#myAgentDetail", align: 'center'}
            ]]
        });
        //日查详情监听
        table.on('tool(myAgent)', function (obj) {
            var data = obj.data;
            var playerId = data.playerId;
            var date = data.date;
            console.log(data);
            $('#detailId').val(playerId);
            form.render();
            switch (obj.event) {
                //日查详情按钮
                case 'myAgentDetail':
                    $('.myAgent').addClass('layui-hide');
                    $('.myAgentDetail').removeClass('layui-hide');
                    $('.detailDiv').removeClass('layui-hide');
                    $('.searchDiv').addClass('layui-hide');

                    //日查详情--代理
                    table.render({
                        elem: "#AgentDetail"
                        , url: "/wechat/day-details"
//                        , page: false
                        , where: {
                            is_agent: 1,
                            agent_id: playerId,
                            search_date: date,
                            start_time: $('#startTime').val(),
                            end_time: $('#endTime').val()
                        }
                        , cols: [[
                            {field: "nickname", title: '代理昵称', align: 'center', width: '40%'}
                            , {field: "id", title: "ID", align: 'center', width: '30%'}
                            , {field: "consume", title: '业绩(元)',align: 'center', width: '31%', minWidth: 100}
                        ]]
                    });
                    //日查详情--玩家
                    table.render({
                        elem: "#PlayerDetail"
                        , url: "/wechat/day-details"
                        , where: {
                            is_agent: 0,
                            agent_id: playerId,
                            search_date: date,
                            start_time: $('#startTime').val(),
                            end_time: $('#endTime').val()
                        }
                        , cols: [[
                            {field: "nickname", title: '玩家昵称', align: 'center', width: '40%'}
                            , {field: "id", title: "ID", align: 'center', width: '31%'}
                            , {field: "consume", title: '业绩(元)', align: 'center', width: '30%', minWidth: 100}
                        ]]
                    });
            }
        });

        //周查
        var initWeekCol = [];
        var rebateSwitch = '<?php echo $rebateSwitch;?>';
        console.log(rebateSwitch);
        if(rebateSwitch == 1){
            initWeekCol = [
                {field: "rebate_week", title: '日期', align: 'center', width: '50%'},
                {field: "consume", title: '业绩(元)', align: 'center', width: '60%', minWidth: '50%'}
            ];
        }else{
            initWeekCol = [
                {field: "rebate_week", title: '日期', align: 'center', width: '30%'}
                , {field: "consume", title: '业绩(元)', align: 'center', width: '30%'}
                , {field: "rebate", title: '收益(元)', align: 'center',width: '50%', minWidth: '40%'}
            ];
        }
        console.log(initWeekCol);
        table.render({
            elem: "#myAgentWeek"
            , url: "/wechat/results-week"
            , method: 'post'
            , where: {
                start_time: $('#startTime').val(),
                end_time: $('#endTime').val()
            }
            , cols: [initWeekCol]
        });

        //周查详情监听
        table.on('tool(myAgentWeek)', function (obj) {
            var data = obj.data;
            var playerId = data.parent_id;
            var date = data.rebate_week;
            
            $('#detailId').val(playerId);
            form.render();
            console.log(data);
            switch (obj.event) {
                //周查详情按钮
                case 'myAgentWeekDetail':
                    $('.myAgentWeek').addClass('layui-hide');
                    $('.myAgentWeekDetail').removeClass('layui-hide');
                    $('.detailDiv').removeClass('layui-hide');
                    $('.searchDiv').addClass('layui-hide');

                    //周查详情--代理
                    table.render({
                        elem: "#AgentWeekDetail"
                        , url: "/wechat/week-details"
//                        , page: true
                        , where: {
                            is_agent: 1,
                            agent_id: playerId,
                            search_date: date,
                            start_time: $('#startTime').val(),
                            end_time: $('#endTime').val()
                        }
                        , cols: [[
                            {field: "nickname", title: '代理昵称', width: 100, align: 'center'}
                            , {field: "id", title: "ID", width: 100, align: 'center'}
                            , {field: "consume", title: '业绩(元)', width: 100, align: 'center'}
                        ]]
                    });
                    //周查详情--玩家
                    table.render({
                        elem: "#PlayerWeekDetail"
                        , url: "/wechat/week-details"
//                        , page: true
                        , where: {
                            is_agent: 2,
                            agent_id: playerId,
                            search_date: date,
                            start_time: $('#startTime').val(),
                            end_time: $('#endTime').val()
                        }
                        , cols: [[
                            {field: "nickname", title: '玩家昵称', width: 100, align: 'center'}
                            , {field: "id", title: "ID", width: 100, align: 'center'}
                            , {field: "consume", title: '业绩(元)', width: 100, align: 'center'}
                        ]]
                    });
            }
        });

        form.on('radio(redio)', function (data) {
            if (data.value == 1) {
                $('.detailDiv').addClass('layui-hide');
                $('.searchDiv').removeClass('layui-hide');
                $('.myAgentWeekDetail').addClass('layui-hide');
                $('.myAgentDetail').addClass('layui-hide');
                $('.myAgentWeek').addClass('layui-hide');
                $('.myAgent').removeClass('layui-hide');
            } else {
                $('.detailDiv').addClass('layui-hide');
                $('.searchDiv').removeClass('layui-hide');
                $('.myAgentWeekDetail').addClass('layui-hide');
                $('.myAgentDetail').addClass('layui-hide');
                $('.myAgentWeek').removeClass('layui-hide');
                $('.myAgent').addClass('layui-hide');
            }
        });

        //查询
        var active = {
            search1: function () {
                var agentId = $('#agentID').val();
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                var type;
                var thisUrl;
                var thisTable;
                type = $('input[name="sex"]:checked ').val();

                if (type == 1) {
                    thisTable = 'myAgent';
                    thisUrl = '/wechat/results-day';
                } else {
                    thisTable = 'myAgentWeek';
                    thisUrl = '/wechat/results-week';
                }

                reloadIframe('resultsInfo');

                table.reload(thisTable, {
                    url: thisUrl,
                    method: 'post',
//                    page: {
//                        curr: 1
//                    },
                    where: {
                        agentId: agentId,
                        start_time: startTime,
                        end_time: endTime
                    }
                });

            },
            detail1: function () {

                var startTime = $('#startTimeDetail').val();
                var endTime = $('#endTimeDetail').val();
                var playerId = $('#detailId').val();
                var thisUrl;
                var thisTable;
                var isAgent;
                var type = $('input[name="sex"]:checked ').val();

                if (type == 1) {
                    thisUrl = '/wechat/day-details';
                    var layID = $(".layui-tab-title .layui-this").attr("lay-id");
                    if (layID == 'withdrawCash') {
                        thisTable = 'AgentDetail';
                        isAgent = 1;
                    } else {
                        thisTable = 'PlayerDetail';
                        isAgent = 0;
                    }
                } else {
                    thisUrl = '/wechat/week-details';
                    var layID = $(".layui-tab-title .layui-this").attr("lay-id");
                    if (layID == 'withdrawCash') {
                        thisTable = 'AgentWeekDetail';
                        isAgent = 1;
                    } else {
                        thisTable = 'PlayerWeekDetail';
                        isAgent = 2;
                    }
                }

                table.reload(thisTable, {
                    url: thisUrl,
                    method: 'post',
//                    page: {
//                        curr: 1
//                    },
                    where: {
                        agentId: playerId,
                        startTime: startTime,
                        endTime: endTime,
                        is_agent: isAgent
                    }
                });
            }
        };
        $('#search1').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        $('#detail1').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

    });
</script>
</body>
</html>