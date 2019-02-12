<body class="login-bg">

<div class="login layui-anim layui-anim-up">
    <div class="message">一拳娱乐-管理后台</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form" action="/user/login">
        <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>

<script>
    layui.use(['form', 'layer'], function(){
        var form = layui.form;
        var layer = layui.layer;

        form.on('submit(login)', function(data){
            $ = layui.$;

            $.post("/user/login", data.field, function(res){
                if (res.code == 0) {
                    layer.msg(res.msg);
                }
                if (res.code == 1) {
                    layer.msg(res.msg);
                }
                if (res.code == 2) {
                    location.href = '/index/index';
                }

            }, 'json');

            return false;
        })
    })
</script>
