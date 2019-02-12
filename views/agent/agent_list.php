
<style>
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
    .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">代理相关</a>
            <a>
                <cite>代理列表</cite>
            </a>
        </span>
</div>
<div class="x-body">
    <form action="" class="layui-form BGO" >
        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="代理昵称" id="agentNick">
        </div>
        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="代理id" id="player_id">
        </div>
        <div class="layui-btn"  data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
    </form>
    <table class="layui-table" id="agentTable" lay-filter="table1" > </table>
</div>
<script type="text/html" id="baragentTable">
    <div id="layerDemo">
        <button class="layui-btn layui-btn-xs" lay-event="consume">消耗详情</button>
        <button class="layui-btn layui-btn-xs" lay-event="lowerLevel">下级详情</button>
    </div>
</script>

<!--消耗详情弹出层-->
<div class="x-body" id="consumedetails" style="display: none;">
    <!--过滤条件-->
    <div  class="layui-form">
        <div class="layui-form-item" style="margin: 0 auto;width: 55%">
            <div class="layui-input-inline" style="width: 200px">
                <input type="text" class="layui-input" placeholder="开始日期" id="startTime1">
            </div>
            <div class="layui-input-inline" style="width: 200px">
                <input type="text" class="layui-input" placeholder="结束日期" id="endTime1">
            </div>
            <div class="layui-btn"  data-type="reload1" id="search1">查询</div>
        </div>

        <div class="layui-form-item" style="text-align: center;margin-left: -20%">
            <div class="layui-input-block">
                <input name="sex" title="每日" lay-filter="redio" type="radio" checked="" value="1">
                <input name="sex" title="七日" lay-filter="redio" type="radio" value="2">
            </div>
        </div>
    </div>
    <!--表格数据-->
    <div class="main" id="day">
        <table class="layui-table" id="consumeDetailsTableDay" lay-filter="sort"></table>
    </div>
    <div class="main layui-hide" id="week">
        <table class="layui-table" id="consumeDetailsTableWeek" lay-filter="sort"></table>
    </div>
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
            <div class="layui-btn"  data-type="reload2" id="search2">查询</div>
        </div>
    </div>
    <!--表格数据-->
    <table class="layui-table " id="lowerlevelTable" lay-filter="sort"></table>
</div>

