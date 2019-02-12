<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营查询</a>
            <a>
                <cite>直兑记录</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<form action="" class="layui-form">
    <div class="layui-input-inline">
        <input type="text" class="layui-input" placeholder="开始时间" id="start_time">
    </div>
    <div class="layui-input-inline">
        <input type="text" class="layui-input" placeholder="结束时间" id="end_time">
    </div>
    <div class="layui-input-inline">
        <input type="text" class="layui-input" placeholder="玩家ID" id="player_id">
    </div>
    <div class="layui-input-inline">
        <input type="text" class="layui-input" placeholder="订单id" id="order_id">
    </div>
    <div class="layui-btn" style="margin-left:-4px;" data-type="search" id="search">查询</div>
</form>

<table class="layui-table" id="game_log" lay-filter="game_log_lay" style="margin-top: 50px;">
    <caption><h2>直兑记录</h2></caption>
</table>
</body>

<script>
    layui.use(['table', 'laydate'], function () {
        var table = layui.table;
        var laydate = layui.laydate;
        var $ = layui.$;
//        var myDate = new Date();
//        var month = myDate.getMonth()+1;
//        var today = myDate.getFullYear()+"-"+month+"-"+myDate.getDate();
//        var tomorrow = myDate.getFullYear()+"-"+month+"-"+(myDate.getDate()+1);
        laydate.render({elem: '#start_time'});
        laydate.render({elem: '#end_time'});

        //加载页面时加载数据（根据时间区间加载）
        table.render({
            elem: '#game_log'
            , url: '/operation-search/exchange'
            , method: 'post'
            , page: true
            , cols: [[
                {field: 'id', title: '序号',width:60}
                , {field: 'player_id', title: '玩家id',width:100}
                , {field: 'nickname', title: '玩家名称',width:100}
                , {field: 'order_id', title: '订单号',width:160}
                , {field: 'type', title: '支付类型',width:100}
                , {field: 'terrace', title: '平台',width:100}
                , {field: 'code', title: '账号',width:200}
                , {field: 'name', title: '玩家姓名(直兑是输入的姓名)',width:210}
                , {field: 'amount', title: '提现金额',width:100}
                , {field: 'service_charge', title: '手续费',width:100}
                , {field: 'status', title: '支付状态',width:100}
                , {field: 'create_time', title: '创建时间', sort: true,width:200}
                , {field: 'finish_time', title: '更新时间', sort: true,width:200}
                , {field: 'memo', title: '备注', sort: true,minWidth: 200}
            ]]
        });

        $('#search').on('click', function () {
            table.reload('game_log', {
                url: '/operation-search/exchange',
                method: 'post',
                page:{
                    curr:1
                },
                where: {
                    startTime: $('#start_time').val(),
                    endTime: $('#end_time').val(),
                    playerId: $('#player_id').val(),
                    orderId: $('#order_id').val()
                }
            })
        });
    });
</script>