<body>

<div class="x-body">
    <div class="layui-inline"> <!-- 注意：这一层元素并不是必须的 -->
        <input type="text" class="layui-input" id="test1">
    </div>

    <table id="demo" lay-filter="test"></table>
</div>
</body>


<script>
    layui.use(['table', 'laydate'], function(){
        var laydate = new laydate;
        var table = new table;

        laydate.render({
            elem: '#test'
            ,type: 'month'
            ,range: true //或 range: '~' 来自定义分割字符
        });


        table.render({
            elem: '#demo'
            ,height: 315
            ,method: 'post'
            ,url: '/agent/rebate-details'
            ,page: true
            ,cols: [[
                {field: 'player_id', title: '玩家ID', sord: true}
                ,{field: 'lower_id', title: '下级ID', sord: true}
                ,{field: 'lower_num', title: '下级返利', sord: true}
                ,{field: 'slower_id', title: '下下级ID', sord: true}
                ,{field: 'slower_num', title: '下下级返利', sord: true}
                ,{field: 'sslower_id', title: '下下下级ID', sord: true}
                ,{field: 'sslower_num', title: '下下下级返利', sord: true}
            ]]
        });
    });
</script>

<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#test1' //指定元素
            ,range: '~'
        });
    });
</script>