//获取时间：

function getTime() {
    var d1 = new Date();

    var year = d1.getFullYear();
    var month = d1.getMonth() + 1;
    var date = d1.getDate();
    var hour = d1.getHours();
    var minute = d1.getMinutes();
    var second = d1.getSeconds();

    var getTime = document.getElementById('getTime');

    getTime.innerHTML = year + '年' + fn(month) + '月' + fn(date) + '日 ' + fn(hour) + ':' + fn(minute) + ':' + fn(second);
}

function fn(str) {
    var num;
    num = str < 10 ? '0' + str : str;
    return num;
}

setInterval(function () {
    getTime();
}, 1000);


//弹出框提示：
$.alert = function (msg) {
    $msgBox = $('<div class="msg-box"><div class="inner"><p>提示信息</p><span class="msg">' + msg + '</span><span class="al">&times;</span></div></div>').prependTo($(document.body));
    $('.al').on('click', function () {
        $msgBox.remove();
    });
}

//获取url
function UrlSearch() {
    var name, value;
    var str = location.href; //取得整个地址栏
    var num = str.indexOf("?")
    str = str.substr(num + 1); //取得所有参数   stringvar.substr(start [, length ]

    var arr = str.split("&"); //各个参数放到数组里
    for (var i = 0; i < arr.length; i++) {
        num = arr[i].indexOf("=");
        if (num > 0) {
            name = arr[i].substring(0, num);
            value = arr[i].substr(num + 1);
            this[name] = value;
        }
    }
}
var Request = new UrlSearch(); //实例化

// var base_url = "http://dltest.sparkingfuture.com/basic/web/index.php?gid=" +Request.gid ;
var base_url = "http://" + location.host + "/index.php?gid=" + Request.gid;


//刷新页面
function myrefresh() {
    window.location.reload();
}

//查询代理
$('#edit_par_id').hide();
$('.blank button').hide();
$('.edit_box').hide();

