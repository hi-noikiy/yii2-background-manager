<style>
    #summarizing {
        float: right;
        margin-right: 10%;
        width: 20%;
        background-color: lavender;
        border-radius: 20px;
    }

    #amount {
        padding-left: 5%;
    }
    .my-skin .layui-layer-btn a {
        background-color: #84c101;
        border: 1px solid #84c101;
        color: #FFF;
    }
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>VIP充值</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div>
    <form action="" style="margin-top:1%">
        <input type="hidden" value="<?php echo $proportion; ?>" id="proportion">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">* 用户ID&nbsp;&nbsp;&nbsp;</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" lay-verify="required" name="userID" id="userID">
            </div>
        </div>
        <div>
            <label for="" class="layui-form-label">用户昵称</label>
            <div id="nickname" style="padding-top: 10px;color: lawngreen"></div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">* 充值金额</label>
            <div class="layui-input-inline">
                <input type="text" autocomplete="off" class="layui-input" lay-verify="required" name="priceNum"
                       id="priceNum">
            </div>
            <div class="layui-input-inline" style="margin-top: 1%">
                (当前兑换比例：1:100)
            </div>
        </div>
        <div>
            <label for="" class="layui-form-label">输出金额</label>
            <div class="layui-input-inline">
                <input type="text" readonly class="layui-input" name="outMoney" id="outMoney">
            </div>
        </div>
        <br>
        <div>
            <label for="" class="layui-form-label">*充值订单号</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="(转账单号后四位)" name="orderId" id="orderId">
            </div>
        </div>
        <div class="layui-form-item">
            <div style="position: absolute;left:5%;margin-top: 1%">
                <div class="layui-btn" lay-submit="" lay-filter="officialCharge" data-type="recharge" id="officialCharge">确认支付</div>
                <button class="layui-btn" type="reset">重置</button>
            </div>
        </div>
    </form>

    <div class="layui-inline" id="summarizing">
        <div id="amount">
            <label>充值金额: </label>
            <div class="layui-input-inline">
                <input type="text" style="background-color:transparent" readonly class="layui-input" name="all" id="all"
                       value=<?php echo $summarizing['all']; ?>/>
            </div>
        </div>

        <div id="amount">
            <label style="width: 30%">今日充值: </label>
            <div class="layui-input-inline">
                <input type="text" readonly style="background-color:transparent" class="layui-input" name="today"
                       id="today" value=<?php echo $summarizing['today']; ?>/>
            </div>
        </div>
    </div>

    <form action="" class="layui-form BGO" style="margin-top: 5%">
        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="开始时间" id="start_time">
        </div>
        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="结束时间" id="end_time">
        </div>
        <div class="layui-inline">
            <input type="text" class="layui-input" placeholder="玩家id" id="player_id">
        </div>
        <div class="layui-btn" data-type="search" id="search"><i class="layui-icon">&#xe615;</i></div>
    </form>

    <table class="layui-table" id="followUpRecordTable" lay-filter="table"></table>
</div>
<script type="text/html" id="baragentTable">
    <button class="layui-btn layui-btn-xs" lay-event="pay" id="pay">确认支付</button>
</script>
<script type="text/javascript">
    window.onload = function () {
        var proportion = $("#proportion").val();

        var priceNum = document.getElementById('priceNum');
        var outMoney = document.getElementById('outMoney');
        priceNum.onkeyup = function (e) {
            var priceNumVal = $("#priceNum").val();
            if (/\D/.test(priceNumVal)) {
                alert('只能输入数字');
                this.value = '';
            }
            var key = e.keyCode || e.charCode;
            //有些按键不做处理，如上下左右箭头, Home, End, PageUp, PageDown
            if (key >= 33 && key <= 40) return;
            outMoney.value = priceNum.value * proportion;
        }
    };
