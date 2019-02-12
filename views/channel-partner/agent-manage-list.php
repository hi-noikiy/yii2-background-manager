<style>
    /*多行显示*/
    .layui-table-cell{
        height: auto;
        text-overflow: inherit;
        overflow: visible;
        white-space: normal;
        word-wrap: break-word;
    }
</style>
<div class="x-body"  style="overflow: scroll">
    <form action="" class="layui-form">
        <div class="layui-input-inline channelListDiv">
            <select name="channelList" id="channelList">
                <?php foreach ($channelList as $key=>$val){ ?>
                    <option value=<?php echo $val['channel_id'];?>> <?php echo $val['channel_name'];?></option>
                <?php } ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="代理id" id="agentId">
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
        </div>
        <div class="layui-input-inline layui-hide endTimeClass">
            <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
        </div>
        <div class="layui-btn"  data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
    </form>

    <table class="layui-table-cell" id="agentTable" lay-filter="table1"> </table>
</div>

<script>
    layui.use(['table','layer','form','laydate'],function () {
        var laydate = layui.laydate;
        var date = new Date();
        var month = date.getMonth() + 1;
        var strDate = date.getDate() - 1;
        var today = date.getFullYear() + "-" + month + "-" + strDate;

        laydate.render({
            elem:'#startTime',
            value: today
        });
        laydate.render({elem:'#endTime'});

        var table = layui.table;

        //table数据渲染(自动加载)
        table.render({
            elem:"#agentTable"
            ,url:"/channel-partner/agent-manage-list"
            ,method: 'post'
            ,toolbar: 'true' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
            ,defaultToolbar: ['filter', 'print', 'exports']
            ,page:true
            ,where:{
                'startTime':today,
                'endTime':today
            }
            ,autoSort: false
            ,cols:[[
                {field:"id",title:"序号",width:60}
                ,{field:"stat_date",title:"统计日期",width:120,sort:true}
                ,{field:"create_time",title:"创建日期",width:180}
                ,{field:"topIdName",title:"顶级ID",width:100}
                ,{field:"parentIdName",title:"上级ID",width:100}
                ,{field:"idName",title:"用户ID",width:150}
                ,{field:"telTrueName",title:"真实姓名/电话",width:120}
                ,{field:"day_under_consume",title:"当日伞下业绩(元)",width:200,sort:true}
                ,{field:"radio_under_consume",title:"环比昨日",width:100}
                ,{field:"day_direct_consume",title:"当日直属玩家业绩(元)",width:200,sort:true}
                ,{field:"radio_direct_consume",title:"环比昨日",width:100}
                ,{field:"new_player",title:"当日直属新增玩家",width:190,sort:true}
                ,{field:"new_agent",title:"当日直属新增代理",width:190,sort:true}
                ,{field:"new_direct_consume",title:"新增直属业绩(元)",minWidth:200,sort:true}
            ]]
            ,done: function () {
                var arrtd = $('tbody').find('tr').find('td:eq(10) div');
                for (var i=0;i<arrtd.length;i++){
                    var str = arrtd[i].innerHTML.substr(-1,1);
                    console.log(str.substr(-1,1));
                    if(str == "↓"){
                        $(arrtd[i]).parent().parent().css('background-color', 'yellow');//设置css
                    }
                }
            }
        });

        //排序
        table.on('sort(table1)', function(obj){
            table.reload('agentTable', {
                url:'/agent/agent-manage-list',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        //查询
        var active = {
            search:function(){
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                var agentId = $('#agentId').val();

                table.reload('agentTable',{
                    url:'/agent/agent-manage-list',
                    method: 'post',
                    page:{
                        curr:1
                    },
                    where:{
                        startTime:startTime,
                        endTime:endTime,
                        agentId:agentId
                    }
                })
            }
        };
        $('#search').on('click',function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        $("#agentId").blur(function () {
            var agentId = $('#agentId').val();
            if (agentId) {
                $('.endTimeClass').removeClass('layui-hide');
            }else{
                $('.endTimeClass').addClass('layui-hide');
            }
        });
    })
</script>