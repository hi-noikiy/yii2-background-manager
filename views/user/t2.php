<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
            <select name="name">
                <option value=""></option>
                <option value="0">上海</option>
                <option value="1">北京</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">输入框</label>
        <div class="layui-input-block">
            <input type="text" name="title" required lay-verify="required" placeholder="输入框内容" autocomplete="on" class="layui-input"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">选择框</label>
        <div class="layui-input-block">
            <select name="city" lay-verify="">
                <option value="">请选择一个城市</option>
                <option value="010">北京</option>
                <option value="021">上海</option>
                <option value="0571">杭州</option>
            </select>
        </div>

        <div class="layui-input-block">
            <select name="city" lay-verify="">
                <option value="010">北京</option>
                <option value="021" disabled>上海</option>
                <option value="0571" selected>杭州</option>
            </select>
        </div>

        <hr/>
        <div class="layui-input-block">
            <select name="quiz">
                <option value="">请选择</option>
                <optgroup label="城市记忆">
                    <option value="你工作的第一个城市">你工作的第一个城市？</option>
                </optgroup>
                <optgroup label="学生时代">
                    <option value="你的工号">你的工号？</option>
                    <option value="你最喜欢的老师">你最喜欢的老师？</option>
                </optgroup>
            </select>
        </div>

        <hr/>
        <div class="layui-input-block">
            <select name="city" lay-verify="" lay-search>
                <option value="010">layer</option>
                <option value="020">form</option>
                <option value="0571">layim</option>
            </select>
        </div>
    </div>

    <hr/>
    <div class="layui-form-item">
        <label class="layui-form-label">复选框</label>
        <div class="layui-form-block">
            <input type="checkbox" name="" title="写作" checked>
            <input type="checkbox" name="" title="发呆">
            <input type="checkbox" name="" title="禁用" disabled>
        </div>
    </div>

    <hr/>
    <div class="layui-form-item">
        <label class="layui-form-label">复选框</label>
        <div class="layui-form-block">
            <input type="checkbox" name="" title="写作" lay-skin="primary" checked>
            <input type="checkbox" name="" title="发呆" lay-skin="primary">
            <input type="checkbox" name="" title="禁用" lay-skin="primary" disabled>
        </div>
    </div>

    <hr/>
    <div class="layui-form-item">
        <label class="layui-form-label">复选框</label>
        <div class="layui-form-block">
            <input type="checkbox" name="" title="写作" lay-skin="switch" lay-text="ON|OFF" checked>
            <input type="checkbox" name="" title="发呆" lay-skin="switch" lay-text="开启|关闭">
            <input type="checkbox" name="" title="禁用" lay-skin="switch" disabled>
        </div>
    </div>

    <hr/>
    <div class="layui-form-item">
        <label class="layui-form-label">单选框</label>
        <div class="layui-form-block">
            <input type="radio" name="sex" value="man" title="男"/>
            <input type="radio" name="sex" value="woman" title="女" checked/>
            <input type="radio" name="sex" value="middle" title="中性" disabled/>

            <textarea name="text1" required lay-verify="required" placeholder="请输入内容" class="layui-textarea"></textarea>
        </div>
    </div>

    <hr/>
    <div class="layui-form-item">

        <div class="layui-inline">
            <label class="layui-form-label">范围</label>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="text" name="price_min" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="text" name="price_max" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline" style="width: 100px;">
                <input type="password" name="" autocomplete="off" class="layui-input">
            </div>
        </div>

    </div>

    <div class="layui-form-item">
        <div class="layui-form-label">范围</div>
        <div class="layui-form-block">
            <select lay-ignore>
                <option>lang</option>
            </select>
        </div>
    </div>
</form>