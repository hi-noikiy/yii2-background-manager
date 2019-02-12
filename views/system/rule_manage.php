<style>
  .x-nav{margin-bottom:10px!important;padding:0!important;}
  .BGO{background-color: #EEEEEE;padding:1px;}
</style>
  
  <body >
  <div class="x-body">
  <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">平台系统设置</a>
            <a>
                <cite>权限管理</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
  </div>
    <!--<div class="x-body">-->
      <div class="layui-row ">
        <form class="layui-form layui-col-md12 x-so layui-form-pane BGO">
          <div class="layui-input-inline">
            <select name="cateid">
              <option>规则分类</option>
              <option>文章</option>
              <option>会员</option>
              <option>权限</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="contrller">
              <option>请控制器</option>
              <option>Index</option>
              <option>Goods</option>
              <option>Cate</option>
            </select>
          </div>
          <div class="layui-input-inline">
            <select name="action">
              <option>请方法</option>
              <option>add</option>
              <option>login</option>
              <option>checklogin</option>
            </select>
          </div>
          <input class="layui-input" placeholder="权限名" name="cate_name" >
          <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加</button>
        </form>
      </div>
      <xblock>
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
      </xblock>
      <table class="layui-table" id="ruleManage" lay-filter="ruleManage"></table>

  </div>
    <!--</div>-->
  <script type="text/html" id="barDemo">
    <a class="layui-icon" lay-event="edit">&#xe642;</a>
    <a class="layui-icon" lay-event="del">&#xe640;</a>
  </script>
    <script>
      layui.use(['laydate','table'], function(){
        var laydate = layui.laydate;

          var table = layui.table;
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });


          table.render({
              elem:"#ruleManage"
              ,url:"/test/t206"
              ,page:true
              ,cols:[[
                  {type:"checkbox",title:""}
                  ,{field:"ID",title:"ID"}
                  ,{field:"ID",title:"权限规则"}
                  ,{field:"ID",title:"权限名称"}
                  ,{field:"ID",title:"权限分类"}
                  ,{title:"操作",toolbar: '#barDemo'}
              ]]
          });
          table.on('tool(ruleManage)', function(obj){
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
                      ,title: "添加管理员" //不显示标题栏
                      ,closeBtn: 1
                      ,area: ['40%','50%']
                      ,shade: 0.8
                      ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                      // ,btn: ['添加黑名单','返回']
                      ,btnAlign: 'c'
                      ,moveType: 1 //拖拽模式，0或者1
                      ,content:$('#ruleManage1')
                      ,success:function () {

                      }
                  })

              } else if(obj.event === 'del'){
                  layer.confirm('确认要删除吗？',function(index){
                      //发异步删除数据
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
<div class="x-body" id="ruleManage1" style="display: none">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                ID
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="username" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">
                <span class="x-red">*</span>权限规则
            </label>
            <div class="layui-input-inline">
                <input type="text" id="rule" name="phone" required="" lay-verify="phone"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">
                <span class="x-red">*</span>权限名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name1" name="phone" required="" lay-verify="phone"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">
                <span class="x-red">*</span>权限分类
            </label>
            <div class="layui-input-inline">
                <input type="text" id="type" name="phone" required="" lay-verify="phone"
                       autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                增加
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

        //监听提交
        form.on('submit(add)', function(data){
            console.log(data);
            //发异步，把数据提交给php
            layer.alert("增加成功", {icon: 6},function () {
                // 获得frame索引
                var index = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(index);
            });
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

</html>