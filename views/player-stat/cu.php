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

        table.render({
            elem: '#LAY-app-system-order'
            ,url: '/player-stat/cu-api'
            ,cols: [[
                {field: 'stat_date', title: '日期', fixed: 'left'}
                ,{field: 'regist', title: '新增用户', sort: true}
                ,{field: 'ltv', title: 'LTV', sort: true}
                ,{field: 'c_0_avg', title: '当日消耗', sort: true}
                ,{field: 'c_1_avg', title: '次日消耗', sort: true}
                ,{field: 'c_2_avg', title: '2日消耗', sort: true}
                ,{field: 'c_3_avg', title: '3日消耗', sort: true}
                ,{field: 'c_4_avg', title: '4日消耗', sort: true}
                ,{field: 'c_5_avg', title: '5日消耗', sort: true}
                ,{field: 'c_6_avg', title: '6日消耗', sort: true}
                ,{field: 'c_7_avg', title: '7日消耗', sort: true}
                ,{field: 'c_8_avg', title: '8日消耗', sort: true}
                ,{field: 'c_9_avg', title: '9日消耗', sort: true}
                ,{field: 'c_10_avg', title: '10日消耗', sort: true}
                ,{field: 'c_14_avg', title: '14日消耗', sort: true}
                ,{field: 'c_30_avg', title: '30日消耗', sort: true}
                ,{field: 'c_60_avg', title: '60日消耗', sort: true}
            ]]
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