<style>
    textarea{
        resize: none;
        border:1px solid #e6e6e6;
        height:38px;
        width:190px;
    }
</style>
<body >
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>轮播图设置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <form action="" class="layui-form titleFormStyle InputStyle">
        <div class="layui-form-item ">
            <label for="" class="layui-form-label">播放间隔：</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="interval" id="interval">
            </div>
            <span class="per">s</span>
            <label for="" class="layui-form-label">维护信息：</label>
            <div class="layui-input-inline" style="width:190px;">
                <textarea name="down_info" id="down_info" ></textarea>
            </div>
            <div class="layui-input-inline SwitchStyle" >
                <input type="checkbox" lay-filter="down_switch" lay-skin="switch" name="switch" id="switch" lay-text="开启|关闭">
            </div>
            <div class="layui-input-inline">
                <div class="layui-btn" id="edit_info" lay-submit="" lay-filter="edit_info">修改</div>
            </div>
        </div>
    </form>
    <hr>
    <br>
    <div class="layui-row titleFormStyle">
        <div class="lf ">
            <button class="layui-btn" data-method="add" id="add"><i class="layui-icon">&#xe61f;</i>新增</button>
        </div>
    </div>


    <table class="layui-table" id="moneyPrice"  lay-filter="table1"></table>
    <br>
    <hr>
    <br>
    <table id="record" lay-filter="record" class="layui-table"></table>
</div>
</body>
<script type="text/html" id="barmoneyPrice">
    <div id="layerDemo">
        <div title="修改" class="layui-btn layui-btn-xs" lay-event="revise">修改</div>
        <div class="layui-btn layui-btn-xs layui-btn-danger"  lay-event="del" title="删除">删除</div>
    </div>
