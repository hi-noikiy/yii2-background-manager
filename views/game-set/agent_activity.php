<style>
    .topOption{width:100%;}
    .layui-form-label{width:120px;}
    .sortBtn{float:left;display: none;}
    .rightBtn{float:left;}
    .rightBtn button,.rightBtn a{float:left;}
    .rightBtn a{margin:0 10px;}
    .remove{cursor: pointer; width:10px;height:10px;}
    .remove i1{color:green;font-size:20px;}
</style>

<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a>
            <cite>代理后台活动</cite>
        </a>
    </span>
</div>
<div class="x-body">
    <div class="topOption titleFormStyle">

        <div class="rightBtn">
            <button class="layui-btn" data-method="add" id="add">新增活动</button>
            <button class="layui-btn" data-method="history"  id="history">历史活动库</button>
            <a href="/game-set/agent-activity" class="layui-btn layui-hide" data-method="activityList"  id="activityList">活动列表</a>
            <button class="layui-btn" data-method="sort" id="sort">排序</button>
            <div class="sortBtn">
                <button class="layui-btn " id="subSort">确定</button>
                <button class="layui-btn layui-btn-danger " id="cancel">取消</button>
            </div>
        </div>
    </div>
    <table class="layui-table" id="activityTable" lay-filter="activityTable"></table>
</div>
<script type="text/html" id="barActivity">
    <div id="layerDemo">
        <div class="layui-btn layui-btn-xs" lay-event="revise" title="修改">修改</div>
        <div class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" title="移除">移除</div>
    </div>
