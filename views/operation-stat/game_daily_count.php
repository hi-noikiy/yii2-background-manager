<body>
<style>
    #dateTd {
        border: 1px solid #ddd;
        word-break: normal;
    }
</style>
<div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">运营统计</a>
            <a>
                <cite>游戏日报</cite>
            </a>
        </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新"><i class="layui-icon" style="line-height:30px">&#xe669;</i></a>
</div>
<div class="x-body">
    <form action="/operation-stat/game-daily-count" method="post" class="layui-form">
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="开始日期" value="<?php echo $startTime; ?>" id="startTime" name="startTime">
            </div>
            <div class="layui-input-inline">
                <input type="text" class="layui-input" placeholder="结束日期" value="<?php echo $endTime; ?>" id="endTime" name="endTime">
            </div>
            <div class="layui-input-inline">
                <select name="game_id" id="game_id" class="layui-input">
                    <?php foreach ($games as $key=>$val){ ?>
                        <option value=<?php echo $key;?> <?php if($key == $game_id){echo 'selected';}?> ><?php echo $val;?></option>
                    <?php } ?>
                </select>
            </div>
            <button class="layui-btn" type="submit"><i class="layui-icon">&#xe615;</i></button>
        </div>
    </form>
    <div style="width:100%px; height:100%; overflow:scroll;">
    <table class="layui-table" id="dayinfo_id">
            <tr id="statis_title">
                <td colspan="2">类别</td>
                <?php if(isset($data['date'])){ ?>
                    <?php foreach ($data['date'] as $k=>$v){ ?>
                        <td id="dateTd"><?php echo $v;?></td>
                    <?php } ?>
                <?php } ?>
            </tr>
            <?php if(isset($data['info'])){ ?>
                <?php foreach ($data['info'] as $keyInfo=>$valInfo){ ?>
                    <tbody>
                        <tr>
                            <td rowspan="4" style="vertical-align: middle;text-align: center;"><?php echo $keyInfo;?></td>
                            <td>活跃人数</td>
                            <?php foreach ($valInfo['active'] as $keyActive=>$valActive){ ?>
                                <td><?php echo $valActive;?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>元宝消耗</td>
                            <?php foreach ($valInfo['consume'] as $keyConsume=>$valConsume){ ?>
                                <td><?php echo $valConsume;?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>元宝消耗（占比）</td>
                            <?php foreach ($valInfo['prop'] as $keyProp=>$valProp){ ?>
                                <td><?php echo $valProp;?>%</td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>消耗环比上日</td>
                            <?php foreach ($valInfo['ring_ratio'] as $keyRingRatio=>$valRingRatio){ ?>
                                <td><?php echo $valRingRatio;?></td>
                            <?php } ?>
                        </tr>
                    </tbody>
                <?php } ?>
            <?php } ?>
        </table>
    </div>
</div>
<script>
    //日期查询
    layui.use('laydate',function(){
        var laydate = layui.laydate;
        laydate.render({elem:'#startTime'});
        laydate.render({elem:'#endTime'});
    });

</script>
</body>

