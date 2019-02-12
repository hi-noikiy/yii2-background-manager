
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>官方充值</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <form action="" class="layui-form" style="margin-top:50px;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">用户ID</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="userID">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">货币数量</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required"  name="priceNum">
            </div>
        </div>
        <!--<div class="layui-form-item">-->
            <!--<label for="" class="layui-form-label" style="width: 20%;">现金额度（RMB）</label>-->
            <!--<div class="layui-input-inline" style="width: 60%;">-->
                <!--<input type="text" class="layui-input" lay-verify="required"  name="cashQuota" placeholder="0.00">-->
            <!--</div>-->
        <!--</div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">操作</label>
            <div class="layui-input-inline" style="width: 10%;">
                <select  id="" name="select1">
                    <option value="3">元宝</option>
                    <!--<option value="2">钻石</option>-->
                </select>
            </div>
            <div class="layui-input-inline" style="width: 10%;">
                <select  id="" name="select2">
                    <option value="1">充值</option>
                    <option value="2">消减</option>
                </select>
            </div>
            <div class="layui-input-inline" style="width: 10%;">
                <select  id="" name="select3">
                    <option value="1114112">大厅游戏-元宝</option>
                    <!--<option value="6">焖鸡-钻石</option>-->
                    <!--<option value="7">推倒胡-钻石</option>-->
                    <!--<option value="8">扣点-钻石</option>-->
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">备注</label>
            <div class="layui-input-inline" style="width: 60%;">
                <textarea type="text" class="layui-input" lay-verify=""  name="remarks"></textarea>
            </div>
        </div>
        <div class="layui-form-item" style="width:100%;">
            <div style="position: absolute;left:40%;margin-bottom: 15px;">
                <div class="layui-btn" lay-submit="" lay-filter="officialCharge">确认</div>
                <button class="layui-btn" type="reset">重置</button>
            </div>
        </div>
    </form>
</div>
<script>
    layui.use(['form', 'layedit', 'laydate'], function() {
        var form = layui.form;
        form.on('submit(officialCharge)', function (data) {
            // console.log(data);
            // layer.alert(JSON.stringify(data.field), {
            //     title: '最终的提交信息'
            // })
            //return false;
            $.ajax({
                type:"POST"
                ,url:'/setting/recharge'
                ,data:{
                    'player_id':data.field.userID
                    ,'gold_num':data.field.priceNum
                    ,'gold_type':data.field.select1
                    ,'use_type':data.field.select2
                    ,'gid':data.field.select3
                    ,'money_num':data.field.cashQuota
                    ,'content':data.field.remarks
                }
                ,success:function (res) {
                    res = eval('('+res+')');
                    if (res.code == 0) {
                        layer.msg('充值成功',{time:1000});
                        return ;
                    } else {
                        layer.msg('充值失败',{time:1000});
                    }
                }
                ,error:function () {
                    layer.msg('出现错误',{time:1000});
                }
            })
        })
    })
</script>
</body>
