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
        <a href="#">GM工具</a>
        <a href="#">活动</a>
        <a>
            <cite>每日活动</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
    <div class="topOption titleFormStyle">

        <div class="rightBtn">

            <button class="layui-btn" data-method="add" id="add">新增活动</button>
            <a href="/game-set/activity-history-index" class="layui-btn"  id="history">历史活动库</a>
            <button class="layui-btn" data-method="sort" id="sort">排序</button>
            <div class="sortBtn">
                <button class="layui-btn " id="subSort">确定</button>
                <button class="layui-btn layui-btn-danger " id="cancel">取消</button>
            </div>
        </div>


    </div>
    <table class="layui-table" id="activityTable" lay-filter="activityTable"></table>
    <br>
    <hr>
    <br>
    <table class="layui-table" id="record" lay-filter="record"></table>
</div>
<script type="text/html" id="barActivity">
    <div id="layerDemo">
        <div class="layui-btn layui-btn-xs" lay-event="revise" title="修改">修改</div>
        <div class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del" title="移除">移除</div>
    </div>
</script>
<script>
    layui.use(['table','layer','laydate','upload'],function () {
        var $=layui.jquery,layer=layui.layer;

        var laydate = layui.laydate,layedit = layui.layedit,upload = layui.upload;
        laydate.render({elem:'#startDate',type:'datetime'});
        laydate.render({elem:'#endDate',type:'datetime'});

        var table = layui.table;
        table.render({
            elem:"#activityTable"
            ,url:"/game-set/activity-list"
            ,page:true
            ,cols:[[
                {type:'numbers',title:"序号",width:100,class:'my_sort'}
                ,{field:'title',title:"活动标题"}
                ,{field:'activity_name',title:"标签名称"}
                ,{field:'start_time',title:"开始时间"}
                ,{field:'end_time',title:"结束时间"}
                ,{field:'jump_url',title:"跳转"}
                ,{field: 'type', title: "活动类型"}
                ,{field:'id',title:"ID",style:"display:none;",width:0}
                ,{field:'sort',title:"排序",style:"display:none;",width:0}
                ,{field:'',title:"相关操作",toolbar:'#barActivity',width:100}
            ]]
        });
        $("[data-field='id']").css('display','none');
        $("[data-field='sort']").css('display','none');


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
            data_sort_value.sort(sortNumber)
            $.ajax({
                url:'/game-set/activity-sort',
                type:'POST',
                data:{
                    ids:data_id_value,
                    sorts:data_sort_value
                },
                success:function (res) {
                    console.log(res);
                }
            });
        });

        if (typeof game_jump == "undefined") {
            var game_jump = {};
        }
        if (typeof my_jump == "undefined") {
            var my_jump = [];
        }

        var active = {
            add:function(){
                layer.open({
                    type:1
                    ,title:'新增'
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['50%','70%']
                    //,shade:0.5
                    ,id:"LAY_layuipro"
                    // ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#addActive')
                    ,success:function () {
                        game_jump = {};
                        my_jump = [];
                        $("[type='reset']").click();
                        $('#demo1').attr('src','');
                        $('#demo1').attr('style','');
                        $('#demo2').attr('src','');
                        $('#demo2').attr('style','');
                        $('#jump_type').next().find('.layui-anim').children('dd[lay-value="1"]').click();
                        $('#addActive').attr('model_id',0);
                    }
                })
            }
            ,sort:function () {
                changeBtn('block','none')
                addDom();
                $("#cancel").click(function () {
                    changeBtn('none','block');
                    removeDom();
                    table.reload("activityTable",{url:'/game-set/activity-list'});
                    $("[data-field='id']").css('display','none');
                    $("[data-field='sort']").css('display','none');
                })
                $(".laytable-cell-numbers:not(:first) i1").click(function () {
                    moveRow(1,$(this));
                })
                $(".laytable-cell-numbers:not(:first) i2").click(function () {
                    moveRow(-1,$(this));
                })
            }
        };


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

        //新增


        var form = layui.form;
        //创建一个编辑器
        //var editIndex = layedit.build('LAY_demo_editor');
        form.on('submit(addActive)',function (data) {
            if ($('#jump_type').val() == 2) {
                $('#jump_url').val(my_jump.join('_'));
                //console.log(my_jump);
                //console.log(my_jump.join('_'));
            }
            var data = {
                'title':$('#title').val()
                ,'start_time':$('#startDate').val()
                ,'end_time':$('#endDate').val()
                ,'title_url':$('#demo1').attr('src')
                ,'img_url':$('#demo2').attr('src')
                ,'goods_id':$('#goods_id').val()
                ,'goods_num':$('#goods_num').val()
                ,'jump_type':$('#jump_type').val()
                ,'jump_url':$('#jump_url').val()
                ,'activity_name':$('#activity_name').val()
            };
            if (data.title.length <= 0) {
                return layer.msg('标题必填',{time:1000});
            }
            if (data.start_time.length <= 0) {
                return layer.msg('开始时间必填',{time:1000});
            }
            if (data.end_time.length <= 0) {
                return layer.msg('结束时间必填',{time:1000});
            }
            console.log($('#addActive').attr('model_id'));
            if (typeof $('#addActive').attr('model_id') != "undefined" && $('#addActive').attr('model_id')!=0) {
                data.id = $('#addActive').attr('model_id');
                console.log(data);
                layer.confirm('是否确定对当前活动进行修改?', {icon: 3, title:'提示'}, function(index){
                    $.ajax({
                        type:'POST'
                        ,data:data
                        ,url:"/game-set/activity-set"
                        ,success:function (data) {
                            layer.closeAll();
                            table.reload("activityTable",{url:'/game-set/activity-list'});
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
                    ,url:"/game-set/activity-set"
                    ,success:function (data) {
                        layer.closeAll();
                        table.reload("activityTable",{url:'/game-set/activity-list'});
                    }
                    ,error:function (data) {
                        console.log("失败");
                    }
                })
            }


        })
        function getJump(type = 0){//获取跳转信息
            $.ajax({
                url:'/game-set/activity-jump',
                type:'GET',
                success:function (res) {
                    if (res.code == 0) {
                        game_jump = res.data;
                        if (type != 1) {//创建页面展示
                            my_jump = [];
                        }
                        var html = '<div class="layui-input-inline" id="level_1"><select lay-filter="level_1">';
                        if (type != 1) {//创建页面展示
                            html += '<option value="" selected>请选择</option>'
                            for (var i=0;i<game_jump.length;i++) {
                                html += '<option value="'+game_jump[i].id+'" >'+game_jump[i].remark+'</option>'

                            }
                        } else {
                            for (var i=0;i<game_jump.length;i++) {
                                if (game_jump[i].id == my_jump[0]) {
                                    html += '<option value="'+game_jump[i].id+'" selected>'+game_jump[i].remark+'</option>'
                                } else {
                                    html += '<option value="'+game_jump[i].id+'">'+game_jump[i].remark+'</option>'
                                }
                            }
                        }

                        html += '</select></div>';
                        $('#level_1').after('').remove();

                        $('#input1').after(html);
                        form.render('select');

                        form.on('select(level_1)',function (data) {
                            if (type == 1) {//修改页面
                                my_jump[0] = data.value;
                                for (var i = 0; i < game_jump.length; i++) {
                                    if (game_jump[i].id == data.value) {
                                        gameJumpSelect(i,1,1);
                                    }
                                }
                            } else {
                                my_jump = [];
                                my_jump[0] = data.value;
                                for (var i = 0; i < game_jump.length; i++) {
                                    if (game_jump[i].id == data.value) {
                                        gameJumpSelect(i,1);
                                    }
                                }
                            }

                        });
                        if (type == 1) {
                            $('#level_1 select').next().find('.layui-anim').children('dd[lay-value="'+my_jump[0]+'"]').click();
                        }
                    }
                }
            });
        }
        //追加select元素
        function gameJumpSelect(data,id,type=0){
            $('#level_'+(id+1)).after('').remove();
            if (typeof game_jump[data].child != "undefined") {
                var html ='<div class="layui-input-inline" id="level_'+(id+1)+'"><select  lay-filter="level_'+(id+1)+'">';
                html+='<option value="" >请选择</option>';

                for(var i = 0;i<game_jump[data].child.length;i++) {
                    html+='<option value="'+game_jump[data].child[i].id+'" >'+game_jump[data].child[i].remark+'</option>';
                }
                html +='</select></div>';
                $('#level_'+id).after(html);
                form.render();
                form.on('select(level_'+(id+1)+')',function (data) {
                    my_jump[id] = data.value;
                    //console.log(my_jump);
                });

            }
            if (type == 1) {
                $('#level_2 select').next().find('.layui-anim').children('dd[lay-value="'+my_jump[id]+'"]').click();
                console.log(my_jump);
            }
        }
        form.on('select(jump_type)',function (data) {
            $('#jump_url').val('');
            if (data.value == 1){//外部跳转
                //$('#leve1').css('display','none');
                //$('#leve2').css('display','none');
                $('#input1').css('display','')
                $('#level_1').after().remove();
                $('#level_2').after().remove();
                $('#level_3').after().remove();
                $('#level_4').after().remove();
                $('#level_5').after().remove();
                $('#level_6').after().remove();
            }else if (data.value == 2) {
                $('#input1').css('display','none');
                getJump();
                //$('#input1').after();
                //$('#leve1').css('display','block');
                //$('#leve2').css('display','block');
            } else {

            }
        })



        table.on('tool(activityTable)', function(obj){
            var data = obj.data;
            if(obj.event === 'revise'){
                layer.open({
                    type:1
                    ,title:"修改"
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['80%','85%']
                    ,id:'LAY_layuipro'
                    // ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#addActive')
                    ,success:function (layero,index) {
                        $('#addActive').attr('model_id',data.id);
                        $('#title').val(data.title);

                        laydate.render({elem:'#startDate',type:'datetime',value:data.start_time});
                        laydate.render({elem:'#endDate',type:'datetime',value:data.end_time});
                        $('#demo1').attr('src','');
                        $('#demo2').attr('src','');
                        $('#demo1').attr('style','');
                        $('#demo2').attr('style','');

                        if (typeof data.title_url != "undefined") {
                            if (data.title_url.length>0) {
                                $('#demo1').attr('src',data.title_url);
                                $('#demo1').attr('style','width:84px;height:84px;');
                            }
                        }
                        if (typeof data.img_url != "undefined") {
                            if (data.img_url.length>0) {
                                $('#demo2').attr('src',data.img_url);
                                $('#demo2').attr('style','width:84px;height:84px;');
                            }
                        }
                        $('#goods_id').val(data.goods_id);
                        $('#goods_num').val(data.goods_num);
                        $('#jump_type').val(data.jump_type);
                        $('#jump_url').val(data.jump_url);
                        $('#activity_name').val(data.activity_name);

                        if (data.jump_type == 1) {//外部跳转
                            $('#jump_type').html('<option value="1" selected>外部跳转</option><option value="2" >内部跳转</option><option value="3">webview</option>');

//                            $('#jump_type').next().find('.layui-anim').children('dd[lay-value="1"]').click();
                            $('#jump_url').val(data.jump_url);
                        } else if (data.jump_type == 2) {//内部跳转
                            my_jump = data.jump_url.split('_');
                            //console.log(my_jump);
                            $('#jump_type').html('<option value="1">外部跳转</option><option value="2" selected>内部跳转</option><option value="3">webview</option>');
                            form.render();
                            $('#jump_type').val(2);
                            $('#input1').css('display','none');
                            //getJump();
                            //$('#input1').after();
                            $('#level_1').css('display','block');
                            $('#level_2').css('display','block');
                            getJump(1);
                        }




                        if (data.jump_type == 2) {//内部跳转
                            $('#input1').attr('style','display:none;');
                            my_jump = data.jump_url.split('_')
                            //console.log(data.jump_url);
                            //console.log(my_jump);
                            getJump(1);
                            //console.log(my_jump);
                            //console.log(my_jump[0]);
                            //console.log($('#level_1 dl dd')[1]);
                            //form.render();
                            /*console.log($('#level_1 select option').length);
                            for (var i = 0; i < $('#level_1 select option').length; i++) {
                                console.log(('#level_1 select option')[i])

                                if ($('#level_1 select option')[i].val() == my_jump[0]) {
                                    $('#level_1 select option')[i].attr("selected","selected");
                                    form.render();
                                }
                            }*/
                        } else {
                            $('#input1').css('display','')
                            $('#level_1').after().remove();
                            $('#level_2').after().remove();
                            $('#level_3').after().remove();
                            $('#level_4').after().remove();
                            $('#level_5').after().remove();
                            $('#level_6').after().remove();
                        }

                    }
                })
            }
            else if (obj.event === 'del'){
                var number = obj.data.number;
                var id = obj.data.id;
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
                    ,success:function (layero,index) {
                        $('#num').html(id);
                    }
                    ,yes:function (index,layero) {
                        $.ajax({
                            url:'/game-set/activity-del',
                            type:'POST',
                            data:{
                                'id': id
                            },
                            success:function (data) {
                                table.reload("activityTable",{url:'/game-set/activity-list'});
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



        table.on('sort(activityTable)', function(obj){
            table.reload('activityTable', {
                url:'/test/t205',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });
        table.render({
            elem:"#record"
            ,url:"/game-set/broadcast-index"
            ,page:true
            ,cols:[[
                {type:'numbers',title:"操作时间",align:"center",width:120}
                ,{field:'img_url',title:"操作人",align:"center"}
                ,{field:'jump_url',title:"操作内容",align:"center"}
            ]]
        });
    })
</script>
</body>

<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除ID为<span id="num"></span>的活动信息吗？</h2>
</div>

<div class="x-body" style="display: none" id="addActive">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">活动标题</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input"  lay-verify="required" name="title" id="title">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">标签名称</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="activity_name" id="activity_name">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="startDate" name="date">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="endDate" name="date">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">上传标签图片</label>
            <!--<div class="layui-input-inline">-->
            <!--<input type="file" name="fileUpload">-->
            <!--</div>-->
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test1">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo1">
                    <p id="demoText2"></p>
                </div>
            </div>

        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">上传内容图片</label>
            <!--<div class="layui-input-inline">-->
            <!--<input type="file" name="fileUpload">-->
            <!--</div>-->
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test2">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo2">
                    <p id="demoText"></p>
                </div>
            </div>

        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">是否跳转</label>
            <div class="layui-input-inline">
                <select name="jump" id="jump_type" lay-filter="jump_type">
                    <option value="1">外部跳转</option>
                    <option value="2">内部跳转</option>
                    <option value="3">webview</option>
                </select>
            </div>
            <div class="layui-input-inline" id="input1">
                <input type="text" class="layui-input" id="jump_url">
            </div>
            <!--<div class="layui-input-inline" style="display: none" id="leve1">
                <select name="" >
                    <option value="">1</option>
                </select>
            </div>
            <div class="layui-input-inline" style="display: none" id="leve2">
                <select name="" >
                    <option value="">1</option>
                </select>
            </div>-->

        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">奖励</label>
            <div class="layui-input-inline">
                <select name="jump" id="goods_id">
                    <option value="3">元宝</option>
<!--                    <option value="">钻石</option>-->
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="number" class="layui-input" id="goods_num">
            </div>
        </div>
        <!--<div class="layui-form-item">-->
        <!--<label for="" class="layui-form-label">跳转地址</label>-->
        <!--<div class="layui-input-inline">-->
        <!--<input type="text" class="layui-input" name="jumpLink">-->
        <!--</div>-->
        <!--</div>-->

        <div class="layui-form-item" style="width:100%;">
            <div style="position: absolute;left:40%;margin-bottom: 15px;">
                <div class="layui-btn" lay-submit="" lay-filter="addActive">确认</div>
                <button class="layui-btn" type="reset">重置</button>
            </div>
        </div>
    </form>
</div>
<script>
    layui.use(['laydate','form','layedit','upload','layer','table'],function () {
        var laydate = layui.laydate,layedit = layui.layedit,upload = layui.upload;
        var table = layui.table;
        //var form = layer.form;
        laydate.render({elem:'#startDate',type:'datetime'});
        laydate.render({elem:'#endDate',type:'datetime'});
        if (typeof game_jump == "undefined") {
            var game_jump = {};
        }
        if (typeof my_jump == "undefined") {
            var my_jump = [];
        }
        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '/game-set/activity-img-upload'
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $('#demo1').attr('style','height:84px;width:84px;');
                    $('#demo1').attr('src',res.data);
                } else {
                    return layer.msg('上传失败');
                }
            }
        });
        var uploadInst2 = upload.render({
            elem: '#test2'
            ,url: '/game-set/activity-img-upload'
            ,done: function(res){
                //如果上传失败
                if(res.code == 0){
                    $('#demo2').attr('style','height:84px;width:84px;');
                    $('#demo2').attr('src',res.data);
                } else {
                    return layer.msg('上传失败');
                }
                //上传成功
            }
        });


        var form = layui.form;
        // //创建一个编辑器
        // var editIndex = layedit.build('LAY_demo_editor');
        form.on('submit(addActive)',function (data) {

            if ($('#jump_type').val() == 2) {
                $('#jump_url').val(my_jump.join('_'));
                //console.log(my_jump);
                //console.log(my_jump.join('_'));
            }
            var data = {
                'title':$('#title').val()
                ,'start_time':$('#startDate').val()
                ,'end_time':$('#endDate').val()
                ,'title_url':$('#demo1').attr('src')
                ,'img_url':$('#demo2').attr('src')
                ,'goods_id':$('#goods_id').val()
                ,'goods_num':$('#goods_num').val()
                ,'jump_type':$('#jump_type').val()
                ,'jump_url':$('#jump_url').val()
                ,'activity_name':$('#activity_name').val()
            };
            if (data.title.length <= 0) {
                return layer.msg('标题必填',{time:1000});
            }
            if (data.start_time.length <= 0) {
                return layer.msg('开始时间必填',{time:1000});
            }
            if (data.end_time.length <= 0) {
                return layer.msg('结束时间必填',{time:1000});
            }
            if (typeof $('#addActive').attr('model_id') != "undefined" && $('#addActive').attr('model_id') != 0) {
                data.id = $('#addActive').attr('model_id');
                console.log(data);
                layer.confirm('是否确定对当前活动进行修改?', {icon: 3, title:'提示'}, function(index){
                    $.ajax({
                        type:'POST'
                        ,data:data
                        ,url:"/game-set/activity-set"
                        ,success:function (data) {
                            layer.closeAll();
                            table.reload("activityTable",{url:'/game-set/activity-list'});
                        }
                        ,error:function (data) {
                            console.log("失败");
                        }
                    })
                    layer.closeAll();
                });
            } else {
                $.ajax({
                    type:'POST'
                    ,data:data
                    ,url:"/game-set/activity-set"
                    ,success:function (data) {
                        layer.closeAll();
                        table.reload("activityTable",{url:'/game-set/activity-list'});
                    }
                    ,error:function (data) {
                        console.log("失败");
                    }
                })
            }


        })
        function getJump(){//获取跳转信息
            $.ajax({
                url:'/game-set/activity-jump',
                type:'GET',
                success:function (res) {
                    if (res.code == 0) {
                        game_jump = res.data;
                        my_jump = [];
                        var html = '<div class="layui-input-inline" id="level_1"><select lay-filter="level_1">';
                        html += '<option value="" selected>请选择</option>'
                        for (var i=0;i<game_jump.length;i++) {
                            if (i == 0) {
                                html += '<option value="'+game_jump[i].id+'" >'+game_jump[i].remark+'</option>'
                            } else {
                                html += '<option value="'+game_jump[i].id+'">'+game_jump[i].remark+'</option>'
                            }
                        }
                        html += '</select></div>';
                        $('#level_1').after('').remove();

                        $('#input1').after(html);
                        form.render();

                        form.on('select(level_1)',function (data) {
                            my_jump = [];
                            my_jump[0] = data.value;
                            for (var i = 0; i < game_jump.length; i++) {
                                if (game_jump[i].id == data.value) {
                                    gameJumpSelect(i,1);
                                }
                            }
                        });
                    }
                }
            });
        }
        //追加select元素
        function gameJumpSelect(data,id){
            $('#level_'+(id+1)).after('').remove();
            if (typeof game_jump[data].child != "undefined") {
                var html ='<div class="layui-input-inline" id="level_'+(id+1)+'"><select  lay-filter="level_'+(id+1)+'">';
                html+='<option value="" >请选择</option>';

                for(var i = 0;i<game_jump[data].child.length;i++) {
                    if (i == 0) {
                        html+='<option value="'+game_jump[data].child[i].id+'" >'+game_jump[data].child[i].remark+'</option>';
                    } else {
                        html+='<option value="'+game_jump[data].child[i].id+'">'+game_jump[data].child[i].remark+'</option>';
                    }
                }
                html +='</select></div>';
                $('#level_'+id).after(html);
                form.render()
                var id_ = id+1;
                form.on('select(level_'+id_+')',function (data) {
                    my_jump[id] = data.value;
                    //console.log(my_jump);
                });
            }
        }
        form.on('select(jump_type)',function (data) {
            $('#jump_url').val('');
            if (data.value == 1){//外部跳转
                //$('#leve1').css('display','none');
                //$('#leve2').css('display','none');
                $('#input1').css('display','')
                $('#level_1').after().remove();
                $('#level_2').after().remove();
                $('#level_3').after().remove();
                $('#level_4').after().remove();
                $('#level_5').after().remove();
                $('#level_6').after().remove();
            }else if (data.value == 2) {
                $('#input1').css('display','none');
                getJump();
                //$('#input1').after();
                //$('#leve1').css('display','block');
                //$('#leve2').css('display','block');
            } else {

            }
        })

    })

    //    历史信息库
</script>
<!--历史信息库-->
<div class="x-body" style="display: none" id="historyInfo">
    <table class="layui-table" id="historyTable" lay-filter="historyTable"></table>
</div>
<script type="text/html" id="barhistoryBtn">
    <a class="" lay-event="revise" title="查看"><i class="layui-icon">&#xe63c;</i></a>
</script>