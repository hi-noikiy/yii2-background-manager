<body>
<table id="demo" lay-filter="test"></table>
<script>
    layui.use('table', function(){
        var table = layui.table;

        //第一个实例
        table.render({
            elem: '#demo'
            ,url: '/test/t100' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'DAILI_ID', title: 'ID', width:80, sort: true, fixed: 'left'}
                ,{field: 'PLAYER_INDEX', title: 'UID', width:80}
                ,{field: 'PASSWORD', title: '密码', width:80, sort: true}
                ,{field: 'NAME', title: '昵称', width:80}
                ,{field: 'TEL', title: '电话', width: 80}
                ,{field: 'ADDRESS', title: '地址', width: 80, sort: true}
                ,{field: 'TYPE', title: '类型', width: 80, sort: true}
                ,{field: 'PARENT_INDEX', title: '上级代理', width: 80}
                ,{field: 'CREATE_TIME', title: '创建时间', width: 135, sort: true}
            ]]
        });
    });
</script>
</body>