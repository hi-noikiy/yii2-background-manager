<style>
    .dateIcon{right:208px;top:10px;}
</style>
<body>
<div class="x-body">
    <!--过滤条件-->
    <div class="refresh refreshThis"><i class="layui-icon">ဂ</i></div>
    <div  class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
            </div>
            <div class="layui-input-inline">
                <i class="layui-icon dateIcon">&#xe637</i>
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
            </div>
            <div class="layui-input-inline">
                <i class="layui-icon dateIcon">&#xe637</i>
            </div>
            <button class="layui-btn"  data-type="search1" id="search1"><i class="layui-icon">&#xe615;</i></button>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="中心订单号" id="centerOrder">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="商户订单号" id="commercialOrder">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="渠道订单号" id="canalOrder">
            </div>
            <button class="layui-btn"  data-type="search2" id="search2"><i class="layui-icon">&#xe615;</i></button>
        </div>
    </div>
    <!--表格数据-->
    <table class="layui-table " id="orderTable" lay-filter="sort">
        <caption><h2>中心订单</h2></caption>
    </table>
</div>
<script>
    layui.use(['table','layer','laydate'],function () {
        //日期查询
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});
        //表格渲染
        var table = layui.table;
        table.render({
            elem:"#orderTable"
            ,url:"/test/t206"
            ,page:true
            ,cols:[[
                {field:"number",title:'序号',sort:true}
                ,{field:"centerOrder",title:'中心订单号'}
                ,{field:"commercialOrder",title:'商户订单号'}
                ,{field:"canalOrder",title:'渠道订单号'}
                ,{field:"price",title:'金额'}
                ,{field:"startTime",title:'发起时间'}
                ,{field:"productCODE",title:'产品CODE'}
                ,{field:"sign",title:'签名'}
                ,{field:"state",title:'状态'}
                ,{field:"payIP",title:'支付IP'}
                ,{field:"accountID",title:'账号ID'}
                ,{field:"parameter ",title:'透传参数'}
                ,{field:"finishTime",title:'订单完成时间'}
            ]]
        });
        //查询
        var $ = layui.$, active = {
            search1: function(){
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                //执行重载
                table.reload('orderTable', {
                    url:'/test/t204'
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        start_time: startTime,
                        end_time: endTime
                    }
                });
            },
            search2: function(){
                var centerOrder = $('#centerOrder').val();
                var commercialOrder = $('#commercialOrder').val();
                var canalOrder = $('#canalOrder').val();
                //执行重载
                table.reload('orderTable', {
                    url:'/test/t206'
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        centerOrder: centerOrder,
                        commercialOrder: commercialOrder,
                        canalOrder: canalOrder
                    }
                });
            }
        };
        //查询按钮绑定事件
        $('#search1').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        $('#search2').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        table.on('sort(sort)', function(obj){
            table.reload('logTable', {
                url:'/test/t205',
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