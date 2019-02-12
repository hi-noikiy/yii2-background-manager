<button class="layui-btn" id="getCheckedData">获得表格选中行</button>
<table id="grid" lay-filter="grid"></table>

<script>
    layui.use(['table', 'layer', 'element', 'jquery', 'form'], function (table, layer, element, $, form) {
        var jsonData = [{
            id: 1,
            city: 20000
        }, {
            id: 2,
            city: 20001
        }];

        // 监听修改update到表格中
        form.on('select(testSelect)', function (data) {
            debugger;
            var elem = $(data.elem);
            var trElem = elem.parents('tr');
            var tableData = table.cache['grid'];
            // 更新到表格的缓存数据中，才能在获得选中行等等其他的方法中得到更新之后的值
            tableData[trElem.data('index')][elem.attr('name')] = data.value;
            // 其他的操作看需求 TODO
        });
        $('#getCheckedData').click(function () {
            debugger;
            // 验证一下下拉选择之后有没有作用到表格缓存数据
            var checkStatus = table.checkStatus('grid'); //test即为基础参数id对应的值
            console.log(checkStatus.data); //获取选中行的数据
            console.log(checkStatus.data.length); //获取选中行数量，可作为是否有选中行的条件
            console.log(checkStatus.isAll); //表格是否全选
        });
        var tableIns = table.render({
            elem: '#grid',
            width: 600,
            height: 300,
            data: jsonData,
            done: function (res, curr, count) {
                count || this.elem.next('.layui-table-view').find('.layui-table-header').css('overflow', 'auto');
                layui.each($('select'), function (index, item) {
                    var elem = $(item);
                    elem.val(elem.data('value')).parents('div.layui-table-cell').css('overflow', 'visible');
                });
                form.render();
            },
            size: 'lg',
            cols: [[ //表头
                {type: 'checkbox', fixed: true},
                {field: 'id', title: 'ID', fixed: true},
                {
                    field: 'city',
                    title: '城市',
                    align: 'center',
                    width: 200,
                    templet: function (d) {
                        // 模板的实现方式也是多种多样，这里简单返回固定的
                        return '<select name="city" lay-filter="testSelect" lay-verify="required" data-value="' + d.city + '" >\n' +
                        '        <option value=""></option>\n' +
                        '        <option value="18000">北京</option>\n' +
                        '        <option value="20000">上海</option>\n' +
                        '        <option value="20001">广州</option>\n' +
                        '        <option value="20002">深圳</option>\n' +
                        '        <option value="20003">杭州</option>\n' +
                        '      </select>';
                    }
                }
            ]]
        });
    });
</script>

var selectStart = '<select name="city" lay-filter="testSelect" lay-verify="required" data-value="' + d.id + '" >\n';
    payChannelObj = eval('(' +payChannel+ ')');
    var option = '';
    $.each(payChannelObj,function (k,v){
    option = option+'<option value='+ v.id+'>'+ v.channel_name+'</option>\n';
    });
    var selectEnd = '</select>';
console.log(selectStart+option+selectEnd);
return selectStart+option+selectEnd;





<script type="text/html" id="payChannelTpl">
    <input type="radio" id="2" name="channel{{d.id}}" value="{{d.id}}" title="heepay_2" lay-filter="redio"
    {{#  if(d.pay_channel == 2){ }}
    checked
    {{#  } }}>
    <input type="radio" id="4" name="channel{{d.id}}" value="{{d.id}}" title="竣付通" lay-filter="redio"
    {{#  if(d.pay_channel == 4){ }}
    checked
    {{#  } }}>
    <input type="radio" id="5" name="channel{{d.id}}" value="{{d.id}}" title="微信H5" lay-filter="redio"
    {{#  if(d.pay_channel == 5){ }}
    checked
    {{#  } }}>
    <input type="radio" id="9" name="channel{{d.id}}" value="{{d.id}}" title="heepay_3" lay-filter="redio"
    {{#  if(d.pay_channel == 9){ }}
    checked
    {{#  } }}>
    <input type="radio" id="10" name="channel{{d.id}}" value="{{d.id}}" title="heepay_4" lay-filter="redio"
    {{#  if(d.pay_channel == 10){ }}
    checked
    {{#  } }}>
    <input type="radio" id="11" name="channel{{d.id}}" value="{{d.id}}" title="heepay_5" lay-filter="redio"
    {{#  if(d.pay_channel == 11){ }}
    checked
    {{#  } }}>
    <input type="radio" id="12" name="channel{{d.id}}" value="{{d.id}}" title="heepay_6" lay-filter="redio"
    {{#  if(d.pay_channel == 12){ }}
    checked
    {{#  } }}>
</script>