</script>
<script>
    layui.use(['table','layer','form','upload'],function () {
        var table = layui.table;
        var $=layui.jquery,layer=layui.layer;
        var form = layui.form;
        var upload = layui.upload;
        if (typeof my_jump == "undefined") {
            var my_jump = [];
        }
        if (typeof game_jump == "undefined") {
            var game_jump = {};

        }

        //------------------------------------------------------------
        //跳转
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
                        var html = '';
                        if (type != 1) {//创建页面展示
                            html += '<select lay-filter="level_1"><option value="" selected>请选择</option>'
                            for (var i=0;i<game_jump.length;i++) {
                                html += '<option value="'+game_jump[i].id+'" >'+game_jump[i].remark+'</option>'

                            }
                        } else {
                            html += '<select lay-filter="level_1">'
                            for (var i=0;i<game_jump.length;i++) {
                                if (game_jump[i].id == my_jump[0]) {
                                    html += '<option value="'+game_jump[i].id+'" selected>'+game_jump[i].remark+'</option>'
                                } else {
                                    html += '<option value="'+game_jump[i].id+'">'+game_jump[i].remark+'</option>'
                                }
                            }
                        }
                        html += '</select>';
                        $('#level_1').html(html);

                        form.render();

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
            if (typeof game_jump[data].child != "undefined") {
                var html ='<select lay-filter="level_2">';
                html+='<option value="" >请选择</option>';

                for(var i = 0;i<game_jump[data].child.length;i++) {
                    html+='<option value="'+game_jump[data].child[i].id+'" >'+game_jump[data].child[i].remark+'</option>';
                }
                html += '</select>';
                $('#level_2').html(html);
                form.render();
                form.on('select(level_2)',function (data) {
                    my_jump[1] = data.value;
                    $('#jump_url').val(my_jump.join('_'));
                    console.log(my_jump);
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
                my_jump = [];
                $('#jump_type').val(1);
                $('#input1').css('display','')
                $('#level_1').css('display','none');
                $('#level_2').css('display','none');
            }else if (data.value == 2) {//内部跳转
                $('#jump_type').val(2);
                $('#input1').css('display','none');
                getJump();
                //$('#input1').after();
                $('#level_1').css('display','block');
                $('#level_2').css('display','block');
            } else {

            }
        })




        //维护信息和轮播图播放间隔设置
        $.ajax({
            url:'/game-set/interval-downtime',
            success:function (res) {
                if (res.code == 0) {
                    var data = res.data;
                    $('#interval').val(data['interval']);
                    $('#down_info').val(data['downtime'].info);
                    $('#switch').val(data['downtime'].time);
                    if (data['downtime'].time == 0) {
                        $('#switch').next('div').click();
                    }
                } else {
                    $('#interval').val('');
                    $('#down_info').val('');
                    $('#switch').val('');
                }
            }
        });
        $('#down_info').focus(function () {
            $('#down_info').height(38);
            $('#down_info').parent('.layui-input-inline').width(520);
            $('#down_info').width(500);
        });
        $('#down_info').blur(function () {
            $('#down_info').height(38);
            $('#down_info').parent('.layui-input-inline').width(210);
            $('#down_info').width(190);
        })
        $('#edit_info').on('click',function () {
            $.ajax({
                url:'/game-set/broadcast-redis',
                type:'POST',
                data:{
                    interval:$('#interval').val(),
                    downtime:{
                        time:$('#switch').val(),
                        info:$('#down_info').val()
                    }
                },
                success:function (res) {
                    if (res.code == 0) {
                        layer.msg('修改成功');
                    } else {
                        layer.msg('修改失败');
                    }
                },
                error:function () {
                    layer.msg('出现错误');
                }
            })
        })
        form.on('switch(down_switch)', function(data){
            if (data.elem.checked) {
                $('#switch').attr('value',0);
            } else {
                $('#switch').attr('value',1);
            }
        });

        var uploadInst = upload.render({
            elem: '#test1'
            ,url: '/game-set/upload-img'
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code != 0){
                    return layer.msg('上传失败');
                }
                //上传成功
                $('#demo1').attr("src",res.data);
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
        //table数据渲染
        table.render({
            elem:"#moneyPrice"
            ,url:"/game-set/broadcast-index"
            ,page:true
            ,cols:[[
                {type:'numbers',title:"序号",align:"center"}
                ,{field:'img_url',title:"图片",sort:true,align:"center"}
                ,{field:'jump_url',title:"跳转地址",align:"center"}
                ,{field:'info',title:"备注",align:"center"}
                ,{field:'',title:"操作",toolbar:'#barmoneyPrice',width:150,align:"center"}
            ]]
        });
        //修改删除功能
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            if(obj.event==='revise'){
                layer.open({
                    type:1
                    ,title:'操作'
                    ,closeBtn:1
                    ,area:['60%','60%']
                    ,shade:0.5
                    ,id:"LAY_layuipro"
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#createLayer')
                    ,success:function (layero,index) {
                        $('#demo1').attr("src",data.img_url);
                        //$('#link').val(data.callback_url);
                        $('#annotation').val(data.info);
                        if (data.jump_type == 1) {//外部跳转
                            $('#jump_type').html('<option value="1" selected>外部跳转</option><option value="2" >内部跳转</option><option value="3">webview</option>');
                            //$('#jump_type').next().find('.layui-anim').children('dd[lay-value="1"]').click();
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
                        $('#annotation').val(data.info);
                    }
                    ,yes:function (index, layero) {
                        $.ajax({
                            url:'/game-set/broadcast-create',
                            type:'POST',
                            data:{
                                id:data.id,
                                //callback_url:$('#link').val(),
                                jump_url:$('#jump_url').val(),
                                img_url:$('#demo1').attr('src'),
                                info:$('#annotation').val(),
                                jump_type:$('#jump_type').val()
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.close(index);
                                    table.reload('moneyPrice',{
                                        url:"/game-set/broadcast-index",
                                        page:true
                                    });
                                    //return ;
                                } else {
                                    layer.msg('修改失败',{time:1000});
                                }
                            }
                        })
                    }
                    ,btn2:function (index, layero) {
                        $('#demo1').attr("src",' ');
                        $('#demoText').empty()
                    }
                })
                /*$.ajax({
                    url:'',
                    type:'POST',
                    data:{
                        id:data.id
                    },
                    success:function () {

                    }
                })*/

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
                        $('#num').html(data.currencyID);
                    }
                    ,yes:function (index, layero) {
                        $.ajax({
                            url:'/game-set/broadcast-del',
                            type:'POST',
                            data:{
                                id:data.id
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.close(index);
                                    table.reload('moneyPrice',{
                                        url:"/game-set/broadcast-index",
                                        page:true
                                    });
                                    //return ;
                                } else {
                                    layer.msg('删除失败',{time:1000});
                                }

                            },
                        })
                    }
                })
            }
        })
