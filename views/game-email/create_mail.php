
<style>
    label{width: 20%!important;}
    .body .layui-input-inline{width: 60%!important;}
    #reviRecord p{background-color:#EEEEEE;margin:10px 0;padding:5px 10px;border-radius:5px;}
    textarea{resize: none!important;}
</style>
<body>
<div class="x-body body">

    <div class="layui-form-item back">
        <a href="email-index"><i class="layui-icon layui-icon-left" style="font-size: 20px; color: #666666;">返回</i></a>
    </div>

    <form action="" class="layui-form" method="get" enctype="multipart/form-data">
        <input type="hidden" id="email_id" value="">
        <input type="hidden" id="revise_index" value="">
        <input type="hidden" id="attachment_content" value="">
        <input type="hidden" id="log_info" value="">
        <div class="layui-form-item">
            <label class="layui-form-label">发布对象</label>
            <div class="layui-input-inline">
                <select name="publishObject" id="send_type" class="layui-input" lay-filter="user" lay-verify="required">
                    <option value="">请选择发布对象</option>
                    <option value="1">全服</option>
                    <option value="2">多用户</option>
                    <option value="3">单用户</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item" id="objID" style="display: none">
            <div class="layui-col-xs10">
                <label for="" class="layui-form-label" style="position:relative;left:4%;">对象ID</label>
                <div class="layui-input-inline" style="width:72%!important;position:relative;left:4%;">
                    <textarea type="text" class="layui-textarea"  name="objectID" id="receive_play" lay-verify="objectID" placeholder="请输入8个数字+英文"  ></textarea>
                </div>
            </div>
<!--            <div class="layui-col-xs2" id="hidden">-->
<!--                <div class="layui-row">-->
<!--                    <div  class="layui-inline">-->
<!--                        <button type="button" class="layui-btn layui-btn-sm demoMore" title="上传文件" lay-data="{url: '/game-email/excel-receive-players', accept: 'file'}">上传文件</button>&nbsp;<small style="color:#e4393c;line-height:30px;">只能上传excel文件</small>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="layui-row">-->
<!--                    <div class="layui-inline">-->
<!--                        <input type="button"  id="deriveExcel" class="layui-btn layui-btn-sm" value="导出excel">-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <div class="layui-col-xs2">

        </div>


        <div class="layui-form-item">
            <label for="" class="layui-form-label">标题</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" name="title" lay-verify="title" id="title1">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">内容</label>
            <div class="layui-input-inline">
                <textarea id="contentText" class="layui-textarea" type="text" class="layui-input" name="content" lay-verify=""></textarea>
            </div>
        </div>
<!--        <div class="layui-form-item">-->
<!--            <label for="" class="layui-form-label">赠送</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <div class="layui-btn " data-type="addFile" id="addFileBtn" style="float:left;">添加附件</div>-->
<!--                <div class="layui-input-inline " id="content">-->
<!---->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">发送时间</label>
            <div class="layui-input-inline"  style="width:10%!important;">
                <input type="text" class="layui-input" id="sendDate1" placeholder="日期" name="sendingDate" lay-verify="required" readonly placeholder="11111">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">是否弹出公告</label>
            <div class="layui-input-inline" style="width:10%!important;">
                <select name="announcement" id="announcement" lay-filter="PopupBulletin" lay-verify="required" >
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select>
            </div>
            <div class="layui-input-inline"  style="width:10%!important;">
                <!--<input type="text" id="publicDate1" style="display:none" >-->
                <input type="text" class="layui-input" id="publicDate1" placeholder="日期" name="date" style="display:none">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">修改记录</label>

            <div class="layui-input-inline" style="width:60%!important;">
                <div id="reviRecord" style="line-height:30px">
                </div>
            </div>
        </div>
        <div class="layui-col-xs3 layui-col-xs-offset5" id="edit_button" >
            <!--href="maillist"-->
            <a  class="layui-btn" lay-submit="" lay-filter="createMail" id="submit">确认</a>
            <button class="layui-btn" type="reset">重置</button>
        </div>
    </form>



</div>

