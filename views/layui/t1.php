<script>
//    layui.use(['layer', 'form'], function(){
//        var layer = layui.layer
//            ,form = layui.form;
//
//        layer.msg('Hello World!');
//    });
//
//    layui.config({
//        debug: true
//    });

//    layui.define(function(exports){
//        alert('lang');
//
//        exports('demo', function(){
//            alert('Hello');
//        });
//    });

//    layui.define(['layer', 'laypage'], function(exports){
//        alert('lang');
//
//        exports('demo', function(){
////            layer.msg('hello');
//            alert('hello');
//        });
//    });
//
//    layui.demo();

//    layui.use(['laypage', 'layedit'], function(laypage, layedit){
//        laypage();
//
//        layedit.build();
//    });

    layui.data('test', {
        key: 'nickname'
        ,value: '闲心'
    });
/*
    layui.data('test', {
        key: 'nickname'
        ,remove: true
    });
    layui.data('test', null);
    */

    var localTest = layui.data('test');
//    console.log(localTest.nickname);

    var device = layui.device();
//    console.log(device);

    var device = layui.device('windows');
//    console.log(device);

    var cache = layui.cache
    console.log(cache);
</script>