<style>
    td{width:25%;}
    .BGO{background-color: #EEEEEE;padding:1px;}
    /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">玩家相关</a>
            <a>
                <cite>玩家查询</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">

    <!--查询框-->
    <form action="" class="layui-form BGO" onkeydown="if(event.keyCode==13) return false;" method="post">
        <!--<lable class="layui-form-label">用户ID</lable>-->
        <div class="layui-input-inline">
            <input id="searchCont" type="text" class="layui-input" placeholder="用户ID">
        </div>
        <div id="searchBtn" class="layui-btn layui-button" style="margin-left:-4px;"><i class="layui-icon  ">&#xe615;</i></div>
    </form>
    <!--用户信息表-->
    <table id="table" class="layui-table" lay-filter="table1">
        <caption><h2>用户信息</h2></caption>
        <tr>
            <td>玩家ID</td>
            <td></td>
            <td>玩家微信昵称</td>
            <td></td>
        </tr>
        <tr>
            <td>玩家机器码</td>
            <td></td>
            <td>头像</td>
            <td></td>
        </tr>
        <tr>
            <td>联系电话</td>
            <td></td>
            <td>注册时间</td>
            <td></td>
        </tr>
        <tr>
            <td>最后登陆时间</td>
            <td></td>
            <td>玩家ip</td>
            <td></td>
        </tr>
        <tr>
            <td>性别</td>
            <td></td>
            <td>省份</td>
            <td></td>
        </tr>
        <tr>
            <td>城市</td>
            <td></td>
            <td>玩家状态</td>
            <td></td>
        </tr>
        <tr>
            <td>认证时间</td>
            <td></td>
        </tr>
    </table>
    <hr>
    <!--上级信息表-->
    <table id="table" class="layui-table">
        <caption><h2>上级信息</h2></caption>
        <tr>
            <td>绑定邀请码的代理ID</td>
            <td><div id="reviseBtn1" class="layui-btn layui-btn-xs" lay-event="revise" style="float:left;display:none">修改</div></td>
            <td>代理昵称</td>
            <td></td>
        </tr>
        <tr>
            <td>创建时间</td>
            <td></td>
            <td>代理级别</td>
            <td></td>
        </tr>
    </table>
</div>

<script>
    var tds = $("#table td");
    var thisData;
    //点击查询按钮时，向后端提交检索数据，并拿到后端返回结果放在对应td中
    $("#searchBtn").on('click',function () {
        var searchCont = $("#searchCont").val();
        $.ajax({
            type:"POST"
            ,url:"/player-related/search-player"
            ,data:{uid:searchCont}
            ,success:function (val) {
                thisData=val;
                console.log(val);
                var data = eval("("+val+")");
                if(data.code == 0){
                    for (var index = 1; index < tds.length; index = index + 2){
                        tds[index].innerHTML="";
                    }
                    
                    var  j=1;
                    for (var key in data.data){
                        tds[j].innerHTML=data.data[key];
                        j+=2;
                    }
                    $('#reviseBtn1').css('display','block');
                    $('#reviseBtn2').css('display','block');
                }else{
                    alert(data.msg);
                }

            }
        })
    });

    $('#reviseBtn1').click(function () {
//            var input1 = document.createElement('input');
//            var input1Val = $(tds[27]).contents();
//            $(input1).val(input1Val[0].data);
//            $(tds[27]).empty();
//            $(tds[27]).append(input1);
//            $(this).css('display','none');
//            $('#completeBtn1').css('display','block');
            layui.use(['table','form'],function () {
                var form = layui.form;
                var dataObj = eval("("+thisData+")");
                var originalplayerId = dataObj.data.parentIndex;
                var playerId = dataObj.data.player_id;
                var tel = dataObj.data.phone_num;
                var timer1;
                layer.open({
                    type: 1
                    , title: '修改代理ID' //不显示标题栏
                    , closeBtn: 1
                    , area: ['400', '400']
                    , shade: 0.8
                    , id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    , btn: ['确认', '取消']
                    , btnAlign: 'c'
                    , moveType: 1 //拖拽模式，0或者1
                    , content: $('#reviseagentid')
                    , success: function (layero, index) {
                        $('#playerIdCont').val(originalplayerId);
                        //设置获取验证码倒计时
                        $('#getCodeBtn').on('click', function () {
                            //向后端发送tel
                            $.ajax({
                                url: '/agent/get-code/',
                                type: "POST",
                                data: {'tel': tel},
                                success: function (o) {
                                    var Obj = eval("("+o+")");
                                    if(Obj.code == '0'){
                                        layer.msg("验证码发送成功！", {time: 1000});
                                    }else{
                                        layer.msg(Obj.msg, {time: 1000});
                                    }
                                },
                                error: function (data) {
                                    var span = $('#span').html();
                                    var span1 = span;
                                    var a = parseInt(span);
                                    console.log(span);
                                    layer.msg("验证码发送失败！", {time: 1000});
                                    timer1 = setInterval(function () {
                                        if (a !== 0) {
                                            $('#getCodeBtn').addClass('layui-btn-disabled');
                                            $('#span').show();
                                            a--;
                                            console.log(a);
                                            $('#span').text(a + 's');
                                        } else {
                                            $('#getCodeBtn').removeClass('layui-btn-disabled');
                                            clearInterval(timer1);
                                            timer1 = null;
                                            $('#span').hide();
                                            $('#span').html('5s');
                                        }
                                    }, 1000);

                                    console.log("time11=" + timer1);
                                }
                            })

                        });
                    }
                    , yes: function (index, layero) {
                        var playerIdCont = $('#playerIdCont').val();
                        var identifyingCode = $('#identifyingCode').val();
                        if(!identifyingCode){
                            layer.msg("操作验证码不能为空！", {time: 1000});
                            return;
                        }
                        $.ajax({
                            url: '/player-related/edit-parent-id/',
                            type: "POST",
                            data: {
                                'playerIdCont': playerIdCont,
                                'identifyingCode': identifyingCode,
                                'playerId' : playerId
                            }
                            , success: function (ret) {
                                var result = eval("("+ret+")");
                                if(result.code == '0'){
                                    layer.msg("修改成功！", {time: 1000});
                                    layer.close(index);
                                }else{
                                    layer.msg(result.msg, {time: 1000});
                                    $('#identifyingCode').val();
                                    form.render();
                                    layer.close(index);
                                }
                            }
                            , error: function () {
                                layer.msg("修改失败！", {time: 1000});
                            }
                        });
                    }
                    //点击取消按钮时清除定时器   问题在这段这段是点击取消按钮执行的
                    , btn2: function (index, layero) {
                        console.log('index=' + index);
                        clearInterval(timer1);
                        console.log("time22=" + timer1);
                        //debugger
                        $('#getCodeBtn').removeClass('layui-btn-disabled');
                        $('#span').hide();
                        $('#span').html('5s');
                    }
                });
            })
        });

    $('#completeBtn1').click(function () {
        var inputVal1 = $(tds[27]).find('input');
        $(tds[27]).empty();
        $(tds[27]).html(inputVal1.val());
        $(this).css('display','none');
        $('#reviseBtn1').css('display','block')
    })
</script>
</body>
<!--修改代理ID弹出层-->
<div id="reviseagentid" style="display: none;">
    <form action="" class="layui-form" style="text-align: center">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">上级代理ID</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="playerIdCont" id="playerIdCont">
            </div>
        </div>
        <div class="layui-form-item" id="getCode">
            <label for="" class="layui-form-label">操作验证码</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="identifyingCode" id="identifyingCode">
            </div>
        </div>
        <div class="layui-btn" id="getCodeBtn">获取验证码</div>
        <span id="span" style="display: none;">5s</span>
    </form>
</div>