//新增
        var active = {
            add:function(){
                layer.open({
                    type:1
                    ,title:'新增'
                    ,closeBtn:1
                    ,area:['60%','60%']
                    //,shade:0.5
                    ,id:"LAY_layuipro"
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#createLayer')
                    ,success:function (layero,index) {
                        $('#demo1').attr("src",'');
                        //$('#link').val('')
                        $('#annotation').val('')
                    }
                    ,yes:function (index, layero) {
                        var jump_url = my_jump.join('_');
                        $.ajax({
                            url:'/game-set/broadcast-create',
                            type:'POST',
                            data:{
                                //callback_url:$('#link').val(),
                                img_url:$('#demo1').attr('src'),
                                info:$('#annotation').val(),
                                jump_url:jump_url,
                                jump_type:$('#jump_type').val()
                            },
                            success:function (res) {
                                if (res.code == 0) {
                                    layer.close(index);
                                    table.reload('moneyPrice',{
                                        url:"/game-set/broadcast-index",
                                        page:true
                                    });
                                    //return ;
                                } else {
                                    layer.msg('修改失败',{time:1000});
                                }
                            }
                        })
                    }
                    ,btn2:function (index, layero) {
                        $('#demo1').attr("src",' ');
                        $('#demoText').empty()
                    }
                })
            }
        };
        $('#add').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });
//排序
        table.on('sort(table1)', function(obj){
            table.reload('moneyPrice', {
                url:'/game-set/broadcast-index',
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
                ,{field:'img_url',title:"操作人",sort:true,align:"center"}
                ,{field:'jump_url',title:"操作内容",align:"center"}
            ]]
        });

    })

</script>



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
<div class="x-body" id="createLayer" style="display: none">
    <form action="" class="layui-form" style="margin-top:50px;" lay-filter="reviseCurrencyForm">
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">选择图片</label>
            <div class="layui-input-inline" >
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="test1">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" id="demo1">
                        <p id="demoText"></p>
                    </div>
                </div>
            </div>

        </div>
        <!--<div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">图片跳转链接</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" src="" id="link" name="link">
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">是否跳转</label>
            <div class="layui-input-inline">
                <select name="jump" id="jump_type" lay-filter="jump_type">
                    <option value="1" selected>外部跳转</option>
                    <option value="2">内部跳转</option>
                    <option value="3">webview</option>
                </select>
            </div>
            <div class="layui-input-inline" id="input1">
                <input type="text" class="layui-input" id="jump_url">
            </div>
            <div class="layui-input-inline" style="display: none;" id="level_1">
                <select name="" lay-filter="level_1">
                </select>
            </div>
            <div class="layui-input-inline" style="display: none;" id="level_2">
                <select name="" lay-filter="level_2">
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label" style="width: 20%;">备注</label>
            <div class="layui-input-inline" style="width: 60%;">
                <input type="text" class="layui-input" id="annotation" name="annotation">
            </div>
        </div>


        <!--<div class="layui-form-item" style="width:100%;">-->
        <!--<div style="position: absolute;left:40%;margin-bottom: 15px;">-->
        <!--<button class="btn" lay-submit="" lay-filter="submit">确认</button>-->
        <!--<button class="btn" type="reset">重置</button>-->
        <!--</div>-->
        <!--</div>-->
    </form>
