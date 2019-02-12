<body>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">开始时间</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="start_date" name="start_date"/>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束时间</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="end_date" name="end_date"/>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-order" lay-submit lay-filter="LAY-app-order-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="LAY-app-system-order" lay-filter="LAY-app-system-order"></table>
        </div>
    </div>
</div>

<script>
    layui.use(['table', 'laydate', 'form'], function(){
        var $ = layui.$;
        var table = layui.table
            ,laydate = layui.laydate
            ,form = layui.form;

        laydate.render({
            elem: '#start_date'
            ,value: '<?= $start_date?>'
            ,max: '<?= $end_date?>'
        });
        laydate.render({
            elem: '#end_date'
            ,value: '<?= $end_date?>'
            ,max: '<?= $end_date?>'
        });

        var channelId = "<?= $channel_id ?>";
        console.log(channelId);
        table.render({
            elem: '#LAY-app-system-order'
            ,url: '/consume/sub-game-api'
            ,cols: [[
                {field: 'stat_date', title: '日期', fixed: 'left', sort: true}
                ,{field: 'consume', title: '总消耗', sort: true}

                ,{field: 'br_ttz', title: '百人推筒子', sort: true}
                ,{field: 'br_ttz_consume_contrast', title: '环比昨日'}
                ,{field: 'br_ttz_player_number', title: '活跃人', sort: true}
                ,{field: 'br_ttz_number_contrast', title: '环比昨日'}

                ,{field: 'sz', title: '三张', sort: true}
                ,{field: 'sz_consume_contrast', title: '环比昨日'}
                ,{field: 'sz_player_number', title: '活跃人', sort: true}
                ,{field: 'sz_number_contrast', title: '环比昨日'}

                ,{field: 'ps', title: '拼十', sort: true}
                ,{field: 'ps_consume_contrast', title: '环比昨日'}
                ,{field: 'ps_player_number', title: '活跃人', sort: true}
                ,{field: 'ps_number_contrast', title: '环比昨日'}
//                ,{field: 'gg', title: 'GG伞下消耗', sort: true}
//                ,{field: 'br_ttz', title: '环比昨日', sort: true}
//                ,{field: 'br_ttz', title: '活跃人', sort: true}
//                ,{field: 'br_ttz', title: '环比昨日', sort: true}
            ]]
//            , done:function () {
//                if(channelId != 1){
//                    $("[data-field='gg']").css('display','none');
//                }
//            }
            ,page: true
            ,cellMinWidth: 120
            ,toolbar: true
            ,autoSort: false
        });

        table.on('sort(LAY-app-system-order)', function(obj) {
            table.reload('LAY-app-system-order', {
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

//        监听搜索
        form.on('submit(LAY-app-order-search)', function(data){
            var field = data.field;

            table.reload('LAY-app-system-order', {
                where: field
            })
        });
    });
</script>
</body>
