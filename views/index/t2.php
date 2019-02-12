<body class="layui-anim layui-anim-up">
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>金币日志</cite>
            </a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
    </div>

    <div class="x-body">
        <div class="layui-row">
            <form class="layui-form layui-col-md12 x-so">
                <input class="layui-input" placeholder="开始日" name="start" id="start">
                <input class="layui-input" placeholder="截止日" name="end" id="end">
                <input type="text" name="username"  placeholder="用户ID" autocomplete="off" class="layui-input">
                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
            </form>
        </div>

        <xblock>
            <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
            <button class="layui-btn" onclick="x_admin_show('添加用户','./member-add.html',600,400)"><i class="layui-icon"></i>添加</button>
        </xblock>

        <table id="test1"></table>

        <xblock style="margin-top:50px">
            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe60a;</i>操作日志</button>
        </xblock>

        <table id="test2"></table>
    </div>

<script>
    layui.use(['laydate', 'table'], function(){
        var laydate = layui.laydate
            ,table = layui.table;

        laydate.render({
            elem: '#start'
            ,isInitValue: true
            ,value: '2018-08-29'
        });
        laydate.render({
            elem: '#end'
            ,isInitValue: true
            ,value: '2018-09-04'
        });

        table.render({
            elem: '#test1'
            ,url: '/index/t3'
            ,cols: [[
                {type: 'checkbox'}
                ,{field:'ID', title:'ID', width:80}
                ,{field:'ORDER_ID', title:'订单ID', width:200}
                ,{field:'SOURCE_TYPE', title:'充值来源', width:120, sort:true}
                ,{field:'PLAYER_ID', title:'玩家ID', width:120}
                ,{field:'MONEY_TYPE', title:'货币', width:120}
                ,{field:'COUNT', title:'操作数量', width:120}
                ,{field:'PRE_COUNT', title:'场前元宝', width:120, sort:true}
                ,{field:'OPERATION_TYPE', title:'操作类型', width:120}
                ,{field:'CREATE_TIME', title:'插入时间', width:200}
                ,{field:'REMARK', title:'备注'}
            ]]
            ,page: true
        });

        table.render({
            elem: '#test2'
            ,url: '/index/t4'
            ,cols: [[
                {field:'id', title:'ID'}
                ,{field:'username', title:'管理员名称'}
                ,{field:'op_time', title:'操作时间'}
                ,{field:'op_content', title:'操作内容'}
            ]]
            ,page: true
        });
    });
</script>
</body>