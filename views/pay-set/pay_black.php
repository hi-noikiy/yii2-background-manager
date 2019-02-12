<body>
    <div class="x-body">
        <div class="refresh refreshThis"><i class="layui-icon "  >ဂ</i></div>

        <div class="rf ">
            <div class="layui-btn layui-btn-xs1" data-type="search" id="add_black_list" data-method="addBlackList"><i class="layui-icon">&#xe608;</i>添加支付黑名单</div>
        </div>
        <table class="layui-table" id="black_list" lay-filter="black_list_lay" style="margin-top: 50px;">
            <caption><h2>黑名单统计</h2></caption>
        </table>
    </div>
</body>


<script>
    layui.use(['table', 'layer'], function(){
        var table = layui.table;
        var $ = layui.$;

        table.render({
            elem: '#black_list'
            ,url: '/pay-set/pay-black'
            ,method: 'post'
            ,page: true
            ,cols: [[
                {field: 'nickname', title: '昵称'}
                ,{field: 'uid', title: '账号ID'}
                ,{field: 'reg_time', title: '注册时间', sort: true}
                ,{field: 'last_login_time', title: '最后登录时间', sort: true}
                ,{field: 'right',title:'操作',width: 100, align: 'center', toolbar: "#barDemo"}
            ]]
        });

//        添加排序的表格重载
        table.on('sort(black_list_lay)', function(obj){
            table.reload('black_list', {
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

//        监测工具条
        table.on('tool(black_list_lay)', function(obj){
            var data = obj.data;
            var layEvent = obj.event;
            var tr = obj.tr;

            if (layEvent === 'del') {
                layer.confirm('整的要删除这一行吗？', function(index) {
                    obj.del();
                    layer.close(index);

                    $.ajax({
                        url:'/pay-set/pay-black-del',
                        type:"POST",
                        data:{
                            'uid':obj.data.uid
                        }
                        ,dataType:'json'
                        ,success:function (data) {
                            if (data.code == 0) {
                                layer.msg('清除成功！',{time:1000});
                            } else {
                                layer.msg('清除失败', {time:1000});
                            }
                        }
                        ,error:function (data) {
                            layer.msg('清除失败！',{time:1000});
                        }
                    })
                })
            }
        });

//        添加弹出框
        $('#add_black_list').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });

        var active = {
            addBlackList:function(){
                layer.open({
                    type: 1
                    ,title: "黑名单创建" //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['30%','30%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['创建','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$("#bad_id")
                    ,yes:function (index,layero) {
                        //alert('添加成功');
                        var uid = $('#uid').val();
                        var level = $('#level').val();
                        console.log(layero, uid);

//                        $.ajax({
//                            type:'POST'
//                            ,url:'/game-set/add-bad-pocker'
//                            ,data:{uid:uid,level:level}
//                            ,success:function () {
//                                table.reload('blackList', {url:'/game-set/bad-pocker-list'});
//                            }
//                            ,error:function () {
//
//                            }
//                        })
                        layer.close(index);//设置关闭弹出层

                    }
                });
            }
        }
    });
</script>

<script type="text/html" id="barDemo">
    <a class="delIcon" lay-event="del" title="删除"><i class="layui-icon">&#xe640;</i></a>
</script>