

<!--充值限额开关-->
<form action="" class="layui-form" style="padding-top: 20px;">
    <div class="layui-form-item">
        <label class="layui-form-label">充值限额开关</label>
        <div class="checkbox layui-input-inline">
            <input type="checkbox" name="switch" lay-skin="switch" lay-text="ON|OFF" lay-filter="rechargeLimit" onclick="update('money_switch')"<?php if($money_switch){ echo " checked = \"checked\" ";}?> value="<?php echo $money_switch;?>">
        </div>
    </div>
</form>
<hr>
<!--充值额度设置-->

<form action="" class="layui-form">
    <div class="layui-form-item">
        <label for="" class="layui-form-label">排行榜可见度</label>
        <div class="layui-input-inline"><input type="text" class="layui-input" name="listVisibility" value="<?php echo $top_num;?>"></div>
        <button class="layui-btn" onclick="update('top_num')">更新</button>
    </div>
</form>
<hr>

<div class="layui-col-sm4">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">金额上限</label>
            <div class="layui-input-inline"><input type="text" class="layui-input" name="maximumAmount" value="<?php echo $all_num;?>"></div>
            <button class="layui-btn" onclick="update('all_num')">更新</button>
        </div>
    </form>
</div>
<div class="layui-col-sm4">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">场内限额</label>
            <div class="layui-input-inline"><input type="text" class="layui-input" name="fieldLimit" value="<?php echo $on_game;?>"></div>
            <button class="layui-btn" onclick="update('on_game')">更新</button>
        </div>
    </form>
</div>
<div class="layui-col-sm4">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">场外限额</label>
            <div class="layui-input-inline"><input type="text" class="layui-input" name="OTCLimit" value="<?php echo $off_game;?>"></div>
            <button class="layui-btn" onclick="update('off_game')">更新</button>
        </div>
    </form>
</div>
<hr>
<script>
    function update(data) {
        var value = {};
        switch (data) {
            case 'money_switch':
                value={
                    money_switch:$("[name='switch']").val()
                }
                break;
            case 'top_num':
                value={
                    top_num:$("[name='listVisibility']").val()
                }
                break;
            case 'all_num':
                value={
                    all_num:$("[name='maximumAmount']").val()
                }
                break;
            case 'on_game':
                value={
                    on_game:$("[name='fieldLimit']").val()
                }
                break;
            case 'off_game':
                value={
                    off_game:$("[name='OTCLimit']").val()
                }
                break;

        }
        $.ajax({
            url:'/pay-set/pay-limit',
            type:'POST',
            data:value
            ,
            success:function (res) {
                res = eval('('+res+')');
                if (res.code == 200) {
                    layer.msg('成功',{time:1000});
                    return;
                } else {
                    layer.msg('失败',{time:1000});
                    return;
                }
            }
        })
    }


    layui.use('form',function () {
        var form = layui.form;
        form.on('switch(rechargeLimit)', function(data){
            if (data.elem.checked) {
                $("[name='switch']").val(1);
            } else {
                $("[name='switch']").val(0);
            }
            update('money_switch');

        });

    })

</script>