</script>
<script>
    layui.use(['table','layer','laydate','upload','form'],function () {
        var laydate = layui.laydate,layedit = layui.layedit,upload = layui.upload,table = layui.table,form = layui.form;

        laydate.render({elem:'#startDate',type:'datetime'});
        laydate.render({elem:'#endDate',type:'datetime'});
        laydate.render({elem:'#showTimeStart',type:'time'});
        laydate.render({elem:'#showTimeEnd',type:'time'});

        table.render({
            elem:"#activityTable"
            ,url:"/game-set/agent-activity"
            ,page:true
            ,method:'post'
            ,cols:[[
                {type:'numbers',title:"序号",width:100,class:'my_sort'}
                ,{field:'id',title:"ID",style:"display:none;",width:0}
                ,{field:'sort',title:"排序",style:"display:none;",width:0}
                ,{field:'showTypeName',title:"展示规则"}
                ,{field:'title',title:"活动标题"}
                ,{field:'content',title:"活动内容"}
                ,{field:'img_url',title:"活动地址图片"}
                ,{field:'start_time',title:"开始时间",width:180}
                ,{field:'end_time',title:"结束时间",width:180}
                ,{field:'show_time_start',title:"开始展示时间",width:180}
                ,{field:'show_time_end',title:"结束展示时间",width:180}
                ,{field:'',title:"相关操作",toolbar:'#barActivity',minWidth:100}
            ]]
        });
        $("[data-field='id']").css('display','none');
        $("[data-field='sort']").css('display','none');

        table.on('tool(activityTable)', function(obj){
            var data = obj.data;
            console.log(data);
            if(obj.event === 'revise'){
                resetParam();
                layer.open({
                    type:1
                    ,title:"修改"
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['80%','85%']
                    ,id:'LAY_layuipro'
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#addActive')
                    ,btn:['确认','取消']
                    ,success:function (layero,index) {
                        laydate.render({elem:'#startDate',type:'datetime',value:data.start_time});
                        laydate.render({elem:'#endDate',type:'datetime',value:data.end_time});
                        laydate.render({elem:'#showTimeStart',type:'time',value:data.show_time_start});
                        laydate.render({elem:'#showTimeEnd',type:'time',value:data.show_time_end});

                        console.log(data.img_url);
                        if (data.img_url) {
                            if (data.img_url.length > 0) {
                                $('#demo2').attr('src',data.img_url);
                                $('#demo2').attr('style','width:84px;height:84px;');
                            }
                        }

                        $('#content').val(data.content);
                        $('#title').val(data.title);
                        $('#sortData').val(data.sort);
                        $('#activityId').val(data.id);
                        $('#showType').val(data.show_type);

                        form.render();
                    }
                    ,yes:function (layero,index) {
                        saveData();
                    }
                })
            }else if (obj.event === 'del'){
                var id = obj.data.id;
                console.log(id);
                layer.open({
                    type:1
                    ,title:"刪除"
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['30%','25%']
                    ,id:'LAY_layuipro'
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,content:$('#del')
                    ,yes:function (index,layero) {
                        $.ajax({
                            url:'/game-set/agent-activity-del',
                            type:'POST',
                            data:{
                                'id': id
                            },
                            success:function (data) {
                                table.reload("activityTable",{url:'/game-set/agent-activity'});
                            },
                            error:function () {
                                console.log("失败");

                            }
                        });
                        layer.close(index);
                    }
                })
            }
        });

        /*排序事件*/
        //点击排序按钮后增加箭头元素
        function addDom(){
            var frag1 = document.createDocumentFragment();
            var i1 = document.createElement("i1");
            $(i1).addClass("layui-icon remove");
            $(i1).html("&#xe62f;&nbsp;&nbsp;&nbsp;");
            $(i1).css({"color":"green","fontSize":16,"fontWeight":900});
            frag1.appendChild(i1);
            var frag2 = document.createDocumentFragment();
            var i2 = document.createElement("i2");
            $(i2).addClass("layui-icon remove");
            $(i2).css({"color":"red","fontSize":16,"fontWeight":900});
            $(i2).html("&nbsp;&nbsp;&nbsp;&#xe601;");
            frag2.appendChild(i2);
            $(".laytable-cell-numbers:not(:first)").prepend(frag1);
            $(".laytable-cell-numbers:not(:first)").append(frag2);
        }
        //排序按钮和确认取消按钮切换
        function changeBtn(submit,sort){
            $('.sortBtn').css('display',submit);
            $('#sort').css('display',sort)
        }
        //点击箭头移动当前行 a>0上移，a<0下移
        function moveRow(a,This){
            var currentRow = This.parents("tr");
            if (a>0) {
                var contrastRow = This.parents("tr").prev("tr");
                currentRow.insertBefore(contrastRow);
            }else if(a<0){
                var contrastRow = This.parents("tr").next("tr");
                currentRow.insertAfter(contrastRow);
            }
        }
        //点击确认或取消按钮后移除箭头元素
        function removeDom(){
            $(".laytable-cell-numbers:not(:first) i1").remove();
            $(".laytable-cell-numbers:not(:first) i2").remove();
        }
        //排序确定按钮绑定事件
        $('#subSort').on('click',function () {
            var data_id = $("table tr [data-field='id']");
            var data_sort = $("table tr [data-field='sort']");
            var data_id_value = [];
            var data_sort_value = [];
            for (var i=1;i<data_id.length;i++) {
                data_id_value.push(data_id[i].getElementsByTagName('div')[0].innerHTML) ;
                data_sort_value.push(data_sort[i].getElementsByTagName('div')[0].innerHTML) ;
            }
            function sortNumber(a,b)
            {
                return a - b
            }
            data_sort_value.sort(sortNumber);
            $.ajax({
                url:'/game-set/agent-activity-sort',
                type:'POST',
                data:{
                    ids:data_id_value,
                    sorts:data_sort_value
                },
                success:function (res) {
                    console.log(res);
                    table.reload("activityTable",{url:'/game-set/agent-activity'});
                }
            });
        });

        //添加活动
        var active = {
            add:function(){
                resetParam();

                layer.open({
                    type:1
                    ,title:'新增'
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['50%','70%']
                    ,id:"LAY_layuipro"
                    ,btnAlign:'c'
                    ,moveType:1
                    ,btn:['确认','取消']
                    ,content:$('#addActive')
                    ,yes:function (layero,index) {
                        saveData();
                    }
                })
            },sort:function () {
                changeBtn('block','none');
                addDom();
                $("#cancel").click(function () {
                    changeBtn('none','block');
                    removeDom();
                    table.reload("activityTable",{url:'/game-set/agent-activity'});
                    $("[data-field='id']").css('display','none');
                    $("[data-field='sort']").css('display','none');
                });
                $(".laytable-cell-numbers:not(:first) i1").click(function () {
                    moveRow(1,$(this));
                });
                $(".laytable-cell-numbers:not(:first) i2").click(function () {
                    moveRow(-1,$(this));
                })
            }
            ,history:function () {
                $("#activityList").removeClass('layui-hide');
                $("#history").addClass('layui-hide');
                $("#historyInfo").removeClass('layui-hide');
                table.render({
                    elem:"#activityTable"
                    ,url:"/game-set/agent-activity-history"
                    ,page:true
                    ,method:'post'
                    ,cols:[[
                        {type:'numbers',title:"序号",width:100,class:'my_sort'}
                        ,{field:'id',title:"ID",style:"display:none;",width:0}
                        ,{field:'sort',title:"排序",style:"display:none;",width:0}
                        ,{field:'showTypeName',title:"展示规则"}
                        ,{field:'title',title:"活动标题"}
                        ,{field:'content',title:"活动内容"}
                        ,{field:'img_url',title:"活动地址图片"}
                        ,{field:'start_time',title:"开始时间",width:180}
                        ,{field:'end_time',title:"结束时间",width:180}
                        ,{field:'show_time_start',title:"开始展示时间",width:180}
                        ,{field:'show_time_end',title:"结束展示时间",width:180}
//                        ,{field:'',title:"相关操作",toolbar:'#barActivity',minWidth:100}
                    ]]
                });
                $("[data-field='id']").css('display','none');
                $("[data-field='sort']").css('display','none');
            }
            ,activityList:function () {
                $("#activityList").addClass('layui-hide');
                $("#history").removeClass('layui-hide');
                $("#historyInfo").addClass('layui-hide');
            }
        };

        //保存数据
        function saveData() {
            var activityId = $('#activityId').val();
            var data = {
                'id':activityId
                ,'title':$('#title').val()
                ,'sort':$('#sortData').val()
                ,'content':$('#content').val()
                ,'start_time':$('#startDate').val()
                ,'end_time':$('#endDate').val()
                ,'img_url':$('#demo2').attr('src')
                ,'show_type':$('#showType').val()
                ,'show_time_start':$('#showTimeStart').val()
                ,'show_time_end':$('#showTimeEnd').val()
            };

            if (data.start_time.length <= 0) {
                return layer.msg('开始时间必填',{time:1000});
            }
            if (data.end_time.length <= 0) {
                return layer.msg('结束时间必填',{time:1000});
            }
            if (activityId) {
                layer.confirm('是否确定对当前活动进行修改?', {icon: 3, title:'提示'}, function(index){
                    $.ajax({
                        type:'POST'
                        ,data:data
                        ,url:"/game-set/agent-activity-set"
                        ,success:function (data) {
                            layer.closeAll();
                            table.reload("activityTable",{url:'/game-set/agent-activity'});
                        }
                        ,error:function (data) {
                            console.log("失败");
                        }
                    })
                });
            } else {
                $.ajax({
                    type:'POST'
                    ,data:data
                    ,url:"/game-set/agent-activity-set"
                    ,success:function (obj) {
                        var data = eval('('+obj+')');
                        console.log(data);
                        if(data.code == 0){
                            layer.closeAll();
                            table.reload("activityTable",{url:'/game-set/agent-activity'});
                        }else{
                            alert(data.msg);
                        }
                    }
                    ,error:function (data) {
                        console.log("失败");
                    }
                })
            }
        }

        $('#layerDemo .layui-btn').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });
        $('#add').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });
        $('#sort').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });
        $('#history').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });
        $('#activityList').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });

        /*上传图片*/
        upload.render({
            elem: '#test2'
            ,url: '/game-set/activity-img-upload'
            ,data:{'path':'agent-activity'}
            ,done: function(res){
                console.log(res);
                //如果上传失败
                if(res.code == 0){
                    $('#title').val('');
                    $('#content').val('');
                    $('#demo2').attr('style','height:84px;width:84px;');
                    $('#demo2').attr('src',res.data);
                } else {
                    return layer.msg('上传失败');
                }
            }
        });

        function resetParam() {
            $('#demo2').attr('src','').attr('style','');
            $("#showTimeStart").val('');
            $("#showTimeEnd").val('');
            $('#title').val('');
            $('#content').val('');
            $('#startDate').val('');
            $('#endDate').val('');
            $('#showType').val(1);
            form.render();
        }
    })
