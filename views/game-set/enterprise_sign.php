<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">游戏系统设置</a>
        <a>
            <cite>企业签设置</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
<div class="x-body">
    <div class="titleFormStyle">
        <div class="layui-btn" style="float:left" data-method="add" id="add"><i class="layui-icon">&#xe61f;</i>添加企业签</div>
    </div>
    <hr>
    <div style="width:500px;margin:0 auto;">
        <label for="" class="layui-form-label">选择企业签</label>
        <select name="" id="enterprise" multiple="multiple" style="height:400px;width:500px;">
            <option value="1">1</option>
            <option value="2">12</option>
            <option value="3">123</option>
            <option value="4">1234</option>
            <option value="5">12345</option>
            <option value="6">123456</option>
            <option value="7">12345</option>
            <option value="8">1234</option>
            <option value="9">123</option>
            <option value="10">12</option>
        </select>
        <div style="width:64px;margin:20px auto;">
            <div class="layui-btn">确认</div>
        </div>

    </div>
</div>
</body>
<script>
    layui.use(['form'], function() {
        var form = layui.form;
        var $ = layui.$;
        var active = {
            add:function(){
                layer.open({
                    type:1
                    ,title:'添加企业签'
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['40%','30%']
                    //,shade:0.5
                    ,id:"LAY_layuipro"
                    ,btn:['确认','取消']
                    ,btnAlign:'c'
                    ,moveType:1
                    ,content:$('#addLayer')
                    ,yes:function (index, layero) {
                        var addsignval = $('#signName').val();
                        var options = document.createElement('option');
                        $(options).html(addsignval);
                        console.log(options);
                        $('#enterprise').append(options);
                        layer.close(index);
                    }
                })
            }
        };
        $('#add').on('click',function () {
            var othis = $(this),method = othis.data('method');
            active[method]?active[method].call(this.othis):'';
        });

        // $('#submitAdd').click(function () {
        //     var addsignval = $(this).prev().children().val()
        //     var options = document.createElement('option')
        //     $(options).html(addsignval);
        //     $('#enterprise').append(options)
        // })
    })
</script>

<div class="x-body" style="display:none" id="addLayer">
    <form action="" class="layui-form" style="width:300px;margin:0 auto;">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="请输入要添加的企业签" id="signName">
        </div>
        <!--<div class="layui-btn" id="submitAdd">添加</div>-->
    </form>
</div>