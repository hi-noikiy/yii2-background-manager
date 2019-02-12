<style>
    .lf{
        float:left;
    }
    .buttonStyle{
        text-align: center;
        margin: 0 auto;
        margin-bottom: -30px;
        width:155px;
        height:60px;
    }
    .add{
        border-radius: 20px;
    }
</style>

<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a><a><cite>vip商城列表</cite></a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
    <div class="buttonStyle">
        <div class="lf layui-btn add" data-method="add" id="add">
            <i class="layui-icon">&#xe61f;</i>
            添加vip账户
        </div>
    </div>
    <div class="list1">
        <table id="memberList" class="layui-table" lay-filter="table1">
            <caption><h2></h2></caption>
        </table>
    </div>

<script type="text/html" id="barMemberList">
    <div id="layerBtn">
        <button lay-event="revise" class="layui-btn layui-btn-sm">修改</button>
        <button lay-event="del" class="layui-btn layui-btn-sm layui-btn-danger" >删除</button>
    </div>
</script>

<script>
    layui.use(['table','layer','form'],function () {
        var table = layui.table;
        var form = layui.form;
        //vip商城列表自动加载
        table.render({
            elem:'#memberList'
            ,url:'/system/vip-recharge'
            ,method:"post"
            ,page:true
            ,cols:[[
                {field:'uid',title:'序号'}
                ,{field:'nickname',title:'昵称'}
                ,{field:'typeName',title:'类型'}
                ,{field:'number',title:'号码'}
                ,{field:'',title:'操作',toolbar:'#barMemberList'}
            ]]
        });

        //vip商城列表的监听事件
        table.on('tool(table1)',function (obj) {
            var data =obj.data;
            console.log(data);
            //修改操作
            if (obj.event==='revise'){
                var originalPublic;
                data.public==="是"? originalPublic=1:originalPublic=0;
                var id = data.id;
                var originalNickName = data.nickname;
                var originalType = data.type;
                var originalNumber = data.number;
                console.log(originalType);
                layer.open({
                    type: 1
                    ,title: false //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['40%','40%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['确认输入','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#revisecabbagelist')
                    ,success:function (layero,index) {
                        $('#updateNickname').val(originalNickName);
                        $('#updateType').val(originalType);
                        $('#updateNumber').val(originalNumber);
                        form.render();
                    }
                    ,yes:function (index,layero) {
                        var nickname = $('#updateNickname').val();
                        var type = $('#updateType').val();
                        var number = $('#updateNumber').val();
                        $.ajax({
                            url:'/system/update-vip-recharge-info',
                            type:"POST",
                            data:{
                                'id':id,
                                'nickname':nickname,
                                'type':type,
                                'number':number
                            }
                            ,success:function (data) {
                                data = eval("("+data+")");
                                if(data.code == 0){
//                                    layer.confirm('修改成功！',{time:1000});
//                                    layer.close(index);
                                    location.reload();
                                }else{
                                    layer.confirm(data.msg,{time:1000});
                                }

                            }
                            ,error:function () {
                                console.log("失败");
                                layer.msg('修改失败！',{time:1000});
                            }
                        });
                    }
                })

           //删除操作
            }else if(obj.event==='del'){
                var rid = obj.data.id;
                var nickname = obj.data.nickname;
                console.log(rid);
                console.log(nickname);
                layer.open({
                    type:1
                    ,title:false
                    ,closeBtn:1
                    ,area:['30%','30%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,content:$('#del')
                    ,success:function (layero,index) {
                        $('#num').html(nickname);
                    }
                    ,yes:function (index,layero) {
                        $.ajax({
                            url:'/system/remove',
                            type:'POST',
                            data:{
                                'id': rid
                            },
                            success:function (data) {
                                console.log("成功");
                                //删除成功后重载表格
                                table.reload('memberList', {
                                    url:'/system/vip-recharge'
                                });
                                layer.close(index);
                                layer.msg('删除成功！',{time:1000});
                            },
                            error:function () {
                                console.log("失败");
                                layer.msg('删除失败！',{time:1000});

                            }
                        });

                    }
                })
            }
        });

        var active = {
            //新增vip账户功能
            add:function () {
                layer.open({
                    type: 1
                    ,title: false //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['40%','40%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['添加','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#addContent')
                    ,yes:function (index,layero) {
                        var nickname = $('#addName').val();
                        var type = $('#addType').val();
                        var number = $('#addNumber').val();
                        $.ajax({
                            url:'/system/update-vip-recharge-info',
                            type:"POST",
                            data:{
                                'nickname':nickname,
                                'type':type,
                                'number':number
                            }
                            ,success:function (data) {
                                data = eval("("+data+")");
                                if(data.code == 0){
                                    layer.close(index);
                                    layer.confirm('添加成功！',{time:1000});
                                    table.reload('memberList', {
                                        url:'/system/vip-recharge'
                                    });
                                }else{
                                    alert(data.msg);
                                }
                            }
                            ,error:function () {
                                alert("添加失败!");
                            }
                        });
                    }
                })
            }
        };
        //添加vip账号
        $('#add').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });

        //排序
        table.on('sort(sort1)', function(obj){
            table.reload('memberList', {
                url:'/system/vip-recharge',
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

<!--vip商城列表的修改弹出层-->
<style>
    .center{
        position:absolute;
        left: 20%;
    }
</style>

<!-- 弹出层 -->
<body>
    <!-- 修改弹出层 -->
    <div class="x-body" id="revisecabbagelist" style="display:none;">
        <div class="center">
            <div><h2>修改信息</h2></div>
            <form action="" class="layui-form">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">昵称</label>
                    <div class="layui-input-inline">
                        <input  id="updateNickname" type="text" class="layui-input" name="updateNickname">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select id="updateType">
                            <?php foreach ($type as $key=>$val){ ?>
                                <option value=<?php echo $key; ?> ><?php echo $val;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">号码</label>
                    <div class="layui-input-inline">
                        <input  id="updateNumber" type="text" class="layui-input" name="updateNumber">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 添加弹出层-->
    <div class="x-body" id="addContent" style="display:none;">
        <div class="center">
            <div><h2>添加vip账户</h2></div>
            <form action="" class="layui-form">
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">昵称</label>
                    <div class="layui-input-inline">
                        <input  id="addName" type="text" class="layui-input" name="addName">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">类型</label>
                    <div class="layui-input-inline">
                        <select name="addType" id="addType">
                            <?php foreach ($type as $key=>$val){ ?>
                                <option value=<?php echo $key; ?> ><?php echo $val;?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="" class="layui-form-label">号码</label>
                    <div class="layui-input-inline">
                        <input  id="addNumber" type="text" class="layui-input" name="addNumber">
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

<!--ip商城列表的删除弹出层-->
<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除<span id="num"></span>吗</h2>
</div>
