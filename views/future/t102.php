<table id="demo1" lay-filter="demo1"></table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#demo1'
            ,height: 315
            ,url: '/future/t102'
            ,'page': true
            ,method: 'post'
            ,cols: [[ //标题栏
                {field: 'f_id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                ,{field: 'f_game', title: '游戏ID', width: 120, sort: true, edit:'text'}
                ,{field: 'f_num', title: '元宝数量', width: 80, sort: true}
                ,{field: 'f_price', title: '价格', width: 80, sort: true}
                ,{field: 'f_type', title: '类型', width: 80, sort: true}
                ,{field: 'f_award', title: '注释', width: 80, sort: true}
                ,{field: 'f_desc', title: '描述', width: 120, sort: true}
                ,{fixed: 'right', width:150, align: 'center', toolbar: '#barDemo'}
            ]]
        });

        table.on('tool(demo1)', function(obj){
            var data = obj.data;
            var layEvent = obj.event;
            var tr = obj.tr;

//            查看
            if (layEvent == 'detail') {

//            编辑
            } else if (layEvent == 'edit') {

            } else if (layEvent == 'del'){
                $.post('/future/t102?type=1', {f_id:data.f_id}, function(data, status){
                    if (status == 'success') {
                        layer.msg('success');
                    }
                }, 'json');
            }
        });
    });
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
</script>