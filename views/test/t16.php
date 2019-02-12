<form class="layui-form" action="/index/index">
    <div class="layui-form-item">
        <label class="layui-form-label">输入框</label>
        <div class="layui-input-block">
            <input type="text" name="" placeholder="请输入" autocomplete="off" class="layui-input" lay-verify="phone"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">下拉选择框</label>
        <div class="layui-input-block">
            <select name="interest" lay-filter="aihao">
                <option value="0">写作</option>
                <option value="0">阅读</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">复选框</label>
        <div class="layui-input-block">
            <input type="checkbox" name="like[write]" title="写作" lay-filter="like"/>
            <input type="checkbox" name="like[read]" title="阅读" lay-filter="like"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开关关</label>
        <div class="layui-input-block">
            <input type="checkbox" lay-skin="switch" lay-filter="kaiguan"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开关开</label>
        <div class="layui-input-block">
            <input type="checkbox" checked lay-skin="switch"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">单选框</label>
        <div class="layui-input-block">
            <input type="radio" name="sex" value="0" title="男" lay-filter="sex"/>
            <input type="radio" name="sex" value="1" title="女" lay-filter="sex" checked/>
        </div>
    </div>

    <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">请填写描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入你内容" class="layui-textarea"></textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>


<script>
    layui.use('form', function(){
        var form = layui.form;

        form.on('select(aihao)', function(data){
            console.log(data);
            console.log(data.elem);
            console.log(data.value);
            console.log(data.othis);
        });

        form.on('checkbox(like)', function(data){
            console.log(data);
        });

        form.on('checkbox(kaiguan)', function(data){
            console.log(data);
        });

        form.on('radio(sex)', function(data){
            console.log(data);
        });

        form.on('submit(*)', function(data){
            console.log(data);
        });
    })
</script>