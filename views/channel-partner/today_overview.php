<fieldset class="layui-elem-field">
    <legend>数据统计</legend>
    <div class="layui-field-box">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                        <ul class="layui-row layui-col-space10 layui-this ">
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['recharge'];?></cite></p>元</span></div>
                                    <div>今日: 充值额度</div>
                                </div>

                            </li>
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <p><cite><?php echo $data['consume'];?></cite></p>
                                    <p>今日: 元宝消耗</p>
                                </div>
                            </li>
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <p><cite><?php echo $data['deposit'];?></cite></p>
                                    <div>今日: 元宝淤积</div>
                                </div>
                            </li>
                            <li class="layui-col-md3">
                                <div class="x-admin-backlog-body">
                                    <div><span>RMB:<p style="display: inline-block"><cite><?php echo $data['agent_ti'];?></cite></p>元</span></div>
                                    <div>今日: 提现额度</div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>
