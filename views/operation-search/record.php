<br/>
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
        <input type="text" class="layui-input" placeholder="渠道CODE" id="channel_id">
    </div>
    <div class="layui-input-inline">
        <select name="gid" id="gid">
            <option value="">选择游戏</option>
            <?php foreach ($games as $key=>$value){ ?>
            <option value=<?php echo $key;?> ><?php echo $value;?></option>
            <?php } ?>
        </select>
    </div>
    <div class="layui-input-inline">
        <input type="text" class="layui-input" placeholder="牌桌ID" id="table_id">
    </div>
    <div class="layui-btn" style="margin-left:-4px;" data-type="search" id="search">查询</div>
</form>

<table class="layui-table" id="game_log" lay-filter="game_log_lay" style="margin-top: 50px;">
    <caption><h2>战绩日志</h2></caption>
</table>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
</script>

<table class="layui-table" id="player_game_log" lay-filter="player_game_log_lay" style="margin-top: 50px;">
    <caption><h2>玩家日志</h2></caption>
</table>

<script>
    layui.use(['table', 'laydate'], function(){
        var table = layui.table;
        var laydate = layui.laydate;
        var $ = layui.$;

//        var myDate = new Date();
//        var month = myDate.getMonth()+1;
//        var today = myDate.getFullYear()+"-"+month+"-"+myDate.getDate();
//        var tomorrow = myDate.getFullYear()+"-"+month+"-"+(myDate.getDate()+1);

        laydate.render({
            elem: '#start_time'
            ,value: ''
        });
        laydate.render({
            elem: '#end_time'
            ,value: ''
        });

        //加载页面时加载数据（根据时间区间加载）
        table.render({
            elem: '#game_log'
            ,url: '/operation-stat/game-log'
            ,method: 'post'
            ,page: true
            ,where:{
                start_time:$('#start_time').val(),
                end_time:$('#end_time').val(),
                player_id:$('#player_id').val(),
                channel_id:$('#channel_id').val(),
                gid:$('#gid').val(),
                table_id:$('#table_id').val()
            }
            ,cols: [[
                {field: 'channel_id', title: '渠道CODE'}
                ,{field: 'gid', title: '游戏ID'}
                ,{field: 'table_id', title: '牌桌ID'}
                ,{field: 'player_num', title: '玩家人数'}
                ,{field: 'dizhu', title: '底注'}
                ,{field: 'start_time', title: '开始时间', sort: true}
                ,{field: 'end_time', title: '结束时间', sort: true}
                ,{fixed: 'right', width:150, align:'center', toolbar: '#barDemo'} //这里的toolbar值是模板元素的选择器
            ]]
        });

        table.on('sort(game_log_lay)', function(obj){
            table.reload('game_log', {
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        $('#search').on('click',function () {

            table.reload('game_log',{
                url: '/operation-stat/game-log',
                method: 'post',
                where: {
                    start_time: $('#start_time').val(),
                    end_time: $('#end_time').val(),
                    player_id: $('#player_id').val(),
                    channel_id: $('#channel_id').val(),
                    gid: $('#gid').val(),
                    table_id: $('#table_id').val(),
                }
            })
        });

        //查看详情
        table.on('tool(game_log_lay)',function (obj) {
            var data = obj.data;
            console.log(data);
            var layEvent = obj.event;
            if (layEvent == 'detail') {
                table.render({
                    elem: '#player_game_log'
                    ,url: '/operation-stat/player-game-log'
                    ,method: 'post'
                    ,where:{
                        record_id:data.id,
                        game_id:data.gid
                    }
                    ,page: true
                    ,cols: [[
                        {field: 'player_id', title: '玩家ID'}
                        ,{field: 'nickname', title: '玩家姓名'}
                        ,{field: 'gold_old', title: '场前元宝'}
                        ,{field: 'win_gold', title: '输赢元宝'}
                        ,{field: 'gold_new', title: '场后元宝'}
                        ,{field: 'table_pos', title: '牌桌位置'}
                        ,{field: 'player_card', title: '牌型'}
                        ,{field: 'operate', title: '操作', sort: true}
                        ,{field: 'mengxin', title: '萌新', sort: true}
                    ]]
                });
            }
        });
    });
</script>