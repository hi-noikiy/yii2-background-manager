<style>
    #title{text-decoration: none;}
    #title li{text-decoration: none;float:left;padding:5px 20px;line-height:38px;}
    .selectedLi{border-bottom:2px solid #009688;color:#009688;font-size: 18px;font-weight: 700;}
    .hiddenItem{display:none}
    #title>li:hover{cursor: pointer;}
</style>
<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>跑马灯</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <div class="layui-row titleFormStyle">
        <div class="lf ">
            <button class="layui-btn " data-type="createGM" id="create"><i class="layui-icon">&#xe61f;</i>创建跑马灯</button>
        </div>
    </div>

    <div class="layui-row" style="margin:10px 5px;">
        <ul id="title">
            <li class="selectedLi">GM信息</li>
            <li >玩家信息</li>
        </ul>
        <hr>
    </div>
    <div id="item1"  class="hiddenItem">
        <div  class=" layui-row" >
            <div class="layui-form-item">
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" placeholder="用户ID" id="ID">
                </div>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
                </div>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
                </div>
                <button class="layui-btn"  data-type="reload" id="search"><i class="layui-icon">&#xe615</i></button>
            </div>
        </div>
        <div class="layui-row titleFormStyle">
            <div class="lf">
                <button class="layui-btn " data-type="blackBtn" id="blackBtn">黑名单</button>
                <button class="layui-btn" data-type="whiteBtn" id="whiteBtn">白名单</button>
            </div>
        </div>

        <table class="layui-table" id="playerInfo" lay-filter="playerInfo"></table>
    </div>
    <div id="item2" >
        <div class="layui-tab" style="margin-top:50px;">
            <ul class="layui-tab-title">
                <li class="layui-this">全部</li>
                <li>未播</li>
                <li>已播</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <table class="layui-table" id="allGMList" lay-filter="table1"></table>
                </div>
                <div class="layui-tab-item">
                    <table class="layui-table" id="notPlayedGMList" lay-filter="table2"></table>
                </div>
                <div class="layui-tab-item">
                    <table class="layui-table" id="playedGMList" lay-filter="table3"></table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<!-- 全部 -->
<script type="text/html" id="barAllGMList">
    <div class="layer">
        <div title="修改" class="layui-btn layui-btn-xs record2 hiddenItem" lay-event="reviseGMAllBtn" >修改</div>
        {{#  if(d.play_status == 1){ }}
        <div class="stop layui-btn layui-btn-xs"  lay-event="changeAllBttonn" lay-submit="" title="暂停">暂停</div>
        <!--<button class="layui-btn layui-btn-sm layui-btn-warm changeBtn record2 hiddenItem stop" lay-event="changeAllBttonn" >暂停</button>-->
        {{#  } }}
        {{#  if(d.play_status == 2){ }}
        <div class="layui-btn layui-btn-xs" lay-event="changeAllBttonn" lay-submit="" title="播放">播放</div>
        <!--<button class="layui-btn layui-btn-sm layui-btn-normal changeBtn record2 hiddenItem" lay-event="changeAllBttonn" >播放</button>-->
        {{#  } }}
        <div title="删除" class="layui-btn layui-btn-xs layui-btn-danger  record2 hiddenItem" lay-event="delAllBtn">删除</div>
        <div class="layui-btn layui-btn-xs reviseRecordIcon record1 hiddenItem" lay-event="detailsGMAllBtn" title="修改记录">修改记录</div>
    </div>
</script>
<!-- 未播 -->
<script type="text/html" id="barNotPlayedGMList">
    <div class="layer">
        <div title="修改" class="layui-btn layui-btn-xs" lay-event="reviseGMNotPlayBtn">修改</div>
        {{#  if(d.play_status == 1){ }}
        <div class="stop layui-btn layui-btn-xs"  lay-event="changeNotPlayBtn" lay-submit="" title="暂停">暂停</div>
        {{#  } }}
        {{#  if(d.play_status == 2){ }}
        <div class="layui-btn layui-btn-xs" lay-event="changeNotPlayBtn" lay-submit="" title="播放">播放</div>
        {{#  } }}
        <div class="layui-btn layui-btn-xs" title="删除" lay-event="delNotPlayBtn">删除</div>
        <div class="layui-btn layui-btn-xs record1 hiddenItem " lay-event="detailsGMNotPlayBtn" title="修改记录" >修改记录</div>
    </div>
</script>
<!-- 已播 -->
<script type="text/html" id="barPlayedGMList">
    <div class="layer">
        <div title="修改" class="layui-btn layui-btn-xs"  lay-event="reviseGMPlayedBtn">修改</div>
        {{#  if(d.play_status == 1){ }}
        <div class="stop layui-btn layui-btn-xs"  lay-event="changePlayedBtn" lay-submit="" title="暂停">暂停</div>
        {{#  } }}
        {{#  if(d.play_status == 2){ }}
        <div class="layui-btn layui-btn-xs" lay-event="changePlayedBtn" lay-submit="" title="播放">播放</div>
        {{#  } }}
        <div class="layui-btn layui-btn-xs layui-btn-danger" title="删除" lay-event="delPlayedBtn">删除</div>
        <div class="reviseRecordIcon record1 hiddenItem layui-btn layui-btn-xs " lay-event="detailsGMPlayedBtn" title="修改记录">修改记录</div>
    </div>
</script>

<script>
    layui.use(['table','layer','laydate','form'],function () {
        var laydate = layui.laydate;
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var $ = layui.$;
        //记录当前点击的是摆明到还是黑名单1.白，2.黑
        var blackwhite= 1;

        //玩家消息/GM列表切换事件
        $('#title li').on('click',function () {
            $(this).addClass('selectedLi');
            $(this).siblings().removeClass('selectedLi');
            if($(this)[0].innerHTML ==='玩家信息'){
                $('#item2').addClass('hiddenItem');
                $('#item1').removeClass('hiddenItem');
            }else{
                $('#item1').addClass('hiddenItem');
                $('#item2').removeClass('hiddenItem');
            }
        });

        var active={
            //黑名单弹出层
            blackBtn:function () {
                layer.open({
                    type:1
                    ,title:"黑名单"
                    ,closeBtn:1
                    ,area:['80%','85%']
                    ,btnAlign:'c'
                    ,anim:3
                    ,maxmin:true
                    ,content:$('#whiteListLayer')
                    ,success:function (layero,index) {
                        blackwhite = 2;
                        table.render({
                            elem:"#whiteList"
                            ,url:"/marquee/white-black"
                            ,page:true
                            ,where:{
                                id:$("[name='whiteID']").val(),
                                sign:2
                            }
                            ,cols:[[
                                {field:"account",title:'ID',sort:true,align:"center"}
                                ,{field:"weixin_nickname",title:'昵称',align:"center"}
                                ,{field:"created_time",title:'创建时间',align:"center"}
                                ,{field:"content",title:'最近发言内容',align:"center"}
                                ,{field:'',title:'移除',width:280,toolbar:'#whiteDel'}
                            ]]
                            ,done:function (res, curr, count) {
                                var trs = $('#whiteList').next().find('tbody tr');
                                for(var j=0;j<trs.length;j++){
                                    var i = document.createElement('i');
                                    i.innerHTML="☆";
                                    $(i).css({"float":"left","color":"#F581B1","font-size":16});
                                    $(trs[j]).children('td:first').children('div').prepend(i);
                                }
                            }
                        })
                        //移除白名单
                        table.on('tool(whiteList)',function (data) {
                            var layEvent = data.event;
                            var data = data.data;
                            if (layEvent == 'remove') {
                                layer.open({
                                    type:1
                                    ,title:false
                                    ,closeBtn:1
                                    ,anim:3
                                    ,maxmin:true
                                    ,area:['30%','25%']
                                    ,id:'LAY_layuipro'
                                    ,btn:['确认','取消']
                                    ,btnAlign:'c'
                                    ,moveType:1
                                    ,content:$('#removeList')
                                    ,success:function (layero,index) {
                                        //将删除的跑马灯编号显示在提示窗口
                                        $('#remove_index').html(data.player_index);
                                    }
                                    ,yes:function (index,layero) {
                                        $.ajax({
                                            url:'/marquee/remove-list',
                                            data:{
                                                id:data.player_index,
                                                sign:2
                                            },
                                            type:"POST",
                                            success:function (res) {
                                                res = eval('('+res+')');
                                                if (res.code == 200) {
                                                    table.reload('whiteList',{
                                                        url:"/marquee/white-black"
                                                        ,page:true
                                                        ,where:{
                                                            id:$("[name='whiteID']").val(),
                                                            sign:2
                                                        }
                                                    })
                                                } else {
                                                    layer.msg('删除失败',{time:1000});
                                                }
                                            }
                                        })
                                        layer.close(index);
                                    }
                                })
                            }

                        })
                    }
                });
            },
            //白名单弹出层
            whiteBtn:function () {
                layer.open({
                    type:1
                    ,title:"白名单"
                    ,closeBtn:1
                    ,area:['80%','85%']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,anim:3
                    ,maxmin:true
                    ,content:$('#whiteListLayer')
                    ,success:function (layero,index) {
                        blackwhite = 1;
                        table.render({
                            elem:"#whiteList"
                            ,url:"/marquee/white-black"
                            ,page:true
                            ,where:{
                                id:$("[name='whiteID']").val(),
                                sign:1
                            }
                            ,cols:[[
                                {field:"u_id",title:'ID',sort:true,align:"center"}
                                ,{field:"weixin_nickname",title:'昵称',align:"center"}
                                ,{field:"created_time",title:'创建时间',align:"center"}
                                ,{field:"content",title:'最近发言内容',align:"center"}
                                ,{field:'',title:'移除',width:280,toolbar:'#whiteDel'}
                            ]]
                            ,done:function (res, curr, count) {
                                var trs = $('#whiteList').next().find('tbody tr');
                                for(var j=0;j<trs.length;j++){
                                    var i = document.createElement('i');
                                    i.innerHTML="☆";
                                    $(i).css({"float":"left","color":"#F581B1","font-size":16});
                                    $(trs[j]).children('td:first').children('div').prepend(i);
                                }
                            }
                        })
                        //移除白名单
                        table.on('tool(whiteList)',function (data) {
                            var layEvent = data.event;
                            var data = data.data;
                            if (layEvent == 'remove') {
                                layer.open({
                                    type:1
                                    ,title:false
                                    ,closeBtn:1

                                    ,area:['30%','25%']
                                    ,id:'LAY_layuipro'
                                    ,btn:['确认','取消']
                                    ,btnAlign:'c'
                                    ,moveType:1
                                    ,content:$('#removeList')
                                    ,success:function (layero,index) {
                                        //将删除的跑马灯编号显示在提示窗口
                                        $('#remove_index').html(data.player_index);
                                    }
                                    ,yes:function (index,layero) {
                                        $.ajax({
                                            url:'/marquee/remove-list',
                                            data:{
                                                id:data.player_index,
                                                sign:1
                                            },
                                            type:"POST",
                                            success:function (res) {
                                                res = eval('('+res+')');
                                                if (res.code == 200) {
                                                    table.reload('whiteList',{
                                                        url:"/marquee/white-black"
                                                        ,page:true
                                                        ,where:{
                                                            id:$("[name='whiteID']").val(),
                                                            sign:1
                                                        }
                                                    })
                                                } else {
                                                    layer.msg('删除失败',{time:1000});
                                                }
                                            }
                                        })
                                        layer.close(index);
                                    }
                                })
                            }

                        })
                    }
                });
            },
            //创建跑马灯弹出层
            createGM:function () {
                layer.open({
                    type:1
                    ,title:"修改记录"
                    ,closeBtn:1
                    ,anim:3
                    ,maxmin:true
                    ,area:['90%','80%']
                    ,id:'LAY_layuipro'
                    // ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#createGM')
                    ,success:function (layero,index) {
                    }
                });
            }
        };
        $('#blackBtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        $('#whiteBtn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        /*创建跑马灯*/
        $('#create').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

        //添加白名单
        form.on('submit(whiteadd)',function (data) {
            if (blackwhite == 1) {
                var add_sign = 1;
            } else {
                var add_sign = 2;
            }
            $.ajax({
                url:'/marquee/operate-list',
                data:{
                    id:data.field.whiteID,
                    sign:add_sign
                },
                type:"POST",
                success:function (res) {
                    res = eval('('+res+')');
                    if (res.code == 200) {
                        table.reload('whiteList',{
                            url:"/marquee/white-black"
                            ,page:true
                            ,where:{
                                id:$("[name='whiteID']").val(),
                                sign:add_sign
                            }
                        })
                    } else if (res.code == -50) {
                        layer.msg(res.msg,{time:1000});
                    } else {
                        layer.msg('添加失败',{time:1000});
                    }
                }
            })
        })


        laydate.render({elem:'#startTime',type: 'datetime'});
        laydate.render({elem:'#endTime',type: 'datetime'});

        table.render({
            elem:"#playerInfo"
            ,url:"/marquee/player-marquee"
            ,page:true
            ,type:'GET'
            ,where:{
                id:$('#ID').val(),
                start_time:$('#startTime').val(),
                end_time:$('#endTime').val()
            }
            ,cols:[[
                {type:"numbers",title:'序号',sort:true,align:"center",templet: '#white'}
                ,{field:"account",title:'ID',align:"center"}
                ,{field:"weixin_nickname",title:'昵称',align:"center"}
                ,{field:"content",title:'发送内容',width:800,align:"center"}
                ,{field:"created_time",title:'创建时间',align:"center"}
            ]],
            done:function (res, curr, count) {
                console.log(res);
                var trs = $('#playerInfo').next().find('tbody tr');
                for(var j=0;j<trs.length;j++){
                    var i = document.createElement('i');
                    i.innerHTML="&nbsp;&nbsp;"
                    $(i).css({"float":"left","color":"#F581B1","font-size":16})
                    $(trs[j]).children('td:first').children('div').prepend(i);
                    if (res.data[j].white == '1'){
                        i.innerHTML="☆";
                    }
                }
            }
        });
        table.on("sort(playerInfo)",function (obj) {
            table.reload('playerInfo', {
                url:'/test/t205',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        //玩家信息查询
        $('#search').on('click',function(){
            table.reload('playerInfo',{
                url:"/marquee/player-marquee"
                ,page:true
                ,type:'GET'
                ,where:{
                    id:$('#ID').val(),
                    start_time:$('#startTime').val(),
                    end_time:$('#endTime').val()
                }
            });
        })

        //修改记录
        function logGm(data){
            var id = data.id;
            var new_data = {is_notice:0};
            layer.open({
                type:1
                ,title:"修改记录"
                ,closeBtn:1
                ,anim:3
                ,maxmin:true
                ,area:['90%','85%']
                ,id:'LAY_layuipro'
                // ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#GMDetailsAll')
                ,success:function (layero,index) {
                    table.reload('record', {
                        url:'/marquee/gm-edit-log',
                        where:{id:data.id}
                    });
                }
            });
            $('#log-search').on('click',function(){
                new_data.start_time = $('#startTime3').val();
                new_data.end_time = $('#terminalTime3').val();
                new_data.keyword = $('#search-content').val();
                table.reload('record', {
                    url:'/marquee/gm-edit-log',
                    where:new_data
                });
            });
        }

        //修改
        function editGm(obj){
            var marquee_id = obj.data.id;
            var new_data = '';
            layer.open({
                type:1
                ,title:"修改跑马灯"
                ,closeBtn:1
                ,anim:3
                ,maxmin:true
                ,area:['80%','85%']
                ,id:'LAY_layuipro'
                ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#reviseGM')
                ,success:function (layero,index) {
                    table.render({
                        elem:"#reviseTable"
                        ,url:"/marquee/gm-detail"
                        ,where:{id:marquee_id}
                        ,cols:[[
                            {type:"numbers",title:'序号'}
                            ,{field:"id",title:'ID',sort:true}
                            ,{field:"account",title:'操作账号'}
                            ,{field:"created_time",title:'创建时间'}
                            ,{field:"content",title:'发送内容',edit: 'text'}
                            ,{field:"start_time",title:'开始时间',edit: 'text'}
                            ,{field:"end_time",title:'结束时间',edit: 'text'}
                            ,{field:"is_notice",title:'公告',templet: '#switchTpl', unresize: true}
                        ]]
                        ,done:function (res, curr, count) {
                            var trs = $('#reviseTable').next().find('tbody tr');
                            for(var j=0;j<trs.length;j++){
                                var a= res.data[j].public1;
                                if (res.data[j].is_notice == 1){
                                    $(trs[j]).find('.publicRadio').next().addClass('layui-form-onswitch');
                                    $(trs[j]).find('.publicRadio').next().children('em').html("是");
                                }
                            }
                            new_data = res.data[0];
                        }
                    })
                    //监听表格修改
                    table.on('edit(reviseTable)',function (obj) {
                        new_data = obj.data;
                    });
                    form.on('switch(sexDemo)', function(obj){
                        if (obj.elem.checked) {
                            new_data.is_notice = 1;
                        } else {
                            new_data.is_notice = 0;
                        };
                        layer.tips( this.name + '：'+ obj.elem.checked, obj.othis);
                    });
                }
                ,yes:function (index,layero) {

                    var checked;
                    $("#check").next().hasClass("layui-form-checked")?checked = 1:checked = 0;
                    $.ajax({
                        url:'/marquee/gm-create',
                        type:"POST",
                        data:{
                            'content':new_data.content,
                            'start_time':new_data.start_time,
                            'end_time':new_data.end_time,
                            'is_notice':new_data.is_notice,
                            'id':marquee_id
                        }
                        ,success:function (data) {
                            console.log("成功");
                            layer.close(index);
                            table.reload('allGMList', {
                                url:'/marquee/gm-marquee',
                            });
                            table.reload('notPlayedGMList', {
                                url:'/marquee/gm-marquee',
                                where:{is_play:0}
                            });
                            table.reload('playedGMList', {
                                url:'/marquee/gm-marquee',
                                where:{is_play:1}
                            });
                        }
                        ,error:function () {
                            console.log("失败");
                        }
                    });
                    layer.close(index);
                }
            })
        }

        //暂停和播放
        function playPause(obj,my_this){
            //获取点击按钮对于的tr数据的唯一值
            var id = obj.data.id;
            //获取点击的按钮
            var $This = my_this;
            //声明变量保存
            var turn;
            console.log($This);
            // 判断是开启还是暂停
            $This.hasClass("stop")?turn = 2:turn = 1;
            //向后端发送数据并修改按钮状态
            $.ajax({
                type: 'post'
                , data: {
                    'id': id
                    ,'play_status': turn
                }
                , url: '/marquee/gm-play-pause'
                , success: function (data) {
                    console.log(turn);
                    if (turn == 2) {
                        $This.removeClass('stop');
                        // $This.removeClass('layui-btn-warm');
                        // $This.addClass('layui-btn-normal');
                        // $This.addClass('start');
                        $This.children("i").html('&#xe652;');
                        console.log($This)
                    } else {
                        // $This.removeClass('start');
                        $This.addClass('stop');
                        // $This.addClass('layui-btn-warm');
                        $This.children("i").html('&#xe651;');
                    }
                }
                ,error:function () {

                }
            });
        }

        //删除
        function deleteGm(obj){
            var number = obj.data.number;
            var id = obj.data.id;
            layer.open({
                type:1
                ,title:false
                ,closeBtn:1
                ,area:['30%','25%']
                ,id:'LAY_layuipro'
                ,btn:['确认','取消']
                ,btnAlign:'c'
                ,moveType:1
                ,content:$('#delGMAll')
                ,success:function (layero,index) {
                    //将删除的跑马灯编号显示在提示窗口
                    $('#num').html(id);
                }
                ,yes:function (index,layero) {
                    //向后端发送将要删除的跑马灯ID
                    $.ajax({
                        url:'/marquee/gm-delete',
                        type:'POST',
                        data:{
                            'id': id
                        },
                        success:function (data) {
                            console.log("成功");
                            //删除成功后重载表格
                            table.reload('allGMList', {
                                url:'/marquee/gm-marquee',
                            });
                            table.reload('notPlayedGMList', {
                                url:'/marquee/gm-marquee',
                                where:{is_play:0}
                            });
                            table.reload('playedGMList', {
                                url:'/marquee/gm-marquee',
                                where:{is_play:1}
                            });
                        },
                        error:function () {
                            console.log("失败");

                        }
                    });
                    layer.close(index);
                }
            })
        }

        /*全部*/
        //全部tab表格
        table.render({
            elem:"#allGMList"
            ,url:"/marquee/gm-marquee"
            ,page:true
            // ,id:"rechargeReload"
            ,cols:[[
                {type:"numbers",title:'序号',width:100}
                ,{field:"id",title:'ID',sort:true,width:120}
                ,{field:"account",title:'操作账号'}
                ,{field:"created_time",title:'创建时间'}
                ,{field:"content",title:'发送内容'}
                ,{field:"interval_time",title:'播放间隔',templet:function (d) {
                        return d.interval_time+'s';
                    }}
                ,{field:"is_notice",title:'公告',templet:function (d) {
                        if (d.is_notice == 1) {
                            return '是';
                        } else {
                            return '否';
                        }
                    }}
                ,{field:"start_time",title:'开始时间'}
                ,{field:"end_time",title:'结束时间'}
                ,{field:'',title:'操作',width:280,toolbar:'#barAllGMList',width:150}
            ]]
            ,done:function (res, curr, count) {
                var trs = $('#allGMList').next().find('tbody tr');
                for(var j=0;j<trs.length;j++){
                    if (res.data[j].is_notice == 1){
                        $(trs[j]).find('.record1').removeClass('hiddenItem');
                        $(trs[j]).children("td:eq(6)").children('div').css("color","#F581B1")
                    }
                    else{
                        $(trs[j]).children("td:eq(6)").children('div').css("color","#01AAED")
                    }
//                    if (res.data[j].is_play == 0){//未播显示按钮
                    $(trs[j]).find('.record2').removeClass('hiddenItem');
//                    }
                }
            }
        });
        //全部tab下的按钮弹出层
        table.on('tool(table1)', function(obj){
            var data = obj.data;

            if(obj.event === 'detailsGMAllBtn'){ //全部tab下的详情按钮弹出层
                logGm(data)
            } else if(obj.event === 'reviseGMAllBtn'){ //全部tab下的修改按钮弹出层
                editGm(obj);

            } else if(obj.event === 'changeAllBttonn'){ //全部页面下的开启/暂停切换按钮
                playPause(obj,$(this));
            }
            else if (obj.event === 'delAllBtn'){ //全部tab下的删除按钮弹出层
                deleteGm(obj);
            }
        });
        //全部tab表格排序
        table.on('sort(table1)', function(obj){
            table.reload('allGMList', {
                url:'/test/t205',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        /*未播*/
        //未播tab表格
        table.render({
            elem:"#notPlayedGMList"
            ,url:"/marquee/gm-marquee"
            ,page:true
            ,where:{
                is_play:0
            }
            ,cols:[[
                {type:"numbers",title:'序号'}
                ,{field:"id",title:'ID',sort:true}
                ,{field:"account",title:'操作账号'}
                ,{field:"created_time",title:'创建时间'}
                ,{field:"content",title:'发送内容'}
                ,{field:"interval_time",title:'播放间隔',templet:function (d) {
                        return d.interval_time+'s';
                    }}
                ,{field:"is_notice",title:'公告',templet:function (d) {
                        if (d.is_notice == 1) {
                            return '是';
                        } else {
                            return '否';
                        }
                    }}
                ,{field:"start_time",title:'开始时间'}
                ,{field:"end_time",title:'结束时间'}
                ,{field:'',title:'操作',width:280,toolbar:'#barNotPlayedGMList',width:230}
            ]]
            ,done:function (res, curr, count) {
                var trs = $('#notPlayedGMList').next().find('tbody tr');
                for(var j=0;j<trs.length;j++){
                    var a= res.data[j].public1;
                    if (res.data[j].is_notice == 1){
                        $(trs[j]).find('.record1').removeClass('hiddenItem')
                        $(trs[j]).children("td:eq(6)").children('div').css("color","#F581B1")
                    }
                    else{
                        $(trs[j]).children("td:eq(6)").children('div').css("color","#01AAED")
                    }
                }
            }
        });
        //未播tab下的按钮弹出层
        table.on('tool(table2)', function(obj){
            var data = obj.data;
            switch(obj.event){
                //详情按钮
                case 'detailsGMNotPlayBtn':
                    logGm(data);
                    break;
                //修改按钮
                case 'reviseGMNotPlayBtn':
                    editGm(obj);
                    break;
                //开启/暂停按钮
                case 'changeNotPlayBtn':
                    playPause(obj,$(this));
                    break;
                //删除按钮
                case 'delNotPlayBtn':
                    deleteGm(obj);
                    break;
            }
        });
        //未播tab表格排序
        table.on('sort(table2)', function(obj){
            table.reload('notPlayedGMList', {
                url:'/marquee/gm-marquee',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        /*已播*/
        //已播tab表格
        table.render({
            elem:"#playedGMList"
            ,url:"/marquee/gm-marquee"
            ,page:true
            ,where:{
                is_play:1
            }
            ,cols:[[
                {type:"numbers",title:'序号'}
                ,{field:"id",title:'ID' ,sort:true}
                ,{field:"account",title:'操作账号'}
                ,{field:"created_time",title:'创建时间'}
                ,{field:"content",title:'发送内容'}
                ,{field:"interval_time",title:'播放间隔',templet:function (d) {
                        return d.interval_time+'s';
                    }}
                ,{field:"is_notice",title:'公告',templet:function (d) {
                        if (d.is_notice == 1) {
                            return '是';
                        } else {
                            return '否';
                        }
                    }}
                ,{field:"start_time",title:'开始时间'}
                ,{field:"end_time",title:'结束时间'}
                ,{field:'',title:'操作',width:280,toolbar:'#barPlayedGMList',width:150}
            ]]
            ,done:function (res, curr, count) {
                var trs = $('#playedGMList').next().find('tbody tr');
                for(var j=0;j<trs.length;j++){
                    var a= res.data[j].public1;
                    if (res.data[j].is_notice == 1){
                        $(trs[j]).find('.record1').removeClass('hiddenItem')
                        $(trs[j]).children("td:eq(6)").children('div').css("color","#F581B1")
                    }
                    else{
                        $(trs[j]).children("td:eq(6)").children('div').css("color","#01AAED")
                    }
                }
            }
        });
        //已播tab下的按钮弹出层
        table.on('tool(table3)', function(obj){
            var data = obj.data;
            switch(obj.event){
                case 'detailsGMPlayedBtn':
                    logGm(data);
                    break;
                case 'reviseGMPlayedBtn':
                    editGm(obj);
                    break;
                case 'changePlayedBtn':
                    playPause(obj,$(this));
                    break;
                case 'delPlayedBtn':
                    deleteGm(obj);
                    break;
            }
        });
        //已播tab表格排序
        table.on('sort(table3)', function(obj){
            table.reload('playedGMList', {
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


<!--//白名单-->
<div style="display: none" id="whiteListLayer" class="x-body">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="输入添加ID" name="whiteID">
            </div>
            <div class="layui-btn" id="whiteadd" lay-filter="whiteadd" lay-submit><i class="layui-icon">&#xe654;</i>添至名单</div>
        </div>
    </form>
    <table lay-filter="whiteList" id="whiteList" class="layui-table"></table>
</div>
<script type="text/html" id="whiteDel">
    <button class="layui-btn layui-btn-danger layui-btn-sm" lay-event="remove">移除</button>
</script>

<!--弹出层显示的内容-->
<!--全部tab下的详情弹出层-->
<div class="x-body" id="GMDetailsAll" style="display: none;">

    <div>
        <form action="" class="layui-form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">内容检索</label>
                <div class="layui-input-inline" style="width:565px;">
                    <input type="text" class="layui-input" id="search-content">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label" >开始时间</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="startTime3" name="startTime2" placeholder="开始日期">
                </div>
                <label for="" class="layui-form-label" >结束时间</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="terminalTime3" name="terminalTime2"  placeholder="结束日期">
                </div>
                <!--<label for="" class="layui-form-label">公告</label>-->
                <!--<div class="layui-input-block">-->
                <!--<input type="checkbox" name="switch" lay-skin="switch" lay-text="ON|OFF" lay-filter="log-notice">-->
                <div class="layui-btn " style="margin-left:40px;" id="log-search"><i class="layui-icon">&#xe615</i></div>
                <!--</div>-->
            </div>
            <!--style="float:right;position: relative;right:300px;top:-100px;padding:0 30px;height:80px;line-height: 80px;font-size: 20px;"-->
        </form>
        <table class="layui-table" lay-data="{url:'', page: true}" id="record">
            <thead>
            <tr>
                <th lay-data="{align:'center',field:'marquee_id', width:50}" rowspan="2">GM-ID</th>
                <th lay-data="{align:'center',field:'content'}" rowspan="2">内容修改</th>
                <th lay-data="{align:'center'}" colspan="5">操作设置</th>
                <th lay-data="{align:'center',field:'account'}" rowspan="2">操作人</th>
                <th lay-data="{align:'center',field:'updated_time'}" rowspan="2">修改时间</th>
            </tr>
            <tr>
                <th lay-data="{align:'center',field:'operate_type'}">操作动作</th>
                <th lay-data="{align:'center',field:'start_time'}">开始时间</th>
                <th lay-data="{align:'center',field:'end_time'}">结束时间</th>
                <th lay-data="{align:'center',field:'interval_time'}" >播放间隔</th>
                <th lay-data="{align:'center',field:'is_notice'}">公告</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    layui.use(['laydate','form'],function () {
        var laydate = layui.laydate;
        var form = layui.form
        laydate.render({elem:'#startTime3'});
        laydate.render({elem:'#terminalTime3'});

    })
</script>

<!--全部tab下的修改按钮弹出层-->
<div class="x-body" id="reviseGM" style="display: none;">
    <table class="layui-table" id="reviseTable" lay-filter="reviseTable"></table>
</div>
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="公告" value="{{d.is_notice}}" class="publicRadio" lay-skin="switch" lay-text="是|否" lay-filter="sexDemo">
</script>
<script>
    layui.use('table',function () {
        var table=layui.table;
    })
</script>

<!--全部tab下的删除按钮弹出层-->
<div class="x-body" id="delGMAll"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除ID为<span id="num"></span>的跑马灯吗？</h2>
</div>
<div class="x-body" id="removeList"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认移除玩家<span id="remove_index"></span>？</h2>
</div>

<!--创建跑马灯-->
<div class="x-body" id="createGM" style="display: none">
    <form action="" class="layui-form">
        <div class="layui-form-item" style="margin-left:30px!important;">
            <label for="" class="layui-form-label" >开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="startTime2" name="startTime2" lay-verify="required">
            </div>
            <label for="" class="layui-form-label" >结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="terminalTime2" name="terminalTime2" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" >内容</label>
            <div class="layui-input-inline">
                <textarea  id="" cols="80" rows="10"  style="resize:none;" placeholder="50个中文字符，避免出现敏感字符" name="content" lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item" style="margin-left:67px!important;" >
            <input type="checkbox" title="以公告的形式" lay-skin="primary" lay-filter="check" id="check2">
        </div>

        <div class="layui-form-item" style="width:100%;">
            <div style="position: absolute;left:40%;margin-bottom: 15px;">
                <div class="layui-btn" lay-submit="" lay-filter="submit">确认</div>
                <div class="layui-btn" type="reset">重置</div>
            </div>
        </div>
    </form>
</div>
<script>
    layui.use(['laydate','form'],function () {
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime2',type:'datetime'});
        laydate.render({elem:'#terminalTime2',type:'datetime'});
        var form = layui.form;
        var AnnouncementForm;
        form.on('submit(submit)',function (data) {
            $('#check2').next().hasClass("layui-form-checked")? AnnouncementForm=1: AnnouncementForm=0;
            $.ajax({
                type:"POST"
                ,url:'/marquee/gm-create'
                ,data:{
                    'content':data.field.content,
                    'start_time':data.field.startTime2,
                    'end_time':data.field.terminalTime2,
                    'is_notice':AnnouncementForm
                }
                ,success:function () {
                    window.location.href = '/test/pmdlist';
                }
                ,error:function () {
                    console.log("失败");
                }
            })
        });
    })


</script>