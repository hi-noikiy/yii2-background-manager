<body>
<div class="x-body">
    <table class="layui-table" id="historyInfo"></table>
</div>
<script type="text/html" id="barHistoryInfo">
    <div>
        <button class="layui-btn layui-btn-xs">查看</button>
        <button class="layui-btn layui-btn-xs layui-btn-warm">暂停</button>
    </div>
</script>
<script>
    layui.use('table',function () {
        var table = layui.table;
        table.render({
            elem:'#historyInfo'
            ,url:'/test/t206'
            ,page:true
            ,cols:[[
                {field:"ID",title:"ID"}
                ,{field:"number",title:"序列号"}
                ,{field:"title",title:"标题"}
                ,{field:"content",title:"内容"}
                ,{field:"appendix",title:"是否带附件"}
                ,{field:"publishObject",title:"发布对象"}
                ,{field:"sendData",title:"发送时间"}
                ,{field:"createData",title:"创建时间"}
                ,{field:"status",title:"状态"}
                ,{field:"issuer",title:"发布人"}
                ,{field:"auditing",title:"审核"}
                ,{field:"",title:"操作",toolbar:"#barHistoryInfo",width:110}
            ]]
        })
    })
</script>
</body>
