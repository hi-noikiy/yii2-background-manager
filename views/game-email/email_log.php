
<body>
<div class="x-body">
    <table class="layui-table" id="historyInfo" lay-filter="table1"></table>
</div>
<script type="text/html" id="barHistoryInfo">
    <div>
        <div class="layui-btn layui-btn-xs" lay-event="details" title="查看">查看</div>
        {{#  if(d.play_btn == 1){ }}
        {{#  if(d.play_pause == 0){ }}
        <div class="layui-btn layui-btn-xs layui-btn-warm stop" lay-event="stop" title="暂停">暂停</div>
        {{#  } }}
        {{#  if(d.play_pause == 1){ }}
        <div class=" layui-btn layui-btn-xs" lay-event="stop" title="播放">播放</div>
        {{#  } }}
        {{#  } }}
    </div>
</script>
<script>
    layui.use('table',function () {
        var table = layui.table;
        table.render({
            elem:'#historyInfo'
            ,url:'/game-email/log-info'
            ,page:true
            ,cols:[[
                {field:"id",title:"ID"}
                ,{field:"email_code",title:"序列号"}
                ,{field:"title",title:"标题"}
                ,{field:"content",title:"内容"}
                ,{field:"attachment",title:"是否带附件",templet:function (d) {
                        var attachment = d.attachment;
                        if (attachment == null || attachment.length == 0) {
                            return '无';
                        } else {
                            return '有';
                        }
                    }}
                ,{field:"receive_player",title:"发布对象"}
                ,{field:"send_time",title:"发送时间"}
                ,{field:"created_time",title:"创建时间"}
                ,{field:"send_status",title:"状态",templet:function (d) {
                        var status = d.status;
                        if (status == 0) {
                            return '删除';
                        } else {
                            return '已发送';
                        }

                    }}
                ,{field:"creator",title:"发布人"}
                ,{field:"phone",title:"审核"}
                ,{field:"",title:"操作",toolbar:"#barHistoryInfo",width:110}
            ]]
            ,done:function (res, curr, count) {

                var data = res.data;
                for (var i=0;i<data.length;i++){
                    // debugger
                    var a = data[i].send_status;
                    var trs,tri;
                    if (a == 3){
                        trs = $('tr');
                        tri = trs[i+1];
                        //$(tri).css('backgroundColor',"#FFB800");
                        var tds = $(tri).children();
                        $(tds[8]).css("backgroundColor","#84D945");
                    }
                }
            }
        });
        table.on('tool(table1)',function (obj) {
            var data = obj.data;
            if (obj.event==='details'){
                layer.open({
                    type:2
                    ,title:false
                    ,closeBtn:1
                    ,area:['95%','95%']
                    ,id:'LAY_layuipro'
                    // ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:'/game-email/create-email-index'
                    ,success:function (layero,index) {

                        var frameId = "#" + layero.find('iframe')[0].id;
                        //修改页面渲染发布对象数据
                        var optionsPubObj = $(frameId).contents().find('#send_type').next().children('dl').children('dd');
                        for (var i=0;i<optionsPubObj.length;i++){
                            if (data.send_type == optionsPubObj[i].getAttribute('lay-value')) {
                                $(optionsPubObj[i]).addClass("layui-this");
                                $(optionsPubObj[i]).click();
                                $(frameId).contents().find('#send_type').next().children('dl').remove();
                                $(frameId).contents().find('#send_type').next().children('div').children("i").remove()
                            }
                        }



                        var optionsAnno = $(frameId).contents().find('#announcement').next().children('dl').children('dd');
                        for (var i=0;i<optionsAnno.length;i++){
                            if (data.is_pop === optionsAnno[i].getAttribute('lay-value')){
                                $(optionsAnno[i]).addClass("layui-this");
                                $(optionsAnno[i]).click();
                                $(frameId).contents().find('#announcement').next().children('dl').remove()
                                $(frameId).contents().find('#announcement').next().children('div').children("i").remove()
                            }
                            if (data.is_pop === "1"){
                                $(frameId).contents().find('#publicDate1').css("display","")
                            }
                            if (data.send_type ==="2"||data.send_type ==="3"){
                                $(frameId).contents().find('#objID').css("display",'')
                            }
                        }

                        $(frameId).contents().find('#receive_play').val(data.receive_player);
                        $(frameId).contents().find('#receive_play').attr('readonly','readonly');
                        $(frameId).contents().find('#title1').val(data.title);
                        $(frameId).contents().find('#title1').attr('readonly','readonly');
                        $(frameId).contents().find('#contentText').val(data.content);
                        $(frameId).contents().find('#contentText').attr('readonly','readonly');
                        $(frameId).contents().find('#sendDate1').val(data.send_time);
                        $(frameId).contents().find('#sendDate1').attr('disabled','disabled');
                        $(frameId).contents().find('#addFileBtn').css('display','none');

                        $(frameId).contents().find('#publicDate1').val(data.pop_time);
                        $(frameId).contents().find('#publicDate1').attr('disabled','disabled');
                        $(frameId).contents().find('#hidden').css('display','none');
                        $(frameId).contents().find('.back').css('display','none');
                        $(frameId).contents().find('#edit_button').hide();
                        //渲染附件数据
                        var xfile = data.typeT+"×"+data.num;
                        var parent1 = $(frameId).contents().find("#content");
                        function createDom2(xfile,parent){
                            var frag=document.createDocumentFragment();
                            var span = document.createElement('span');
                            span.innerHTML= xfile;
                            $(span).css({"display":"inline-block","backgroundColor":"#e1e1e1","padding":"5px 10px","margin-left":5,"borderRadius":5,"margin":5});
                            frag.append(span);
                            parent.append(frag);
                        }
                        data.attachment = JSON.parse(data.attachment);
                        if (typeof data.attachment != "undefined" && data.attachment) {
                            for (var i = 0; i < data.attachment.length; i++) {
                                createDom2(data.attachment[i].name+'*'+data.attachment[i].num,parent1);
                            }
                        }

                        //createDom2(xfile,parent1);
                        $(frameId).contents().find('#hidden').remove();



                        var spans = $(frameId).contents().find("#content").children('span');

                        var giveArr = [];
                        var reg = /^(.+?)\×/;
                        for (var i = 0;i<spans.length;i++){
                            var give = spans[i].innerHTML.split(reg);
                            giveArr.push([give[1],give[2]]);
                        }


                        //修改记录
                        $.ajax({
                            type:'GET',
                            url:'/game-email/log-detail',
                            data:{
                                id:data.id
                            },
                            success:function (res) {
                                res = eval('('+res+')');
                                var data = res.data;
                                if (data.length > 0) {
                                    var log_html = '';
                                    for (var i = 0; i < data.length; i++) {
                                        if (data[i].operate_type == 3) {//删除
                                            log_html += '<p>删除：'+data[i].creator+'于'+data[i].time+'将邮件删除</p>';
                                        } else {
                                            if (data[i].content_type == 1) {
                                                log_html += '<p>修改发送时间：'+data[i].creator+'于'+data[i].time+'将发送时间'+data[i].old_content+'修改为：'+data[i].new_content+'</p>';
                                            } else if (data[i].content_type == 2) {
                                                log_html += '<p>修改结束时间：'+data[i].creator+'于'+data[i].time+'将结束时间'+data[i].old_content+'修改为：'+data[i].new_content+'</p>';
                                            } else if (data[i].content_type == 3) {
                                                log_html += '<p>修改指定对象：'+data[i].creator+'于'+data[i].time+'将指定对象'+data[i].old_content+'修改为：'+data[i].new_content+'</p>';
                                            } else if (data[i].content_type == 4) {
                                                log_html += '<p>修改标题：'+data[i].creator+'于'+data[i].time+'将邮件标题'+data[i].old_content+'修改为：'+data[i].new_content+'</p>';
                                            } else if (data[i].content_type == 5) {
                                                var old_content = JSON.parse(data[i].old_content);
                                                var new_content = JSON.parse(data[i].new_content);
                                                var old_attachment = '';
                                                var new_attachment = '';
                                                for (var i = 0; i < old_content.length; i++) {
                                                    old_attachment += old_content[i].name +'数量'+old_content[i].num+' ';
                                                }
                                                for (var i = 0; i < new_content.length; i++) {
                                                    new_attachment += new_content[i].name +'数量'+new_content[i].num+' ';
                                                }
                                                log_html += '<p>修改赠送：'+data[i].creator+'于'+data[i].time+'将赠送'+old_attachment+'修改为：'+new_attachment+'</p>';
                                            } else if (data[i].content_type == 6) {
                                                log_html += '<p>修改邮件内容：'+data[i].creator+'于'+data[i].time+'将邮件内容'+data[i].old_content+'修改为：'+data[i].new_content+'</p>';
                                            }
                                        }

                                    }
                                    $(frameId).contents().find('#reviRecord').html(log_html);
                                }
                            },
                            error:function () {
                                console.log('修改记录错误');
                            }
                        })
                    },
                    yes:function (index,layero) {
                        //向后端发送将要删除的跑马灯ID
                        $.ajax({
                            url:'',
                            type:'POST',
                            data:{
                                'id': id
                            },
                            success:function (data) {
                                console.log("成功");
                                //删除成功后重载表格
                                table.reload('mailListTable', {
                                    url:'/test/t205',
                                });
                            },
                            error:function () {
                                console.log("失败");
                            }
                        });
                        layer.close(index);
                    }
                });
            }else if (obj.event==='stop'){
                //获取点击按钮对于的tr数据的唯一值
                var id = data.id;
                //获取点击的按钮
                var $This = $(this);
                //声明变量保存
                var turn;

                // 判断是开启还是暂停
                $This.hasClass("stop")?turn = 1:turn = 0;
                //向后端发送数据并修改按钮状态
                $.ajax({
                    type: 'POST'
                    , data: {
                        'id': id
                        ,'is_play': turn
                    }
                    , url: '/game-email/play-pause'
                    //,dataType:'JSON'
                    , success: function (data) {
                        if (turn == 1) {
                            $This.removeClass('stop');
                            // $This.removeClass('layui-btn-warm');
                            // $This.addClass('start');
                            $This.html('播放');
                        } else {
                            // $This.removeClass('start');
                            $This.addClass('stop');
                            // $This.addClass('layui-btn-warm');
                            $This.html('暂停');
                        }
                    }
                    ,error:function () {
                        layer.msg('操作失败',{time:1000});
                    }
                });
            }
        })
    })
</script>
</body>
<div class="x-body" id="details" style="display: none;">
    <table class="layui-table" id="historyDetails"></table>
</div>
