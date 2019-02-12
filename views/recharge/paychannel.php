<blockquote class="layui-elem-quote">操作说明：注意状态！！</blockquote>

<table id="payment" lay-filter="lay-payment"></table>

<table id="log" lay-filter="lay-log"></table>

<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="开启|关闭" lay-filter="lay-status" {{ d.status == 1 ? 'checked' : '' }}/>
</script>

<script>
    layui.use(['table', 'layer'], function(){
        var table = layui.table
            ,form = layui.form;

        table.render({
            elem: '#payment'
            ,url: '/recharge/paychannel'
            ,page: true
            ,method: 'post'
            ,cols: [[
                {field: 'id', title: '序号', width: '3%'}
                ,{field: 'class_code', title: '类名', width: '5%'}
                ,{field: 'channel_code', title: '渠道标识', width: '5%'}
                ,{field: 'appid', title: 'APPID', width: '5%'}
                ,{field: 'appkey', title: 'APPKEY', width: '5%'}
                ,{field: 'reserve1', title: '扩展字段1', width: '3%'}
                ,{field: 'reserve2', title: '扩展字段2', width: '3%'}
                ,{field: 'reserve3', title: '扩展字段3', width: '3%'}
                ,{field: 'reserve4', title: '扩展字段4', width: '3%'}
                ,{field: 'status', title: '状态', templet: '#statusTpl', width: '5%'}
                ,{field: 'trade_url', title: '渠道下单地址'}
                ,{field: 'notify_url', title: '点对点通知'}
                ,{field: 'return_url', title: '页面通知'}
                ,{field: 'launch_url', title: '拉起地址'}

            ]]
        });
    });
</script>