$('#ser').on('click', function () {

    if ($('#id').val() == '') {
        $.alert('请填写用户ID');
        return false;
    } else {
        var id = $('#id').val();
        $.ajax({
            type: 'post',
            url: base_url + '&r=admin/get-daili-info',
            data: {player_index: id},
            success: function (res) {
                // console.log(res);
                var json = res;
                if (json.ret_code == 0) {

                    $('.PLAYER_INDEX').html(json.data.PLAYER_INDEX);
                    $('.NAME').html(json.data.NAME);
                    $('.TRUE_NAME').html(json.data.TRUE_NAME);
                    $('.DAILI_LEVEL').html(json.data.DAILI_LEVEL);
                    $('.TEL').html(json.data.TEL);
                    $('.ADDRESS').html(json.data.ADDRESS);
                    $('.CREATE_TIME').html(json.data.CREATE_TIME);
                    $('.bank_account').html(json.data.BANK_ACCOUNT);


                    $('#d_PLAYER_INDEX').html(json.data.parent_info.PLAYER_INDEX);
                    $('#d_NAME').html(json.data.parent_info.NAME);
                    $('#TIME').html(json.data.parent_info.CREATE_TIME);
                    $('#LEVEL').html(json.data.parent_info.DAILI_LEVEL);

                    $('#edit_par_id').show();
                    $('.blank button').show();
                    $('.edit_box').show();
                } else {
                    $.alert('该id不存在，请确认后重新输入');

                }

                //修改该查询帐号所绑定的上级ID
                $('#edit_par_id').on('click', function () {

                    // var PLAYER_INDEX = $('.PLAYER_INDEX').html();
                    var d_PLAYER_INDEX = $('#d_PLAYER_INDEX').html();

                    $msgBox = $('<div class="msg-box"><div class="inner"><p>请修改：</p><input type="text" id="daili_id_input" placeholder="' + d_PLAYER_INDEX + '"><button id="edit_id_btn">修改</button><span class="al">&times;</span></div></div>').prependTo($(document.body));
                    $('.al').on('click', function () {
                        $msgBox.remove();
                        return false;
                    });

                    $('#edit_id_btn').on('click', function () {
                        var parent_index = $('#daili_id_input').val();
                        if (parent_index != "") {
                            $.ajax({
                                type: 'post',
                                url: base_url + '&r=admin/updata-daili-parentindex',
                                data: {player_index: id, parent_index: parent_index},
                                success: function (res) {
                                    var json = res;
                                    if (json.ret_code === 0) {
                                        $msgBox.remove();
                                        $.alert('修改成功');
                                        $('#d_PLAYER_INDEX').html(parent_index);
                                    } else {
                                        $msgBox.remove();
                                        $.alert('修改失败，请重试或联系客服');
                                    }
                                }
                            });
                        } else {
                            $('#daili_id_input').attr('placeholder', '不能为空');
                        }
                    });
                });

                //更改代理级别：
                $('.edit_daili_level').on('click', function () {
                    var level = $('.DAILI_LEVEL').html();
                    $msgBox = $('<div class="msg-box"><div class="inner"><p>请更改：</p><input type="text" id="input_edit" placeholder=""><button id="update">修改</button><span class="al">&times;</span></div></div>').prependTo($(document.body));
                    $('.al').on('click', function () {
                        $msgBox.remove();
                        return false;
                    });
                    $('#update').click(function () {
                        var level_new = $('#input_edit').val();
                        if (level_new != '') {
                            $.ajax({
                                type: 'post',
                                url: base_url + '&r=admin/updata-daili',
                                data: {player_index: $('#id').val(), daili_level: level_new},
                                success: function (res) {
                                    var json = res;
                                    if (json.ret_code == 0) {
                                        $msgBox.remove();
                                        $.alert('更改成功');
                                        $('.DAILI_LEVEL').html(level_new);
                                    }
                                }
                            });
                        } else {
                            $.alert('请填写代理级别');
                        }
                    });
                });


                //修改用户信息 ：
                $('#edit').on('click', function () {
                    $('.edit_name').attr('placeholder', json.data.NAME);
                    $('.edit_true_name').attr('placeholder', json.data.TRUE_NAME);
                    // $('.edit_daili_level').attr( 'placeholder', json.data.DAILI_LEVEL );
                    $('.edit_tel').attr('placeholder', json.data.TEL);
                    $('.edit_address').attr('placeholder', json.data.ADDRESS);
                    $('.edit_bank').attr('placeholder', json.data.BANK_ACCOUNT);


                    $('.user_edit').show();
                    $('.edit_off').click(function () {
                        $('.user_edit').hide();
                    });

                    $('.edit_user_btn').on('click', function () {
                        var dataObj = {
                            NAME: $('.edit_name').val(),
                            TRUE_NAME: $('.edit_true_name').val(),
                            TEL: $('.edit_tel').val(),
                            ADDRESS: $('.edit_address').val(),
                            PLAYER_INDEX: $('.PLAYER_INDEX').html(),
                            BANK_ACCOUNT: $('.edit_bank').val()
                        };

                        $.ajax({
                            type: 'post',
                            url: base_url + '&r=admin/updata-daili-info',
                            data: dataObj,
                            success: function (res) {
                                console.log(res);
                                var json = res;
                                if (json.ret_code == 0) {
                                    $('.user_edit').hide();
                                    $.alert('修改成功');
                                    $('.NAME').html(dataObj.NAME);
                                    $('.TRUE_NAME').html(dataObj.TRUE_NAME);
                                    $('.TEL').html(dataObj.TEL);
                                    $('.ADDRESS').html(dataObj.ADDRESS);
                                    $('.bank_account').html(dataObj.BANK_ACCOUNT);
                                }
                            }
                        });

                    });
                });
            }
        });
    }
});

