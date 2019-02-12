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
                <cite>代理开局统计</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <form action="" class="layui-form BGO">
        <!--<label for="" class="layui-form-label">日期选择</label>-->
        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" placeholder="日期选择">
        </div>
        <button class="layui-btn" style="margin-left:-4px;" id="search" data-type="search"><i class="layui-icon">&#xe615;</i></button>
        <div class="layui-btn"><i class="layui-icon">&#xe601;</i></div>
    </form>
    <table class="layui-table" id="agencyOpenStat" lay-filter="sort"></table>
</div>
<script>
    //日期查询
    layui.use('laydate',function(){
        var laydate = layui.laydate;
        laydate.render({elem:'#date'});
    });
    layui.use('table',function () {
        //table数据渲染
        var table =layui.table;
        table.render({
            elem:"#agencyOpenStat"
            ,url:"/test/t206"
            ,page:true
            ,cols:[[
                {field:"number",title:"代理ID"}
                ,{field:"higherLevel",title:"上级信息（ID）"}
                ,{field:"startData",title:"创建时间",sort:true}
                ,{field:"nickName",title:"昵称"}
                ,{field:"userName",title:"真实姓名"}
                // ,{field:"address",title:"地址"}
                ,{field:"agentYBTotal",title:"代理元宝总量"}
                ,{field:"agencyDailyIncome",title:"代理日收益"}
                ,{field:"openMembNum",title:"开局会员数（环比）"}
                ,{field:"perMemberConsumption",title:"会员人均消耗（环比）",sort:true}
                ,{field:"MemberOpeningTime",title:"会员开局人次（环比）",sort:true}
            ]]
        });
        //排序
        table.on('sort(sort)', function(obj){
            table.reload('agencyOpenStat', {
                url:'/test/t205',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        var active = {
            search:function() {
                var searchDate = $('#date').val();
                table.reload('agencyOpenStat',{
                    page:{
                        curr:1
                    }
                    ,where:{
                        date:searchDate
                    }
                })
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
    })
</script>
</body>
</html>