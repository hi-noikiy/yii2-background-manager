<body>
<div class="x-body">
    <div action="" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="用户ID" id="ID">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="商户订单号" id="order_id">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="渠道订单号" id="channel_oid">
            </div>
            <div class="layui-input-inline">
                <select name="" id="payStatus">
                    <option value="">支付状态</option>
                    <option value="0">支付中</option>
                    <option value="1">支付成功</option>
                    <option value="2">支付失败</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="订单创建日期" id="date">
            </div>
            <button class="layui-btn" data-method="search" id="search" data-type="reload" id="search">查询</button>
        </div>
    </div>
    <table class="layui-table " id="rechargeTable"  lay-filter="sort"></table>
</div>
<script>
    layui.use(['table','layer','laydate'],function () {
        var laydate = layui.laydate;
        laydate.render({elem:'#date'})
        var table = layui.table;

        //自动加载
        table.render({
            elem:"#rechargeTable"
            ,url:"/withdraw/recharge-query"
            ,method:"POST"
            ,page:true
            ,cols:[[
                {field:"id",title:'序号',width:60}
                ,{field:"order_id",title:'商户订单号',width:180}
                ,{field:"channel_oid",title:'渠道订单号',sort:true,width:150}
                ,{field:"player_id",title:'玩家ID',sort:true, width: 100}
                ,{field:"nickname",title:'昵称',width:120}
                ,{field:"goods_price",title:'商品价格',width:90}
                ,{field:"pay_channel",title:'支付渠道', width:110}
                ,{field:"pay_type",title:'支付方式', width:110}
                ,{field:"pay_terminal",title:'支付机型', width:110}
                ,{field:"status",title:'支付状态', width:110}
                ,{field:"goods_num",title:'元宝数量', width:110}
                ,{field:"player_create",title:'玩家创角时间',sort:true, width: 170}
                ,{field:"create_time",title:'订单创建时间', width: 170}
                ,{field:"finish_time",title:'到账时间', width: 170}
                ,{field:"pay_time",title:'支付时间', minWidth:180}
            ]]
            ,done:function (res, curr, count) {
                var arrtd = $('tbody').find('tr').find('td:eq(9) div');
                var goodsType = $('tbody').find('tr').find('td:eq(5) div');
                for (var i=0;i<arrtd.length;i++){
                    console.log(arrtd[i].innerHTML);
                    if (arrtd[i].innerHTML == 0){
                        $(arrtd[i]).html('未完成')
                    }
                    if (arrtd[i].innerHTML == 1){
                        $(arrtd[i]).html('已完成');
                    }else{
                        $(arrtd[i]).html('未完成')
                    }

                    if (goodsType[i].innerHTML == 1){
                        $(goodsType[i]).html('充值')
                    }
                    if (goodsType[i].innerHTML == 2){
                        $(goodsType[i]).html('活动')
                    }
                }
            }
        });
        //查询
        var $ = layui.$, active = {
            reload: function(){
                var userId = $('#ID').val();
                var payStatus = $('#payStatus').val();
                var date = $('#date').val();
                var orderId = $('#order_id').val();
                var channelOid = $('#channel_oid').val();

                //执行重载
                table.reload('rechargeTable', {
                    url:'/withdraw/recharge-query'
                    ,method:"POST"
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        userId: userId,
                        payStatus: payStatus,
                        date: date,
                        orderId: orderId,
                        channelOid: channelOid
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
            table.reload('rechargeTable', {
                url:'/withdraw/recharge-query',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
    });
    function replacement_order(orderId) {
        $.ajax({
            url:"/withdraw/do-fail-order",
            type:'post',
            data:{
                'orderId':orderId
            }
            ,success:function (data) {
                data = eval("("+data+")");
                console.log(data);
                if(data.code == 200){
                    console.log(data.msg);
                    alert("补单成功！");
                    //修改成功后重载表格
                    table.reload('agentTable', {
                        url:'/withdraw/recharge-query'
                    });
                }else{
                    alert(data.msg);
                }
            }
            ,error:function (data) {
                console.log("失败");
            }
        })
    }
</script>
</body>

