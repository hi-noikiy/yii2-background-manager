<style>
    .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">渠道合伙人</a>
            <a>
                <cite>伞下玩家情况</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <form action="" class="layui-form">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="会员ID" id="memberID">
        </div>

        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="合伙人ID" id="dailiID">
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
                ,url:"/channel-partner/player-info"
                ,method:"post"
                ,page:true
                ,where:{
                    hasLogin:hasLogin//今日登陆的玩家
                }
                ,cols:[[
                    {field:"id",title:"序号",sort:true}
                    ,{field:"agentId",title:"上级ID"}
                    ,{field:"agentName",title:"上级姓名"}
                    ,{field:"player_id",title:"玩家ID"}
                    ,{field:"goldBar",title:"自身元宝"}
                    ,{field:"expend",title:"当日当前消耗"}
                    ,{field:"nickname",title:"微信昵称"}
                    ,{field:"reg_time",title:"注册时间"}
                    ,{field:"ip",title:"IP地址"}
                    ,{field:"machine_code",title:"mac地址"}
                    ,{field:"last_login_time",title:"最后登陆时间"}
                ]]
            })
        });
        //渲染table数据
        table.render({
            elem:"#memberList"
            ,url:"/channel-partner/player-info"
            ,method:"post"
            ,page:true
            ,cols:[[
                {field:"id",title:"序号",sort:true}
                ,{field:"agentId",title:"上级ID"}
                ,{field:"agentName",title:"上级姓名"}
                ,{field:"player_id",title:"玩家ID"}
                ,{field:"goldBar",title:"自身元宝"}
                ,{field:"expend",title:"当日当前消耗"}
                ,{field:"nickname",title:"微信昵称"}
                ,{field:"reg_time",title:"注册时间"}
                ,{field:"ip",title:"IP地址"}
                ,{field:"machine_code",title:"mac地址"}
                ,{field:"last_login_time",title:"最后登陆时间"}
            ]]
        });
        //排序
        table.on('sort(table1)', function(obj){
            table.reload('memberList', {
                url:'/channel-partner/player-info',
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
                    url:'/channel-partner/player-info',
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
<!--修改代理ID弹出层-->
<!--style="display: none"-->
<div class="x-body" id="reviseagentid" style="display: none">
    <form action="" class="layui-form" style="margin:30px 0 0 100px;">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">会员ID</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="playerIdCont" id="playerIdCont">
            </div>
        </div>
        <div class="layui-form-item" id="getCode">
            <label for="" class="layui-form-label">操作验证码</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="identifyingCode" id="identifyingCode">
            </div>
            <div class="layui-btn layui-btn-sm " style="margin:3px 5px;"id="getCodeBtn">获取验证码</div><span id="span" style="display: none;">5s</span>
        </div>
    </form>
</div>
