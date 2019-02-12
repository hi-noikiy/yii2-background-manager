<!--充值白名单得增删改查的新页面-->
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
            ,url: '/recharge/white-list-api'
            ,cols: [[
                {field: 'create_time', title: '录入时间', fixed: 'left'}
                ,{field: 'player_id', title: '玩家ID'}
                ,{field: 'player_name', title: '玩家昵称'}
                ,{field: 'consume', title: '历史消耗'}
                ,{field: 'is_agent', title: '是否代理'}
                ,{field: 'under_consume', title: '伞下消耗'}
                ,{field: 'status', title: '状态'}
                ,{field: 'regist_time', title: '创角时间'}
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