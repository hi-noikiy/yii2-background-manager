<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">代理相关</a>
            <a>
                <cite>渠道合伙人列表</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <form action="" class="layui-form BGO">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="代理ID" id="agentNick">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="代理昵称" id="nickName">
            </div>
            <div class="layui-btn"  data-type="search" id="search"><i class="layui-icon">&#xe615</i></div>
    </form>
    <table class="layui-table" id="partnerListTable"  lay-filter="table1"></table>
</div>
<script type="text/html" id="barpartnerList">
    <div id="layerDemo">
        <button class="layui-btn layui-btn-xs" lay-event="consume">消耗详情</button>
        <button class="layui-btn layui-btn-xs" lay-event="lowerLevel">下级详情</button>
    </div>
</script>
<script>
    layui.use(['table','layer','laydate'],function () {
        var $ = layui.$;
        $(".refresh").on("click",function(){
            window.location.href = window.location.href;
        });

        var table =  layui.table;
        var laydate = layui.laydate;
        laydate.render({
            elem: '#startTime1'
            ,value: ''
        });
        laydate.render({
            elem: '#endTime1'
            ,value: ''
        });
        laydate.render({
            elem: '#startTime2'
            ,value: ''
        });
        laydate.render({
            elem: '#endTime2'
            ,value: ''
        });
        //渠道合伙人table列表
        table.render({
            elem:"#partnerListTable"
            ,url:"/agent/partner-list"
            ,method: 'post'
            ,page:true
            ,toolbar: 'true' //开启工具栏，此处显示默认图标，可以自定义模板，详见文档
            ,defaultToolbar: ['filter', 'print', 'exports']
            ,cols:[[
                {field:"id",title:"序号"}
                ,{field:"player_id",title:"用户ID",sort:true}
                ,{field:"parent_index",title:"上级信息（ID）"}
                ,{field:"name",title:"昵称"}
                ,{field:"true_name",title:"真实姓名"}
                ,{field:"tel",title:"电话"}
                ,{field:"address",title:"地址"}
                ,{field:"create_time",title:"创建时间",sort:true}
                ,{field:"pay_back_gold",title:"当前余额",sort:true}
                ,{field:"follow",title:"跟进人"}
                ,{field:"goldExpendAll",title:"历史总消耗(元宝)"}
                ,{field:"goldExpendMonth",title:"当月消耗(元宝)"}
                ,{field:"lowerDali",title:"伞下代理数量"}
                ,{field:"lowerPlayer",title:"伞下玩家数量"}
/*                ,{field:"newDaili",title:"昨日新增代理数量"}
                ,{field:"newPlayer",title:"昨日新增玩家数量"}*/
                ,{field:"",title:"操作",width:205,toolbar:"#barpartnerList"}
            ]]
        });
        //渠道合伙人页面查询功能
        var active = {
            search:function () {
                var agentNick = $('#agentNick').val();
                var nickName = $('#nickName').val();
                table.reload('partnerListTable',{
                    url:'/agent/partner-list'
                    ,method: 'post'
                    ,scriptCharset:"utf-8"
                    ,page:{
                        curr:1
                    }
                    ,where:{
                        agentNick:agentNick,
                        nickName:nickName
                    }
                })
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //消耗详情/下级详情按钮监听事件
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            console.log(data);
            switch (obj.event) {
                case 'consume':
                    var player_id = data.player_id;
                    layer.open({
                        type: 1
                        ,title: false //不显示标题栏
                        ,closeBtn: 1
                        ,area: ['60%','70%']
                        ,shade: 0.8
                        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                        // ,btn: ['确认','取消']
                        ,btnAlign: 'c'
                        ,moveType: 1 //拖拽模式，0或者1
                        ,content:$('#consumedetails')
                        ,success:function (layero,index) {
                            //消耗详情页面的table渲染
                            table.render({
                                elem:"#consumeDetailsTable"
                                ,url:"/agent/partner-expend-detail/"
                                ,method:'post'
                                ,page:true
                                ,where:{
                                    playerId:player_id
                                }
                                ,cols:[[
                                    {field:"DAY",title:'日期',sort:true}
                                    ,{field:"NUM",title:'消耗详情'}
                                ]]
                            });
                            //消耗详情页面的查询功能
                            var active = {
                                reload1: function(){
                                    var userID = player_id;
                                    var startTime = $('#startTime1').val();
                                    var endTime = $('#endTime1').val();

                                    //执行重载
                                    table.reload('consumeDetailsTable', {
                                        url:'/agent/partner-expend-detail/'
                                        ,page: {
                                            curr: 1 //重新从第 1 页开始
                                        }
                                        ,where: {
                                            playerId: userID,
                                            start_time: startTime,
                                            end_time: endTime
                                        }
                                    });
                                }
                            };
                            $('#search1').on('click', function(){
                                var type = $(this).data('type');
                                active[type] ? active[type].call(this) : '';
                            });
                        }
                    });
                    break;
                case 'lowerLevel':
                    var player_id2 = data.player_id;
                    layer.open({
                        type: 1
                        ,title: false //不显示标题栏
                        ,closeBtn: 1
                        ,area: ['60%','70%']
                        ,shade: 0.8
                        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                        // ,btn: ['确认','取消']
                        ,btnAlign: 'c'
                        ,moveType: 1 //拖拽模式，0或者1
                        ,content:$('#lowerlevel')
                        ,success:function (layero,index) {
                            table.render({
                                elem: "#lowerlevelTable"
                                ,url: "/agent/lower-level-detail"
                                ,method:"post"
                                , page: true
                                , where:{
                                    playerId:player_id2
                                }
                                , cols: [[
                                    {field: "playerId", title: '代理ID', sort: true}
                                    , {field: "nickname", title: '昵称'}
                                    , {field: "one", title: '一级返利'}
                                    , {field: "two", title: '二级返利'}
                                    , {field: "lowerAllExpends", title: '伞下消耗'}
                                ]]
                            });
                            //查询
                            var active = {
                                reload2: function(){
                                    var lowerId = $('#lowerId').val();
                                    var startTime = $('#startTime2').val();
                                    var endTime = $('#endTime2').val();
                                    //执行重载
                                    table.reload('lowerlevelTable', {
                                        url:'/agent/lower-level-detail'
                                        ,page: {
                                            curr: 1 //重新从第 1 页开始
                                        }
                                        ,where: {
                                            lowerId: lowerId,
                                            start_time: startTime,
                                            end_time: endTime
                                        }
                                    });
                                }
                            };
                            //查询按钮绑定事件
                            $('#search2').on('click', function(){
                                var type = $(this).data('type');
                                active[type] ? active[type].call(this) : '';
                            });
                        }
                    });
                    break;
            }
        });

        //排序
        table.on('sort(table1)', function(obj){
            table.reload('partnerListTable', {
                url:'/agent/partner-list',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
    })
</script>
</body>

<!--消耗详情弹出层-->
<div class="x-body" id="consumedetails" style="display: none;">
    <!--过滤条件-->
    <div  class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="开始日期" id="startTime1">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="结束日期" id="endTime1">
            </div>
            <button class="layui-btn"  data-type="reload1" id="search1">查询</button>
        </div>
    </div>
    <!--表格数据-->
    <table class="layui-table " id="consumeDetailsTable" lay-filter="sort"></table>
</div>

<!--下级详情弹出层-->
<div class="x-body" id="lowerlevel" style="display: none;">
    <!--过滤条件-->
    <div  class="layui-form" >
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="代理ID" id="lowerId">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="开始日期" id="startTime2">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="结束日期" id="endTime2">
            </div>
            <button class="layui-btn"  data-type="reload2" id="search2">查询</button>
        </div>
    </div>
    <!--表格数据-->
    <table class="layui-table " id="lowerlevelTable" lay-filter="sort"></table>
</div>