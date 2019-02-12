<body>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">GM工具</a>
            <a>
                <cite>公告邮件</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <div class="layui-row titleFormStyle">
        <div class="lf">
            <!--<a href="#" onclick="jumplink('param')">111111</a>-->
            <a href="create-email-index" class="layui-btn"><i class="layui-icon">&#xe61f;</i>创建邮件</a>
            <button class="layui-btn"onclick="x_admin_show('历史信息库','/game-email/email-log-index',1600,600)" data-type="history" id="historyBtn"><i class="layui-icon">&#xe621;</i>历史信息库</button>
        </div>
    </div>

    <table class="layui-table" id="mailListTable" lay-filter="sort"></table>
</div>
<script type="text/html" id="barMailList">
    <div id="btn">
        <div title="修改" class="layui-btn layui-btn-xs" lay-event="revise"><i class="layui-icon">修改</i></div>
        <div class="layui-btn layui-btn-danger layui-btn-xs" title="删除" lay-event="del"><i class="layui-icon">删除</i></div>
    </div>
</script>
<script>
    function jumplink(param){
        window.location.href="/game-email/create-email-index?"+param ;
    }

    layui.use(['table','form'],function () {
        var table = layui.table;
        var form = layui.form;
        var $ = layui.$;
        //table渲染数据
        table.render({
            elem:"#mailListTable"
            ,url:"/game-email/index"
            ,page:true
            ,cols:[[
                {field:"id",title:"ID",sort:true}
                ,{field:"email_code",title:"序列号"}
                ,{field:"title",title:"标题",width:110}
                ,{field:"content",title:"内容",width:110}
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
                        if (d.send_status == 2) {
                            return '准备发送';
                        } else if (d.send_status == 1)  {
                            return '待发送';
                        }

                    }}
                ,{field:"creator",title:"发布人"}
                ,{field:"phone",title:"审核"}
                // {field:"index",title:"序号",sort:true}
                // ,{field:"number",title:"序列号"}
                // ,{field:"title",title:"标题",width:110}
                // ,{field:"content",title:"内容",width:110}
                // ,{field:"appendix",title:"是否带附件"}
                // ,{field:"publishObject",title:"发布对象"}
                // ,{field:"sendData",title:"发送时间"}
                // ,{field:"status",title:"状态"}
                // ,{field:"issuer",title:"发布人"}
                // ,{field:"auditing",title:"审核"}
                ,{field:"",title:"操作",toolbar:"#barMailList",align:"center",width:150}
            ]]
            ,done:function (res, curr, count) {

                var data = res.data;
                for (var i=0;i<data.length;i++){
                    // debugger

                    var a = data[i].send_status;
                    var trs,tri;
                    if (a == 2){
                        trs = $('tr');
                        tri = trs[i+1];
                        $(tri).css({backgroundColor:"red",color:"#FFF"});
                        $(tri).find('td').css({fontSize:18});
                        var tds = $(tri).children();
                        $(tds[11]).css("backgroundColor","white");
                    }/*else if(a == 1){
                        trs = $('tr');
                        tri = trs[i+1];
                        var tds = $(tri).children();
                        $(tds[7]).css("backgroundColor","#84D945");
                    }*/
                }
            }
        });

        //table排序功能
        table.on('sort(sort)', function(obj){
            table.reload('mailListTable', {
                url:'/game-email/index',
                initSort: obj
                ,where: {
                    field: obj.field
                    ,order: obj.type
                }
            });
        });

        function createDom2(type,number){
            var frag=document.createDocumentFragment();
            var frag=document.createDocumentFragment();
            var child = document.getElementById('createmail');
            var span = document.createElement('span');
            var btn = document.createElement('div');
            var i = document.createElement('i');
            btn.innerHTML='删除';
            $(btn).addClass('layui-btn layui-btn-xs del');
            $(btn).css({"margin-right":10});
            $(btn).attr("lay-filter","del");
            span.innerHTML= type+"×"+number;
            $(span).css({"display":"inline-block","backgroundColor":"#e1e1e1","padding":"5px 10px","margin-left":5,"borderRadius":5,"margin":5});
            $(i).css({"color": "#E5E5E5;","font-size":12,"position":"relative","left":-12,"top":-13,"cursor":"pointer"});
            $(i).addClass("layui-icon layui-icon-close");
            frag.append(span);
            frag.append(i);
            child.append(frag);
        }

        //table监听事件，监听操作按钮
        table.on('tool(sort)',function (obj) {
            var data = obj.data;
            switch (obj.event){
                case 'revise':
                    var param = "id="+encodeURIComponent(data.id);
                    jumplink(param);
//                     layer.open({
//                         type:2
//                         ,title:false
//                         ,closeBtn:1
//                         ,area:['80%','80%']
//                         ,id:'LAY_layuipro'
//                         // ,btn:['确认','取消']
//                         ,btnAlign:'c'
//                         ,moveType:1
//                         ,content:'#createmail'
//                         ,success:function (layero,index) {
//                              var frameId = "#" + layero.find('iframe')[0].id;
//                              // debugger
//                              //修改页面渲染发布对象数据
//                              var optionsPubObj = $(frameId).contents().find('#publishObject').next().children('dl').children('dd');
//                              for (var i=0;i<optionsPubObj.length;i++){
//                                  if (data.publishObject === optionsPubObj[i].innerHTML) {
//                                      $(optionsPubObj[i]).addClass("layui-this");
//
//                                      $(optionsPubObj[i]).click();
//                                  }
//
//                                  if (data.publishObject ==="多用户"||data.publishObject ==="单用户"){
//                                      $(frameId).contents().find('#objectID').css("display",'')
//                                  }
//                              }
//                              var optionsAnno = $(frameId).contents().find('#announcement').next().children('dl').children('dd');
//                              for (var i=0;i<optionsAnno.length;i++){
//                                  if (data.announcement === optionsAnno[i].innerHTML){
//                                      $(optionsAnno[i]).addClass("layui-this");
//                                      $(optionsAnno[i]).click();
//                                  }
//                                  if (data.announcement === "是"){
//                                      $(frameId).contents().find('#publicDate1').css("display","")
//                                  }
//                              }
//
//                              $(frameId).contents().find('#title1').val(data.title);
//                              $(frameId).contents().find('#objectID').val(data.objectID1);
//                              $(frameId).contents().find('#contentText').val(data.content);
//                              $(frameId).contents().find('#sendDate1').val(data.sendDate);
//                              $(frameId).contents().find('#announcement').val(data.announcement1);
//                              $(frameId).contents().find('#publicDate1').val(data.publicDate);
//
//                              //渲染附件数据
//                              var xfile = data.typeT+"×"+data.num;
//                              var parent1 = $(frameId).contents().find("#content");
//                              function createDom2(xfile,parent){
//                                  // debugger
//                                  var frag=document.createDocumentFragment();
//                                  var span = document.createElement('span');
//                                  var i = document.createElement('i');
//                                  span.innerHTML= xfile;
//                                  $(span).css({"display":"inline-block","backgroundColor":"#e1e1e1","padding":"5px 10px","margin-left":5,"borderRadius":5,"margin":5});
//                                  $(i).css({"color": "#E5E5E5;","font-size":12,"position":"relative","left":-12,"top":-13,"cursor":"pointer"});
//                                  $(i).addClass("layui-icon layui-icon-close");
//                                  frag.append(span);
//                                  // frag.appendChild(btn);
//                                  frag.append(i);
//                                  parent.append(frag);
//                              }
//                               createDom2(xfile,parent1);
//
//
//                              var giveArr = [];
//                              var spans = $(frameId).contents().find("#content").children('span');
//                              var reg = /^(.+?)\×/;
//                              for (var i = 0;i<spans.length;i++){
//                                  var give = spans[i].innerHTML.split(reg);
//                                  giveArr.push([give[1],give[2]]);
//                              }
//                              $(frameId).contents().find('#content').on('click','i',function () {
//                                  var cont1 = $(this).prev().html();
//                                  var reg1 = /^(.+?)\×/;
//                                  var type1 = cont1.match(reg1)[1];
//                                  $(this).prev().remove();
//                                  $(this).remove();
//                              });
//
//
//                              $(frameId).contents().find('#addFileBtn').click(function () {
//                                  var parent2 = $(frameId).contents().find('#addContent');
//                                  var seleoptions =  $(frameId).contents().find('#type1').next().children('dl').children('dd');
//                                  for (var i=0;i<giveArr.length;i++){
//                                      var xfilei = giveArr[i][0]+"×"+giveArr[i][1];
//
//                                      var typei = giveArr[i][0]
//                                      setTimeout(function () {
//                                          createDom2(xfilei,parent2);
//
//                                          seleoptions.each(function () {
//
//
//                                              if($(this).html()=== typei){
//
//                                                  $(this).addClass("layui-disabled");
//                                              }
//                                          })
//                                      },100);
//                                  }
//                              });
//
//                              $.ajax({
//                                      url:'/test/t206',
//                                      data:{
//                                          index:obj.data.number
//                                      },
//                                      success:function () {
//                                      },
//                                      error:function () {
//                                      }
//                                  }
//                              )
//                         },
//                         yes:function (index,layero) {
//                             //向后端发送将要删除的跑马灯ID
//                             $.ajax({
//                                 url:'',
//                                 type:'POST',
//                                 data:{
//                                     'id': id
//                                 },
//                                 success:function (data) {
//                                     console.log("成功");
//                                     //删除成功后重载表格
//                                     table.reload('mailListTable', {
//                                         url:'/test/t205',
//                                     });
//                                 },
//                                 error:function () {
//                                     console.log("失败");
//                                 }
//                             });
//                             layer.close(index);
//                         }
//                     });
                    break;
                case 'del':
                    var id = obj.data.ID;
                    layer.open({
                        type:1
                        ,title:false
                        ,closeBtn:1
                        ,area:['30%','25%']
                        ,id:'LAY_layuipro'
                        ,btn:['确认','取消']
                        ,btnAlign:'c'
                        ,moveType:1
                        ,content:$('#del')
                        ,success:function (layero,index) {
                            $('#num').html(id);
                        }
                        ,yes:function (index,layero) {
                            //向后端发送将要删除的跑马灯ID
                            $.ajax({
                                url:'/game-email/delete',
                                type:'POST',
                                data:{
                                    id:obj.data.id
                                },
                                success:function (data) {
                                    console.log("成功");
                                    //删除成功后重载表格
                                    table.reload('mailListTable', {
                                        url:'/game-email/index',
                                    });
                                },
                                error:function () {
                                    console.log("失败");
                                }
                            });
                            layer.close(index);
                        }
                    });
                    break;
            }
        })


    })
</script>
</body>

<div class="x-body" id="del"  style="display: none;text-align: center;padding-top:10%;">
    <h2 class="center">确认删除<span id="num"></span>邮件吗？</h2>
</div>


<style>
    label{width: 20%!important;}
    .body .layui-input-inline{width: 60%!important;}
</style>


<div class="x-body" id="historyLayer" style="display: none">
    <table class="layui-table" id="historyInfo" lay-filter="table1"></table>
</div>
<script type="text/html" id="barHistoryInfo">
    <div>
        <button class="layui-btn layui-btn-xs" lay-event="details">查看</button>
<!--        <button class="layui-btn layui-btn-xs layui-btn-warm stop" lay-event="stop">暂停</button>-->
    </div>
</script>

<div class="x-body" id="details" style="display: none;">
    <table class="layui-table" id="historyDetails"></table>
</div>