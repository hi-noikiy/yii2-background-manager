<style>
    h3{text-align: center;}
</style>
<body>
<div class="x-body">


    <h3>封停账号</h3>
    <form action="" class="layui-form" style="margin-top:30px;">

        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 30%;">玩家ID</label>
            <div class="layui-input-inline" style="width: 30%;">
                <input type="text" class="layui-input" lay-verify="required" name="playerID1">
            </div>
            <div>
                <button class="layui-btn" lay-submit="" lay-filter="closure">账号封停</button>
            </div>
        </div>
    </form>

    <h3 style="margin-top:60px;">解封账号</h3>
    <form action="" class="layui-form" style="margin-top:50px;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 30%;">玩家ID</label>
            <div class="layui-input-inline" style="width: 30%;">
                <input type="text" class="layui-input" lay-verify="required" name="playerID2">
            </div>
            <div>
                <button class="layui-btn" lay-submit="" lay-filter="deblocking">解封账号</button>
            </div>
        </div>
    </form>
    <form action="" class="layui-form" style="margin-top:30px;">
        <div class="layui-form-item" >
            <label for="" class="layui-form-label" style="width: 30%;" >已封停账号</label>
            <div class="layui-input-inline" style="width: 35%;background-color:#EEEEEE;line-height: 28px;padding:10px;">
                <div id="sealoffAccount" ></div>
            </div>
        </div>
    </form>
</div>
<script>
    //封停账号列表
    $.ajax({
        type:'post'
        ,data:{
        }
        ,url:"/closure/index"
        ,success:function (res) {
            res = eval ("(" + res + ")");
            console.log(res.data);
            $('#sealoffAccount').html(res.data);
            console.log("成功");
        }
        ,error:function (data) {
            console.log("失败");
        }
    })


    layui.use('form',function () {
        var form = layui.form;
        form.on('submit(closure)', function(data){
            $.ajax({
                type:'post'
                ,data:{
                    'status':1,
                    'player':data.field.playerID1
                }
                ,url:"/closure/update"
                ,success:function (data) {
                    data = eval('('+data+')');
                    if (data.code == 0) {
                        layer.msg('封停成功',{time:1000});
                        return false;
                    } else if (data.code == -44) {
                        layer.msg('玩家不存在',{time:1000});
                        return false;
                    } else {
                        layer.msg('封停失败',{time:1000});
                        return false;
                    }
                    console.log(data.code)
                }
                ,error:function (data) {
                    console.log('请求错误')
                }
            })
        });
        form.on('submit(deblocking)', function(data){
            $.ajax({
                type:'post'
                ,data:{
                    'status':2,
                    'player':data.field.playerID2
                }
                ,url:"/closure/update"
                ,success:function (data) {
                    data = eval('('+data+')');
                    if (data.code == 0) {
                        layer.msg('解封成功',{time:1000});
                        return false;
                    } else if (data.code == -44) {
                        layer.msg('玩家不存在',{time:1000});
                        return false;
                    } else {
                        layer.msg('解封失败',{time:1000});
                        return false;
                    }
                }
                ,error:function (data) {
                    console.log("失败");
                }
            })
        });
    })
</script>
</body>
