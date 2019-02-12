<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营查询</a>
            <a>
                <cite>举报统计</cite>
            </a>
        </span>
</div>
<div class="x-body">
    <form action="" class="layui-form">
        <div class="layui-row BGO">
            <div class="layui-col-xs7">
                <div class="layui-input-inline">
                    <select name="type" lay-filter="changeType" class="layui-form-label type" id="type"
                            style="width: 100%;">
                        <option value="1" selected>举报用户</option>
                        <option value="2">被举报用户</option>
                    </select>
                </div>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="player_id" placeholder="用户ID">
                </div>
                <div class="layui-btn" style="margin-left:2px;" data-type="search" id="search"><i class="layui-icon">&#xe615;</i>
                </div>
            </div>
        </div>
    </form>
    <table id="reported" class="layui-table" lay-filter="table1"></table>
</div>
<script type="text/html" id="detail">
    <button class="layui-btn layui-btn-xs" lay-event="detail">查看被举报人详情</button>
</script>
<script type="text/html" id="sealOff">
    <button type="button" class="layui-btn layui-btn-xs seal" id="seal" lay-event="sealOff">封停</button>
</script>
<script type="text/html" id="deblocking">
    <button type="button" class="layui-btn layui-btn-xs deblocking" id="deblocking" lay-event="deblocking">解封</button>
