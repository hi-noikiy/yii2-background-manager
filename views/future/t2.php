<div class="layui-tab">
    <ul class="layui-tab-title">
        <li class="layui-this">标题一</li>
        <li>标题二</li>
        <li>标题三</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">内容1</div>
        <div class="layui-tab-item">内容2</div>
        <div class="layui-tab->item">内容3</div>
    </div>
</div>

<script>
//    layui.use(['form', 'upload'], function(){
//        var form = layui.form
//            ,upload = layui.upload;
//
//        form.on('submit(test)', function(data){
//            console.log(data);
//        });
//
//        upload({
//            url: '/future/t2'
//            ,success: function(data){
//                console.log(data);
//            }
//        });
//    })

    layui.use(['layer', 'laypage', 'laydate'], function(){
        var layer = layui.layer
            ,laypage = layui.laypage
            ,laydate = layui.laydate;

        var $ = layui.$;
        $(".layui-this").html('郎海礁');

        console.log(layer);
    })
</script>