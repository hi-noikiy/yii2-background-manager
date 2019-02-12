<body class="layui-anim layui-anim-up">
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">运营统计</a>
        <a>
            <cite>每日元宝淤积</cite></a>
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
            ,url: '/operate/get-gold-alert' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'id', title: 'ID', sort: true, fixed: 'left'}
                ,{field: 'yesterday_sed', title: '昨日元宝淤积'}
                ,{field: 'today_recharge', title: '今日充值', sort: true}
                ,{field: 'today_add', title: '今日增发'}
                ,{field: 'today_new', title: '今日新手赠送'}
                ,{field: 'today_give', title: '今日首冲赠送'}
                ,{field: 'today_consume', title: '今日消耗'}
                ,{field: 'today_sed', title: '当前淤积'}
            ]]
            ,page: true
        });
    });
</script>
</body>