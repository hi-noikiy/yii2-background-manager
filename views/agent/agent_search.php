<style>
    td{width:25%;}
    .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">代理相关</a>
        <a>
            <cite>查询代理</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <!--查询框-->
    <form action="" class="layui-form BGO" onkeydown="if(event.keyCode==13) return false;" method="post">
        <div class="layui-input-inline">
            <input id="searchCont" type="text" class="layui-input" placeholder="用户ID" name="uid"/>
        </div>
        <div class="layui-btn" id="searchBtn"><i class="layui-icon">&#xe615;</i></div>
    </form>
<div style="position: relative">
    <table id="table" class="layui-table table" lay-event lay-ski="nob">
        <caption><h2>用户信息</h2></caption>
        <tr>
            <td>玩家ID</td>
            <td></td>
            <td>昵称</td>
            <td></td>
        </tr>
        <tr>
            <td>电话</td>
            <td></td>
            <td>住址</td>
            <td></td>
        </tr>
        <tr>
            <td>真实姓名</td>
            <td></td>
            <td>代理等级</td>
            <td></td>
        </tr>
        <tr>
            <td>上级代理</td>
            <td></td>
            <td>伞下玩家数量</td>
            <td></td>
        </tr>
        <tr>
            <td>可开通下级数量</td>
            <td id="btn1"></td>
            <td>创建时间</td>
            <td></td
        </tr>
        <tr>
            <td>可提现金额</td>
            <td></td>
            <td>历史总收益</td>
            <td></td>
        </tr>
        <tr>
            <td>最后登录时间</td>
            <td></td>
            <td>剩余可开通下级数量</td>
            <td id="btn1"></td>
        </tr>
        <tr>
            <td>下级代理数量</td>
            <td></td>
        </tr>
    </table>
</div>
    <hr>
    <br/>
    <br/>
    <table id="table2" class="layui-table table" style="position: relative">
        <caption><h2>上级信息</h2></caption>
        <tr>
            <td>绑定邀请码的代理ID</td>
            <td id="btn2"></td>
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

    <hr>
    <!--管理员操作记录表-->
    <table id="table3" class="layui-table">
        <caption><h2>管理员操作记录</h2></caption>
        <tr>
            <td>操作时间</td>
            <td>操作人</td>
            <td>操作内容</td>
        </tr>
    </table>
    <hr>
</div>

<script>
    layui.use('layer',function () {
        var layer = layui.layer;
        //点击查询按钮时，向后端提交检索数据，并拿到后端返回结果放在对应td中
        var tds = $(".table td");
        $("#searchBtn").on('click',function () {
            var searchCont = $("#searchCont").val();
            $.ajax({
                type:"POST"
                ,url:"/agent/agent-search"
                ,data:{uid:searchCont}
                ,success:function (val) {
                    data = eval("("+val+")");
                    console.log(data);
                    if(data.code === 0){
                        var  j=1;
                        // var tds = $(".table td");
                        for (var key in data.data){
                            tds[j].innerHTML=data.data[key];
                            j+=2;
                        }
                        layui.use(['table','layer','form'],function () {
                            var $ = layui.$;
                            var table = layui.table;

                            //管理员操作记录数据渲染
                            table.render({
                                elem: "#table3"
                                , url: "/agent/admin-operation-record"
                                , method: 'post'
                                , page: true
                                , where:{
                                    uid:searchCont
                                }
                                , cols: [[
                                    {field: "op_time", title: "操作时间", sort: true}
                                    , {field: "username", title: "操作人"}
                                    , {field: "op_content", title: "操作内容"}
                                ]]
                            });
                        });
                    }else{
                        alert(data.msg);
                    }

                },
                error:function (err) {
                    layer.open({
                        type:1,
                        title:'错误提示',
                        area:['30%','25%'],
                        btn:['确认'],
                        content:'<div style="width:100%;height: 100%;"><h2 style="width:190px;height:20px;position:absolute;left:30%;top:50%;">您输入的ID不存在！</h2></div>'
                    })
                }
            });


        })
        $('#reviseBtn1').click(function () {
            var input1 = document.createElement('input');
            var input1Val = $(tds[23]).contents();
            $(input1).val(input1Val[0].data)
            $(tds[23]).empty();
            $(tds[23]).append(input1)
            console.log($(this))
            $(this).css('display','none')
            $('#completeBtn1').css('display','block')
        });
        $('#completeBtn1').click(function () {
            var inputVal1 = $(tds[23]).find('input');
            $(tds[23]).empty();
            $(tds[23]).html(inputVal1.val())
            $(this).css('display','none')
            $('#reviseBtn1').css('display','block')
        });

        $('#reviseBtn2').click(function () {
            var input1 = document.createElement('input');
            var input1Val = $(tds[51]).contents();
            $(input1).val(input1Val[0].data)
            $(tds[51]).empty();
            $(tds[51]).append(input1)
            console.log($(this))
            $(this).css('display','none')
            $('#completeBtn2').css('display','block')
        });

        $('#completeBtn2').click(function () {
            var inputVal1 = $(tds[51]).find('input');
            $(tds[51]).empty();
            $(tds[51]).html(inputVal1.val())
            $(this).css('display','none')
            $('#reviseBtn2').css('display','block')
        })


    })
</script>
</body>
