<body>
<div class="x-body">
    <!--<form action="" class="layui-form">-->
            <div class="layui-input-inline">
                <div class="layui-inline">
                    <input type="text" class="layui-input" placeholder="代理ID" id="agentID">
                </div>
            </div>
            <div class="layui-inline">
                <input type="text" class="layui-input" id="date" placeholder="日期选择">
            </div>
            <div class="layui-btn" style="margin-left:-4px;" data-type="search" id="search">查询</div>
            <div class="layui-btn">导出</div>
    <!--</form>-->
    <table class="layui-table" id="agencyOpen"  lay-filter="sort"> </table>
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
            elem:"#agencyOpen"
            ,url:"/test/t206"
            ,page:true
            ,cols:[[
                {field:"number",title:"代理ID"}
                ,{field:"higherLevel",title:"上级信息（ID）"}
                ,{field:"startData",title:"创建时间",sort:true}
                ,{field:"nickName",title:"昵称"}
                ,{field:"userName",title:"真实姓名"}
                ,{field:"address",title:"地址"}
                ,{field:"agencyDailyIncome",title:"代理日收益"}
                ,{field:"openMembNum",title:"开局会员数（环比）"}
                ,{field:"perMemberConsumption",title:"会员人均消耗（环比）",width:180,sort:true}
                ,{field:"MemberOpeningTime",title:"会员开局人次（环比）",width:180,sort:true}
            ]]
        });
        var active = {
            search:function () {
                var agentID = $('#agentID').val();
                var date = $('#date').val();
                table.reload('agencyOpen',{
                    page:{
                        curr:1
                    }
                    ,where:{
                        agentID:agentID
                        ,date:date
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
            table.reload('agencyOpen', {
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
</html>