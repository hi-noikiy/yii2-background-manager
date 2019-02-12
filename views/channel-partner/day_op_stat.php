<style type="text/css">
    .layui-table-cell {
        height: auto;
        line-height: 20px;
        padding: 0 10px;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        box-sizing: border-box;
    }
    .BGO{background-color: #EEEEEE;padding:1px;}
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>每日运营统计</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <div class="list2">
        <form action="" class="layui-form BGO">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="startTime" name="start" placeholder="开始日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="endTime" name="end" placeholder="结束日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="partnerId" name="partnerId" placeholder="渠道合伙人id">
            </div>
            <div class="layui-btn" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
        </form>

        <table id="dailyOpera" class="layui-table" lay-filter="sort"></table>
    </div>
</div>
<script>
    layui.use(['table','laydate'],function () {
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});

        var table = layui.table;
        table.render({
            elem:'#dailyOpera'
            ,url:'/channel-partner/day-op-stat'
            ,method:"post"
            ,page:true
            ,cols:[[
                {field:'create_time',title:'日期'}
                ,{field:'regist',title:'新增注册',sort:true}
                ,{field:'dnu',title:'新增登陆'}
                ,{field:'ru1',title:'次日留存(%)'}
                ,{field:'total_login',title:'总登陆用户'}
                ,{field:'dau',title:'日登陆（DAU）'}
                ,{field:'recharge',title:'充值金额（元）'}
                ,{field:'system_recharge',title:'系统增发'}
                ,{field:'consume',title:'元宝消耗'}
                ,{field:'deposit',title:'元宝淤积',width:120}
                ,{field:'pay_user',title:'付费用户数'}
                ,{field:'arpu',title:'ARPU'}
                ,{field:'agent_div',title:'代理分成（元）'}
                ,{field:'agent_ti',title:'代理提现（元）'}
                ,{field:'new_user',title:'新手赠送（元宝）'}
                ,{field:'fist_recharge',title:'首冲赠送（元宝）'}
            ]]
        });
        var active = {
            search:function () {
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                var partnerId = $('#partnerId').val();
                table.reload('dailyOpera',{
                    page:{
                        curr:1
                    }
                    ,where:{
                        startTime:startTime
                        ,endTime:endTime
                        ,partnerId:partnerId
                    }
                })
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //排序
        table.on('sort(sort)', function(obj){
            table.reload('dailyOpera', {
                url:'/channel-partner/day-op-stat',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
    })
</script>
</body>
