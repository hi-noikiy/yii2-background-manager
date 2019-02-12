<!--我觉着可以经所有的表格操作功能放在一起使用、使用回调方式进行检测-->

<div class="demoTable">
    搜索日期：
    <div class="layui-inline">
        <input class="layui-input" name="id" id="demoReload" autocomplete="off">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="LAY_table_user" lay-filter="user"></table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#LAY_table_user'
            ,url: '/future/t5'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field: 'id', title: 'ID', width:80, sort:true, fixed:true}
                ,{field: 'stat_date', title: '日期', width:200, sort:true, fixed:true}
                ,{field: 'user_all', title: '用户数', width:80, sort:true, fixed:true}
                ,{field: 'play_all', title: '总局数', width:80, sort:true, fixed:true}
                ,{field: 'play_accord', title: '满5局次数', width:80, sort:true, fixed:true}
                ,{field: 'win_count', title: '赢局数', width:80, sort:true, fixed:true}
                ,{field: 'win_sum', title: '赢元宝数', width:80, sort:true, fixed:true}
                ,{field: 'lose_count', title: '输局数', width:80, sort:true, fixed:true}
                ,{field: 'lose_sum', title: '输元宝数', width:80, sort:true, fixed:true}
            ]]
            ,id:'testReload'
            ,page:true
            ,height: 'full-10'
            ,cellMinWidth: 80
            ,initSort: {
                field: 'stat_date'
                ,type: 'asc'
            }
        });

        table.on('tool(user)', function(obj){
            var data = obj.data;
            console.log(data);
        })

        var $ = layui.$, active = {
            reload: function(){
                var demoReload = $('#demoReload');

                table.reload('testReload', {
                    page: {
                        curr: 1
                    }
                    ,where:{
                        id: demoReload.val()
                    }
                });
            }
        };

        $('.demoTable .layui-btn').on('click', function(){
            var type = $(this).data('type');
            console.log(type);
            active[type] ? active[type].call(this) : '';
        });
    });
</script>