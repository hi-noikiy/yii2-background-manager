<style>
    .layui-form-label{width:120px;}
</style>
<body>

<div class="x-body" >
    <div class="layui-form-item">
        <a href="/game-set/activity-index"><i class="layui-icon layui-icon-left" style="font-size: 20px; color: #666666;">返回</i></a>
    </div>
    <table class="layui-table" id="historyTable" lay-filter="historyTable"></table>
</div>
</body>
<script type="text/html" id="barhistoryBtn">
    <a class="" lay-event="check" title="查看"><i class="layui-icon">&#xe63c;</i></a>
</script>
<script>
    layui.use(['table','layer','form'],function () {
        var table = layui.table;
        var form = layui.form;
        table.render({
            elem:"#historyTable"
            ,url:"/game-set/activity-history"
            ,page:true
            ,cols:[[
                {type:'numbers',title:"序号"}
                ,{field:'title',title:"活动标题",sort:true}
                ,{field:'start_time',title:"开始时间",sort:true}
                ,{field:'end_time',title:"结束时间",sort:true}
                ,{field:'jump_url',title:"跳转",templet:function (d) {
                        if (d.jump_type ==2) {
                            if (typeof d.jump_content != "undefined") {
                                if (d.jump_content.length > 0) {
                                    return d.jump_content[0]+'_'+d.jump_content[1]
                                } else {
                                    return d.jump_url
                                }
                            } else {
                                return d.jump_url
                            }
                        } else {
                            return d.jump_url
                        }
                    }}
                ,{field:'',title:"操作",toolbar:'#barhistoryBtn',width:100}
            ]]
        });
        table.on('tool(historyTable)', function(obj){
            var data = obj.data;
            if(obj.event === 'check'){
                layer.open({
                    type:1
                    ,title:'详情'
                    ,closeBtn:1
                    ,shade: 0.8
                    ,anim:3
                    ,maxmin:true
                    ,area:['50%','70%']
                    ,content:$('#checkActive')
                    ,success:function (layero,index) {
                        /*var linkVal=$('#link').next().children('div').children().val();
                        if(linkVal=="外部跳转"){
                            $('#leve1').css('display','none');
                            $('#leve2').css('display','none');
                            $('#input1').css('display','')
                        }else{
                            $('#input1').css('display','none');
                            $('#leve1').css('display','block');
                            $('#leve2').css('display','block');
                        }*/
                        $('#title').val(data.title);
                        $('#startDate').val(data.start_time);
                        $('#endDate').val(data.end_time);
                        $('#goods_num').val(data.goods_num);
                        $('#demo1').attr('src','');
                        $('#demo2').attr('src','');
                        $('#demo1').attr('style','');
                        $('#demo2').attr('style','');
                        if (typeof data.title_url != "undefined") {
                            if (data.title_url.length>0) {
                                $('#demo1').attr('src',data.title_url);
                                $('#demo1').attr('style','width:84px;height:84px');
                            }
                        }
                        if (typeof data.img_url != "undefined") {
                            if (data.img_url.length>0) {
                                $('#demo2').attr('src',data.img_url);
                                $('#demo2').attr('style','width:84px;height:84px');
                            }
                        }
                        if (data.jump_type == 1) {
                            $("[name='jump_type']").html('<option  selected value="">外部跳转</option>');
                            $('#leve1').css('display','none');
                            $('#leve2').css('display','none');
                            $('#input1').css('display','')

                        } else if (data.jump_type) {//内部跳转
                            $("[name='jump_type']").html('<option selected>内部跳转</option>');
                            $('#input1').css('display','none');
                            $('#leve1').css('display','block');
                            $('#leve2').css('display','block');
                            if (typeof data.jump_content != "undefined") {
                                if (data.jump_content.length > 0) {
                                    $('#leve1').html('<select disabled><option selected value="">'+data.jump_content[0]+'</option></select>');
                                    $('#leve2').html('<select disabled><option selected value="">'+data.jump_content[1]+'</option></select>');
                                }
                            }

                        }
                        $('#jump_url').val(data.jump_url)
                        form.render('select')
                    }
                    ,yes:function (index,layero) {

                    }
                })
            }
        })

    })

</script>

<div class="x-body" style="display: none" id="checkActive">
    <form action="" class="layui-form">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">活动标题</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input"  lay-verify="required" name="title" id="title" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="startDate" name="date" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="endDate" name="date" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">上传标签图片</label>
            <!--<div class="layui-input-inline">-->
            <!--<input type="file" name="fileUpload">-->
            <!--</div>-->
            <div class="layui-upload">
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo1">
                    <p id="demoText2"></p>
                </div>
            </div>

        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">上传内容图片</label>
            <!--<div class="layui-input-inline">-->
            <!--<input type="file" name="fileUpload">-->
            <!--</div>-->
            <div class="layui-upload">
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo2">
                    <p id="demoText"></p>
                </div>
            </div>

        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">是否跳转</label>
            <div class="layui-input-inline">
                <select name="jump_type" id="link" lay-filter="link" disabled>
                    <option value="0">外部跳转</option>
                    <option value="1">内部跳转</option>
                </select>
            </div>
            <div class="layui-input-inline" id="input1">
                <input type="text" class="layui-input" id="jump_url" readonly>
            </div>
            <div class="layui-input-inline" style="display: none" id="leve1" >
                <select name="" disabled>
                    <option value="">1</option>
                </select>
            </div>
            <div class="layui-input-inline" style="display: none" id="leve2">
                <select name="" disabled>
                    <option value="">1</option>
                </select>
            </div>

        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">奖励</label>
            <div class="layui-input-inline">
                <select name="jump" id="" disabled>
                    <option value="0" selected>元宝</option>
<!--                    <option value="1">钻石</option>-->
                </select>
            </div>
            <div class="layui-input-inline">
                <input type="number" class="layui-input" id="goods_num" readonly>
            </div>
        </div>
        <!--<div class="layui-form-item">-->
        <!--<label for="" class="layui-form-label">跳转地址</label>-->
        <!--<div class="layui-input-inline">-->
        <!--<input type="text" class="layui-input" name="jumpLink">-->
        <!--</div>-->
        <!--</div>-->
    </form>
</div>