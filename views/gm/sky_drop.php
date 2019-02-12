<!-- 天降红包-->
<style>
    .r input{border:none}
    .r tr th{width: 20%;}
    .r tr td{padding:0;}
    .subBtn{width:70px;margin:0 auto;line-height: 90px;}

</style>


<body>
<div class="x-nav">
    <span class="layui-breadcrumb">
        <a href="#">GM工具</a>
        <a>
            <cite>天降红包</cite>
        </a>
    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>

</div>
    <div class="x-body">
        <form action="" class="layui-form InputStyle titleFormStyle" >
            <div class="layui-form-item">
                    <label for="" class="layui-form-label">开关：</label>
                    <div class="layui-input-inline SwitchStyle">
                        <input type="checkbox" class="layui-input" lay-skin="switch" lay-text="ON|OFF" lay-filter="skyDropSwitch" id="skyDropSwitch" <?php if ($data["switch"] == 1) {echo 'checked';}?>>
                    </div>

                <div class="layui-input-inline">
                    <div class="layui-input-inline">
                        <div class="layui-btn " lay-submit="submitSkyDrop" lay-filter="submitSkyDrop">修改
                    </div>
                </div>
            </div>
        </form>
    </div>


    <form action="/gm/send-hongbao" class="layui-form r" method="post">
        <div class="layui-row">
            <div class="layui-col-sm6">
                <table id="HONGBAO" lay-filter="HONGBAO" class="layui-table">
                    <thead>
                    <tr>
                        <th>奖项</th>
                        <th>用户ID</th>
                        <th>金额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th>一等奖</th>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank1uid" id="rank_1_uid"></td>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank1gold" id="rank_1_gold"></td>
                    </tr>
                    <tr>
                        <th>二等奖</th>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank2uid" id="rank_2_uid"></td>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank2gold" id="rank_2_gold"></td>
                    </tr>
                    <tr>
                        <th>三等奖</th>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank3uid" id="rank_3_uid"></td>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank3gold" id="rank_3_gold"></td>
                    </tr>
                     <tr>
                        <th>幸运奖</th>
                        <td>
                            <div class="layui-row">
                            <div class="layui-col-sm5">
                                <input type="text" class="layui-input" lay-verify="required" name="minYB" id="sys_yuanbao_range_1">
                            </div>

                            <div class="layui-col-sm1" style="margin-top:8px;">
                                <span>~</span>
                            </div>
                            <div class="layui-col-sm6">
                                <input type="text" class="layui-input" lay-verify="required" name="maxYB" id="sys_yuanbao_range_2">
                            </div>
                        </div>
                        </td>
                        <td><input type="text" class="layui-input" lay-verify="required" name="rank4gold" id="rank_4_gold"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-row">
            <div class="layui-col-sm2">&nbsp;
            </div>
            <div class="layui-col-sm2">
                <button class="layui-btn" type="submit" lay-submit >发奖</button>
            </div>
            <div class="layui-col-sm8">&nbsp;
            </div>
        </div>
    </form>
    <div class="layui-col-sm12">
        <div class="layui-col-sm6">
            <p class="temp-title-cn">
                <caption><h2>当前发奖总额度: <?php echo $data['totalCost']?> 元宝</h2></caption>
            </p>
        </div>
        <br/><br/><br/><br/>
        <div class="layui-col-sm12"></div>
        <div class="layui-col-sm12"></div>
        <div class="layui-col-sm2">
            <form class="layui-form">
            <div class="layui-form">
                <div class="layui-form-item">
                    <div class="layui-input-inline" style="margin-left: 0;" >

                        <select name=""  id="hbtimes" lay-filter="changeGame">
                            <option value=<?php echo $data['times']['maxTimes']?> selected>选择时间</option>
                            <?php for($i=1; $i<=$data['times']['maxTimes']; $i++){ ?>
                                <option value=<?php echo $i?>><?php echo '第'.$i.'次'?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="layui-btn" style="margin-left:-4px;" data-type="search" id="search">查询</div>
            </div>
        </form>
        </div>

    </div>
    
    <table class="layui-table" id="hongbaoListTable" lay-filter="sort"></table>
</div>


<script>
    /**
     * 表格渲染
     */
    layui.use(['table','laydate'],function () {
        var table = layui.table;
        var $ = layui.$;
        table.render({
            elem:'#hongbaoListTable'

            ,url:'/gm/query-hongbao'
            ,method:"get"
            ,page:false
            ,where:{
                    hbTimes:$("#hbtimes").val(),
                }
            ,cols:[[
                {field:'rank',title:'奖项'}
                ,{field: 'uid', title: '获奖ID'}
                ,{field:'gold',title:'金额(元宝)'}
                ,{field:'create_time',title:'发奖时间'}
                ,{field:'total',title:'总计'}
            ]]
            , done: function (res, curr, count) {
                merge(res, curr, count);
            }
        });
        //查询
        $('#search').on('click',function () {
            table.reload('hongbaoListTable',{
                url:'/gm/query-hongbao',
                method: 'get',
                where:{
                    hbTimes:$("#hbtimes").val(),
                }
            })
        });
    })


    /**
     * 开关
     */
    layui.use(['table','layer','laydate','form'],function () {
        var laydate = layui.laydate;
        var  $ = layui.$;
        var form = layui.form;
        var table = layui.table;
        form.on('submit(submitSkyDrop)',function () {
            $.ajax({
                url:'/gm/hongbao-open',
                type:'GET',
                data:{
                    hbSwitch:$("#skyDropSwitch").is(":checked")
                },
                success:function (res) {
                    if (res.code == 0){
                        alert("修改成功");
                    } else {
                        alert("修改失败");
                    }
                    console.log(res.code);
                }
            });
        });
    });
    
</script>
</body>