</script>
<script>
    var sealDiv;
    layui.use(['table', 'layer', 'form', 'element'], function () {
        var table = layui.table;
        var layer = layui.layer;
        var form = layui.form;

        //自动加载
        table.render({
            elem: '#reported'
            , url: '/operation-search/report' //数据接口
            , method: "post"
            , page: true //开启分页
            , cols: [[ //表头
                {field: 'id', title: '序号',width:60}
                , {field: 'playerid', title: '举报用户ID',width:100}
                , {field: 'be_report', title: '被举报用户ID',width:120}
                , {field: 'reportedType', title: '举报类型', sort: true,width:100}
                , {field: 'reportContent', title: '举报内容',width:100}
                , {field: 'create_time', title: '举报时间',width:180}
                , {field: 'mobile', title: '手机号',width:100}
                , {field: 'qq', title: 'qq号',width:120}
                , {field: 'wechat', title: '微信号',width:100}
                , {field: 'gameName', title: '游戏名',width:100}
                , {field: 'tableid', title: '桌子号',width:100}
                , {field: 'right', title: '操作', minWidth: 150, toolbar: '#detail'} //这里的toolbar值是模板元素的选择器//fixed 固定不动
            ]]
        });

        //查看详情
        table.on('tool(table1)', function (obj) {
            var data = obj.data;
            console.log(data);
            if (data.isSeal === 1) {
                console.log(data.isSeal);
                if (obj.event === "detail") {
                    var id = data.id;
                    layer.open({
                        type: 1
                        , title: false
                        , closeBtn: 1
                        , area: ['90%', '90%']
                        , id: 'LAY_layuipro'
                        // ,btn:['确认','取消']
                        , btnAlign: 'c'
                        , moveType: 1
                        , content: $('#reportedCheck')
                        , success: function (layero, index) {
                            sealDiv = index;
                            table.render({
                                elem: '#reportedTable'
                                , url: '/operation-search/report-detail'
                                , method: "post"
                                , where: {
                                    id: id
                                }
                                , page: true
                                , cols: [[
                                    {field: 'be_report', title: '被举报人ID',width:100}
                                    , {field: 'time', title: '被举报次数',sort: true,width:120}
                                    , {field: 'waiGuaTime', title: '外挂嫌疑(次)',width:120}
                                    , {field: 'zuoBiTime', title: '合伙作弊(次)',width:120}
                                    , {field: 'ruMaTime', title: '言语辱骂/地域歧视(次)',width:200}
                                    , {field: 'shuaPingTime', title: '恶意刷屏(次)',width:120}
                                    , {field: 'wenZiTime', title: '文字举报(次)',width:120}
                                    , {field: 'statisticsData', title: '统计时间', sort: true,width:180}
                                    , {field: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#deblocking'}
                                ]]
                            })
                        }
                    })
                }
            } else {
                console.log(data.isSeal);
                if (obj.event === "detail") {
                    var id = data.id;
                    layer.open({
                        type: 1
                        , title: false
                        , closeBtn: 1
                        , area: ['90%', '90%']
                        , id: 'LAY_layuipro'
                        // ,btn:['确认','取消']
                        , btnAlign: 'c'
                        , moveType: 1
                        , content: $('#reportedCheck')
                        , success: function (layero, index) {
                            sealDiv = index;
                            console.log('this--' + sealDiv);
                            table.render({
                                elem: '#reportedTable'
                                , url: '/operation-search/report-detail'
                                , method: "post"
                                , where: {
                                    id: id
                                }
                                , page: true
                                , cols: [[
                                    {field: 'be_report', title: '被举报人ID',width:100}
                                    , {field: 'time', title: '被举报次数',sort: true,width:120}
                                    , {field: 'waiGuaTime', title: '外挂嫌疑(次)',width:120}
                                    , {field: 'zuoBiTime', title: '合伙作弊(次)',width:120}
                                    , {field: 'ruMaTime', title: '言语辱骂/地域歧视(次)',width:200}
                                    , {field: 'shuaPingTime', title: '恶意刷屏(次)',width:120}
                                    , {field: 'wenZiTime', title: '文字举报(次)',width:120}
                                    , {field: 'statisticsData', title: '统计时间', sort: true,width:180}
                                    , {field: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#deblocking'}
                                ]]
                            })
                        }
                    })
                }
            }

        });

        //封停--解封
        table.on('tool(reportedTable)', function (obj) {
            console.log(obj);
            var data = obj.data;
            console.log(sealDiv);
            //封停
            if (obj.event === "sealOff") {
                $.ajax({
                    url: "/gm/seal",
                    type: 'post',
                    data: {
                        'status': 1,
                        'playerId': data.be_report
                    }
                    , success: function (data) {
                        data = eval("(" + data + ")");
                        console.log(data);
                        if (data.code == 0) {
                            alert("封停成功！");
                            table.reload('reported', {
                                url: "/operation-search/report"
                                , method: 'post'
                            });
                            layer.close(sealDiv);
                        } else {
                            alert(data.msg);
                        }
                    }
                    , error: function (data) {
                        console.log("失败");
                    }
                })
            }

            //解封
            if (obj.event === 'deblocking') {
                $.ajax({
                    url: "/gm/seal",
                    type: 'post',
                    data: {
                        'status': 2,
                        'playerId': data.be_report
                    }
                    , success: function (data) {
                        data = eval("(" + data + ")");
                        console.log(data);
                        if (data.code == 0) {
                            alert("解封成功！");
                            table.reload('reported', {
                                url: "/operation-search/report"
                                , method: 'post'
                            });
                            layer.close(sealDiv);
                        } else {
                            alert(data.msg);
                        }
                    }
                    , error: function (data) {
                        console.log("请求错误");
                    }
                })
            }

        });

        //监听事件
        var active = {
            search: function () {
                var playerId = $('#player_id').val();
                var type = $("#type").val();

                table.reload('reported', {
                    url: "/operation-search/report"
                    , method: "post"
                    , page: {
                        curr: 1
                    }
                    , where: {
                        playerId: playerId,
                        type: type
                    }
                })
            }
        };
        //查询
        $('#search').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //排序
        table.on('sort(table1)', function (obj) {
            table.reload('reported', {
                url: '/operation-search/report',
                initSort: obj
                , where: {
                    field: obj.field
                    , order: obj.type
                }
            });
        });

        //类型选择
        form.on('select(changeType)', function (data) {
            $("#player_id").val('');
            layer.reload();
        });
    });
</script>
</body>

<!--查看弹出层-->
<div class="x-body" id="reportedCheck" style="display: none;">
    <table class="layui-table" id="reportedTable" lay-filter="reportedTable"></table>
</div>

<style>
    .x-body .inform {
        width: 30%;
        background-color: #2D93CA;
        float: left;
        margin: 7px;
        padding: 5%;
        box-sizing: border-box;
    }
</style>

<div class="x-body" id="sealOffLayer" style="display:none;">
    <div class="layui-form-item">
        <div class="inform"><p>会员I111D</p>
            <p>123456987</p></div>
        <div class="inform"><p>IP地址</p>
            <p>192.163.12.55</p></div>
        <div class="inform"><p>设备号</p>
            <p>12de45e5de8</p></div>
    </div>

    <div class="layui-form-item">
        <button class="layui-btn layui-btn-sm">解封</button>
        <button class="layui-btn layui-btn-sm layui-btn-danger">封停</button>
    </div>
    <table class="layui-table" id="sealOff1">
    </table>
</div>

<script>
    layui.use('table', function () {
        var table = layui.table;
    });
</script>
