<style>
    h3{text-align: center;}
    .t1{text-align: center;}
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>封停账号</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
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
    <div class="layui-form-item">
        <label for="" class="layui-form-label" style="width: 30%;">备注</label>
        <div class="layui-input-inline" style="width: 30%;">
            <input type="text" class="layui-input"  name="remark">
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

<h3 style="margin-top:60px;">已封停账号</h3>
<div class="layui-form" style="margin-top:30px;">
    <div class="layui-form-item" >
        <table class="layui-table" style="width: 60%;margin-left: 20%">
            <tr>
                <td class="t1">封停用户ID</td>
<!--                <td class="t1">封停用户昵称</td>-->
                <td class="t1">封停时间</td>
<!--                <td class="t1">备注</td>-->
<!--                <td class="t1">操作人</td>-->
            </tr>
            <?php foreach ($data as $key=>$value){ ?>
                <tr>
                    <td class="t1"><?php echo $key;?></td>
                    <td class="t1"><?php echo $value;?></td>
                </tr>
            <?php } ?>

        </table>
    </div>
</div>
</div>
<script>
    layui.use('form',function () {
        var form = layui.form;

        /** 封停 */
        form.on('submit(closure)', function(data){
            $.ajax({
                type:'post'
                ,method:'post'
                ,data:{
                    'status':1,//封停
                    'playerId':data.field.playerID1
                }
                ,url:"/gm/seal"
                ,success:function (data) {
                    console.log(data);
                    data = eval('('+data+')');
                    if (data.code == 0) {
                        alert('封停成功');
                    } else if (data.code == -302) {
                        alert('玩家不存在');
                    } else {
                        alert('封停失败');
                    }
                }
                ,error:function (data) {
                    alert('请求错误');
                }
            })
        });

        /** 解封 */
        form.on('submit(deblocking)', function(data){
            $.ajax({
                method:'post'
                ,data:{
                    'status':2,//解封
                    'playerId':data.field.playerID2
                }
                ,url:"/gm/seal"
                ,success:function (data) {
                    data = eval('('+data+')');
                    console.log(data);
                    if (data.code == 0) {
                        alert('解封成功');
                    } else {
                        alert('解封失败');
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
