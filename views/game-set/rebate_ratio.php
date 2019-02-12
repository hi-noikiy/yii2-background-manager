<body>
<blockquote class="layui-elem-quote">蓝色区域为可编辑区域</blockquote>

<table id="ratio" lay-filter="test"></table>

    <script>
        layui.use('table', function(){
            var table = layui.table;

            table.render({
                elem: '#ratio'
                ,url: '/game-set/rebate-ratio'
                ,method: 'post'
                ,page: true
                ,toolbar: 'default'
                ,defaultToolBar: ['print', 'exports']
                ,cols: [[
                    {field: 'id', title: 'ID'}
                    ,{field: 'level', title: '代理等级'}
                    ,{field: 'min', title: '最低消耗', style: 'background-color:#009688;color: #fff'}
                    ,{field: 'max', title: '最高消耗', style: 'background-color:#009688;color: #fff'}
                    ,{field: 'ratio', title: '返利比例', style: 'background-color:#009688;color: #fff'}
                    ,{field: 'create_time', title: '创建时间'}
                    ,{field: 'update_time', title: '更新时间'}
                ]]
            });

            table.on('edit(test)', function(obj) {
                console.log(obj.value);
                console.log(obj.field);
                console.log(obj.data);
            });
        })
    </script>
</body>