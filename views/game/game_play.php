<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>玩法参与统计</cite>
            </a>
        </span>
</div>
<div class="x-body">
    <div class="layui-form">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
        </div>
        <div class="layui-input-inline">
            <select name="" id="game_id" class="layui-input">
                <option value="0">所有游戏</option>
                <?php foreach ($games as $key => $val) { ?>
                    <option value=<?php echo $key; ?>> <?php echo $val; ?> </option>
                <?php } ?>
            </select>
        </div>
        <button class="layui-btn" data-method="search" id="search" data-type="reload" id="search">
            <i class="layui-icon">&#xe615;</i>
        </button>
    </div>
    <table class="layui-table " id="recordDetails"></table>
</div>
<script>
    //日期查询
    layui.use('laydate', function () {
        var laydate = layui.laydate;
        laydate.render({elem: '#startTime'});
        laydate.render({elem: '#endTime'});
    });
    layui.use(['table', 'layer'], function () {
        var table = layui.table;
        table.render({
            elem: "#recordDetails"
            , url: "/game/game-play"
            , page: true
            , id: "rechargeReload"
            , cols: [[
                {field: "stat_date", title: '统计日期',width:180}
//                , {field: "channel_id", title: '渠道ID',width:120}
                , {field: "game_id", title: '游戏名称',width:120}
                , {field: "player_number", title: '参与人数',width:100}
                , {field: "player_times", title: '参与人次',width:100}
                , {field: "consume", title: '消耗',width:80}
                , {field: "percentage", title: '消耗百分比',width:120}
                , {field: "ratio_number", title: '环比人数',width:120}
                , {field: "ratio_times", title: '环比人次',width:120}
            ]]
            , done: function (res, curr, count) {
                var arrtd = $('tbody').find('tr').find('td:eq(2) div');
                for (var i = 0; i < arrtd.length; i++) {
                    console.log(arrtd[i].innerHTML);
                    if (arrtd[i].innerHTML == 524815) {
                        $(arrtd[i]).html('推筒子')
                    }
                    if (arrtd[i].innerHTML == 524816) {
                        $(arrtd[i]).html('三张牌');
                    }
                    if (arrtd[i].innerHTML == 524817) {
                        $(arrtd[i]).html('填大坑');
                    }
                    if (arrtd[i].innerHTML == 524818) {
                        $(arrtd[i]).html('牛牛');
                    }
                    if (arrtd[i].innerHTML == 524821) {
                        $(arrtd[i]).html('百人推筒子');
                    }
                }
            }
        });

        //查询
        var $ = layui.$, active = {
            reload: function () {
                //执行重载
                table.reload('rechargeReload', {
                    url: '/game/game-play'
                    , page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    , where: {
                        start_time: $('#startTime').val()
                        , end_time: $('#endTime').val()
                        , game_id: $('#game_id').val()

                    }
                });
            }
        };
        $('#search').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    })
</script>
</body>

