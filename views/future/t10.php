<table id="demo" lay-filter="test"></table>



<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#demo'
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
                ,{fixed:'right', width:150, align:'center', toolbar:'#barDemo'}
            ]]
            ,id:'lang'
        });

        table.on('tool(lang)', function(obj){
            var data = obj.data;
            console.log(data);
        });

        var checkStatus = table.checkStatus('demo');
        console.log(checkStatus);
    })
</script>

<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>

    <!-- 这里同样支持 laytpl 语法，如： -->
    {{#  if(d.auth > 2){ }}
    <a class="layui-btn layui-btn-xs" lay-event="check">审核</a>
    {{#  } }}
</script>