<body class="layui-anim layui-anim-up">
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">运营统计</a>
        <a>
            <cite>返利透明化</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="x_admin_show('添加用户','./admin-add.html')"><i class="layui-icon"></i>添加</button>
    </xblock>

    <table id="gold" lay-filter="test"></table>
</div>

<script>
    layui.use('table', function(){
        var table = layui.table;

        //第一个实例
        table.render({
            elem: '#gold'
            ,url: '/operate/get-rebate-details' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', sort: true, fixed: 'left'}
                ,{field: 'player_id', title: '玩家ID'}
                ,{field: 'father_id', title: '父级ID', sort: true}
                ,{field: 'gfather_id', title: '祖父级ID'}
                ,{field: 'ggfather_id', title: '曾祖父级ID'}
                ,{field: 'father_num', title: '父级返利元宝'}
                ,{field: 'gfather_num', title: '祖父级返利元宝'}
                ,{field: 'ggfather_num', title: '曾祖父级返利元宝'}
                ,{field: 'create_time', title: '返利时间'}
            ]]
            ,page: true
        });
    });
</script>
</body>