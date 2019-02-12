<style type="text/css">
    .layui-table-cell {
        height: auto;
        line-height: 20px;
        padding: 0 10px;
        position: relative;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
        box-sizing: border-box;
    }
    .BGO{background-color: #EEEEEE;padding:1px;}
    .x-nav{margin-bottom:10px!important;padding:0!important;}
</style>
<body>
<div class="x-body">
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>玩法参与统计</cite>
            </a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
    </div>
    <div class="list2">
        <form action="" class="layui-form BGO">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="startTime" name="start" placeholder="开始日期">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="endTime" name="end" placeholder="结束日期">
            </div>
            <div class="layui-btn" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
        </form>


        <table class="layui-table" lay-data="{ url:'/test/table/demo2.json?v=2', page: true, limit: 6, limits:[6]}">
            <thead>
            <tr>
                <th lay-data="{field:'username'}" rowspan="2">日期</th>
                <th lay-data="{align:'center'}" colspan="3">内蒙打大A</th>
                <th lay-data="{align:'center'}" colspan="3">山西麻将</th>
                <th lay-data="{align:'center'}" colspan="3">内蒙麻将</th>
                <th lay-data="{align:'center'}" colspan="3">三张牌</th>
                <th lay-data="{align:'center'}" colspan="3">牛牛</th>
                <th lay-data="{align:'center'}" colspan="3">推筒子</th>
                <th lay-data="{align:'center'}" colspan="3">三公</th>
            </tr>
            <tr>
                <th lay-data="{field:'province'}">参与人数</th>
                <th lay-data="{field:'city'}">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone'}">环比上日</th>
                <th lay-data="{field:'province'}">参与人数</th>
                <th lay-data="{field:'city'}">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone'}">环比上日</th>
                <th lay-data="{field:'province'}">参与人数</th>
                <th lay-data="{field:'city'}">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone'}">环比上日</th>
                <th lay-data="{field:'province' }">参与人数</th>
                <th lay-data="{field:'city' }">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone' }">环比上日</th>
                <th lay-data="{field:'province' }">参与人数</th>
                <th lay-data="{field:'city' }">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone' }">环比上日</th>
                <th lay-data="{field:'province' }">参与人数</th>
                <th lay-data="{field:'city' }">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone' }">环比上日</th>
                <th lay-data="{field:'province' }">参与人数</th>
                <th lay-data="{field:'city' }">元宝消耗（百分比）</th>
                <th lay-data="{field:'zone' }">环比上日</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    //日期查询
    // layui.use('laydate',function(){
    //     var laydate = layui.laydate;
    //     laydate.render({elem:'#startTime'});
    //     laydate.render({elem:'#endTime'});
    // });
    layui.use(['table','laydate'],function () {
        var laydate = layui.laydate;
        laydate.render({elem: '#startTime'});
        laydate.render({elem: '#endTime'});
        var table = layui.table;
    })

</script>
</body>
