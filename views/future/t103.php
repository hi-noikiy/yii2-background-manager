<input type="text" class="layui-input" id="test1"/>

<script>
    var d = new Date();
    var a = d.getFullYear();
    var b = d.getMonth();
    var c = d.getDate() - 7;

    console.log(a, b, c);

    layui.use('laydate', function(){
        var laydate = layui.laydate;

        laydate.render({
            elem: '#test1'
            ,type: 'date'
//            ,range: '~'
            ,format: 'yyyy-MM-dd HH:mm:ss'

            ,value: new Date(a,b,c)
            ,min: '2018-07-20'
            ,max: '2018-08-20'
            ,isInitValue: false
        });
    });
</script>