//日志查询
$('#ser_btn1').on('click', function () {

    var str1 = '<table class="table table-hover table-bordered text-center" style="width:800px;margin:20px auto;" id="box"><tr> <th colspan="8">我的日志</th></tr><tr> <th>用户ID</th><th>下级ID</th><th>用户昵称</th><th>提成</th><th>操作前</th><th>操作后</th><th>操作时间</th><th>操作类型</th></tr></table>';

    $('#outbox').empty();
    $('#outbox').append(str1);

    if ($('#id').val() == '') {
        $.alert('请填写用户ID');
        return false;
    } else if ($('#dateinfo1').val() == '') {
        $.alert('请选择起始日');
        return false;
    } else if ($('#dateinfo1').val() == '') {
        $.alert('请选择结束日');
        return false;
    } else {
        $.ajax({
            type: 'post',
            url: base_url + '&r=admin/profit-log',
            data: {player_index: $('#id').val(), start_time: $('#dateinfo1').val(), end_time: $('#dateinfo2').val()},
            success: function (res) {
                var json = res;
                if (json.ret_code == 0) {
                    var str = '';
                    for (var i = 0; i < json.data.length; i++) {
                        var OP_MONEY = json.data[i].OP_MONEY / 100;
                        var num = new Number(OP_MONEY);
                        var OP_MONEY = num.toFixed(2);

                        var PROFIT_MONEY = json.data[i].PROFIT_MONEY / 100;
                        var num = new Number(PROFIT_MONEY);
                        var PROFIT_MONEY = num.toFixed(2);

                        var LEFT_MONEY = json.data[i].LEFT_MONEY / 100;
                        var num = new Number(LEFT_MONEY);
                        var LEFT_MONEY = num.toFixed(2);

                        if (json.data[i].FROM_TYPE == '1') {
                            var TXT = '会员提成';
                        }

                        if (json.data[i].FROM_TYPE == '2') {
                            var TXT = '代理提成';
                        }
                        str += '<tr><td>' + json.data[i].PLAYER_INDEX + '</td><td>' + json.data[i].CHILD_INDEX + '</td><td></td><td>' + OP_MONEY + '</td><td>' + PROFIT_MONEY + '</td><td>' + LEFT_MONEY + '</td><td>' + json.data[i].CREATE_TIME + '</td><td>' + TXT + '</td></tr>';
                    }

                    $('#box').append(str);
                }
            }
        });
    }
});


//创建代理
// $('#distpicker').distpicker({
// 	autoSelect: false
// });

$('#create').on('click', function () {

    if ($('#play_index').val() == '') {
        $('#play_index').attr('placeholder', '用户ID不能为空');
        $('#play_index').focus();
        return false;
    } else if ($('#username').val() == '') {
        $('#username').attr('placeholder', '用户昵称不能为空');
        $('#username').focus();
        return false;
    } else if ($('#truename').val() == '') {
        $('#truename').attr('placeholder', '真实姓名不能为空');
        $('#truename').focus();
        return false;
    } else if ($('#tel').val() == '') {
        $('#tel').attr('placeholder', '手机号码不能为空');
        $('#tel').focus();
        return false;
    } else if (!/^1[3|4|5|7|8][0-9]\d{4,8}$/.test($('#tel').val())) {
        $('#tel').val('');
        $('#tel').attr('placeholder', '请输入正确的手机号码格式(11位)');
        $('#tel').focus();
        return false;
    } else if ($('#province').val() == '') {
        alert('请填写地址');
        $('#province').focus();
        return false;
    } else {
        $msgBox = $('<div class="msg-box"><div class="inner"><p>是否创建？</p><button id="yes" style="width:49%">确认</button><button id="no" style="width:49%">取消</button></div></div>').prependTo($(document.body));
        $('#no').on('click', function () {
            $msgBox.remove();
            return false;
        });
        $('#yes').on('click', function () {
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/create-sub-daili',
                data: {
                    PLAYER_INDEX: $('#play_index').val(),
                    DAILI_LEVEL: $('#daili_level option:selected').val(),
                    NAME: $('#username').val(),
                    TRUE_NAME: $('#truename').val(),
                    TEL: $('#tel').val(),
                    ADDRESS: $('#province').val() + $('#city').val() + $('#district').val() + $('#add').val()
                },
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        $msgBox.remove();
                        $.alert('创建成功,请在代理列表查看');
                        myrefresh();
                    } else {
                        $msgBox.remove();
                        $.alert(json.ret_msg);
                    }
                }
            });
        });
    }
});


