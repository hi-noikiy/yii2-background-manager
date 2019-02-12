<style>
    textarea{
        resize: none;
        border:1px solid #e6e6e6;
        height:38px;
        width:190px;
    }
</style>
<body >
<div class="x-body">
    <h3 style="text-align:center;">充值方式</h3>
    <table class="layui-table" id="payMode"  lay-filter="table1"></table>
    <h3 style="text-align:center;">充值渠道</h3>
    <div class="rf">
        <button class="layui-btn" data-method="add" id="add"><i class="layui-icon">&#xe61f;</i>新增</button>
    </div>
    <table class="layui-table" id="payChannel"  lay-filter="table2"></table>

</div>
</body>
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="sex" value="{{d.status}}" lay-skin="switch" lay-text="开|关" id="{{d.id}}" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<div class="x-body" id="createLayer" style="display: none">
    <form action="" class="layui-form" style="margin-top:50px;" lay-filter="reviseCurrencyForm">
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">appid</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" src="" id="appid" name="appid">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">appkey</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" id="appkey" name="appkey">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">渠道码</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" id="channel_code" name="channel_code">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">备注</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" id="remark" name="remark">
            </div>
        </div>
        <!--<div class="layui-form-item" style="width:100%;">-->
        <!--<div style="position: absolute;left:40%;margin-bottom: 15px;">-->
        <!--<button class="btn" lay-submit="" lay-filter="submit">确认</button>-->
        <!--<button class="btn" type="reset">重置</button>-->
        <!--</div>-->
        <!--</div>-->
    </form>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
    layui.use(['table','layer','form','upload'],function () {
        var table = layui.table;
        var $ = layui.jquery, layer = layui.layer;
        var form = layui.form;
        form.on('switch(status)', function (data) {
            if (data.elem.checked) {
                var status = 1
            } else {
                var status = 0;
            }
            $.ajax({
                url: '/pay-set/pay-mode',
                type:'POST',
                data:{
                    id:data.elem.id,
                    status:status
                },
                success:function(res){
                    res = eval('('+res+')');
                    if (res.code == 200) {
                        layer.msg('成功',{time:1000});
                        return ;
                    } else {
                        layer.msg('失败',{time:1000});
                        return ;
                    }
                }
            });
        });

        //支付方式
        table.render({
            elem: "#payMode"
            , url: '/pay-set/pay-mode-list'
            , page: true
            , cols: [[
                {field: 'id', title: "序号", align: "center"}
                , {field: 'pay_name', title: "支付名称", sort: true, align: "center"}
                , {field: 'status', title: '状态', templet: '#switchTpl'}
            ]]
        })

        //支付渠道
        table.render({
            elem: "#payChannel"
            , url: '/pay-set/pay-channel'
            , page: true
            , cols: [[
                {field: 'id', title: "序号",edit:'text', align: "center"}
                , {field: 'appid', title: "appid",edit:'text', sort: true, align: "center"}
                , {field: 'appkey', title: "appkey",edit:'text', sort: true, align: "center"}
                , {field: 'channel_code', title: "渠道码",edit:'text', sort: true, align: "center"}
                , {field: 'remark', title: "备注",edit:'text', sort: true, align: "center"}
                , {fixed: 'right', width:150, align:'center', toolbar: '#barDemo'}
            ]]
        })
        table.on('edit(table2)', function(obj){ //注：edit是固定事件名，test是table原始容器的属性 lay-filter="对应的值"
            console.log(obj.value); //得到修改后的值
            console.log(obj.field); //当前编辑的字段名
            console.log(obj.data); //所在行的所有相关数据
            $.ajax({
                url:'/pay-set/pay-channel-set',
                type:'POST',
                data:obj.data,
                success:function(res){
                    res = eval('('+res+')');
                    if (res.code == 200) {
                        layer.msg('成功',{time:1000});
                        return ;
                    } else {
                        layer.msg('失败',{time:1000});
                        return ;
                    }
                }
            })
        });

        //新增
        var active = {
            add:function(){
                layer.open({
                    type:1
                    ,title:'新增'
                    ,closeBtn:1
                    ,area:['60%','60%']
                    //,shade:0.5
                    ,id:"LAY_layuipro"
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#createLayer')
                    ,success:function (layero,index) {
                        $('#demo1').attr("src",'');
                        $('#link').val('')
                        $('#annotation').val('')
                    }
                    ,yes:function (index, layero) {
                        $.ajax({
                            url:'/pay-set/pay-channel-set',
                            type:'POST',
                            data:{
                                appid:$('#appid').val(),
                                appkey:$('#appkey').attr('src'),
                                channel_code:$('#channel_code').attr('src'),
                                remark:$('#remark').val()
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.close(index);
                                    table.reload('table2',{
                                        url:"/pay-set/pay-channel",
                                        page:true
                                    });
                                    //return ;
                                } else {
                                    layer.msg('修改失败',{time:1000});
                                }
                            }
                        })
                    }
                    ,btn2:function (index, layero) {
                        $('#demo1').attr("src",' ');
                        $('#demoText').empty()
                    }
                })
            }
        };
        $('#add').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });

        //工具条删除
        table.on('tool(table2)', function(obj){ //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
            var data = obj.data; //获得当前行数据
            var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
            var tr = obj.tr; //获得当前行 tr 的DOM对象
            if(layEvent === 'del'){ //删除
                layer.confirm('确认删除', function(index){
                    $.ajax({
                        url:'/pay-set/pay-channel-del',
                        type:'POST',
                        data:{
                            id:data.id
                        },
                        success:function (res) {
                            res = eval('('+res+')');
                            if (res.code == 200) {
                                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                            } else {
                                layer.msg('删除失败',{time:1000});
                            }
                        }
                    })
                    layer.close(index);
                    //向服务端发送删除指令
                });
            }
        });

    })
</script>

