<body >
    <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="#">代理相关</a>
                <a>
                    <cite>实时返利</cite>
                </a>
            </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
    </div>
    <div class="x-body">
        <div class="layui-row">
            <form class="layui-form layui-col-md12 x-so">
                <input class="layui-input" placeholder="开始日期" name="start" id="start"/>
                <input class="layui-input" placeholder="截止日期" name="end" id="end"/>
                <input type="text" name="player_id" placeholder="玩家ID" class="layui-input" id="player_id"/>
                <input type="text" name="father_id" placeholder="父级ID" class="layui-input" id="father_id"/>
                <input type="text" name="gfather_id" placeholder="祖父级ID" class="layui-input" id="gfather_id"/>
                <input type="text" name="ggfather_id" placeholder="曾祖父级ID" class="layui-input" id="ggfather_id"/>
<!--                <button class="layui-btn" lay-submit=""  id="search"><i class="layui-icon">&#xe615;</i></button>-->
                <div class="layui-btn"   id="search_"><i class="layui-icon">&#xe615;</i></div>
            </form>
        </div>

        <table id="details" ></table>
    </div>

<script>
    layui.use(['laydate', 'table'], function(){
        var table = layui.table,
            laydate = layui.laydate;

        laydate.render({
            elem: '#start'
        });

        laydate.render({
            elem: '#end'
        });

        table.render({
            elem: '#details'
            ,url: '/agent/rebate-list'
            ,page: true
            ,method: 'get'
            ,cols: [[
                {field: 'id', title: 'ID'}
                ,{field: 'gid', title: '游戏ID'}
                ,{field: 'player_id', title: '玩家ID'}
                ,{field: 'father_id', title: '父级ID', sort: true}
                ,{field: 'gfather_id', title: '祖父级ID', sort: true}
                ,{field: 'ggfather_id', title: '曾祖父级ID', sort: true}
                ,{field: 'num', title: '台费', sort: true}
                ,{field: 'father_num', title: '父级返利', sort: true}
                ,{field: 'gfather_num', title: '祖父级返利', sort: true}
                ,{field: 'ggfather_num', title: '曾祖父级返利', sort: true}
                ,{field: 'create_time', title: '返利时间'}
            ]]
        });

        table.on('sort(details-lay)', function(obj){
            table.reload('details', {
                url: '/agent/rebate-list'
                ,where: {
                    start_time: $('#start').val()
                    ,end_time: $('#end').val()
                    ,player_id: $('#player_id').val()
                    ,father_id: $('#father_id').val()
                    ,gfather_id: $('#gfather_id').val()
                    ,ggfather_id: $('#ggfather_id').val()
                }

            });
        });

        $('#search_').on('click', function(){
            console.log(11111);

            table.reload('details', {
                url: '/agent/rebate-list'
                ,where: {
                    start_time: $('#start').val()
                    ,end_time: $('#end').val()
                    ,player_id: $('#player_id').val()
                    ,father_id: $('#father_id').val()
                    ,gfather_id: $('#gfather_id').val()
                    ,ggfather_id: $('#ggfather_id').val()
                },

            });
        });
    });
</script>

<script type="text/html" id="createTime">
</script>
</body>