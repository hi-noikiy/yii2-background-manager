<table class="layui-table" lay-filter="test"></table>

<script>
    table.on('checkbox(test)', function(obj){
        console.log(obj);
    })
</script>