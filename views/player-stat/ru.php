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
            ,url: '/player-stat/ru-api'
            ,cols: [[
                {field: 'stat_date', title: '日期', fixed: 'left'}
                ,{field: 'all_user', title: '总用户数'}
                ,{field: 'dnu', title: '新增用户', sort: true}
                ,{field: 'ru_1', title: '次日留存', sort: true}
                ,{field: 'ru_2', title: '2日留存', sort: true}
                ,{field: 'ru_3', title: '3日留存'}
                ,{field: 'ru_4', title: '4日留存'}
                ,{field: 'ru_5', title: '5日留存'}
                ,{field: 'ru_6', title: '6日留存'}
                ,{field: 'ru_7', title: '7日留存'}
                ,{field: 'ru_14', title: '14日留存'}
                ,{field: 'ru_30', title: '30日留存'}
                ,{field: 'ru_60', title: '60日留存'}
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
            })
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