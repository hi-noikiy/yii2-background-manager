<style>
    .label{width:150px!important;}
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>跑马灯配置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <div class="layui-col-xs12 layui-col-xs-offset1" style="margin-top: 3%;">
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label label">GM消息时间间隔（s）</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="content1">
                </div>
                <div class="layui-btn" lay-submit="" lay-filter="btn1">更新规则</div>
            </div>
        </form>
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label label">用户消息时间间隔（s）</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="content2">
                </div>
                <div class="layui-btn" lay-submit="" lay-filter="btn2">更新规则</div>
            </div>
        </form>
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label label">用户消息扣费（元宝）</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="content3">
                </div>
                <div class="layui-btn" lay-submit="" lay-filter="btn3">更新规则</div>
            </div>
        </form>
        <form action="" class="layui-form">
            <div class="layui-form-item ">
                <label for="" class="layui-form-label label">展示时间（s）</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="content4">
                </div>
                <div class="layui-btn" lay-submit="" lay-filter="btn4">更新规则</div>
            </div>
        </form>
    </div>
</div>
<script>
    layui.use('form',function () {
        console.log(1111111);
        $.ajax({
            type:"POST"
            ,url:'/marquee/get-pm-config'
            ,success:function (res) {
                res = eval('('+res+')');
                var data = res.data;
                $("[name='content1']").val(data.gm_post);
                $("[name='content2']").val(data.user_post);
                $("[name='content3']").val(data.deduct);
                $("[name='content4']").val(data.show_time);
            }
            ,error:function () {
                layer.msg('获取详情失败！',{time:1000});
            }
        });
        var form = layui.form;
        form.on('submit(btn1)',function (data) {
            $.ajax({
                type:"POST"
                ,url:'/marquee/update-pm-config'
                ,data:{
                    'type':1,
                    'value':data.field.content1,
                }
                ,success:function () {
                    layer.msg('更新成功！',{time:1000});
                }
                ,error:function () {
                    layer.msg('更新失败！',{time:1000});
                }
            })
        });
        form.on('submit(btn2)',function (data) {
            console.log(data.field);
            $.ajax({
                type:"POST"
                ,url:'/marquee/update-pm-config'
                ,data:{
                    'type':2,
                    'value':data.field.content2,
                }
                ,success:function () {
                    layer.msg('更新成功！',{time:1000});
                }
                ,error:function () {
                    layer.msg('更新失败！',{time:1000});
                }
            })
        });
        form.on('submit(btn3)',function (data) {
            console.log(data.field);
            $.ajax({
                type:"POST"
                ,url:'/marquee/update-pm-config'
                ,data:{
                    'type':3,
                    'value':data.field.content3,
                }
                ,success:function () {
                    layer.msg('更新成功！',{time:1000});
                }
                ,error:function () {
                    layer.msg('更新失败！',{time:1000});
                }
            })
        });
        form.on('submit(btn4)',function (data) {
            console.log(data.field);
            $.ajax({
                type:"POST"
                ,url:'/marquee/update-pm-config'
                ,data:{
                    'type':4,
                    'value':data.field.content4,
                }
                ,success:function () {
                    layer.msg('更新成功！',{time:1000});
                }
                ,error:function () {
                    layer.msg('更新失败！',{time:1000});
                }
            })
        });
    })
</script>
</body>

