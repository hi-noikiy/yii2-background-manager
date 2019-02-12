<style>
    .x-nav {
        margin-bottom: 10px !important;
        padding: 0 !important;
    }
    .BGO {
        background-color: #EEEEEE;
        padding: 1px;
    }
</style>
<body>
<div class="x-body">
    <!--导航-->
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">代理相关</a>
            <a>
                <cite>代理树</cite>
            </a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
    </div>
    <!--查询-->
    <form action="/agent/agent-tree" method="post" class="layui-form BGO">
        <div class="layui-input-inline">
            <input type="text" name="agentID" value="<?php if($playerId){echo $playerId;}?>" class="layui-input" placeholder="代理ID" id="agentID">
        </div>
        <div class="layui-input-inline">
            <input type="text" name="startTime" value="<?php if($startDate){echo $startDate;}?>" class="layui-input" placeholder="开始日期" id="startTime">
        </div>
        <div class="layui-input-inline">
            <input type="text" name="endTime" value="<?php if($endDate){echo $endDate;}?>" class="layui-input" placeholder="结束日期" id="endTime">
        </div>
        <button class="layui-btn" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></button>
    </form>
</div>
<div class="layui-form">
    <div class="layui-form-item" >
        <table class="layui-table">
            <tr>
                <td id="t1">代理名称</td>
                <td id="t1">消耗</td>
            </tr>
            <tbody class="cate">
                <?php foreach ($data as $key=>$value){ ?>
                    <tr class="cate" cate-id=<?php echo $value['player_id'];?> fid=<?php echo $value['parent_index'];?> >
                        <td>
                            <i class="layui-icon show" status='true'>&#xe623;</i>
                            <?php echo $value['name'];?>
                        </td>
                        <td class="td-manage">
                            <?php echo $value['expend'];?>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    layui.use('laydate',function(){
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});
    });
    layui.use(['table','layer','form'], function() {
        var table = layui.table;
        var layer = layui.layer;
        var form = layui.form;

        $(document).on('click','.show',function(){
            var status = $(this).attr('status');
            var parentIndex = $(this).parents().parents().attr('cate-id');
            var thisClick = $(this);
            var startTime = $("#startTime").val();
            var endTime = $("#endTime").val();

            $.ajax({
                url:"/agent/get-lower",
                type:'post',
                data:{
                    'parentIndex':parentIndex,
                    'startDate':startTime,
                    'endDate':endTime
                }
                ,success:function (data) {
                    data = eval("("+data+")");
                    console.log(data);
                    if(data.code === 0){
                        console.log(status);
                        if(data.data.length === 0){
                            alert('玩家无下级');return;
                        }
                        if(status == "true"){
                            thisClick.html('&#xe625;');
                            thisClick.attr('status','false');
                            cateId = $(this).parents('tr').attr('cate-id');
                            $("tbody tr[fid="+cateId+"]").show();

                            var level = data.msg * 4;//此时，后台返回成功后msg的值为该玩家的级数
                            console.log(level);
                            var nbsp = getNbsp(level);//根据级数判断元素前面有几个空格
                            $.each(data.data,function(index,value){
                                thisClick.parent().parent('.cate').after('<tr class="cate" cate-id='+'"'+value.player_id+'"'+' fid='+'"'+value.parent_index+'"'+'>' +
                                    '<td>'+nbsp+'<i class="layui-icon show" status="true">&#xe623;</i>'+value.name+'</td>' +
                                    '<td class="td-manage">'+value.expend+'</td></tr>');
                            })
                        }else{
                            cateIds = [];
                            thisClick.html('&#xe623;');
                            thisClick.attr('status','true');
                            cateId = thisClick.parents('tr').attr('cate-id');
                            getCateId(cateId);
                            for (var i in cateIds) {
                                $("tbody tr[cate-id="+cateIds[i]+"]").remove();
                            }
                        }
                    }else{
                        alert(data.msg);
                    }

                }
                ,error:function (data) {
                console.log("失败");
            }
            });
        });

        function getNbsp(num) {
            var nbsp = "&nbsp;";
            var res='';
            for (var i=1;i<=num;i++){
                res += nbsp;
            }
            return res;
        }
    })

</script>
</body>
