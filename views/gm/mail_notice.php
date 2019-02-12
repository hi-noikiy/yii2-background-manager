<body>
<div class="x-body">
    <div class="layui-col-xs11 layui-col-xs-offset10">
        <button class="layui-btn" onclick="x_admin_show('创建邮件','/gm/create-mail',1000,600)">创建邮件</button>
        <button class="layui-btn" onclick="x_admin_show('历史信息库','/gm/history-mail',1000,600)">历史信息库</button>
    </div>
    <table class="layui-table" id="mailListTable" lay-filter="sort"></table>
</div>
<script type="text/html" id="barMailList">
    <div>
        <button class="layui-btn layui-btn-xs">操作</button>
    </div>
</script>
<script>
    layui.use('table',function () {
        var table = layui.table;
        //自动加载
        table.render({
            elem:"#mailListTable"
            ,url:"/gm/mail-notice"
            ,page:true
            ,method: 'post'
            ,cols:[[
                {field:"id",title:"ID",sort:true}
                ,{field:"serial",title:"序列号"}
                ,{field:"title",title:"标题"}
                ,{field:"content",title:"内容"}
                ,{field:"enclosure",title:"附件内容"}
                ,{field:"target",title:"发布对象"}
                ,{field:"send_time",title:"发送时间"}
                ,{field:"create_time",title:"创建时间"}
                ,{field:"status",title:"状态"}
                ,{field:"operator",title:"发布人"}
                ,{field:"examine",title:"审核"}
                ,{field:"",title:"操作",toolbar:"#barMailList"}
            ]]
        });

        table.on('sort(sort)', function(obj){
            table.reload('mailListTable', {
                url:'/gm/mail-notice',
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
