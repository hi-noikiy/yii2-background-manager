<body class="layui-anim layui-anim-up">
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>金币日志</cite>
            </a>
        </span>
</div>

<div class="x-body" style="overflow: scroll">
    <div class="row">
        <div class="layui-input-inline">
            <input class="layui-input" placeholder="开始日" name="start" id="start">
        </div>
        <div class="layui-input-inline">
            <input type="text" name="username" id="username" placeholder="用户ID" class="layui-input">
        </div>
        <div class="layui-input-inline">
            <div class="layui-btn" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
        </div>
    </div>
    <table id="test1"></table>
</div>

<script>
    layui.use(['laydate', 'table', 'form'], function () {
        var $ = layui.$;
        var laydate = layui.laydate;
        var table = layui.table;

        var myDate = new Date();
        //获取当前年
        var year = myDate.getFullYear();
        //获取当前月
        var month = myDate.getMonth() + 1;
        //获取当前日
        var date = myDate.getDate();
        var now = year + "-" + month + "-" + date;
        console.log(now);
        laydate.render({
            elem: '#start'
            , isInitValue: true
            , value: now
        });

        table.render({
            elem: '#test1'
            , url: '/operate/get-gold-log'
            , method: "post"
            , where: {
                date: now
            }
            , cols: [[
                {field: 'ID', title: 'ID', width: 100}
                , {field: 'CREATE_TIME', title: '插入时间', width: 180}
                , {field: 'ORDER_ID', title: '订单ID', width: 200}
                , {field: 'SOURCE_TYPE', title: '充值来源', width: 100, sort: true}
                , {field: 'PLAYER_ID', title: '玩家ID', width: 120}
                , {field: 'MONEY_TYPE', title: '货币', width: 60}
                , {field: 'COUNT', title: '操作数量', width: 100}
                , {field: 'PRE_COUNT', title: '场前元宝', width: 120, sort: true}
                , {field: 'OPERATION_TYPE', title: '操作类型', width: 100}
                , {field: 'REMARK', title: '备注', minWidth: 300}
            ]]
            , done: function (res, curr, count) {
                var arrtd = $('tbody').find('tr').find('td:eq(2) div');
                var operation = $('tbody').find('tr').find('td:eq(7) div');
                var goldType = $('tbody').find('tr').find('td:eq(4) div');
                for (var i = 0; i < arrtd.length; i++) {
                    console.log(arrtd[i].innerHTML);
                    //按照表的注释
                    if (arrtd[i].innerHTML == 0) {
                        $(arrtd[i]).html('游戏内消耗')
                    }
                    if (arrtd[i].innerHTML == 7) {
                        $(arrtd[i]).html('充值');
                    }
                    if (operation[i].innerHTML == 1) {
                        $(operation[i]).html('增加');
                    }
                    if (operation[i].innerHTML == 2) {
                        $(operation[i]).html('减少');
                    }
                    if (goldType[i].innerHTML == 1) {
                        $(goldType[i]).html('元宝');
                    }
                }
            }
            , page: true
        });

        table.on('sort(sort)', function (obj) {
            table.reload('test1', {
                url: '/operate/get-gold-log',
                initSort: obj
                , where: {
                    field: obj.field
                    , order: obj.type
                }
            });
        });

        //查询
        $('#search').on('click', function () {
            var date = $('#start').val();
            var playerId = $('#username').val();
            console.log(date);
            console.log(playerId);
            table.reload('test1', {
                url: '/operate/get-gold-log',
                method: 'post',
                page: {
                    curr: 1
                },
                where: {
                    date: date,
                    playerId: playerId
                }
            })
        });
    });
</script>
</body>