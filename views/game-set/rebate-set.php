
<style>
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
    .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">游戏系统设置</a>
            <a>
                <cite>返利设置</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <div class="buttonStyle">
        <div class="lf layui-btn add" data-method="add" id="add">
            <i class="layui-icon">&#xe61f;</i>
            添加
        </div>
    </div>
    <table class="layui-table" id="levelTable" lay-filter="table1" > </table>
</div>
<script type="text/html" id="baragentTable">
    <div id="layerDemo">
        <button class="layui-btn layui-btn-xs" lay-event="consume">修改</button>
    </div>
</script>

<script>
    layui.use(['table','layer','form'],function () {
        var $ = layui.$;
        var table =layui.table;
        var form = layui.form;

        //table数据渲染(自动加载)
        table.render({
            elem:"#levelTable"
            ,url:"/game-set/rebate-set"
            ,method: 'post'
            ,cols:[[
                {field:"id",title:"序号"}
                ,{field:"desLevel",title:"档位"}
                ,{field:"min",title:"最小值"}
                ,{field:"max",title:"最大值"}
                ,{field:"ratio",title:"返利"}
                ,{field:"",title:"操作",width:205,toolbar:"#baragentTable"}
            ]]
        });

        var active = {
            //新增vip账户功能
            add:function () {
                layer.open({
                    type: 1
                    ,title: false //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['40%','40%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['添加','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#addContent')
                    ,yes:function (index,layero) {
                        var level = $('#level').val();
                        var min = $('#min').val();
                        var max = $('#max').val();
                        var ratio = $('#ratio').val();
                        $.ajax({
                            url:'/game-set/rebate-add',
                            type:"POST",
                            data:{
                                'level':level,
                                'min':min,
                                'max':max,
                                ratio:ratio
                            }
                            ,success:function (data) {
                                data = eval("("+data+")");
                                if(data.code == 0){
                                    layer.close(index);
                                    layer.confirm('添加成功！',{time:1000});
                                    table.reload('levelTable', {
                                        url:'/game-set/rebate-set'
                                    });
                                }else{
                                    alert(data.msg);
                                }
                            }
                            ,error:function () {
                                alert("添加失败!");
                            }
                        });
                    }
                })
            }
        };
        //添加vip账号
        $('#add').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });

        //消耗详情/下级详情按钮监听事件
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            console.log(data);
            switch (obj.event) {
                case 'consume':
                    layer.open({
                        type: 1
                        ,title: '修改返利设置' //不显示标题栏
                        ,closeBtn: 1
                        ,area: ['40%','50%']
                        ,shade: 0.8
                        ,btn: ['修改','取消']
                        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                        // ,btn: ['确认','取消']
                        ,btnAlign: 'c'
                        ,moveType: 1 //拖拽模式，0或者1
                        ,content:$('#edit')
                        ,success:function (layero,index) {
                            $('#edit_level').val(data.level);
                            $('#edit_min').val(data.min);
                            $('#edit_max').val(data.max);
                            $('#edit_ratio').val(data.ratio);
                            form.render();
                        }
                        ,yes:function (index,layero) {
                            var level = $('#edit_level').val();
                            var min = $('#edit_min').val();
                            var max = $('#edit_max').val();
                            var ratio = $('#edit_ratio').val();
                            $.ajax({
                                url:'/game-set/rebate-add',
                                type:"POST",
                                data:{
                                    'level':level,
                                    'min':min,
                                    'max':max,
                                    ratio:ratio
                                }
                                ,success:function (data) {
                                    data = eval("("+data+")");
                                    if(data.code == 0){
                                        layer.close(index);
                                        layer.confirm('修改成功！',{time:1000});
                                        table.reload('levelTable', {
                                            url:'/game-set/rebate-set'
                                        });
                                    }else{
                                        alert(data.msg);
                                    }
                                }
                                ,error:function () {
                                    alert("添加失败!");
                                }
                            });
                        }

                    });
                    break;
            }
        });
    })
</script>
<!-- 添加弹出层-->
<div class="x-body" id="addContent" style="display:none;">
    <div class="center">
        <div><h2>添加级别</h2></div>
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">档位</label>
                <div class="layui-input-inline">
                    <input  id="level" type="text" class="layui-input" name="level">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">范围</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" id="min">-<input class="layui-input" type="text" id="max">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">返利</label>
                <div class="layui-input-inline">
                    <input  id="ratio" type="text" class="layui-input" name="ratio">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="x-body" id="edit" style="display: none;">
    <!--过滤条件-->
    <div  class="layui-form">
        <form action="" class="layui-form" style="text-align: center">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">档位</label>
                <div class="layui-input-inline">
                    <input  id="edit_level" type="text" class="layui-input" name="edit_level">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">范围</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" id="edit_min">
                </div>
                <div class="layui-form-mid">-</div>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" id="edit_max">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">返利</label>
                <div class="layui-input-inline">
                    <input  id="edit_ratio" type="text" class="layui-input" name="edit_ratio">
                </div>
            </div>
        </form>
    </div>
</div>
