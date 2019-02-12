<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">玩家相关</a>
            <a>
                <cite>会员列表</cite>
            </a>
        </span>
</div>
<div class="x-body">
    <form action="" class="layui-form">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="会员ID" id="memberID">
        </div>

        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="代理ID" id="dailiID">
        </div>

        <div class="layui-btn"  data-type="search" id="search"><i class="layui-icon  ">&#xe615;</i></div>
        <div class="layui-input-inline">
            <input type="checkbox" class="layui-input" title="今日登陆" lay-skin="primary" lay-filter="loggedIn">
        </div>
    </form>
    <!--会员列表table-->
    <table id="memberList" class="layui-table"  lay-filter="table1"></table>
</div>
<script type="text/html" id="barmemberList">
    <button class="layui-btn layui-btn-xs" lay-event="revise" id="reviseAgentIDBtn" title="修改代理ID">修改代理ID</button>
</script>
<script>
    layui.use(['table','form'],function () {
        var form = layui.form;
        var $ = layui.$;
        $(".refresh").on("click",function(){
            window.location.href = window.location.href;
        });
        var table = layui.table;
        var layer = layui.layer;
        //设置已登录默认值为false
        var checked = false;
        form.on('checkbox(loggedIn)',function (data) {
            console.log(data.elem.checked);
            checked = data.elem.checked;
            var hasLogin = 1;
            if(!checked){
                hasLogin = 2;
            }
            table.render({
                elem:"#memberList"
                ,url:"/player-related/member-list"
                ,method:"post"
                ,page:true
                ,where:{
                    hasLogin:hasLogin
                }
                ,cols:[[
                    {field:"id",title:"序号",width:60}
                    ,{field:"agentId",title:"上级ID",width:100}
                    ,{field:"agentName",title:"上级姓名",width:100}
                    ,{field:"u_id",title:"会员ID",width:100}
                    ,{field:"weixin_nickname",title:"微信昵称",width:100}
                    ,{field:"reg_time",title:"注册时间",width:100}
                    ,{field:"ip",title:"IP地址",width:100}
                    ,{field:"machine_code",title:"mac地址",width:150}
                    ,{field:"last_login_time",title:"最后登陆时间",minWidth:180}
                ]]
            })
        });
        //渲染table数据
        table.render({
            elem:"#memberList"
            ,url:"/player-related/member-list"
            ,method:"post"
            ,page:true
            ,cols:[[
                {field:"id",title:"序号",width:60}
                ,{field:"agentId",title:"上级ID",width:100}
                ,{field:"agentName",title:"上级姓名",width:100}
                ,{field:"u_id",title:"会员ID",width:100}
                ,{field:"weixin_nickname",title:"微信昵称",width:100}
                ,{field:"reg_time",title:"注册时间",width:180}
                ,{field:"ip",title:"IP地址",width:100}
                ,{field:"machine_code",title:"mac地址",width:150}
                ,{field:"last_login_time",title:"最后登陆时间",minWidth:180}
            ]]
        });
        var timer1;
        //table事件监听
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            var tel = obj.data.tel;
            console.log(tel);
            //修改会员按钮
            if(obj.event==='revise'){
                var originalplayerId= data.player_id;
                var timer1;
                layer.open({
                    type: 1
                    ,title: '修改代理ID' //不显示标题栏
                    ,closeBtn: 1
                    ,area: ['40%','40%']
                    ,shade: 0.8
                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,btn: ['确认','取消']
                    ,btnAlign: 'c'
                    ,moveType: 1 //拖拽模式，0或者1
                    ,content:$('#reviseagentid')
                    ,success:function (layero,index) {
                        $('#playerIdCont').val(originalplayerId);
                        //设置获取验证码倒计时
                        $('#getCodeBtn').on('click',function(){
                            //向后端发送tel
                            $.ajax({
                                url:'/player-related/send-code/',
                                type:"POST",
                                data:{'tel':tel},
                                success:function(data){

                                },
                                error:function (data) {
                                    var span = $('#span').html();
                                    var span1 = span;
                                    var a=parseInt(span);
                                    console.log(span);
                                    timer1 = setInterval(function () {
                                        if (a!==0){
                                            $('#getCodeBtn').addClass('layui-btn-disabled');
                                            $('#span').show();
                                            a--;
                                            console.log(a);
                                            $('#span').text(a+'s');
                                        } else{
                                            $('#getCodeBtn').removeClass('layui-btn-disabled');
                                            clearInterval(timer1);
                                            timer1=null;
                                            $('#span').hide();
                                            $('#span').html('5s');
                                        }
                                    },1000);

                                    console.log("time11="+timer1);
                                }
                            })

                        });
                    }
                    ,yes:function (index,layero) {
                        var playerIdCont = $('#playerIdCont').val();
                        var identifyingCode = $('#identifyingCode').val();
                        $.ajax({
                            url:'/test/t201/',
                            type:"POST",
                            data:{
                                'playerIdCont':playerIdCont,
                                'identifyingCode':identifyingCode
                            }
                            ,success:function (data) {
                                layer.msg("修改成功！",{time:1000});
                                layer.close(index);
                            }
                            ,error:function () {
                                layer.msg("修改失败！",{time:1000});
                            }
                        });
                    }
                    //点击取消按钮时清除定时器   问题在这段这段是点击取消按钮执行的
                    ,btn2:function (index, layero) {
                        console.log('index='+index);
                        clearInterval(timer1);
                        console.log("time22="+timer1);
                        //debugger
                        $('#getCodeBtn').removeClass('layui-btn-disabled');
                        $('#span').hide();
                        $('#span').html('5s');
                    }
                });
            }
        });
        //排序
        table.on('sort(table1)', function(obj){
            table.reload('memberList', {
                url:'/player-related/member-list',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        //查询
        var active = {
            search:function(){
                var memberID = $('#memberID').val();
                var dailiId = $('#dailiID').val();
                table.reload('memberList',{
                    url:'/player-related/member-list',
                    method:"post",
                    page:{
                        curr:1
                    },
                    where:{
                        playerId:memberID,
                        dailiId:dailiId
                    }
                });
            }
        };
        $('#search').on('click',function () {
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });



    })
</script>
</body>
