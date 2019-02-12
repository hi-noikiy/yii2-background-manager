<style>
  /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
  .BGO{background-color: #EEEEEE;padding:1px;}
</style>
<script src="https://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
  
  <body >
  <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">平台系统设置</a>
            <a>
                <cite>管理员列表</cite>
            </a>
        </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
  </div>
  <div class="x-body" >
    <!--<div class="x-body">-->
      <div class="layui-row ">
        <form class="layui-form layui-col-md12 x-so BGO">
          <input class="layui-input" placeholder="开始日" name="start" id="start">
          <input class="layui-input" placeholder="截止日" name="end" id="end">
          <input type="text" name="username"  placeholder="请输入用户名" id="username_search" autocomplete="off" class="layui-input">
            <div class="layui-btn"  lay-submit="" id="search" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></div>
            <div id="baicai" style="background-color:#eeeeee; float:right;border:1px;height:40px; width:40px;"></div>
        </form>
      </div>
      <xblock>
<!--        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>-->
        <button class="layui-btn " data-method="add" id="addBtn" ><i class="layui-icon"></i>添加</button>
      </xblock>
      <table class="layui-table" id="manager" lay-filter="manager"></table>

    </div>
<script>
    $('#baicai').click(function(){
        alert(1);
        die();
    });
</script>

  <script type="text/html" id="barDemo">
    <div class="layui-icon layui-btn layui-btn-xs" lay-event="edit">编辑</div>
    <div class="layui-icon layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</div>
  </script>
    <script>
      layui.use(['laydate','table','form'], function(){
        var laydate = layui.laydate;
        var table = layui.table;
        var form = layui.form;
          table.render({
              elem:"#manager"
              ,url:"/auth/manager-list"
              ,page:true
              ,cols:[[
                  //{type:"checkbox",title:""}
                  {field:"id",title:"ID"}
                  ,{field:"username",title:"登录名"}
                  //,{field:"phone",title:"手机"}
                  ,{field:"email",title:"邮箱"}
                  ,{field:"role",title:"角色"}
                  ,{field:"created_at",title:"加入时间",}
                  ,{field:"status",title:"状态",templet:function (d) {
                          if (d.status == 1) {
                              return '正常';
                          }
                      }}
                  ,{title:"操作",toolbar: '#barDemo'}
              ]]
          });
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });
        var role_list = [];

      var active = {
          add:function () {
              layer.open({
                  type: 1
                  ,title: "添加管理员" //不显示标题栏
                  ,closeBtn: 1
                  ,area: ['60%','70%']
                  ,shade: 0.8
                  ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                  // ,btn: ['添加黑名单','返回']
                  ,btnAlign: 'c'
                  ,moveType: 1 //拖拽模式，0或者1
                  ,content:$('#addManager')
                  ,success:function () {
                      $('#username').val('');
                      $('#L_email').val('');
                      sessionStorage.removeItem('role_list');
                      $('#L_pass').val('');
                      $('#L_repass').val('');
                      $('#modelId').val(0);

                      //获取角色列表
                      $.ajax({
                          url:'/auth/role-list',
                          type:'GET',
                          success:function (res) {
                              $('#L_passInput').show();
                              $('#L_repassInput').show();
                              var data = res.data;
                              var html = '';
                              if (res.code == 0) {
                                  for (var i = 0; i < data.length; i++) {
                                      html +='<input type="checkbox" name="like1[write]" lay-filter="roleList" lay-skin="primary" title="'+data[i].name+'" value="'+data[i].name+'">';
                                  }
                              }
                              $('#role').html(html);
                              form.render();
                              role_list = [];
                              form.on('checkbox(roleList)',function (data) {
                                  if (data.elem.checked) {
                                      role_list.push(data.elem.value);
                                  } else {
                                      role_list.splice(role_list.indexOf(data.elem.value))
                                  }
                                  sessionStorage.setItem('role_list',JSON.stringify(role_list));
                                  sessionStorage.getItem('role_list');
                              });
                          }
                      });
                  }
              })
          }
      };
      // function getList () {
      //     $.ajax({
      //         url:'/auth/manager-list',
      //         data:{
      //             start_time:$('#start').val(),
      //             end_time:$('#end').val(),
      //             username:$('#username_search').val()
      //         },
      //         type:'GET',
      //         success:function (res) {
      //             var data = res.data;
      //             var html = '';
      //             if (res.code == 0) {
      //                 for (var i = 0; i < data.length; i++) {
      //                     html +='<input type="checkbox" name="like1[write]" lay-filter="roleList" lay-skin="primary" title="'+data[i].name+'" value="'+data[i].name+'">';
      //                 }
      //             }
      //             $('#role').html(html);
      //             form.render();
      //             role_list = [];
      //             form.on('checkbox(roleList)',function (data) {
      //                 if (data.elem.checked) {
      //                     role_list.push(data.elem.value);
      //                 } else {
      //                     role_list.splice(role_list.indexOf(data.elem.value))
      //                 }
      //                 console.log(role_list);
      //                 sessionStorage.setItem('role_list',JSON.stringify(role_list));
      //                 sessionStorage.getItem('role_list');
      //             });
      //         }
      //     });
      // }

      //getList();
      $('#search').on('click',function () {
          //getList();
          table.reload('manager',{
              where:{
                  start_time:$('#start').val(),
                  end_time:$('#end').val(),
                  username:$('#username_search').val()
              }
          });
      });
      $('#addBtn').on('click', function(){
          var othis = $(this), method = othis.data('method');
          active[method] ? active[method].call(this, othis) : '';
      });

      table.on('tool(manager)', function(obj){
          var data = obj.data;
          if(obj.event === 'stop'){
              layer.confirm('确认要停用吗？',function(index){
                  if($(obj).attr('title')=='启用'){

                      //发异步把用户状态进行更改
                      $(obj).attr('title','停用')
                      $(obj).find('i').html('&#xe62f;');

                      $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                      layer.msg('已停用!',{icon: 5,time:1000});

                  }else{
                      $(obj).attr('title','启用')
                      $(obj).find('i').html('&#xe601;');

                      $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                      layer.msg('已启用!',{icon: 5,time:1000});
                  }

              });
          }
          else if(obj.event === 'edit'){
              layer.open({
                  type: 1
                  ,title: "更新管理员" //不显示标题栏
                  ,closeBtn: 1
                  ,area: ['60%','70%']
                  ,shade: 0.8
                  ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                  // ,btn: ['添加黑名单','返回']
                  ,btnAlign: 'c'
                  ,moveType: 1 //拖拽模式，0或者1
                  ,content:$('#addManager')
                  ,success:function () {
                      $('#username').val(data.username);
                      $('#L_email').val(data.email);
                      $('#L_passInput').hide();
                      $('#L_repassInput').hide();
                      $('#modelId').val(data.id);
                      var roles = data.roles;
                      //获取角色列表
                      $.ajax({
                          url:'/auth/role-list',
                          type:'GET',
                          success:function (res) {
                              var data = res.data;
                              var html = '';
                              role_list = [];
                              sessionStorage.setItem('role_list','');
                              if (res.code == 0) {
                                  console.log(data.length)
                                  console.log(roles)
                                  for (var i = 0; i < data.length; i++) {
                                      if (roles.indexOf(data[i].name) >= 0) {
                                          html +='<input type="checkbox" checked name="like1[write]" lay-filter="roleList" lay-skin="primary" title="'+data[i].name+'" value="'+data[i].name+'">';
                                          role_list.push(data[i].name);
                                      } else {
                                          html +='<input type="checkbox" name="like1[write]" lay-filter="roleList" lay-skin="primary" title="'+data[i].name+'" value="'+data[i].name+'">';
                                      }


                                      /*for (var j = 0; j <data.roles.length; j++) {
                                          if (data[i].name == data.roles[j]) {
                                              html +='<input type="checkbox" checked name="like1[write]" lay-filter="roleList" lay-skin="primary" title="'+data[i].name+'" value="'+data[i].name+'">';

                                          } else {
                                              html +='<input type="checkbox" name="like1[write]" lay-filter="roleList" lay-skin="primary" title="'+data[i].name+'" value="'+data[i].name+'">';

                                          }
                                      }*/
                                  }
                                  sessionStorage.setItem('role_list',JSON.stringify(role_list));
                              }
                              $('#role').html(html);
                              form.render();

                              form.on('checkbox(roleList)',function (data) {
                                  if (data.elem.checked) {
                                      role_list.push(data.elem.value);
                                  } else {
                                      role_list.splice(role_list.indexOf(data.elem.value))
                                  }
                                  sessionStorage.setItem('role_list',JSON.stringify(role_list));
                                  sessionStorage.getItem('role_list');
                              });
                          }
                      });
                  }
              })

          } else if(obj.event === 'del'){
              layer.confirm('确认要删除吗？',function(index){
                  //发异步删除数据
                  $.ajax({
                      url:'/auth/del-manager',
                      type:'POST',
                      data:{
                          id:data.id
                      },
                      success:function (res) {
                          if (res.code == 0) {
                              $(obj).parents("tr").remove();
                              return layer.msg('已删除!',{icon:1,time:1000});
                          } else {
                              return layer.msg('删除失败!',{icon:1,time:1000});
                          }
                      }
                  });

              });


          }
      });



      function delAll (argument) {

        var data = tableCheck.getData();
  
        layer.confirm('确认要删除吗？'+data,function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
      });
    </script>
    <script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();</script>
  </body>


<body>
<div class="x-body" style="display:none" id="addManager">
    <form class="layui-form">
        <div class="layui-form-item">
            <input type="text" id="modelId" style="display: none;"
                   >
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>登录名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="username" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>将会成为您唯一的登入名
            </div>
        </div>
        <!--<div class="layui-form-item">
            <label for="phone" class="layui-form-label">
                <span class="x-red">*</span>手机
            </label>
            <div class="layui-input-inline">
                <input type="text" id="phone" name="phone" required="" lay-verify="phone"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>将会成为您唯一的登入名
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="L_email" class="layui-form-label">
                <span class="x-red">*</span>邮箱
            </label>
            <div class="layui-input-inline">
                <input type="text" id="L_email" name="email" required="" lay-verify="email"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="x-red">*</span>角色</label>
            <div class="layui-input-block" id="role">

            </div>
        </div>
        <div class="layui-form-item" id="L_passInput">
            <label for="L_pass" class="layui-form-label">
                <span class="x-red">*</span>密码
            </label>
            <div class="layui-input-inline" >
                <input type="password" id="L_pass" name="pass" required="" lay-verify="pass"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                6到16个字符
            </div>
        </div>
        <div class="layui-form-item" id="L_repassInput">
            <label for="L_repass" class="layui-form-label">
                <span class="x-red">*</span>确认密码
            </label>
            <div class="layui-input-inline">
                <input type="password" id="L_repass" name="repass" required="" lay-verify="repass"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <div  class="layui-btn" lay-filter="add" lay-submit="">
                保存
            </div>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer','table'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer
            ,table = layui.table;
        var role_list = [];
        //自定义验证规则
        form.verify({
            nikename: function(value){
                if(value.length < 5){
                    return '昵称至少得5个字符啊';
                }
            }
        });

        //监听提交
        form.on('submit(add)', function(data){

            var data = {
                username:$('#username').val(),
                email:$('#L_email').val(),
                role:JSON.parse(sessionStorage.getItem('role_list')),
                password:pass_1
            };
            var url = '/auth/create-manager';
            if ($('#modelId').val() != 0) {//修改
                data.id = $('#modelId').val();
                url = '/auth/update-manager';
            } else {
                //自定义验证规则
                form.verify({
                    nikename: function(value){
                        if(value.length < 5){
                            return '昵称至少得5个字符啊';
                        }
                    }
                    ,pass: [/(.+){6,12}$/, '密码必须6到12位']
                    ,repass: function(value){
                        if($('#L_pass').val()!=$('#L_repass').val()){
                            return '两次密码不一致';
                        }
                    }
                });
                var pass_1 = $('#L_pass').val();
                var pass_2 = $('#L_repass').val();
                if (!sessionStorage.getItem('role_list')) {
                    return layer.msg('角色必选',{time:1000});
                }
                if (pass_1 !== pass_2) {
                    return layer.msg('两次密码输入不一致',{time:1000});
                }
                data.password = pass_1;
            }
            $.ajax({
                url:url,
                type:'POST',
                data:data,
                success:function (res) {
                    if (res.code == -402) {
                        return layer.msg('用户已存在',{time:1000});
                    } else if (res.code == -1) {
                        return layer.msg('失败',{time:1000});
                    } else if (res.code == 0) {
                        table.reload('manager',{});
                        layer.closeAll();
                        return layer.msg('成功',{time:1000});

                    }
                }
            });
            /*//发异步，把数据提交给php
            layer.alert("增加成功", {icon: 6},function () {
                // 获得frame索引
                var index = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(index);
            });*/
            return false;
        });


    });
</script>
<script>var _hmt = _hmt || []; (function() {
    var hm = document.createElement("script");
    hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();</script>
</body>