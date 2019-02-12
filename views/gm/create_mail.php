<style>
    label{width: 20%!important;}
    .layui-input-inline{width: 60%!important;}

</style>
<body>
<div class="x-body">
    <form action="" class="layui-form" >
        <div class="layui-form-item">
            <label class="layui-form-label" >发布对象</label>
            <div class="layui-input-inline" >
                <select name="publishObject " id="publishObject" class="layui-input" >
                    <option value="">全服</option>
                    <option value="">指定对象</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">对象ID</label>
            <div class="layui-input-inline">
                <textarea type="text" id="objectID" class="layui-input" name="objectID"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">标题</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="title" name="title">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">内容</label>
            <div class="layui-input-inline">
                <textarea type="text" id="content" class="layui-input" name="content"></textarea>
            </div>
        </div>
<!--        <div class="layui-form-item">-->
<!--            <label for="" class="layui-form-label">附件</label>-->
<!--            <div class="layui-input-inline">-->
<!--                <textarea type="text" class="layui-input" id="appendix" name="appendix"></textarea>-->
<!--            </div>-->
<!--        </div>-->
        <div class="layui-form-item">
            <label for="" class="layui-form-label">发送时间</label>
            <div class="layui-input-inline"  style="width:10%!important;">
                <input type="text" class="layui-input" id="sendDate" placeholder="日期" name="sendingDate">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="" class="layui-form-label">是否弹出公告</label>
            <div class="layui-input-inline" style="width:10%!important;">
                <select name="announcement" id="announcement">
                    <option value="0">是</option>
                    <option value="1">否</option>
                </select>
            </div>
            <div class="layui-input-inline"  style="width:10%!important;">
                <input type="text" class="layui-input" id="publicDate" placeholder="日期">
            </div>
        </div>
        <div class="layui-col-xs3 layui-col-xs-offset5">
            <button class="layui-btn" lay-submit="" lay-filter="createMail">确认</button>
            <button class="layui-btn" type="reset">重置</button>
        </div>
    </form>
</div>
<script>

    layui.use(['laydate','form'],function () {
        //日期查询;
        var laydate = layui.laydate;
        laydate.render({elem:"#sendDate"});
        laydate.render({elem:"#publicDate"});
        //点击按钮后提交数据
        var form = layui.form;
        form.on('submit(createMail)',function (data) {
            var publishObject = $("#publishObject").val();
            var objectID = $("#objectID").val();
            var title = $("#title").val();
            var content = $("#content").val();
            var appendix = $("#appendix").val();
            var sendDate = $("#sendDate").val();
            var announcement = $("#announcement").val();

            $.ajax({
                url:"/gm/seal"
                ,method:'post'
                ,data:{
                    'publishObject':publishObject,
                    'objectID':objectID,
                    'title':title,
                    'content':content,
                    'appendix':appendix,
                    'sendDate':sendDate,
                    'announcement':announcement
                }
                ,success:function (data) {
                    console.log(data);
                    window.href = "gm/mail-notice";
                }
                ,error:function (data) {
                    alert('请求错误');
                }
            })
        })
    })

</script>
</body>
