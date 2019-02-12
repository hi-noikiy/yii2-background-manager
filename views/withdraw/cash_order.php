<body>
<div class="x-body">
    <div class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="用户ID" id="userID">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="帐单号" id="billNumber">
            </div>
            <div class="layui-input-inline" id="status">
                <select name="orderStatus" id="orderStatus">
                    <option value="">选择状态</option>
                    <option value="0">准备支付</option>
                    <option value="1">支付成功</option>
                    <option value="3">支付失败 待重新查询</option>
                </select>
            </div>
             <div class="layui-input-inline" style="margin: auto">
                <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
             </div>
            <div class="layui-input-inline" style="margin: auto">
                <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
            </div>
            <div class="layui-btn" data-method="search" id="search" data-type="search" id="search">查询</div>
        </div>
    </div>
    <table class="layui-table" id="order" lay-filter="sort"></table>
</div>
<script>
    layui.use(['table','layer','laydate'],function () {
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});
        var table = layui.table;

        //自动加载数据
        table.render({
            elem:"#order"
            ,url:"/withdraw/cash-order"
            ,method: 'post'
            ,page:true
            ,cols:[[
                {field:"ID",title:"序号",width:60},
                {field:"PLAYER_INDEX",title:'用户ID',width:100},
                {field:"TRUE_NAME",title:'真实姓名',width:100},
                {field:"ORDER_ID",title:'订单号',width:280},
                {field:"BANK_ACCOUNT",title:'银行卡号',width:200},
                {field:"PAY_MONEY",title:'提现金额',width:100},
                {field:"PAY_FEE",title:'手续费',width:100},
                {field:"status",title:'支付状态',width:100},
                {field:"CREATE_TIME",title:'创建时间',width:180},
                {field:"UPDATE_TIME",title:'更新时间',width:180},
                {field:"REMARK",title:'备注',minWidth:100}
            ]]
        });
        //查询
        var $ = layui.$, active = {
            search: function(){
                var userID = $('#userID').val();
                var billNumber = $('#billNumber').val();
                var orderStatus= $('#orderStatus').val();
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                //执行重载
                table.reload('order', {
                    url:'/withdraw/cash-order'
                    ,method:"post"
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        PLAYER_INDEX: userID,
                        ORDER_ID:billNumber,
                        PAY_STATUS:orderStatus,
                        start_time: startTime,
                        end_time: endTime
                    }
                });
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //排序
        table.on('sort(sort)', function(obj){
            table.reload('order', {
                url:'/withdraw/cash-order',
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

