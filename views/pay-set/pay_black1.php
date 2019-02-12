<body class="layui-anim layui-anim-up">
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="/index/welcome">首页</a>
            <a>
                <cite>充值黑名单</cite></a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
            <i class="layui-icon" style="line-height:30px">ဂ</i></a>
    </div>

    <div class="x-body">
        <div class="layui-row">
            <form class="layui-form layui-col-md12 x-so">
                <input class="layui-input" placeholder="开始日期" name="start" id="start"/>
                <input class="layui-input" placeholder="结束日期" name="end" id="end"/>
                <input type="text" name="playerid" placeholder="用户ID" autocomplete="off" class="layui-input"/>
                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>

        <xblock>
            <button class="layui-btn layui-btn-danger" onclik="delAll()"><i class="layui-icon"></i>批量开关</button>
            <button class="layui-btn" onclick="x_admin_show('添加用户','/pay-set/pay-black',600,400)"><i class="layui-icon"></i>添加</button>
        </xblock>

        <table id="demo" lay-filter="test"></table>
        <br/>
        <br/>
        <hr/>
        <hr/>
        <div class="layui-row">
            <form class="layui-form layui-col-md12">
                <input class="layui-input" placeholder="开始日期" name="start" id="start"/>
                <input class="layui-input" placeholder="结束日期" name="end" id="end"/>
                <input type="text" name="playerid" placeholder="用户ID" autocomplete="off" class="layui-input"/>
                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>

        <xblock>
            <button class="layui-btn layui-btn-danger" onclik="delAll()"><i class="layui-icon"></i>批量开关</button>
            <button class="layui-btn" onclick="x_admin_show('添加用户','/pay-set/pay-black',600,400)"><i class="layui-icon"></i>添加</button>
        </xblock>

        <table id="demo1" lay-filter="test"></table>

        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <table id="demo2" lay-filter="text"></table>
    </div>

<script type="text/html" id="switchTpl">
    <input type="checkbox" name="sex" value="{{d.id}}" lay-skin="switch" lay-text="开|关" lay-filter="status" {{ d.id == 1 ? 'checked' : '' }}>
</script>

<script type="text/html" id="barDemo">
        <a title="编辑"  onclick="x_admin_show('编辑','member-edit.html',600,400)" href="javascript:;">
            <i class="layui-icon">&#xe642;</i>
        </a>
        <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
            <i class="layui-icon">&#xe640;</i>
        </a>
</script>

<script>
    layui.use(['laydate', 'table'], function(){
        var laydate = layui.laydate;
        var table = layui.table;

        laydate.render({
            elem: '#start'
        });

        laydate.render({
            elem: '#end'
        });

        table.render({
            elem: '#demo'
            ,url: '/pay-set/pay-black-list'
            ,cols: [[
                {type:'checkbox'}
                ,{field:'id', title:'ID'}
                ,{field:'player_id', title:'玩家ID'}
                ,{field:'player_name', title:'玩家昵称'}
                ,{field:'create_time', title:'创建时间'}
                ,{field:'update_time', title:'更新时间'}
                ,{field:'status', title:'状态', templet:'#switchTpl'}
                ,{field:'operator', title:'操作者'}
                ,{title:'操作', templet:'#barDemo'}
            ]]
            ,page: true
        });

        table.render({
            elem: '#demo1'
            ,url: '/pay-set/pay-black-list'
            ,cols: [[
                {type:'checkbox'}
                ,{field:'id', title:'ID'}
                ,{field:'player_id', title:'玩家ID'}
                ,{field:'player_name', title:'玩家昵称'}
                ,{field:'create_time', title:'创建时间'}
                ,{field:'update_time', title:'更新时间'}
                ,{field:'status', title:'状态', templet:'#switchTpl'}
                ,{field:'operator', title:'操作者'}
                ,{title:'操作', templet:'#barDemo'}
            ]]
            ,page: true
        });

        table.render({
            elem: '#demo2'
            ,url: '/pay-set/pay-black-list'
            ,cols: [[
                {type:'checkbox'}
                ,{field:'id', title:'ID'}
                ,{field:'player_id', title:'玩家ID'}
                ,{field:'player_name', title:'玩家昵称'}
                ,{field:'create_time', title:'创建时间'}
                ,{field:'update_time', title:'更新时间'}
                ,{field:'status', title:'状态', templet:'#switchTpl'}
                ,{field:'operator', title:'操作者'}
                ,{title:'操作', templet:'#barDemo'}
            ]]
            ,page: true
        });
    });
</script>
</body>