</div>
<script>
    // layui.use(['table','layer','laydate','upload'],function () {
    //     var form = layui.form;
    //     if (typeof my_jump == "undefined") {
    //         var my_jump = {};
    //     }
    //     if (typeof game_jump == "undefined") {
    //         var game_jump = [];
    //     }
    //
    //     //------------------------------------------------------------
    //     //跳转
    //     function getJump(type = 0){//获取跳转信息
    //         $.ajax({
    //             url:'/game-set/activity-jump',
    //             type:'GET',
    //             success:function (res) {
    //                 if (res.code == 0) {
    //                     game_jump = res.data;
    //                     if (type != 1) {//创建页面展示
    //                         my_jump = [];
    //                     }
    //                     var html = '';
    //                     if (type != 1) {//创建页面展示
    //                         html += '<select lay-filter="level_1"><option value="" selected>请选择</option>'
    //                         for (var i=0;i<game_jump.length;i++) {
    //                             html += '<option value="'+game_jump[i].id+'" >'+game_jump[i].remark+'</option>'
    //
    //                         }
    //                     } else {
    //                         for (var i=0;i<game_jump.length;i++) {
    //                             if (game_jump[i].id == my_jump[0]) {
    //                                 html += '<option value="'+game_jump[i].id+'" selected>'+game_jump[i].remark+'</option>'
    //                             } else {
    //                                 html += '<option value="'+game_jump[i].id+'">'+game_jump[i].remark+'</option>'
    //                             }
    //                         }
    //                     }
    //                     html += '</select>';
    //                     $('#level_1').html(html);
    //
    //                     form.render('select');
    //
    //                     form.on('select(level_1)',function (data) {
    //                         if (type == 1) {//修改页面
    //                             my_jump[0] = data.value;
    //                             for (var i = 0; i < game_jump.length; i++) {
    //                                 if (game_jump[i].id == data.value) {
    //                                     gameJumpSelect(i,1,1);
    //                                 }
    //                             }
    //                         } else {
    //                             my_jump = [];
    //                             my_jump[0] = data.value;
    //                             console.log(my_jump);
    //                             for (var i = 0; i < game_jump.length; i++) {
    //                                 if (game_jump[i].id == data.value) {
    //                                     console.log(44444);
    //                                     gameJumpSelect(i,1);
    //                                 }
    //                             }
    //                         }
    //
    //                     });
    //                     if (type == 1) {
    //                         $('#level_1 select').next().find('.layui-anim').children('dd[lay-value="'+my_jump[0]+'"]').click();
    //                     }
    //                 }
    //             }
    //         });
    //     }
    //     //追加select元素
    //     function gameJumpSelect(data,id,type=0){
    //         console.log(game_jump[data].child);
    //         if (typeof game_jump[data].child != "undefined") {
    //             var html ='<select lay-filter="level_2">';
    //             html+='<option value="" >请选择</option>';
    //
    //             for(var i = 0;i<game_jump[data].child.length;i++) {
    //                 html+='<option value="'+game_jump[data].child[i].id+'" >'+game_jump[data].child[i].remark+'</option>';
    //             }
    //             html += '</select>';
    //             $('#level_2').html(html);
    //             form.render();
    //             form.on('select(level_2)',function (data) {
    //                 my_jump[1] = data.value;
    //                 console.log(my_jump);
    //             });
    //
    //         }
    //         if (type == 1) {
    //             $('#level_2 select').next().find('.layui-anim').children('dd[lay-value="'+my_jump[id]+'"]').click();
    //             console.log(my_jump);
    //         }
    //     }
    //     form.on('select(jump_type)',function (data) {
    //         $('#jump_url').val('');
    //         if (data.value == 1){//外部跳转
    //             //$('#leve1').css('display','none');
    //             //$('#leve2').css('display','none');
    //             $('#input1').css('display','')
    //             $('#level_1').css('display','none');
    //             $('#level_2').css('display','none');
    //         }else if (data.value == 2) {
    //             $('#input1').css('display','none');
    //             getJump();
    //             //$('#input1').after();
    //             $('#level_1').css('display','block');
    //             $('#level_2').css('display','block');
    //         } else {
    //
    //         }
    //     })
    // })


</script>

<!--货币价格的删除弹出层-->
<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除<span id="num"></span>轮播吗？</h2>
</div>
