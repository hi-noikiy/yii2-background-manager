<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">游戏系统设置</a>
            <a>
                <cite>渠道设置</cite>
            </a>
        </span>
</div>
<div class="x-body">
    <form action="" class="layui-form" >
        <div class="layui-btn" data-type="addChannel" id="addChannel">新增渠道</div>

        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="渠道id" id="channelId">
        </div>

        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="代理id" id="agentId">
        </div>
        <div class="layui-btn"  data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
    </form>
    <table class="layui-table" id="channelTable" lay-filter="channelTable"> </table>
</div>
</body>
<script type="text/html" id="barhistoryBtn">
    <a class="" lay-event="check" title="修改">
        <button class="layui-btn layui-btn-xs" lay-event="edit">修改</button>
        <div class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" title="移除">移除</div>
    </a>
</script>
<script>
    layui.use(['table','layer','form'],function () {
        var table = layui.table;
        var form = layui.form;

        table.render({
            elem:"#channelTable"
            ,url:"/game-set/channel-list"
            ,page:true
            ,method:'post'
            ,cols:[[
                {field:'id',title:"序号"}
                ,{field:'channel_id',title:"渠道id"}
                ,{field:'channel_name',title:"渠道名称"}
                ,{field:'agent_id',title:"代理id"}
                ,{field:'create_time',title:"创建时间"}
                ,{field:'',title:"操作",toolbar:'#barhistoryBtn',width:100}
            ]]
        });

        table.on('tool(channelTable)', function(obj){
            var data = obj.data;
            console.log(data);
            if(obj.event === 'edit'){
                layer.open({
                    type:1
                    ,title:"修改"
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['20%','35%']
                    ,id:'LAY_layuipro'
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#addActive')
                    ,btn:['确认','取消']
                    ,success:function (layero,index) {
                        console.log(data.channel_id);
                        console.log(data.channel_name);
                        console.log(data.agent_id);

                        $('#channel_id').val(data.channel_id);
                        $('#channel_name').val(data.channel_name);
                        $('#agent_id').val(data.agent_id);
                        $('#channel').val(data.id);

                        form.render();
                    }
                    ,yes:function (layero,index) {
                        saveData();
                    }
                })
            }else if (obj.event === 'del'){
                var id = obj.data.id;
                console.log(id);
                layer.open({
                    type:1
                    ,title:"刪除"
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['30%','25%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,content:$('#del')
                    ,yes:function (index,layero) {
                        $.ajax({
                            url:'/game-set/channel-del',
                            type:'POST',
                            data:{
                                'id': id
                            },
                            success:function (data) {
                                table.reload("channelTable",{url:'/game-set/channel-list'});
                            },
                            error:function () {
                                console.log("失败");

                            }
                        });
                        layer.close(index);
                    }
                })
            }
        });

        //查询
        var active = {
            search:function(){
                var channelId = $('#channelId').val();
                var agentId = $('#agentId').val();
                table.reload('channelTable',{
                    url:'/game-set/channel-list',
                    method: 'post',
                    page:{
                        curr:1
                    },
                    where:{
                        channelId:channelId,
                        agentId:agentId
                    }
                })
            },
            addChannel:function(){
                layer.open({
                    type:1
                    ,title:'新增'
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['50%','70%']
                    ,id:"LAY_layuipro"
                    ,btnAlign:'c'
                    ,moveType:1
                    ,btn:['确认','取消']
                    ,content:$('#addActive')
                    ,yes:function (layero,index) {
                        saveData();
                    }
                })
            }
        };
        $('#search').on('click',function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        $('#addChannel').on('click',function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //保存数据
        function saveData() {
            var channel = $.trim($('#channel').val());
            var channelId = $.trim($('#channel_id').val());
            var channelName = $.trim($('#channel_name').val());
            var agentId = $.trim($('#agent_id').val());

            if(!channelId || !channelName || !agentId){
                layer.msg('必填项不能为空,带*为必填项');return;
            }

            var data = {
                'id':channel
                ,'channel_id':channelId
                ,'channel_name':channelName
                ,'agent_id':agentId
            };

            if (channel) {
                layer.confirm('是否确定对当前活动进行修改?', {icon: 3, title:'提示'}, function(index){
                    $.ajax({
                        type:'POST'
                        ,data:data
                        ,url:"/game-set/channel-set"
                        ,success:function (data) {
                            layer.closeAll();
                            table.reload("channelTable",{url:'/game-set/channel-list'});
                        }
                        ,error:function (data) {
                            console.log("失败");
                        }
                    })
                });
            } else {
                $.ajax({
                    type:'POST'
                    ,data:data
                    ,url:"/game-set/channel-set"
                    ,success:function (obj) {
                        var data = eval('('+obj+')');
                        console.log(data);
                        if(data.code == 0){
                            layer.closeAll();
                            table.reload("channelTable",{url:'/game-set/channel-list'});
                        }else{
                            alert(data.msg);
                        }
                    }
                    ,error:function (data) {
                        console.log("失败");
                    }
                })
            }
        }
    })
</script>

<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除当前渠道吗？</h2>
</div>

<div class="x-body" style="display: none" id="addActive">
    <form action="" class="layui-form">
        <input type="hidden" value="" id="channel">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">*渠道id</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input"  lay-verify="required" name="channel_id" id="channel_id">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">*渠道名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="channel_name" id="channel_name" />
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">*代理id</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="agent_id" name="agent_id">
            </div>
        </div>
    </form>
</div>
