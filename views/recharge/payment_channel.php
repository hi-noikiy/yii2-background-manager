<blockquote class="layui-elem-quote">操作说明：注意状态！！</blockquote>

<table id="payment-channel" lay-filter="lay-payment-channel"></table>

<table id="log" lay-filter="lay-log"></table>

<script>
    layui.use(['table', 'layer'], function(){
        var table = layui.table
            ,form = layui.form;

        table.render({
            elem: '#payment-channel'
            ,url: '/recharge/payment-channel'
            ,page: true
            ,method: 'post'
            ,cols: [[
                {field, 'id', title: '序号'}
                ,{field, 'payment', title: '支付方式'}
                ,{field, 'pay_channel', title: '支付渠道'}
                ,{field, 'create_time', title: '创建时间'}
                ,{field, 'master', title: '是否轮询'}
                ,{field, 'weight', title: '权重'}
            ]]
        });
    });
</script>