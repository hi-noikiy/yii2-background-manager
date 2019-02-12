<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营查询</a>
            <a>
                <cite>提现查询</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <div action="" class="layui-form BGO">
        <!--<div class="layui-form-item">-->
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="用户ID" id="userID">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="帐单号" id="billNumber">
            </div>
            <div class="layui-input-inline" id="orderStatus">
                <select name="" id="">
                    <option value="">选择订单状态</option>
                    <option value="">准备支付</option>
                    <option value="">支付成功</option>
                    <option value="">支付失败 待重新查询</option>
                    <option value="">支付失败 解除冻结金额</option>
                    <option value="">支付成功 但更新订单状态失败</option>
                    <option value="">支付成功 但减少用户冻结金额失败</option>
                    <option value="">转账失败未成功</option>
                    <option value="">处理中订单 需要重试</option>
                    <option value="">余额不足 单独处理 人工跟进中</option>
                </select>
            </div>

            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
            </div>
            <div class="layui-btn" data-method="search" id="search" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
        <!--</div>-->
    </div>
    <table class="layui-table" id="order" lay-filter="sort"></table>
</div>
<script>
    layui.use(['table','layer','laydate'],function () {
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});
        var table = layui.table;
        table.render({
            elem:"#order"
            ,url:"/test/t206"
            ,page:true
            // ,id:"orderReload"
            ,cols:[[
                {type:"number",title:"序号",sort:true}
                ,{field:"userID",title:'用户ID'}
                ,{field:"userName",title:'真实姓名'}
                ,{field:"orderNumber",title:'订单号'}
                ,{field:"cardNumber",title:'银行卡号'}
                ,{field:"cashAmount",title:'提现金额'}
                ,{field:"poundage",title:'手续费'}
                ,{field:"paymentStatus",title:'支付状态'}
                ,{field:"createTime",title:'创建时间'}
                ,{field:"refreshTime",title:'更新时间'}
                ,{field:"remarks",title:'备注'}
            ]]
        });
        //查询
        var $ = layui.$, active = {
            search: function(){
                var userID = $('#ID').val();
                var billNumber = $('#billNumber').val();
                var orderStatus= $('#orderStatus').val();
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();

                //执行重载
                table.reload('order', {
                    url:'/test/t204'
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        id: userID,
                        billNumber:billNumber,
                        orderStatus:orderStatus,
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

        table.on('sort(sort)', function(obj){
            table.reload('order', {
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