</script>
</body>

<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除当前活动吗？</h2>
</div>

<div class="x-body" style="display: none" id="addActive">
    <form action="" class="layui-form">
        <input type="hidden" value="" id="activityId">
        <input type="hidden" value="" id="sortData">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">活动标题</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input"  lay-verify="required" name="title" id="title">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">活动内容</label>
            <div class="layui-input-inline">
                <textarea name="content" id="content" cols="30" rows="10"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="startDate" name="startDate">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="endDate" name="endDate">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">展示开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="showTimeStart" name="showTimeStart">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">展示结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="showTimeEnd" name="showTimeEnd">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">上传活动图片</label>
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test2">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo2">
                    <p id="demoText"></p>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">弹出类型</label>
            <div class="layui-input-inline">
                <select name="showType" id="showType">
                    <option value="1">一次</option>
                    <option value="2">每次</option>
                    <option value="3">时间段</option>
                </select>
            </div>
        </div>
    </form>
</div>
<!--历史信息库-->
<div class="x-body layui-hide" id="historyInfo">
    <table class="layui-table" id="historyTable" lay-filter="historyTable"></table>
</div>
<script type="text/html" id="barhistoryBtn">
    <a class="" lay-event="revise" title="查看"><i class="layui-icon">&#xe63c;</i></a>
</script>