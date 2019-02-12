<body>
<div class="x-body">
    <div class="layui-col-xs4">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
        </div>
        <div class="layui-btn" style="margin-left:-4px;" data-type="search" id="search">查询</div>
    </div>
    <!--<div class="layui-btn">导出</div>-->
    <div class="layui-inline layui-col-xs1 layui-col-xs-offset7">
        <button class="layui-btn " data-type="add" id="add">添加</button>
    </div>

    <table class="layui-table" id="moneyPrice"  lay-filter="table1"></table>
</div>
<script type="text/html" id="barmoneyPrice">
    <div id="layerDemo">
        <button class="layui-btn layui-btn-xs" lay-event="revise">修改</button>
        <button class="layui-btn layui-btn-xs layui-btn-danger"  lay-event="del" >删除</button>
    </div>
</script>
<script>
    layui.use(['table','layer','form','laydate'],function () {
        //日期查询
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});
        var table = layui.table;
        var $=layui.jquery,layer=layui.layer;
        var form = layui.form;
        //table数据渲染
        table.render({
            elem:"#moneyPrice"
            ,url:"/payment-center/center-account"
            ,page:true
            ,method: 'post'
            ,cols:[[
                {field:'account',title:"序号"}
                ,{field:'1',title:"1元拉起链接",sort:true}
                ,{field:'10',title:"10元拉起链接"}
                ,{field:'50',title:"50元拉起链接"}
                ,{field:'100',title:"100元拉起链接"}
                ,{field:'300',title:"300元拉起链接"}
                ,{field:'500',title:"500元拉起链接"}
                ,{field:'1000',title:"1000元拉起链接"}
                ,{field:'',title:"操作",toolbar:'#barmoneyPrice'}
            ]]
        });
        //修改删除功能
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            if(obj.event==='revise'){
                //获取原数据
                var originalID = data.ID;
                var originalaccountNum = data.accountNum;
                var originalprice = data.price;
                var originallink = data.link;

                layer.open({
                    type:1
                    ,title:'操作'
                    ,closeBtn:1
                    ,area:['60%','60%']
                    ,shade:0.5
                    ,id:"LAY_layuipro"
                    // ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#revise')
                    // ,content:'revisecurrency'
                    ,success:function (layero,index) {
                        $('#ID').val(originalID);
                        $('#accountNum').val(originalaccountNum);
                        $('#price').val(originalprice);
                        $('#link').val(originallink);
                    }
                })
            }else if(obj.event==='del'){

                layer.open({
                    type:1
                    ,title:'删除'
                    ,closeBtn:1
                    ,area:['30%','30%']
                    //,shade:0.5
                    ,id:"LAY_layuipro"
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#del')
                    ,success:function (layero,index) {
                        $('#num').html(data.ID);
                    }
                    ,yes:function (index,layero) {
                        table.reload('moneyPrice');
                        layer.close(index);
                    }
                })
            }
        })
//新增货币功能
        var active = {
            add:function(){
                layer.open({
                    type:1
                    ,title:'新增'
                    ,closeBtn:1
                    ,area:['60%','60%']
                    //,shade:0.5
                    ,id:"LAY_layuipro"
                    // ,btn:['确认','取消']
                    ,btnAlign:'cwww'
                    ,moveType:1
                    ,content:$('#addcount')
                })
            }
        };
        $('#add').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });

        //查询
        var $ = layui.$, active = {
            search: function(){
                var startTime = $('#startTime');
                var endTime = $('#endTime');

                //执行重载
                table.reload('moneyPrice', {
                    url:'/test/t204'
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        start_time: startTime.val(),
                        end_time: endTime.val()
                    }
                });
            }
        };
        //查询按钮绑定事件
        $('#search').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
//排序
        table.on('sort(table1)', function(obj){
            table.reload('moneyPrice', {
                url:'/test/t205',
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


<!--货币价格的修改弹出层-->
<style>
    .btn{
        border-color:#1E9FFF;
        background-color:#1E9FFF;
        color: #ffffff;
        height:28px;
        weight:28px;
        padding:0 15px;
        border:1px solid #1E9FFF;
        margin:5px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
    }
</style>
<!--添加-->
<div class="x-body" id="addcount" style="display: none;">
    <form action="" class="layui-form" style="margin-top:50px;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">账号ID</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="ID" id="ID1">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">账号</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="account" id="account1">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">金额</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="price" id="price1">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">拉起链接</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="link" id="link1">
            </div>
        </div>
        <div class="layui-form-item" style="width:100%;">
            <div style="position: absolute;left:40%;margin-bottom: 15px;">
                <button class="layui-btn" lay-submit="" lay-filter="submit">确认</button>
                <button class="layui-btn" type="reset">重置</button>
            </div>
        </div>
    </form>
</div>

<!--修改-->
<div class="x-body" id="revise" style="display: none;">
    <form action="" class="layui-form" style="margin-top:50px;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">账号ID</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="ID" id="ID">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">账号</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="account" id="account">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">金额</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="price" id="price">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">拉起链接</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" lay-verify="required" name="link" id="link">
            </div>
        </div>
        <div class="layui-form-item" style="width:100%;">
            <div style="position: absolute;left:40%;margin-bottom: 15px;">
                <button class="layui-btn" lay-submit="" lay-filter="submit">确认</button>
                <button class="layui-btn" type="reset">重置</button>
            </div>
        </div>
    </form>
</div>



<!--删除-->
<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除ID为<span id="num"></span>的收款账号吗？</h2>
</div>