//代理申请列表  审核
$.ajax({
    type: 'get',
    url: base_url + '&r=admin/show-appay-daili',
    success: function (res) {
        var json = res;
        // console.log(json);
        if (json.ret_code == 0) {
            var str = '';
            for (var i = 0; i < json.data.length; i++) {
                if (json.data[i].STATUS == 0) {
                    var STATUS = '已拒绝';
                } else if (json.data[i].STATUS == 1) {
                    var STATUS = '申请中';
                } else if (json.data[i].STATUS == 2) {
                    var STATUS = '申请通过';
                }
                if (json.data[i].IMG != null) {
                    var img_arr = json.data[i].IMG.split(",");
                    var img_str = '';
                    // console.log(img_arr);
                    for (var a = 0; a < img_arr.length; a++) {
                        img_str += '<img src="http://oi61q7eoj.bkt.clouddn.com/' + img_arr[a] + '" title="点击放大">';
                    }

                }
                // console.log(img_str);

                str += '<tr><th>' + (i + 1) + '</th><th class="c_id">' + json.data[i].PLAYER_INDEX + '</th><th>' + json.data[i].TEL + '</th><th>' + img_str + '</th><th>' + json.data[i].QUALICATION + '</th><th>' + json.data[i].QUALICATION1 + '</th><th>' + json.data[i].OTHER + '</th><th>' + STATUS + '</th><th><button class="check_btn" data-index="' + i + '">审核</button></th></tr>';
            }
            $('#check tbody').append(str);
        }

        //图片点击蒙版放大
        $('#check img').on('click', function () {
            //图片为100*100，设置蒙版层图片宽高为现在宽高的2倍
            // console.log($(this));
            var src = $(this).context.currentSrc;
            // console.log(src);
            $imgBox = $('<div class="msg-box img-box"><div class="inner"><p>请预览：</p><img src="' + src + '" width="300" height="300"/><span class="al">&times;</span></div></div>').prependTo($(document.body));
            $('.al').on('click', function () {
                $imgBox.remove();
                return false;
            });

        });

        $('.check_btn').on('click', function () {

            var index = $(this).attr('data-index');
            var play_id = $('.c_id').eq(index).html();

            $checkbox = $('<div class="msg-box check-box"><div class="inner"><span class="al">&times;</span><p>请审核：</p><div class="c_box"><p>选择当前审核状态：<select id="c_status"><option value="2"><p>审核通过</option><option value="0">审核不通过</option><option value="1">审核中</option></select></p><p>备注：<input type="text" class="c_res"></p><button class="c_btn">提交</button></div></div></div>').prependTo($(document.body));

            $('.al').on('click', function () {
                $checkbox.remove();
                return false;
            });

            $('.c_btn').on('click', function () {
                var status = $('#c_status').val();

                var dataObj = {
                    player_index: play_id,
                    status: status,
                    remark: $('.c_res').val()
                };


                $.ajax({
                    type: 'post',
                    url: base_url + '&r=admin/audit',
                    data: dataObj,
                    success: function (res) {
                        var json = res;

                        if (json.ret_code == 0) {
                            $checkbox.remove();
                            alert('提交成功');

                        } else {
                            alert(json.ret_msg);
                        }
                    }
                });
            });
        });
    }
});

//修改密码
$('#pwd_btn').on('click', function () {
    if ($('#pwd').val() == '') {
        $('#pwd').attr('placeholder', '请输入原密码');
        $('#pwd').focus();
        return false;
    } else if ($('#pwd_new').val() == '') {
        $('#pwd_new').attr('placeholder', '请输入新密码');
        $('#pwd_new').focus();
        return false;
    } else if ($('#pwd_new_ag').val() == '') {
        $('#pwd_new_ag').attr('placeholder', '请再次输入新密码');
        $('#pwd_new_ag').focus();
        return false;
        return false;
    } else if ($('#pwd_new_ag').val() != $('#pwd_new').val()) {
        $.alert('两次输入密码不一致，请检查');
        $('#pwd_new_ag	').focus();
        return false;
    } else {
        $.ajax({
            type: 'post',
            url: base_url + '&r=admin/user/change-password',
            data: {oldPassword: $('#pwd').val(), newPassword: $('#pwd_new_ag').val(), retypePassword: $('#pwd_new_ag').val()},
            success: function (res) {
                var json = res;
                if (json.ret_code == 0) {
                    $.alert('密码修改成功');
                } else if (json.ret_code == 1006) {
                    $.alert('密码修改失败');
                }
            }
        });
    }
});

//链接跳转
$('.logo').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=index';
});
$('.ser_daili').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=ser_info';
});
$('.add_daili').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=edit_info';
});
$('.list_daili').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=daili_list';
});
$('.list_huiyuan').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=huiyuan_list';
});
$('.check_daili').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=check';
});
$('.blog_list').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=ser_blog';
});
$('.cash_order').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=cash_order';
});
$('.edit_pwd').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=edit_pwd';
});

$('.sx_apply').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=sx_apply';
});
$('.zs_apply').on('click', function () {
    window.location.href = base_url + '&r=admin/route&url=zs_apply';
});


