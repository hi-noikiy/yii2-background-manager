<table class="layui-table" lay-data="{height:315, url:'/future/t5', page:true, id:'test'}" lay-filter="test">
    <thead>
    <tr>
        <th lay-data="{field:'id', width:80, sort: true}">ID</th>
        <th lay-data="{field:'stat_date', width:80}">用户名</th>
        <th lay-data="{field:'user_all', width:80, sort: true}">性别</th>
        <th lay-data="{field:'city'}">城市</th>
        <th lay-data="{field:'sign'}">签名</th>
        <th lay-data="{field:'experience', sort: true}">积分</th>
        <th lay-data="{field:'score', sort: true}">评分</th>
        <th lay-data="{field:'classify'}">职业</th>
        <th lay-data="{field:'wealth', sort: true}">财富</th>
    </tr>
    </thead>
</table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#test'
        });
    });
</script>