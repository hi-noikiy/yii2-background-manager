<body>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="stat_date" name="stat_date"/>
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
            elem: '#stat_date'
            ,value: '<?= $stat_date?>'
            ,max: '<?= $stat_date?>'
        });

        table.render({
            elem: '#LAY-app-system-order'
            ,url: '/consume/whale-user-api'
            ,cols: [[
                {field: 'stat_date', title: '日期', fixed: 'left'}
                ,{field: 'top', title: '顶级代理'}
                ,{field: 'parent', title: '上级代理'}
                ,{field: 'player', title: '玩家ID昵称'}
                ,{field: 'consume', title: '当日消耗(元宝)',width:150, sort: true}
                ,{field: 'recharge', title: '当日充值(元)', sort: true}
                ,{field: 'duihuan', title: '当日兑换(元)', sort: true}
                ,{field: 'win_lose', title: '当日输赢(元)', sort: true}
                ,{field: 'sz', title: '三张消耗', sort: true}
                ,{field: 'br_ttz', title: '百人推筒子消耗',width:150, sort: true}
                ,{field: 'ps', title: '拼十消耗', sort: true}
                ,{field: 'ttz', title: '推筒子消耗', sort:true}
                ,{field: 'regist', title: '注册时间', sort: true}

            ]]
            ,page: true
            ,cellMinWidth: 120
            ,toolbar: true
            ,autoSort: false
        });

        table.on('sort(LAY-app-system-order)', function(obj) {
            console.log(obj.field);
            console.log(obj.type);
            console.log(this);

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