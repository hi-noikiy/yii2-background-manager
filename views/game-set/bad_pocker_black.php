<!--差牌黑名单-->
<style>
    label{width:98px!important;}
    .rf{float: right;}
    .lf{float: left;}
    .per{
        position: relative;
        left:-30px;
    }

</style>
<body>
<div class="x-nav">
       <span class="layui-breadcrumb">
        <a href="/game-set/mengxin">游戏系统设置</a>
        <a>
            <cite>差牌黑名单</cite></a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>

<div class="x-body">

    <form action="/game-set/bad-pocker-black" class="layui-form InputStyle titleFormStyle" method="post">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">黑名单发概率：</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="probability" value="<?= $redis_data['probability']?>">
            </div>
            <span class="lf per" style="margin-top: 10px;" >%</span>

            <div class="layui-input-inline SwitchStyle">
                <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" <?php if($redis_data['status'] == 0) {echo 'checked' ;}?>>
            </div>
            <div class="layui-input-inline">
                <button class="layui-btn" lay-submit lay-filter="formDemo">修改</button>
            </div>
        </div>
    </form>

    <hr/>

    <br>
    <div  class="titleFormStyle">
        <div class="layui-btn " data-type="search" id="addBlackList" data-method="addBlackList" style="float: left;"><i class="layui-icon">&#xe61f;</i>添加黑名单</div>
    </div>
    <!--<br/>-->
    <!--<br/>-->
    <!--<xblock>-->
        <!--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>-->
        <!--<button class="layui-btn" onclick="x_admin_show('添加用户','./order-add.html')"><i class="layui-icon"></i>添加</button>-->
    <!--</xblock>-->


    <table class="layui-table" id="blackList" lay-filter="sort" style="margin-top: 50px;">
        <caption><h2>黑名单统计</h2></caption>
    </table>

    <table class="layui-table" id="op" style="margin-top: 50px; ">
        <caption><h2>操作记录</h2></caption>
    </table>
</div>
<script type="text/html" id="barblackList">
    <div class="demoTable">
        <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del" >移除</button>
        <!--<a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">-->
            <!--<i class="layui-icon">&#xe640;</i>-->
        <!--</a>-->
    </div>
</script>
<script>
    layui.use(['table','laydate','form','layer'],function () {
        var $ = layui.$;
        var layer = layui.layer;
        //table渲染
        var table = layui.table;
        table.render({
            elem:'#blackList'
            ,url:'/game-set/bad-pocker-list'
            ,page:true
            ,height:498
            // ,width:1600
            ,cols:[[
                {field:'id',title:'序号',sort:true}
                ,{field:'uid',title:'玩家ID' }
                ,{field: 'level', title:'黑名单等级'}
                ,{field:'playerName',title:'玩家名称'}
                ,{field:'higherLevelID',title:'上级ID'}
                ,{field:'higherLevelName',title:'上级名称'}
                ,{field:'oneselfYB',title:'自身元宝'}
                ,{field:'winNum',title:'赢场次'}
                ,{field:'lostNum',title:'输场次'}
                ,{field:'winYB',title:'赢元宝'}
                ,{field:'LostYB',title:'输元宝'}
                ,{field:'createDate',title:'创建日期'}
                ,{field:'operatingPer',title:'操作人'}
                ,{field:'',title:'操作',toolbar:'#barblackList'}
            ]]
            ,done:function () {
                //删除table行数据
                table.on('tool(sort)', function(obj){
                    var data = obj.data;
                    var uid = data.uid;

                    layer.confirm('真的删除行么', function(data){
                        $.ajax({
                            type:'POST'
                            ,url:'/game-set/bad-pocker-del'
                            ,data:{uid:uid}
                            ,success:function (res) {
                                table.reload('blackList', {url:'/game-set/bad-pocker-list'});
                            }
                            ,error:function () {

                            }})
                        layer.close(data);
                    });
                });

                $('.demoTable .layui-btn').on('click', function(){
                    var type = $(this).data('type');
                    active[type] ? active[type].call(this) : '';
                });

            }
        });

        //排序
        table.on('sort(sort)',function (obj) {
            table.reload('newSetting',{
                url:'/test/t205'
                ,initSort:obj
                ,where:{
                    field:obj.field
                    ,order:obj.type
                }
            })
        });
        //查询
        var active = {
            search:function () {
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();
                table.reload('newSetting',{
                    url:'/game-set/'
                    ,page:{
                        curr:1
                    }
                    ,where:{
                        key:{
                            startTime:startTime
                            ,endTime:endTime
                        }
                    }
                })
            }
            //添加黑名单弹出层
            ,addBlackList:function () {
                layer.open({
                    type: 1
                    ,title: "黑名单创建" //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['30%','30%']
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['创建','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$("#bad_id")
                    ,yes:function (index,layero) {
                        //alert('添加成功');
                        var uid = $('#uid').val();
                        var level = $('#level').val();

                        $.ajax({
                            type:'POST'
                            ,url:'/game-set/add-bad-pocker'
                            ,data:{uid:uid,level:level}
                            ,success:function () {
                                table.reload('blackList', {url:'/game-set/bad-pocker-list'});
                            }
                            ,error:function () {

                            }
                        });
                        $('#uid').val('');
                        $('#level').val('');
                        layer.close(index);//设置关闭弹出层

                    },
                    btn2:function (index,layero) {
                        $('#uid').val('');
                        $('#level').val('');
                    }
                });
            }
        };
        $('#addBlackList').on('click', function(){
            var othis = $(this), method = othis.data('method');
            active[method] ? active[method].call(this, othis) : '';
        });
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        //form事件
//        var form = layui.form;
//        //修改
//        form.on('submit(revise)',function (data) {
//            $.ajax({
//                type:'POST'
//                ,data:{
//                    'probability':data.field.probability
//                }
//                ,url:''
//                ,success:function () {
//
//                }
//                ,error:function () {
//
//                }
//            })
//        });

        //暂停开启按钮切换
//        form.on('submit(changeBtn)',function (data) {
//            if ($('#changeBtn').hasClass("stop")){
//                $('#changeBtn').removeClass('stop');
//                $('#changeBtn').removeClass('layui-btn-danger');
//                $('#changeBtn').addClass('start');
//                $('#changeBtn').html('开启');
//            }else{
//                $('#changeBtn').removeClass('start');
//                $('#changeBtn').addClass('stop');
//                $('#changeBtn').addClass('layui-btn-danger');
//                $('#changeBtn').html('暂停');
//            }
//
//            $.ajax({
//                type:'POST'
//                ,data:{
//
//                }
//                ,url:''
//                ,success:function () {
//
//                }
//                ,error:function () {
//
//                }
//            })
//        });
        //从后台获取操作记录并显示

        table.render({
            elem:'#op'
            ,url:'/game-set/op-bad-pocker'
            ,page:true
            // ,width:1600
            ,cols:[[
                {field:'username',title:'操作人'}
                ,{field:'op_time',title:'操作时间'}
                ,{field:'op_content',title:'操作内容'}
            ]]
        });
    })
</script>
</body>


<div class="x-body" id="bad_id" style="display: none">
    <form class="layui-form" method="post">
        <label for="">玩家ID</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="uid"  id="uid">
        </div>
        <label for="">等级</label>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="level" id="level">
        </div>
    </form>
</div>