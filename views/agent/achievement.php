<blockquote class="layui-elem-quote">操作说明：注意状态！！</blockquote>

<table id="achieve" lay-filter="lay-achieve"></table>

<script>
    layui.use(['table'], function(){
        var table = layui.table;

        table.render({
            elem: '#achieve'
            ,url: '/agent/achievement'
            ,page: true
            ,method: 'post'
            ,cols: [[
                {field: 'id', title: 'ID'}
                ,{field: 'parent_id', title: '父ID'}
                ,{field: 'player_id', title: '玩家ID'}
                ,{field: 'consume', title: '消耗'}
                ,{field: 'ratio', title: '等级差'}
                ,{field: 'rebate', title: '返利'}
                ,{field: 'type', title: '类型'}
                ,{field: 'rebate_week', title: '返利周'}
                ,{field: 'create_time', title: '执行时间'}
                ,{field: 'is_agent', title: '是否代理'}
            ]]
        });
    });
</script>