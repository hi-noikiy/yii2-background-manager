<table id="test" lay-filter="test"></table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        var tableIns = table.render({
            elem: '#test'
            ,url:'/future/t5'
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,cols: [[
                {field: 'id', title: 'ID', width:80, sort:true, fixed:true}
                ,{field: 'stat_date', title: '日期', width:200, sort:true, fixed:true}
                ,{field: 'user_all', title: '用户数', width:80, sort:true, fixed:true}
                ,{field: 'play_all', title: '总局数', width:80, sort:true, fixed:true}
                ,{field: 'play_accord', title: '满5局次数', width:80, sort:true, fixed:true}
                ,{field: 'win_count', title: '赢局数', width:80, sort:true, fixed:true}
                ,{field: 'win_sum', title: '赢元宝数', width:80, sort:true, fixed:true}
                ,{field: 'lose_count', title: '输局数', width:80, sort:true, fixed:true}
                ,{field: 'lose_sum', title: '输元宝数', width:80, sort:true, fixed:true}
            ]]
        });

        console.log(tableIns);
    });
</script>