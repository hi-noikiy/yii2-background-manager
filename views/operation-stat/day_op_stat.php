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
            ,url:'/operation-stat/day-op-stat'
            ,method:"post"
            ,page:true
            ,toolbar: 'default'
            ,cols:[[
                {field:'stat_date',title:'日期', width: 100}
                ,{field:'all_user',title:'总注册用户',sort:true,width: 120}
                ,{field:'regist',title:'新增绑定',sort:true,width: 100}
                ,{field:'dnu',title:'新增注册',width: 100}
                ,{field:'dau',title:'DAU',width: 100}
                ,{field:'ru_1',title:'次日留存', width: 90}
                ,{field:'ru_2',title:'2日留存', width: 90}
                ,{field:'ru_7',title:'7日留存', width: 90}
                ,{field:'all_amt',title:'总充值(元)',width: 100}
                ,{field:'amt',title:'今日充值(元)',width: 100}
                ,{field:'pay_user',title:'付费用户(人)',width: 100}
                ,{field:'pay_count',title:'付费次数',width: 100}
                ,{field:'arpu',title:'ARPU',width: 100}
                ,{field:'consume',title:'日消耗(元宝)',width: 100}
                ,{field:'fillup',title:'日淤积(元宝)',width: 100}
                ,{field:'tixian',title:'玩家兑换(元)',width: 100}
                ,{field:'daili_tixian',title:'代理提现(元)',width: 100}
                ,{field:'vip',title:'VIP充值(元)',width: 100}
                ,{field:'shouchong',title:'首充(元宝)',width: 100}
                ,{field:'xinshou',title:'新手(元宝)',width: 100}
                ,{field:'hongbao',title:'红包(元宝)',width: 100}
                ,{field:'total',title:'活动汇总(元宝)',minWidth: 100}
            ]]
        });
        var active = {
            search:function () {
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                table.reload('dailyOpera',{
                    page:{
                        curr:1
                    }
                    ,where:{
                        startTime:startTime
                        ,endTime:endTime
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
                url:'/test/t205',
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
