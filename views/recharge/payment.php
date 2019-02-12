<body>

<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <blockquote class="layui-elem-quote">操作说明：请注意拉起方式、开启状态！！</blockquote>
        </div>
        <div class="layui-card-body">
            <table id="payment" lay-filter="lay-payment"></table>
        </div>
        <div class="layui-card-body">
            <table id="log" lay-filter="lay-log"></table>
        </div>
    </div>
</div>
<script type="text/html" id="pullTpl">
    <input type="checkbox" name="pull_type" value="{{d.id}}" lay-skin="switch" lay-text="拉起分享|浏览器" lay-filter="lay-pull" {{ d.pull_type == 1 ? 'checked' : '' }}/>
</script>
<script type="text/html" id="statusTpl">
    <input type="checkbox" name="status" value="{{d.id}}" lay-skin="switch" lay-text="开启|关闭" lay-filter="lay-status" {{ d.status == 1 ? 'checked' : '' }}/>
</script>
<script type="text/html" id="payChannelTpl">
    <div class="layui-form-item">
        <label class="layui-form-label">
            <div class="layui-input-inline">
                <select name="city" lay-verify="">
                    <option value="010">北京</option>
                    <option value="021">上海</option>
                    <option value="0571">杭州</option>
                </select>
            </div>
        </label>
    </div>
</script>

<script>
    var payChannel = '<?= $payChannel ?>';
    layui.use(['table', 'layer', 'form'], function(){
        var table = layui.table
            ,form = layui.form
            ,layer = layui.layer
            ,$ = layui.$;


        table.render({
            elem: '#payment'
            ,url: '/recharge/payment'
            ,page: true
            ,method: 'post'
            ,size: 'lg'
            ,height: 500
            ,cols: [[
                {field: 'id', title: '序号'}
                ,{field: 'pay_name', title: '支付名称'}
                ,{field: 'pull_type', title: '拉起方式', templet: '#pullTpl'}
                ,{field: 'status', title: '开启状态', templet: '#statusTpl'}
                ,{field: 'pay_channel', title: '充值渠道', width:350,
                    templet: function (d) {
                        console.log(d);
                        var selectStart = '<select name="city" lay-filter="testSelect" lay-verify="required" data-value="' + d.pay_channel + '" >\n';
                        payChannelObj = eval('(' +payChannel+ ')');
                        var option = '';
                        $.each(payChannelObj,function (k,v){
                            option = option+'<option value='+ v.id+'>'+ v.channel_name+'</option>\n';
                        });
                        var selectEnd = '</select>';
                        return selectStart+option+selectEnd;
                    }
                }
                ,{field: 'create_time', title: '创建时间'}
                ,{field: 'update_time', title: '更新时间'}
                ,{field: 'remark', title: '备注', style: 'background-color: #5FB878; color: #fff;'}
            ]]
            ,done: function (res, curr, count) {
                count || this.elem.next('.layui-table-view').find('.layui-table-header').css('overflow', 'auto');

                layui.each($('select'), function (index, item) {
                    var elem = $(item);
                    elem.val(elem.data('value')).parents('div.layui-table-cell').css('overflow', 'visible');
                });
                form.render();
            }
        });

        // 监听修改update到表格中
        form.on('select(testSelect)', function (data) {
            var value = data.value;
            var elem = $(data.elem);
            var trElem = elem.parents('tr');
            var tableData = table.cache['payment'];
            // 更新到表格的缓存数据中，才能在获得选中行等等其他的方法中得到更新之后的值
            tableData[trElem.data('index')][elem.attr('name')] = data.value;
            // 其他的操作看需求 TODO
            console.log(value);
            console.log(tableData[trElem.data('index')].id);

            $.ajax({
                url:"/recharge/update-radio",
                type: 'post',
                data: {
                    'field': value
                    ,'value': tableData[trElem.data('index')].id
                }
                ,success:function (data) {
                    data = eval("("+data+")");
                    console.log(data.res);

                    if (data.res > 0) {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新成功');
                    } else {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新失败');
                    }
                }
                ,error:function (data) {
                    layer.msg('更新失败');
                }
            });
        });

        form.on('switch(lay-status)', function(obj){
            $.ajax({
                url:"/recharge/update-switch",
                type:'post',
                data:{
                    'id': this.value
                    ,'field': this.name
                    ,'value': obj.elem.checked == true ? 1 : 0
                }
                ,success:function (data) {
                    data = eval("("+data+")");
                    console.log(data.res);

                    if (data.res == 1) {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新成功');
                    } else {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新失败');
                    }
                }
                ,error:function (data) {
                    layer.msg('更新失败');
                }
            });
        });

        form.on('switch(lay-pull)', function(obj) {
            var val = obj.elem.checked == true ? 1 : 2;
//            console.log(val);

            $.ajax({
                url:"/recharge/update-switch",
                type: 'post',
                data: {
                    'id': this.value
                    ,'field': this.name
                    ,'value': val
                }
                ,success:function (data) {
                    data = eval("("+data+")");
                    console.log(data.res);

                    if (data.res == 1) {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新成功');
                    } else {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新失败');
                    }
                }
                ,error:function (data) {
                    layer.msg('更新失败');
                }
            });
        });

        form.on('radio(redio)', function (data) {
            var val = data.value;
            console.log(this.id);

            $.ajax({
                url:"/recharge/update-radio",
                type: 'post',
                data: {
                    'field': this.id
                    ,'value': val
                }
                ,success:function (data) {
                    data = eval("("+data+")");
                    console.log(data.res);

                    if (data.res > 0) {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新成功');
                    } else {
                        table.reload('payment', {
                            url: '/recharge/payment'
                        });
                        layer.msg('更新失败');
                    }
                }
                ,error:function (data) {
                    layer.msg('更新失败');
                }
            });
        });

        table.render({
            elem: '#log'
            ,url: '/recharge/op-log'
            ,page: true
            ,cols: [[
                {field: 'id', title: '序号'}
                ,{field: 'id', title: '操作者'}
                ,{field: 'id', title: '操作前'}
                ,{field: 'id', title: '操作后'}
                ,{field: 'id', title: '操作日期'}
            ]]
        });
    });
</script>
</body>