<table class="layui-table" id="money" lay-filter="table1"></table>

<script>
    layui.use('table', function(){
        var table = layui.table;

        table.render({
            elem: '#money'
            ,'url': '/payment-center/t1'
            ,method: 'post'
            ,page: true
            ,cols: [[
                {field:'account', title:'账号'}
                ,{field:'1', title:'1元充值链接'}
                ,{field:'10', title:'10元充值链接'}
                ,{field:'50', title:'50元充值链接'}
                ,{field:'100', title:'100元充值链接'}
                ,{field:'300', title:'300元充值链接'}
                ,{field:'500', title:'500元充值链接'}
                ,{field:'1000', title:'1000元充值链接'}
            ]]
        });
    });
</script>