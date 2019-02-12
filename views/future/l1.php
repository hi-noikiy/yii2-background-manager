<div id="content">
    我是外部内容
</div>


<script>
    layui.use('layer', function(){
        var layer = layui.layer;

//        0（信息框，默认）1（页面层）2（iframe层）3（加载层）4（tips层）
//        layer.open({
//            type: 4,
//            title: ['文本', 'font-size:18px;'],
//            content: '传入任意文本或html'
//        })

//        layer.open({
//            type: 1,
//            content: '传入任意的文本或者html'
//        });

//        layer.open({
//            type: 1,
//            content: $('#content')
//        });

//        $.post('/future/l1', {}, function(str){
//            layer.open({
//                type: 1,
//                content: str
//            });
//        })

//        layer.open({
//            type: 2,
////            content: 'http://www.baidu.com'
//            content: ['http://www.baidu.com', 'no']
//        });

//        layer.open({
//            type: 4,
//            content: ['内容', '#content']
//        });

//        layer.open({
//            skin: 'demo-class'
//        });
//        layer.config({
//            skin: 'demo-class'
//        });

//        layer.open({
//            type: 2,
//            content: 'http://www.baidu.com',
//            offset: ['100px', '500px']
//        });

//        layer.alert('酷毙了', {icon: 1});
//        layer.msg('不开心', {icon: 6});
        layer.confirm('纳尼？', {
            btn: ['按钮一','按钮二', '按钮三'],
            ,btn3: function(index, layero){

        }, function(index, layero){

        }, function(index){

        }
        });
    });
</script>

