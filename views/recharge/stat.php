<body>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">开始时间</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="start_date" name="start_date"/>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束时间</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="end_date" name="end_date"/>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-order" lay-submit lay-filter="LAY-app-order-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table id="LAY-app-system-order" lay-filter="LAY-app-system-order"></table>
        </div>
    </div>
</div>
<script type="text/html" id="addressTpl">
    <a href="{{d.address}}" class="layui-table-link" target="_blank">{{d.address}}</a>
</script>
<script type="text/html" id="payTypeTpl">
    {{#  if(d.pay_type == 'wechat'){ }}
        微信
    {{#  } else if(d.pay_type == 'alipay') { }}
        支付宝
    {{#  } else if (d.pay_type == 'unionpay') { }}
        银联
    {{# } else { }}
        VIP充值
    {{# } }}
</script>

<script>
    layui.use(['table', 'laydate', 'form'], function(){
        var table = layui.table
            ,laydate = layui.laydate
            ,form = layui.form;

        laydate.render({
            elem: '#start_date'
            ,value: '<?= $start_date?>'
            ,max: '<?= $end_date?>'
        });
        laydate.render({
            elem: '#end_date'
            ,value: '<?= $end_date?>'
            ,max: '<?= $end_date?>'
        });

        table.render({
            elem: '#LAY-app-system-order'
            ,url: '/recharge/stat-api'
            ,cols: [[
                {field: 'start_time', title: '开始时间', fixed: 'left'}
                ,{field: 'end_time', title: '结束时间'}
                ,{field: 'pay_channel', title: '支付渠道'}
                ,{field: 'pay_type', title: '支付方式', templet: '#payTypeTpl'}
                ,{field: 'address', title: '商户地址', templet: '#addressTpl'}
                ,{field: 'rate', title: '费率'}
                ,{field: 'amt', title: '收款金额', style: 'background-color: #5FB878; color: #fff;'}
            ]]
            ,page: true
            ,cellMinWidth: 120
        });

//        监听搜索
        form.on('submit(LAY-app-order-search)', function(data){
            var field = data.field;

            table.reload('LAY-app-system-order', {
                where: field
            })
        });
    });
</script>
</body>