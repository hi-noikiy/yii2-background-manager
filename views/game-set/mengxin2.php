<body>
<div class="x-body">
    <form class="layui-form" action="/game-set/mengxin2" method="post">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">触发概率：</label>
                <div class="layui-input-inline">
                    <input type="text" name="probability" class="layui-input">
                </div>
                <div class="layui-form-mid">%</div>

                <label class="layui-form-label">局数：</label>
                <div class="layui-input-inline">
                    <input type="text" name="jushu" class="layui-input">
                </div>

                <label class="layui-form-label">时长(小时)：</label>
                <div class="layui-input-inline">
                    <input type="text" name="max_time" class="layui-input">
                </div>

                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit lay-filter="formDemo">修改</button>
                </div>
            </div>

            <div class="layui-inline">
                <div class="layui-input-inline">
                    <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" lay-filter="status">
                </div>
            </div>
        </div>
    </form>

    <script>
        layui.use('form', function(){
            var form = layui.form;

            form.on('switch(status)', function(data){
                console.log(data);
                console.log(data.elem); //得到checkbox原始DOM对象
                console.log(data.elem.checked); //开关是否开启，true或者false
                console.log(data.value); //开关value值，也可以通过data.elem.value得到
                console.log(data.othis); //得到美化后的DOM对象

                $.ajax({
                    url: '/game-set/mengxin2',
                    method: 'post',
                    data: data.elem.checked,
                    dataType: 'JSON',
                    success: function(res) {
                        if(res.code==0){

                        }else{

                        }
                    },
                    error:function(data){

                    }
                });
                return false;
            });
        });
    </script>

    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>

    <div class="layui-col-xs12 layui-col-md4">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="开始日期" id="startTime">
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" placeholder="结束日期" id="endTime">
        </div>
        <div class="layui-btn" data-type="search" id="search">查询</div>
    </div>

    <table class="layui-table" id="newSetting" lay-filter="sort">
        <caption><h2>萌新统计</h2></caption>
    </table>
    <table class="layui-table" id="warning">
        <caption><h2>异常警告</h2></caption>
    </table>
    <h2 style="text-align: center">操作记录</h2>
    <div style="background-color: #F2F2F2;padding:10px;">
        <p id="operationRecord">111</p>
    </div>
</div>



<script>
//    layui.use(['table','laydate','form'],function () {
//        var $ = layui.$;
//        //日期
//        var laydate = layui.laydate;
//        laydate.render({elem:'#startTime'});
//        laydate.render({elem:'#endTime'});
//        //table渲染
//        var table = layui.table;
//        table.render({
//            elem:'#newSetting'
//            ,url:'/test/t205'
//            ,page:true
//            ,cols:[[
//                {field:'date',title:'日期',sort:true}
//                ,{field:'newUserNum',title:'新用户人数'}
//                ,{field:'totalSiteNum',title:'总场次'}
//                ,{field:'fiveWin',title:'满足5局赢用户'}
//                ,{field:'gameLostNum',title:'输掉的场次'}
//                ,{field:'winYB',title:'赢元宝'}
//                ,{field:'LostYB',title:'输元宝'}
//            ]]
//        });
//        table.render({
//            elem:'#warning'
//            ,url:'/test/t205'
//            ,page:true
//            ,cols:[[
//                {field:'userID',title:'用户ID'}
//                ,{field:'userName',title:'用户名称'}
//                ,{field:'winNum',title:'胜利次数'}
//                ,{field:'',title:'操作'}
//            ]]
//        });
//        //排序
//        table.on('sort(sort)',function (obj) {
//            table.reload('newSetting',{
//                url:'/test/t205'
//                ,initSort:obj
//                ,where:{
//                    field:obj.field
//                    ,order:obj.type
//                }
//            })
//        });
//        //查询
//        var active = {
//            search:function () {
//                var startTime = $('#startTime').val();
//                var endTime = $('#endTime').val();
//                table.reload('newSetting',{
//                    url:'/test/t204'
//                    ,page:{
//                        curr:1
//                    }
//                    ,where:{
//                        key:{
//                            startTime:startTime
//                            ,endTime:endTime
//                        }
//                    }
//                })
//            }
//        };
//        $('#search').on('click', function(){
//            var type = $(this).data('type');
//            active[type] ? active[type].call(this) : '';
//        });
//        //form事件
////        var form = layui.form;
//        //修改
////        form.on('submit(revise)',function (data) {
////            $.ajax({
////                type:'POST'
////                ,data:{
////                    'probability':data.field.probability,
////                    'number':data.field.number
////                }
////                ,url:''
////                ,success:function () {
////
////                }
////                ,error:function () {
////
////                }
////            })
////        });
//
//        //暂停
////        form.on('submit(changeBtn)',function (data) {
////            if ($('#changeBtn').hasClass("stop")){
////                $('#changeBtn').removeClass('stop');
////                $('#changeBtn').removeClass('layui-btn-danger');
////                $('#changeBtn').addClass('start');
////                $('#changeBtn').html('开启');
////            }else{
////                $('#changeBtn').removeClass('start');
////                $('#changeBtn').addClass('stop');
////                $('#changeBtn').addClass('layui-btn-danger')
////                $('#changeBtn').html('暂停');
////            }
////
////            $.ajax({
////                type:'POST'
////                ,data:{
////
////                }
////                ,url:''
////                ,success:function () {
////
////                }
////                ,error:function () {
////
////                }
////            })
////        });
//        //从后台获取操作记录并显示
//        $.ajax({
//            type:"GET"
//            ,url:'/test/t206'
//            ,data:{}
//            ,dataType:'JSON'
//            ,success:function (val) {
//                console.log(val);
//                // var data = JSON.parse(val);
//                // console.log(data.number);
//                console.log(val.data[0].number);
//                $('#operationRecord').html(val.data[0].number);
//            }
//        })
//    })
</script>
</body>