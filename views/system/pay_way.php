<body>
<div class="x-body">
    <form action="game-set/table-fee" class="layui-form"  style="margin-top:50px;">
        <div class="layui-form-item" >
            <label for="" class="layui-form-label" style="width: 30%;">微信</label>
            <div class="layui-input-inline" >
                <select name="chess" class="wechat" id="wechat" style="width: 30%;">
                    <option value="0">关闭渠道</option>
                    <?php foreach ($data['wechat'] as $key=>$val){ ?>
                        <option value=<?php echo $val['id']; ?> <?php if($val['is_use'] == 1){echo "selected";}?> ><?php echo $val['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item" >
            <label for="" class="layui-form-label" style="width: 30%;">支付宝</label>
            <div class="layui-input-inline" >
                <select name="chess" class="alipay" id="alipay" style="width: 30%;">
                    <option value="0">关闭渠道</option>
                    <?php foreach ($data['alipay'] as $k=>$v){ ?>
                        <option value=<?php echo $v['id']; ?> <?php if($v['is_use'] == 1){echo "selected";}?> ><?php echo $v['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item" >
            <label for="" class="layui-form-label" style="width: 30%;">银联</label>
            <div class="layui-input-inline" >
                <select name="chess" class="unionpay" id="unionpay" style="width: 30%;">
                    <option value="0">关闭渠道</option>
                    <?php foreach ($data['unionpay'] as $k=>$v){ ?>
                        <option value=<?php echo $v['id']; ?> <?php if($v['is_use'] == 1){echo "selected";}?> ><?php echo $v['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item layui-col-xs4 layui-col-xs-offset4" style="margin-top: 30px;">
            <div class="layui-btn layui-btn-danger" lay-submit="" lay-filter="force">提交修改</div>
        </div>
    </form>
</div>
<script>
    layui.use('form',function () {
        var form = layui.form;
        var $ = layui.$;
        form.on('submit(force)',function (data) {
            var alipay = $("#alipay").val();
            var wechat = $("#wechat").val();
            var unionpay = $("#unionpay").val();
            $.ajax({
                type: 'post'
                , data: {
                    'alipay': alipay,
                    'wechat': wechat,
                    'unionpay': unionpay
                }
                , url: '/system/pay-way'
                , success: function (data) {
                    dataObj = eval("(" + data + ")");
                    console.log(dataObj);
                    if(dataObj.code === 200){
                        alert('修改成功！');
                        window.location("/system/pay-way");
                    }else{
                        alert(dataObj.msg);
                    }
                }
                ,error:function () {
                    alert('修改失败,请稍后重试！');
                }
            });
        })
    })
</script>
</body>