<script language="javascript" type="text/javascript">
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURIComponent(r[2]); return null;
    }

    layui.use(['laydate','form','upload'],function () {
        var $ = layui.$;

        //修改
        var url = location.search;
        var id = 0;
        if (url!==''){

            id = getQueryString('id')?getQueryString('id'):0;
            $.ajax({
                type:"GET"
                ,url:'/game-email/detail'
                ,data:{
                    'id':getQueryString('id')
                }
                ,success:function (res) {
                    if (res.length > 0) {
                        res = eval ("(" + res + ")");
                        $('#title1').val(res.title);

                        $('#contentText').val(res.content);
                        $('#sendDate1').val(res.send_time);
                        $('#announcement').val(res.is_pop);
                        if (typeof res.pop_time != "undefined") {
                            $('#publicDate1').val(res.pop_time);
                        }

                        var optionsPubObj = $('#send_type').next().children('dl').children('dd');

                        for (var i=0;i<optionsPubObj.length;i++){
                            if (res.send_type == optionsPubObj[i].getAttribute('lay-value')) {
                                $(optionsPubObj[i]).addClass("layui-this");
                                $(optionsPubObj[i]).click();
                            }
                            if (res.send_type =="2" || res.send_type =="3"){
                                $('#objID').css("display",'')
                            }
                        }
                        var optionsAnno = $('#announcement').next().children('dl').children('dd');
                        for (var i=0;i<optionsAnno.length;i++){
                            if (res.is_pop == optionsAnno[i].getAttribute('lay-value')){
                                $(optionsAnno[i]).addClass("layui-this");
                                $(optionsAnno[i]).click();
                            }
                            if (res.is_pop == "1"){
                                $('#publicDate1').css("display","")
                            }
                        }
                        $('#receive_play').val(res.receive_player);
                        var parent3 = $("#content");
                        if (!(res.attachment == "" || res.attachment == null || res.attachment == undefined)) {
                            res.attachment = JSON.parse(res.attachment);
                            for (var i= 0; i < res.attachment.length; i++) {
                                createDom(res.attachment[i].name,res.attachment[i].code,res.attachment[i].num,parent3);
                            }
                        }

                        //修改记录
                        $.ajax({
                            type:'GET',
                            url:'/game-email/log-detail',
                            data:{
                                id:id
                            },
                            success:function (res) {
                                res = eval('('+res+')');
                                var data = res.data;
                                if (data.length > 0) {
                                    var log_html = '';
                                    for (var i = 0; i < data.length; i++) {
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
                                    $('#reviRecord').html(log_html);
                                }
                            },
                            error:function () {

                            }
                        })


                    }
                }
                ,error:function () {
                    console.log("发生错误");
                }
            })

            /*var obj= getQueryString("obj");
            var title= getQueryString("title");
            var content= getQueryString("content");
            var appendix= getQueryString("appendix");
            var sendc= getQueryString("isPublic");
            var publicDate= getQueryString("publicDate");
            var typeT="元宝";Date= getQueryString("sendDate");
            var isPubli
            var num = "123";*/




        }

        var upload = layui.upload;
        //执行实例
        var uploadInst = upload.render({
            elem: '.demoMore', //绑定元素
            url: '/game-email/excel-receive-players', //上传接口
            size: '',
            auto:true,
            exts:'xls|xlsx',
            done: function(res, index, upload){
                if (res.code == 200) {
                    $('#receive_play').val(res.data);
                };
                layer.msg("成功",{time:1000})
            },
            error: function (r) {
                layer.msg("失败",{time:1000})
            }
        });




        var $ = layui.$;
        //日期查询;
        var laydate = layui.laydate;
        var d = new Date();
        var str1 = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+(d.getSeconds()+1);
        var str2 = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+(d.getHours())+":"+d.getMinutes()+":"+d.getSeconds();
        laydate.render({elem:"#sendDate1",type:"datetime",min:str1});
        laydate.render({elem:"#publicDate1",type:"datetime",min:str2,btns: ['clear', 'confirm']});
        //点击按钮后提交数据
        var form = layui.form;

        //创建邮件按钮

        form.on('submit(createMail)',function (data) {
            if (data.field.publishObject == 1) {
                data.field.objectID = '';
            }
            if (data.field.objectID) {
                var receive_player = data.field.objectID;
                receive_player.split(',');
                if (Array.isArray(receive_player)) {
                    for (var i = 0; i < receive_player.length; i++) {
                        if (!receive_player[i].match(/^\d{8}[a-zA-Z]+$/)) {
                            layer.msg('对象ID输入有误',{time:1000});
                            return false;
                        }
                    }
                }
            }
            if (data.field.title.length > 10) {
                layer.msg('标题最多10个字',{time:1000});
                return ;
            }
            if (data.field.content.length > 200) {
                layer.msg('内容最多200个字',{time:1000});
                return ;
            }

            var attachment = [];
            contentArr=[];
            var content1 = $('#content').children('span');
            var type1;
            var attachment_val;
            for (var i=0;i<content1.length;i++){
                type1 = content1[i].innerHTML.split('×');
                attachment_val = content1[i].getAttribute('value');
                contentArr.push({"code":attachment_val,"name":type1[0],"num":type1[1]});
            }
            if (contentArr.length > 0) {
                for (var i = 0; i < contentArr.length; i++) {
                    attachment.push({"code":contentArr[i].code,"name":contentArr[i].name,"num":contentArr[i].num});
                }
                if ((new Date(data.field.sendingDate)).getTime() - (new Date()).getTime() <= 2*60*60*1000) {
                    layer.msg('附带附件邮件设置发送时间需晚于创建时间两个小时',{time:3000});
                    return false;
                }

                //发送验证码
                $.ajax({
                    type:"POST"
                    ,url:'/game-email/sms-code'
                    ,data:{}
                    ,success:function (res) {
                        layer.msg('验证码已发送，有效期1分钟',{time:1000});
                    }
                    ,error:function () {
                        console.log("发生错误");
                    }
                })
                layer.open({
                    type: 1
                    , title: '请填写验证码' //不显示标题栏
                    , closeBtn: 1
                    , area: ['40%', '40%']
                    , shade: 0.8
                    , id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    , btn: ['确认', '取消']
                    , btnAlign: 'c'
                    , moveType: 1 //拖拽模式，0或者1
                    , content: $('#code')
                    ,yes: function (index, layero) {
                        $.ajax({//验证验证码
                            type:"POST"
                            ,url:'/game-email/verify-code'
                            ,data:{
                                code:($('#code-content').val()).trim()
                            }
                            ,success:function (res) {
                                res = eval ("(" + res + ")");
                                if (res.code == -48) {
                                    layer.msg('验证码超时',{time:1000});
                                } else if (res.code == -49) {
                                    layer.msg('输入的验证码有误请重新输入',{time:1000});
                                } else {
                                    var new_data = {
                                        'send_type':data.field.publishObject
                                        ,'receive_player':data.field.objectID
                                        ,'title':data.field.title
                                        ,'content':data.field.content
                                        ,'attachment':attachment
                                        ,'send_time':data.field.sendingDate
                                        ,'is_pop':data.field.announcement
                                        ,'pop_time':data.field.date
                                    };
                                    if (id) {
                                        new_data.id = id;
                                    }
                                    if ($('#email_id').val()) {
                                        new_data.id = $('#email_id').val();
                                    }
                                    $.ajax({
                                        type:"POST"
                                        ,url:'/game-email/create'
                                        ,data:new_data
                                        ,success:function (res) {
                                            res = eval ("(" + res + ")");
                                            if (res.code == 0) {
                                                console.log($('#revise_index').val());
                                                if ($('#revise_index').val()) {
                                                    parent.location.reload();
                                                } else {
                                                    window.location.href='/game-email/email-index';
                                                }

                                            } else {
                                                layer.msg('创建失败');
                                            }

                                        }
                                        ,error:function () {
                                            console.log("失败");
                                        }

                                    })
                                };
                            }
                            ,error:function () {
                                console.log("发生错误");
                            }
                        })
                        // contentArr=[];
                        // var content1 = $('#content').children('span');
                        // var type1;
                        // for (var i=0;i<content1.length;i++){
                        //     type1 = content1[i].innerHTML.split('×');
                        //     contentArr.push([type1[0],type1[1]]);
                        // }

                    }
                })
            } else {
                var new_data = {
                    'send_type':data.field.publishObject
                    ,'receive_player':data.field.objectID
                    ,'title':data.field.title
                    ,'content':data.field.content
                    ,'attachment':attachment
                    ,'send_time':data.field.sendingDate
                    ,'is_pop':data.field.announcement
                    ,'pop_time':data.field.date
                }
                if (id) {
                    new_data.id = id;
                }
                $.ajax({
                    type:"POST"
                    ,url:'/game-email/create'
                    ,data:new_data
                    ,success:function (res) {
                        res = eval ("(" + res + ")");
                        if (res.code == 0) {
                            if ($('#revise_index').val()) {
                                parent.location.reload();
                            } else {
                                window.location.href='/game-email/email-index';
                            }
                        } else {
                            layer.msg('创建失败');
                        }

                    }
                    ,error:function () {
                        console.log("失败");
                    }

                })
            }


        });

        //附件弹出层的添加按钮

        form.on('submit(add)',function (data) {

            var type = $("#type1").find("option:selected").text();
            var typeVal = $("#type1").find("option:selected").val();
            if (typeVal === ""){
                return false
            } else{
                //var typeV = $('#type1').next().children('dl').children('.layui-this').attr('lay-value');
                $('#type1').next().children('dl').children('.layui-this').addClass("layui-disabled");
                $('.layui-anim dd').removeClass("layui-this");
                $("#type1").next(".layui-form-select").children(".layui-anim").children(".layui-select-tips").click();
                var number = $("#number").val();
                var parent1 = $('#addContent');
                console.log(parent1);
                console.log(typeVal);
                createDom(type,typeVal,number,parent1);
            }
        });

        function createDom(type,value,number,parent){
            var frag=document.createDocumentFragment();
            // var parent = document.getElementById(parent);
            var span = document.createElement('span');
            var btn = document.createElement('div');
            var i = document.createElement('i');
            span.innerHTML= type+"×"+number;
            $(span).css({"display":"inline-block","backgroundColor":"#e1e1e1","padding":"5px 10px","margin-left":5,"borderRadius":5,"margin":5});
            $(span).attr("value",value);
            $(i).css({"color": "#E5E5E5;","font-size":12,"position":"relative","left":-12,"top":-13,"cursor":"pointer"});
            $(i).addClass("layui-icon layui-icon-close");
            frag.append(span);
            frag.append(i);
            parent.append(frag);
        }



        // 附件弹出层的删除按钮
        $('#addContent').on('click','i',function () {
            // var child = $('#addContent>*');
            var cont = $(this).prev().html();
            var reg = /^(.+?)\×/;
            var type2 = cont.match(reg)[1];
            // var selType = $("#type1 option");
            var dds = $('#type1').next().find("dd");
            // var dds = $(".layui-anim dd");
            console.log(dds);
            for (var j=0;j<dds.length;j++) {
                if (type2 === dds[j].innerHTML){
                    // debugger
                    $(dds[j]).removeClass("layui-disabled");
                }
            }

            $(this).prev().remove();
            $(this).remove();
            // for (var z=0;z<contentArr.length;z++){
            //     if (type === contentArr[z].typeT){
            //         contentArr.splice(z,1);
            //     }
            //     // console.log(contentArr);
            // }
        });

        //自定义校验规则
        form.verify({
            //objectID:[/^\d{8}[a-zA-Z]+$/,'对象ID必须为8个数字+英文'],
            title:[/[\S]{1,10}/,'最多输入10个字符'],
            content:[/[\S]{1,200}/,'最多输入200个字符'],
            // addContent:[/\d*/,'1111'],
            type:[/\d/,'内容为空或重复添加'],
            num:[/^[1-9]\d*$/,"请输入正整数"]
        });

        //点击添加附件的弹出层
        var active = {
            addFile: function () {
                contentArr=[];
                var content1 = $('#content').children('span');
                var type1;
                var attachment_val;
                for (var i=0;i<content1.length;i++){
                    type1 = content1[i].innerHTML.split('×');
                    attachment_val = content1[i].getAttribute('value');
                    contentArr.push({"code":attachment_val,"name":type1[0],"num":type1[1]});
                }
                var parent1 = $('#addContent');
                layer.open({
                    type: 1
                    , title: '请添加附件' //不显示标题栏
                    , closeBtn: 1
                    , area: ['80%', '80%']
                    , shade: 0.8
                    , id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    , btn: ['确认', '取消']
                    , btnAlign: 'c'
                    , moveType: 1 //拖拽模式，0或者1
                    , content: $('#addFile')
                    ,success:function (layero,index) {
                        $('#addContent').empty();
                        var dds = $('#type1').next().find("dd");
                        var parent2 = $("#addContent")
                        $("#type1").next().find('dd').removeClass("layui-disabled");
                        console.log(dds);
                        for (var i=0;i<contentArr.length;i++){
                            var typeT = contentArr[i]['name'];
                            var num = contentArr[i]['num'];
                            var value = contentArr[i]['code'];

                            for (var j=0;j<dds.length;j++){
                                if (typeT === dds[j].innerHTML){
                                    createDom(typeT,value,num,parent2);
                                    $(dds[j]).addClass("layui-disabled")
                                }
                            }
                        }
                    }
                    ,yes: function (index, layero) {
                        $("#content").empty();
                        $("#addContent>*").clone( ).appendTo($("#content"));
                        // debugger
                        contentArr.length=0;
                        // console.log(contentArr);
                        //在第二个弹出层点击确定按钮给数组赋值
                        var content2 = $('#addContent').children('span');
                        for (var i=0;i<content2.length;i++){
                            var type1 = content2[i].innerHTML.split('×');
                            console.log(content2[i].getAttribute("value"));
                            var attachment_val = content2[i].getAttribute("value");
                            contentArr.push({"code":attachment_val,"name":type1[0],"num":type1[1]});                        }
                        console.log(contentArr);
                        layer.close(index)
                    }

                })
            }
        }

        $('#content').on('click','i',function () {
            var child = $('#addContent>*');

            var cont1 = $(this).prev().html();
            var reg1 = /^(.+?)\×/;
            var type1 = cont1.match(reg1)[1];

            $(this).prev().remove();
            $(this).remove();
            // for (var z=0;z<contentArr.length;z++){
            //     console.log(contentArr,z,type,contentArr[z].typeT);
            //     // debugger
            //
            //     if (type1 === contentArr[z].typeT){
            //         contentArr.splice(z,1);
            //     }
            // }
            // console.log(contentArr);

        });
        $('#addFileBtn').on('click',function () {
            var type = $(this).data('type');
            console.log(type);
            active[type] ? active[type].call(this) : '';
        });




        //监听发布对象，显示隐藏对象ID
        form.on('select(user)', function(data) {
            $('#receive_play').val('');
            if (data.value === "" || data.value == 1) {
                $("#objID").css("display", "none");
                $("#objID textarea").attr("lay-verify","");
            } else {
                $("#objID").css("display", "");
                $("#objID textarea").attr("lay-verify","objectID");
            }
        })

        //判断发送时间函数
        function contrastTime(start) {
            var evalue = document.getElementById(start).value;
            var dB = new Date(evalue.replace(/-/g, "/"));//获取当前选择日期
            var d = new Date();
            var str = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();//获取当前实际时间
            // console.log(Date.parse(str));

            // console.log(Date.parse(dB));
            if (Date.parse(str) > Date.parse(dB)) {//时间戳对比
                return 1;
            }else if (Date.parse(str)-Date.parse(dB)>7200) {
                return 2;
            }else{
                return 0;
            }

        }


        //监听弹出公告，隐藏日期框
        form.on('select(PopupBulletin)',function (data) {
            $("#publicDate").val("");
            if (data.value === "0"){
                $("#publicDate1").css("display","none");
                $("#publicDate1").attr("lay-verify","")
            } else{
                $('#publicDate1').css("display","");
                $("#publicDate1").attr("lay-verify","required")
            }
        })

    })

</script>
</body>


<div style="display:none" class="x-body" id="addFile">
    <div action="" class="layui-form" method="get" enctype="multipart/form-data">
        <!--<form action="">-->

        <div class="layui-form-item">
            <label class="layui-form-label" >物品</label>
            <div class="layui-input-inline" >
                <select name="goods " id="type1" class="layui-input" lay-filter="type" lay-verify="type">
                    <option value="" disable>请选择物品</option>
                    <option value="1">元宝</option>
                    <option value="2">表情包</option>
                    <option value="3">献花</option>
                    <option value="4">亲亲</option>
                    <option value="5">干杯</option>
                    <option value="6">炸弹</option>
                    <option value="7">抓鸡</option>
                    <option value="8">鸡蛋</option>
                    <option value="9">宝箱</option>
                </select>
            </div>
            <div class="layui-input-inline" style="width:7%!important;">
                <input type="number" class="layui-input" lay-verify="num" id="number" placeholder="数量" >
            </div>
            <button  class="layui-btn" lay-submit="" lay-filter="add" id="addGoodsBtn">添加</button>
        </div>

        <!--</form>-->



        <div class="layui-form-item">
            <label for=""class="layui-form-label">添加内容</label>
            <div id="addContent" class="" style="width:50%;float:left">

            </div>

        </div>
        <!--<div class="layui-col-xs3 layui-col-xs-offset5">-->
        <!--<a href="maillist" class="layui-btn" lay-submit="" lay-filter="createMail" id="submit2">确认</a>-->
        <!--<button class="layui-btn" type="reset">重置</button>-->
        <!--</div>-->
    </div>
</div>

<div style="display:none" class="x-body" id="code">
    <h3>添加附件需要手机号 <span id="tel"></span> 输入验证码进行验证</h3>
    <div class="layui-form-item">
        <label for="" class="layui-form-label">请填写验证码</label>
        <div class="layui-input-inline">
            <input type="text" id="code-content" class="layui-input">
        </div>
    </div>
</div>
<script>

</script>
</html>