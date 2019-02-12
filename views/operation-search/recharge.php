<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营查询</a>
            <a>
                <cite>充值查询</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <div action="" class="layui-form BGO">
        <!--<div class="layui-form-item">-->
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="用户ID" id="ID">
            </div>
            <div class="layui-input-inline">
                <select name="" id="" id="payStatus">
                    <option value="">支付状态</option>
                    <option value="">未支付</option>
                    <option value="">支付成功</option>
                    <option value="">支付失败</option>
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="日期" id="date">
            </div>
            <button class="layui-btn" data-method="search" id="search" data-type="reload" id="search"><i class="layui-icon">&#xe615;</i></button>
        <!--</div>-->
    </div>
    <table class="layui-table " id="rechargeTable"  lay-filter="sort"></table>
</div>
<script>
    layui.use(['table','layer','laydate'],function () {
        var laydate = layui.laydate;
        laydate.render({elem:'#date'})
        var table = layui.table;
        table.render({
            elem:"#rechargeTable"
            ,url:"/test/t206"
            ,page:true
            // ,id:"rechargeReload"
            ,cols:[[
                {field:"userID",title:'用户ID',sort:true}
                ,{field:"orderNumber",title:'订单号'}
                //,{field:"percentage",title:'用户ID'}
                ,{field:"userName",title:'用户名'}
                ,{field:"rechargeAmount",title:'充值金额'}
                ,{field:"goldCoins",title:'金币数量'}
                ,{field:"payStatus",title:'支付状态'}
                ,{field:"payTime",title:'支付时间'}
                ,{field:"payment",title:'是否到账'}
                ,{field:"paymentDate",title:'到账时间'}
                ,{field:"supplement",title:'补单操作'}
            ]]
        });
        //查询
        var $ = layui.$, active = {
            reload: function(){
                var userID = $('#ID').val();
                var payStatus = $('#payStatus').val();
                var date = $('#date').val();

                //执行重载
                table.reload('rechargeTable', {
                    url:'/test/t204'
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        id: userID,
                        payStatus: payStatus,
                        date: date
                    }
                });
            }
        };
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        table.on('sort(sort)', function(obj){
            table.reload('rechargeTable', {
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

