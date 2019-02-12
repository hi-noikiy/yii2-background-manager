<style>
  /*.x-nav{margin-bottom:10px!important;padding:0!important;}*/
  .BGO{background-color: #EEEEEE;padding:1px;}
</style>
  
  <body >
  <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">平台系统设置</a>
            <a>
                <cite>权限分类</cite>
            </a>
        </span>
      <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
  </div>
  <div class="x-body" >
    <!--<div class="x-body">-->
      <div class="layui-row ">
        <form class="layui-form layui-col-md12 x-so layui-form-pane BGO">
          <input class="layui-input" placeholder="分类名" name="cate_name" id="cate_name">
          <button class="layui-btn"  lay-submit="" id="addCate"><i class="layui-icon"></i>增加</button>
        </form>
      </div>
      <!--<xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" id="addRule"><i class="layui-icon"></i>增加权限</button>
      </xblock>-->
      <table class="layui-table" id="rule" lay-filter="rule"></table>
  </div>
    <!--</div>-->
  <script type="text/html" id="barDemo">
      <div class="layui-btn layui-btn-xs" lay-event="edit">编辑</div>
      <div class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</div>
  </script>
    <script>
      layui.use(['laydate','table'], function(){
        var laydate = layui.laydate;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });


      var table = layui.table;
      table.render({
          elem:"#rule"
          ,url:"/auth/permission-category-list"
          ,page:true
          ,cols:[[
              //{type:"checkbox",title:""}
              //,{field:"id",title:"ID"}
              {field:"name",title:"分类名"}
              ,{title:"操作",toolbar: '#barDemo'}
          ]]
      });
          table.on('tool(rule)', function(obj){
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
                      ,title: "编辑权限" //不显示标题栏
                      ,closeBtn: 1
                      ,area: ['40%','40%']
                      ,shade: 0.8
                      ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                      // ,btn: ['添加黑名单','返回']
                      ,btnAlign: 'c'
                      ,moveType: 1 //拖拽模式，0或者1
                      ,content:$('#editrole')
                      ,success:function () {
                        old_category = data.name;
                        $('#category').val(data.name);
                      }
                  })

              } else if(obj.event === 'del'){
                  layer.confirm('确认要删除吗？',function(index){
                      //发异步删除数据
                      $.ajax({
                          url:'/auth/del-permission-category',
                          data:{
                              category:data.name
                          },
                          success:function (res) {
                              if (res.code == 0) {
                                  layer.msg('成功',{time:1000});
                                  table.reload('rule',{
                                  });
                              } else {
                                  return layer.msg('失败',{time:1000});
                              }
                          }
                          
                      });
                      $(obj).parents("tr").remove();
                      layer.msg('已删除!',{icon:1,time:1000});
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
      $('#addCate').on('click',function () {
          $.ajax({
              url:'/auth/add-permission-category',
              type:'POST',
              data:{
                  category:$('#cate_name').val()
              },
              success:function (res) {

              }
          });
      });
      $('#addRule').on('click',function () {
          layer.open({
              type: 1
              ,title: "编辑权限" //不显示标题栏
              ,closeBtn: 1
              ,area: ['40%','40%']
              ,shade: 0.8
              ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
              // ,btn: ['添加黑名单','返回']
              ,btnAlign: 'c'
              ,moveType: 1 //拖拽模式，0或者1
              ,content:$('#editrole')
              ,success:function () {
                  $.ajax({
                      url:'/auth/update-permission-category',
                      type:'POST',
                      data:{
                          category:$('#category').val(),
                          //description:$('#permission').val()
                      },
                      success:function (res) {

                      }
                  });
              }
          })
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


<body>
<div class="x-body" id="editrole" style="display:none;">
    <form class="layui-form">
        <!--<div class="layui-form-item">
            <label for="username" class="layui-form-label">
                权限名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="permission" name="permission" required=""
                       autocomplete="off" class="layui-input" >
            </div>
        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">
                <span class="x-red">*</span>分类名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="category" name="category" required=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">
            </label>
            <button  class="layui-btn"  lay-filter="add" lay-submit="">
                修改
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //自定义验证规则
        form.verify({
            // nikename: function(value){
            //     if(value.length < 5){
            //         return '昵称至少得5个字符啊';
            //     }
            // }
            // ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            // ,repass: function(value){
            //     if($('#L_pass').val()!=$('#L_repass').val()){
            //         return '两次密码不一致';
            //     }
            // }
        });

        //监听提交
        form.on('submit(add)', function(data){
            //添加权限
            $.ajax({
                url:'/auth/update-permission-category',
                type:'POST',
                data:{
                    old_category:old_category,
                    category:$('#category').val(),
                    //permission:$('#permission').val()
                },
                success:function (res) {

                }
            });
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

</html>