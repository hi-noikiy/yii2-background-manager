<table id="demo" lay-filter="test"></table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#demo'
            ,height: 315
            ,url: '/future/t101'
            ,page: true
            ,method: 'post'
            ,cols: [[
                {field: 'f_id', title: 'ID', width: 80, sort: true, fixed: 'left'}
                ,{field: 'f_game', title: '游戏ID', width: 80, sort: true, edit:'text'}
                ,{field: 'f_num', title: '元宝数量', width: 80, sort: true}
                ,{field: 'f_price', title: '价格', width: 80, sort: true}
                ,{field: 'f_type', title: '类型', width: 80, sort: true}
                ,{field: 'f_award', title: '注释', width: 80, sort: true}
                ,{field: 'f_desc', title: '描述', width: 80, sort: true}
            ]]
            ,text: '接口异常'
        });

        console.log(tableIns);
    });
</script>