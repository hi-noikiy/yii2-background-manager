<body>
<div class="x-body">
    <form action="game-set/table-fee" class="layui-form"  style="margin-top:50px;">
        <div class="layui-form-item" >
            <label for="" class="layui-form-label" style="width: 30%;">子游戏名称</label>
            <div class="layui-input-inline" >
                <select name="chess" class="gameId" lay-filter="gameId" id="" style="width: 30%;">
                    <option value="524818">新版拼十</option>
                    <option value="524817">新填大坑</option>
                    <option value="524815">新推筒子</option>
                    <option value="524816">新三张牌</option>
                </select>
            </div>
            <div class="layui-input-inline" >
                <select name="chess" class="levelId" id="levelId" lay-filter="levelId" style="width: 30%;">
                    <?php foreach($data['ruleLevel'] as $key=>$val){ ?>
                        <option value=<?php echo $key; ?>> <?php echo $val;?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item" >
            <label for="" style="width: 30%;" lay-submit="" class="layui-form-label">当前房费</label>
            <input class="layui-form-label layui-input tableFee" style="margin: auto; color: #00A1CB;width: 10%;" value="" />
        </div>
        <div class="layui-form-item layui-col-xs4 layui-col-xs-offset4" style="margin-top: 30px;">
            <div class="layui-btn layui-btn-danger" lay-submit="" lay-filter="force">提交修改</div>
        </div>
    </form>
</div>
<script>
    $(function () {
        getTableFeeByLevelId();
    });

    //底注级别和台费联动
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer
            ,form = layui.form;
        form.on('select(levelId)', function(data){
            getTableFeeByLevelId();
        });
    });

    //根据游戏id和levelId获取对应的台费
    function getTableFeeByLevelId() {
        var gameId = $(".gameId").val();
        var levelId = $(".levelId").val();
        console.log("gameId: "+gameId+"--levelId: "+levelId);
        if(!levelId){
            alert("参数错误，请刷新重试！");return;
        }
        $.ajax({
            url: '/game-set/get-table-fee-by-level-id?gameId='+gameId+'&levelId='+levelId
            , success: function (data) {
                dataObj = eval(data);
                console.log(dataObj);
                if(dataObj.code === 0){
                    $(".tableFee").val(dataObj.data);
                }else{
                    alert(dataObj.msg);
                }
            }
            ,error:function () {
                alert('修改台费失败,请稍后重试！');
            }
        });
    }

    //子游戏和底注联动
    layui.use(['layer', 'form'], function(){
        var layer = layui.layer
            ,form = layui.form;
        form.on('select(gameId)', function(data){
            var gameId = $(".gameId").val();
            $.ajax({
                type: 'post'
                , data: {
                    'gameId': gameId
                }
                , url: '/game-set/get-rule-level'
                , success: function (data) {
                    dataObj = eval(data);
                    if(dataObj.code === 0){
                        console.log(dataObj);
                        $(".levelId").empty();
                        form.render("select");
                        $(".levelId").append("<option value=''>选择底注</option>");
                        $.each(dataObj.data, function(i,val){
                            $(".levelId").append("<option value='"+i+"'>"+val+"</option>");
                        });
                        form.render("select");

                        $(".tableFee").val("");
                    }else{
                        alert(dataObj.msg);
                    }
                }
                ,error:function () {
                    alert('更新底注失败,请稍后重试！');
                }
            });
        });
    });

    //修改台费
    layui.use('form',function () {
        var form = layui.form;
        var $ = layui.$;
        form.on('submit(force)',function (data) {
            var tableFee = $(".tableFee").val();
            var levelId = $(".levelId").val();
            var gameId = $(".gameId").val();
            console.log(levelId);
            $.ajax({
                type: 'post'
                , data: {
                    'tableFee': tableFee,
                    'gameId': gameId,
                    'levelId':levelId
                }
                , url: '/game-set/table-fee'
                , success: function (data) {
                    dataObj = eval("(" + data + ")");
                    if(dataObj.code === 0){
                        alert('修改成功！');
                    }else{
                        alert(dataObj.msg);
                    }
                }
                ,error:function () {
                    alert('修改失败,请稍后重试！');
                }
            });
        })
    })


</script>
</body>