<script>
    layui.use(['table','layer','form', 'laydate'],function () {
        var $ = layui.$;
        $(".refresh").on("click",function(){
            window.location.href = window.location.href;
        });
        var table =layui.table;
        var laydate = layui.laydate;
        var form = layui.form;

        laydate.render({elem: "#startTime1"});
        laydate.render({elem: "#endTime1"});
        laydate.render({elem: "#startTime2"});
        laydate.render({elem: "#endTime2"});

        var myDate = new Date();
        $('#startTime1').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());
        $('#endTime1').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());
        $('#startTime2').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());
        $('#endTime2').val(myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate());

        //table数据渲染(自动加载)
        table.render({
            elem:"#agentTable"
            ,url:"/agent/agent-list"
            ,method: 'post'
            ,where:{
                agentNick:$('#agentNick').val()
            }
            ,page:true
            ,cols:[[
                {field:"id",title:"序号",width:60}
                ,{field:"player_id",title:"用户ID",sort:true,width:100}
                ,{field:"parent_index",title:"上级信息（ID）",width:100}
                ,{field:"name",title:"昵称",width:100}
                ,{field:"true_name",title:"真实姓名",width:100}
                ,{field:"tel",title:"电话",width:120}
                ,{field:"address",title:"地址",width:100}
                ,{field:"create_time",title:"创建时间",sort:true,width:180}
                ,{field:"pay_back_gold",title:"可提现金额",sort:true,width:100}
                ,{field:"all_pay_back_gold",title:"总收益",sort:true,width:120}
                ,{field:"follow",title:"跟进人",width:100}
                ,{field:"all_consume",title:"历史业绩",width:100}
                ,{field:"week_consume",title:"本周业绩",width:100}
                ,{field:"under_agent",title:"伞下代理",width:100}
                ,{field:"under_user",title:"伞下玩家",width:100}
                ,{field:"",title:"操作",minWidth:205,toolbar:"#baragentTable"}
            ]]
        });

        //消耗详情/下级详情按钮监听事件
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            console.log(data);
            var start_time = $('#startTime1').val();
            var end_time = $('#endTime1').val();

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
                                elem:"#consumeDetailsTableDay"
                                ,url:"/agent/partner-expend-detail/"
                                ,method:'post'
                                ,where:{
                                    playerId:player_id,
                                    start_time:start_time,
                                    end_time:end_time,
                                    type:1
                                }
                                ,cols:[[
                                    {field:"date",title:'日期',sort:true}
                                    ,{field:"value",title:'消耗详情(元)'}
                                ]]
                            });
                            table.render({
                                elem:"#consumeDetailsTableWeek"
                                ,url:"/agent/partner-expend-detail/"
                                ,method:'post'
                                ,where:{
                                    playerId:player_id,
                                    start_time:start_time,
                                    end_time:end_time,
                                    type:2
                                }
                                ,cols:[[
                                    {field:"date",title:'日期',sort:true}
                                    ,{field:"value",title:'消耗详情(元)'}
                                    ,{field:"rebate",title:'返利(元)'}
                                ]]
                            });
                            //消耗详情页面的查询功能
                            var active = {
                                reload1: function(){
                                    var userID = player_id;
                                    var startTime = $('#startTime1').val();
                                    var endTime = $('#endTime1').val();

                                    //执行重载
                                    table.reload('consumeDetailsTableDay', {
                                        url:'/agent/partner-expend-detail/'
                                        ,where: {
                                            playerId: userID,
                                            start_time: startTime,
                                            end_time: endTime,
                                            type:1
                                        }
                                    });
                                    table.reload('consumeDetailsTableWeek', {
                                        url:'/agent/partner-expend-detail/'
                                        ,where: {
                                            playerId: userID,
                                            start_time: startTime,
                                            end_time: endTime,
                                            type:2
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
//                                , page: true
                                , where:{
                                    agentId:player_id2,
                                    start_time:$('#startTime2').val(),
                                    end_time:$('#endTime2').val()
                                }
                                , cols: [[
                                    {field: "playerId", title: '代理ID'}
                                    , {field: "nickname", title: '昵称'}
                                    , {field: "consume", title: '伞下业绩', sort: true}
                                    , {field: "newAgent", title: '伞下新增代理', sort: true}
                                    , {field: "newPlayer", title: '伞下新增玩家', sort: true}
                                ]]
                            });
                            //查询
                            var active2 = {
                                reload2: function(){
                                    var lowerId = $('#lowerId').val();
                                    var startTime = $('#startTime2').val();
                                    var endTime = $('#endTime2').val();
                                    //执行重载
                                    table.reload('lowerlevelTable', {
                                        url:'/agent/lower-level-detail'
//                                        ,page: {
//                                            curr: 1 //重新从第 1 页开始
//                                        }
                                        ,where: {
                                            player_id: lowerId,
                                            start_time: startTime,
                                            end_time: endTime
                                        }
                                    });
                                }
                            };
                            //查询按钮绑定事件
                            $('#search2').on('click', function(){
                                var type = $(this).data('type');
                                active2[type] ? active2[type].call(this) : '';
                            });
                        }
                    });
                    break;
            }
        });

        //排序
        table.on('sort(table1)', function(obj){
            table.reload('agentTable', {
                url:'/agent/agent-list',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        //查询
        var active = {
            search:function(){
                var agentNick = $('#agentNick').val();
                var playerId = $('#player_id').val();
                 table.reload('agentTable',{
                    url:'/agent/agent-list',
                    method: 'post',
                    page:{
                        curr:1
                    },
                    where:{
                        agentNick:agentNick,
                        playerId:playerId
                    }
                })
            }
        };
        $('#search').on('click',function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        form.on('radio(redio)', function (data) {
            if (data.value == 1) {
                $('#day').removeClass('layui-hide');
                $('#week').addClass('layui-hide');
            } else {
                $('#day').addClass('layui-hide');
                $('#week').removeClass('layui-hide');
            }
        });
    })
</script>