$('.quit').on('click', function () {
    $msgBox = $('<div class="msg-box"><div class="inner"><p>确认是否退出？</p><button id="yes" style="width:48%">是</button><button id="no" style="width:48%">否</button></div></div>').prependTo($(document.body));
    $('#no').on('click', function () {
        $msgBox.remove();
        return false;
    });
    $('#yes').on('click', function () {

        $.ajax({
            type: 'post',
            url: base_url + '&r=admin/user/logout',
            success: function (res) {
                var json = res;
                console.log(res);
                if (json.ret_code == 0) {
                    $msgBox.remove();
                    window.location.href = base_url + '&r=admin/user/login';
                }
            }
        });
    });
});


//提现订单
$('#cash_btn').on('click', function () {
    $('#cash_order').empty();
    var str_hd = '<tr><th colspan="11">提现订单</th></tr><tr><th>序号</th><th>用户ID</th><th>真实姓名</th><th>订单号</th><th>银行卡号</th><th>提现金额</th><th>手续费</th><th>支付状态</th><th>创建时间</th><th>更新时间</th><th>备注</th></tr>';
    $('#cash_order').append(str_hd);

    var dataObj = {
        player_index: $('#cash_id').val(),
        order_id: $('#order_id ').val(),
        status: $('#order').val(),
        start_time: $('#dateinfo3').val(),
        end_time: $('#dateinfo4').val(),
        page_index: 1,
        page_size: '20'
    };
    console.log(dataObj);
    $.ajax({
        url: base_url + '&r=admin/get-take-money-order',
        type: 'get',
        data: dataObj,
        success: function (json) {
            console.log(json);
            var json = JSON.parse(json);

            if (json.data.data.length == 0) {
                alert('无内容显示,请确认时间范围并重新查询');
            } else {
                var str = '';
                for (var i = 0; i < json.data.data.length; i++) {

                    var PAY_MONEY = json.data.data[i].PAY_MONEY / 100;
                    var num1 = new Number(PAY_MONEY);
                    var PAY_MONEY = num1.toFixed(2);

                    var PAY_FEE = json.data.data[i].PAY_FEE / 100;
                    var num2 = new Number(PAY_FEE);
                    var PAY_FEE = num2.toFixed(2);

                    if (json.data.data[i].PAY_STATUS == '0') {
                        var PAY_STATUS = '准备支付';
                    } else if (json.data.data[i].PAY_STATUS == '1') {
                        var PAY_STATUS = '支付成功';
                    } else if (json.data.data[i].PAY_STATUS == '2') {
                        var PAY_STATUS = '支付失败 待重新查询';
                    } else if (json.data.data[i].PAY_STATUS == '3') {
                        var PAY_STATUS = '支付失败 解除冻结金额';
                    } else if (json.data.data[i].PAY_STATUS == '4') {
                        var PAY_STATUS = '支付成功 但更新订单状态失败';
                    } else if (json.data.data[i].PAY_STATUS == '5') {
                        var PAY_STATUS = '支付成功  但减少用户冻结金额失败';
                    } else if (json.data.data[i].PAY_STATUS == '6') {
                        var PAY_STATUS = '转账失败 未成功';
                    } else if (json.data.data[i].PAY_STATUS == '7') {
                        var PAY_STATUS = '处理中订单 需要重试';
                    } else if (json.data.data[i].PAY_STATUS == '999') {
                        var PAY_STATUS = '余额不足 单独处理 人工跟进中';
                    }


                    function add0(m) {
                        return m < 10 ? '0' + m : m
                    }

                    function format(shijianchuo) {
                        var time = new Date(shijianchuo);
                        var y = time.getFullYear();
                        var m = time.getMonth() + 1;
                        var d = time.getDate();
                        var h = time.getHours();
                        var mm = time.getMinutes();
                        var s = time.getSeconds();
                        return y + '-' + add0(m) + '-' + add0(d) + ' ' + add0(h) + ':' + add0(mm) + ':' + add0(s);
                    }

                    var time_1 = parseInt(json.data.data[i].CREATE_TIME);
                    var time1 = format(time_1);
                    var time_2 = parseInt(json.data.data[i].UPDATE_TIME);
                    var time2 = format(time_2);

                    str += '<tr><th>' + (i + 1) + '</th><th>' + json.data.data[i].PLAYER_INDEX + '</th><th>' + json.data.data[i].TRUE_NAME + '</th><th>' + json.data.data[i].ORDER_ID + '</th><th>' + json.data.data[i].BANK_ACCOUNT + '</th><th>' + PAY_MONEY + '</th><th>' + PAY_FEE + '</th><th>' + PAY_STATUS + '</th><th>' + time1 + '</th><td>' + time2 + '</td><th>' + json.data.data[i].REMARK + '</th></tr>'
                }

                $('#cash_order').append(str);

                $('#pagebox').show();

                var pagecount = json.data.page_count;
                var pageIndex = '1';

                $('.pagination').jqPagination({
                    current_page: pageIndex,           //设置当前页
                    max_page: pagecount,              //设置最大页
                    page_string: '{current_page}/{max_page}',
                    paged: function (page) {
                        dataObj.page_index = page;
                        $.ajax({
                            type: 'get',
                            url: base_url + '&r=admin/get-take-money-order',
                            data: dataObj,
                            success: function (json) {
                                var json = JSON.parse(json);

                                var str = '';
                                for (var i = 0; i < json.data.data.length; i++) {

                                    var PAY_MONEY = json.data.data[i].PAY_MONEY / 100;
                                    var num1 = new Number(PAY_MONEY);
                                    var PAY_MONEY = num1.toFixed(2);

                                    var PAY_FEE = json.data.data[i].PAY_FEE / 100;
                                    var num2 = new Number(PAY_FEE);
                                    var PAY_FEE = num2.toFixed(2);

                                    if (json.data.data[i].PAY_STATUS == '0') {
                                        var PAY_STATUS = '准备支付';
                                    } else if (json.data.data[i].PAY_STATUS == '1') {
                                        var PAY_STATUS = '支付成功';
                                    } else if (json.data.data[i].PAY_STATUS == '2') {
                                        var PAY_STATUS = '支付失败 待重新查询';
                                    } else if (json.data.data[i].PAY_STATUS == '3') {
                                        var PAY_STATUS = '支付失败 解除冻结金额';
                                    } else if (json.data.data[i].PAY_STATUS == '4') {
                                        var PAY_STATUS = '支付成功 但更新订单状态失败';
                                    } else if (json.data.data[i].PAY_STATUS == '5') {
                                        var PAY_STATUS = '支付成功  但减少用户冻结金额失败';
                                    } else if (json.data.data[i].PAY_STATUS == '6') {
                                        var PAY_STATUS = '转账失败 未成功';
                                    } else if (json.data.data[i].PAY_STATUS == '7') {
                                        var PAY_STATUS = '处理中订单 需要重试';
                                    } else if (json.data.data[i].PAY_STATUS == '999') {
                                        var PAY_STATUS = '余额不足 单独处理 人工跟进中';
                                    }

                                    str += '<tr><th>' + (i + 1) + '</th><th>' + json.data.data[i].PLAYER_INDEX + '</th><th>' + json.data.data[i].TRUE_NAME + '</th><th>' + json.data.data[i].ORDER_ID + '</th><th>' + json.data.data[i].BANK_ACCOUNT + '</th><th>' + PAY_MONEY + '</th><th>' + PAY_FEE + '</th><th>' + PAY_STATUS + '</th><th>' + json.data.data[i].CREATE_TIME + '</th><td>' + json.data.data[i].UPDATE_TIME + '</td><th>' + json.data.data[i].REMARK + '</th></tr>'
                                }

                                $('#cash_order').empty();
                                var str_hd = '<tr><th colspan="11">提现订单</th></tr><tr><th>序号</th><th>用户ID</th><th>真实姓名</th><th>订单号</th><th>银行卡号</th><th>提现金额</th><th>手续费</th><th>支付状态</th><th>创建时间</th><th>更新时间</th><th>备注</th></tr>';
                                $('#cash_order').append(str_hd);

                                $('#cash_order').append(str);
                            }
                        });
                    }
                });
                $(' #j_ghost_plus').click(function () {
                    if ($('#j_ghost_plus').hasClass("ghost_plus_active")) {
                        $('#j_ghost_plus').removeClass("ghost_plus_active");
                        $('#j_ghost_panel').removeClass("ghost_panel_active");
                    } else {
                        $('#j_ghost_plus').addClass("ghost_plus_active");
                        $('#j_ghost_panel').addClass("ghost_panel_active");
                    }

                })
            }
        }
    });
});