</script>
<script src="/static/js/style.js" charset="utf-8"></script>
<script>
    function reloadPage() {
        window.location.href = window.location.href;
        ityzl_SHOW_LOAD_LAYER('正在加载订单列表，请稍后...',300000);
    }

    $("#userID").blur(function () {
        var player_id = $('#userID').val();
        if (player_id) {
            $.ajax({
                url: '/gm/check-nickname'
                , method: "post"
                , data: {
                    'player_id': player_id
                }
                , success: function (res) {
                    res = eval('(' + res + ')');
                    console.log(res);
                    if (res.code == 0) {
                        var nickname = res.data;
                        $('#nickname').html(nickname);
                    } else {
                        layer.msg('输入错误！', {time: 1000});
                    }
                }
                , error: function () {
                    layer.msg('网络错误!', {time: 1000});
                }
            })
        }
    });

    layui.use(['table', 'layer', 'form', 'laydate'], function () {
        var $ = layui.$;
        $(".refresh").on("click", function () {
            reloadPage();
        });
        var table = layui.table;
        var laydate = layui.laydate;

        var date = new Date();
        var month = date.getMonth() + 1;
        var strDate = date.getDate();
        var today = date.getFullYear() + "-" + month + "-" + strDate;
        laydate.render({elem: '#start_time', value: today});
        laydate.render({elem: '#end_time', value: today});

        //table数据渲染(自动加载)
        table.render({
            elem: "#followUpRecordTable"
            , url: "/gm/vip-recharge-record"
            , method: 'post'
            , page: true
            , where: {
                startTime: today,
                endTime: today
            }
            , cols: [[
                {field: "id", title: "序号",width:60}
                , {field: "create_time", title: "充值时间", sort: true,width:180}
                , {field: "player_id", title: "玩家id",width:100}
                , {field: "order_id", title: "订单id",width:100}
                , {field: "nickname", title: "玩家名称",width:100}
                , {field: "amount", title: "充值金额(元)",width:100}
                , {field: "out_amount", title: "输出元宝(元宝)",width:100}
                , {field: "operate_user", title: "操作账号",width:100}
                , {field: "status", title: "状态",width:100}
                , {field: "operate",title: "操作",minWidth:100,toolbar:"#baragentTable"}
            ]]
            ,done: function () {
                var arrtd        = $('tbody').find('tr').find('td:eq(8) div');
                var arrtdOperate = $('tbody').find('tr').find('td:eq(9) div');
                for (var i=0;i<arrtdOperate.length;i++){
                    var str = arrtd[i].innerHTML;
                    if(str == '充值成功'){
                        console.log(str);
                        $(arrtdOperate[i]).html('已完成');
                    }
                }
            }
        });

        //确认充值
        table.on('tool(table)',function (obj) {
            var data = obj.data;
            console.log(data);
            switch (obj.event) {
                case 'pay':
                    console.log('pay');
                    $.ajax({
                        type: "POST",
                        url: '/gm/recharge',
                        data: {id:data.id},
                        dataType: "json",
                        beforeSend:function () {
                            //点击隐藏
                            obj.tr.find('td:eq(9) div').html('');
                            load = ityzl_SHOW_LOAD_LAYER('正在充值中...',300000);
                        },
                        success: function(res){
                            console.log(res);
                            if (res.code == 0) {
                                layer.msg('确认充值成功', {time: 1000});
                                setTimeout(reloadPage, 1000);
                            } else {
                                layer.msg(res.msg, {time: 2000});
                                setTimeout(reloadPage, 1000);
                            }
                        }
                    });
                break;
            }
        });

        var active = {
            search: function () {
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();
                var player_id = $('#player_id').val();
                console.log(start_time + end_time + player_id);
                table.reload('followUpRecordTable', {
                    url: '/gm/vip-recharge-record',
                    method: 'post',
                    page: {
                        curr: 1
                    },
                    where: {
                        startTime: start_time,
                        endTime: end_time,
                        playerId: player_id
                    }
                })
            },
            recharge:function () {
                var player_id = $.trim($('#userID').val());
                var nickname = $('#nickname').html();
                var amount = $('#priceNum').val();
                var outMoney = $('#outMoney').val();
                var orderId = $.trim($('#orderId').val());
                if(!player_id || !amount || !outMoney || !orderId){
                    alert('必填项不能为空，标*为必填项！');
                    disAbleButton(2);
                    return;
                }
               if(orderId.length != 4){
                   alert('确认订单号为转账单号后四位！');
                   disAbleButton(2);
                   return;
               }
                layer.confirm('确定给ID为:'+player_id+'的用户，充值:'+outMoney+'元宝吗？', {
                        btn: ['确定', '取消']//按钮
                    }, function (index) {
                        $(".my-skin .layui-layer-btn a").addClass('layui-hide');
                        if (outMoney > 99999999999) {
                            alert('充值金额超过最高数值，请重新输入！');
                            return;
                        }
                        layer.confirm("请核对订单号为:<p style='color:red'>"+orderId+"</p>", {
                            btn: ['确定', '取消'], //按钮
                            skin : "my-skin"
                        },function () {
                            $.ajax({
                                url: '/gm/recharge-index'
                                , method: "post"
                                , data: {
                                    'player_id'  : player_id,
                                    'nickname'   : nickname,
                                    'amount'     : amount,
                                    'out_amount' : outMoney,
                                    'order_id'    : orderId
                                }
                                ,beforeSend:function () {
                                    ityzl_SHOW_LOAD_LAYER('正在努力创建充值订单，请稍后...',300000);
                                }
                                , success: function (res) {
                                    console.log(res);
                                    res = eval('(' + res + ')');
                                    if (res.code == 0) {
                                        layer.msg('订单创建成功', {time: 1000});
                                        setTimeout(reloadPage, 1000);
                                    } else {
                                        layer.msg(res.msg, {time: 2000});
                                    }
                                    disAbleButton(2);
                                }
                            })
                        }, function (index) {
                            disAbleButton(2);
                            layer.close(index);
                        })
                    }, function (index) {
                        disAbleButton(2);
                        layer.close(index);
                    }
                );
            }

        };

        $('#search').on('click', function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        $('#officialCharge').on('click', function () {
            disAbleButton(1);
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        
        function disAbleButton(type) {
            if(type == 1){
                $('#officialCharge').addClass('layui-hide');
            }else{
                $('#officialCharge').removeClass('layui-hide');
            }
        }

        //排序
        table.on('sort(table)', function(obj){
            table.reload('followUpRecordTable', {
                url:'/gm/vip-recharge-record',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

    });
</script>
</body>
