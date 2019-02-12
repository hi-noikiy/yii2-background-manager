<form class="layui-form" lay-filter="formTest">
    <div class="layui-form-item">
        <label class="layui-form-label">输入框</label>
        <div class="layui-input-block">
            <input type="text" name="username" placeholder="输入用户名" autocomplete="off" class="layui-input"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">下拉选择框</label>
        <div class="layui-input-block">
            <select name="interestt" lay-filter="aihao" lay-verify="required">
                <option></option>
                <option value="0">写作</option>
                <option value="1">阅读</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">复选框</label>
        <div class="layui-input-block">
            <input type="checkbox" name="like[write]" title="写作" lay-verify="required" lay-filter="lang"/>
            <input type="checkbox" name="like[read]" title="阅读" lay-verify="required" lay-filter="lang"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开关关</label>
        <div class="layui-input-block">
            <input type="checkbox" name="guan" lay-skin="switch" lay-filter="guan1"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开关开</label>
        <div class="layui-input-block">
            <input type="checkbox" name="kai" lay-skin="switch" checked/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">单选框</label>
        <div class="layui-form-block">
            <input type="radio" name="sex" value="0" title="男" checked lay-filter="sex"/>
            <input type="radio" name="sex" value="1" title="女" lay-filter="sex"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">填写描述</label>
        <div class="layui-form-block">
            <textarea placeholder="请输入内容" class="layui-textarea" name="miaoshu" lay-verType="alert"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">自定义验证</label>
        <div class="layui-form-block">
            <input type="text" name="ziding" class="layui-input"/>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-form-block">
            <button class="layui-btn" lay-submit lay-filter="go">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script>
    layui.use(['form', 'layer'], function(){
        var form = layui.form,
            layer = layui.layer;

//        监听提交
        form.on('submit(go)', function(data){
//            console.log(data.elem);
//            console.log(data.form);
//            console.log(data.field);

            $.post('/future/t100', data.field, function(data,status){
//                alert(data, status);
//                console.log(data, status);
                if (status == 'success') {
//                    alert('success');
                    layer.msg('成功');
                } else {
                    alert('接口异常');
                }
            }, 'json');

//            阻止表单跳转
            return false;
        });

//        监听select
        form.on('select', function(data){
            console.log(data.elem);
            console.log(data.value);
            console.log(data.othis);

//            console.log(data);
            $.post('/future/t100?type=1', data.value, function(data,status){
                if (status == 'success') {
                    layer.msg('成功2');
                }
            }, 'json');
        });

        form.on('checkbox(lang)', function(data){
//            console.log(data);
            console.log(data.elem);
            console.log(data.elem.checked);
            console.log(data.value);
            console.log(data.othis);
            console.log(data.elem.value);

            $.post('/future/t100?type=2', data.value, function(data,status){
                console.log(data);
            })
        });

        form.on('switch(guan1)', function(data){
            console.log(data);
        });

        form.on('radio(sex)', function(data){
            console.log(data.elem); //得到radio原始DOM对象
            console.log(data.value); //被点击的radio的value值
        });

        form.on('submit(go)', function(data){
            console.log(data.elem);
            console.log(data.form);
            console.log(data.field);

            return false;
        });

        form.val('formTest', {
            "username":'闲心'
            ,"sex":"女"
        });
    });
</script>