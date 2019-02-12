<!-------玩家实名认证------->
<body>
<div class="x-body">
    <div class="layui-row">
        <div class="layui-col-xs3">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">认证日期</label>

                <div class="layui-input-inline">
                    <input type="text" class="layui-input" id="Date">
                </div>
            </div>
        </div>
        <div class="layui-col-xs4">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" id="searchInput">
            </div>
            <button class="layui-btn" id="search">查询</button>
        </div>
        <div class="layui-col-xs1 layui-col-sm-offset3">
            <button class="layui-btn" id="out_put_excel">导出</button>
        </div>
    </div>
    <table id="authentication" class="layui-table" lay-filter="authentication"></table>
</div>
</body>
<script>
    layui.use(['table','form','laydate'],function () {
        var table = layui.table;
        var form = layui.form;
        var laydate = layui.laydate;
        table.render({
            elem:'#authentication'
            ,url:'/player/player-auth'
            ,page:true
            ,cols:[[
                {field:"id",title:"序号"}
                ,{field:"auth_time",title:"认证日期"}
                ,{field:"reg_time",title:"注册日期"}
                ,{field:"player_id",title:"玩家ID"}
                ,{field:"nickname",title:"玩家昵称"}
                ,{field:"phone_num",title:"手机号"}
                //,{field:"created_time",title:"微信"}
                ,{field:"ip",title:"IP"}
                ,{field:"province",title:"地址"}
                ,{field:"machine_code",title:"设备信息"}

            ]]
        });
        laydate.render({
            elem:'#Date',
            done:function (value,date,endDate) {
                table.reload('authentication',{
                    url:'/player/player-auth',
                    where:{
                        time:value,
                        //player_id:$('#searchInput').val(),
                    }
                });
            }
        })
        $('#search').on('click',function () {
            table.reload('authentication',{
                url:'/player/player-auth',
                where:{
                    time:$('#Date').val(),
                    player_id:$('#searchInput').val(),
                    //page:true

                }
            });
        })

        //创建表单提交

        //导出
        $("#out_put_excel").on('click',
            function () {
                var downLoad = {
                    url: '/player/player-auth',
                }
                var form = $("<form>");//定义一个form表单
                form.attr("style", "display:none");
                form.attr("target", "");
                form.attr("method", "get");
                form.attr("action", downLoad.url);//URL
                var input = $("<input>");
                input.attr("type", "hidden");
                input.attr("name", "arrFile");
                input.attr("value", JSON.stringify({
                    time:$('#Date').val(),
                    player_id:$('#searchInput').val(),
                    page:$(".layui-laypage-curr em:eq(1)")[0].innerHTML,
                    limit:$(".layui-laypage-limits [selected]").val(),
                    type:1
                }));
                form.append(input);

                $("body").append(form);//将表单放置在web中

                form.submit();//表单提交
                form.remove();//移除该临时元素


            }
        )

        /*$('#out_put_excel').on('click',function () {
            $.ajax({
                url:'/player/player-auth',
                data:{
                    time:$('#Date').val(),
                    player_id:$('#searchInput').val(),
                    page:true,
                    type:1
                }
            });
        })*/
    })
</script>