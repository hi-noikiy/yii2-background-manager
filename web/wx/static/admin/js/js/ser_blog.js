/**
 * Created by lenovo on 2017/3/27.
 */

jeDate({
    dateCell:"#dateinfo1",
    format:"YYYY-MM-DD hh:mm:ss",
    isinitVal:true,
    isTime:true, //isClear:false,
    //minDate:"2014-09-19 00:00:00",
    okfun:function(val){alert(val)}
});

jeDate({
    dateCell:"#dateinfo2",
    format:"YYYY-MM-DD hh:mm:ss",
    isinitVal:true,
    isTime:true, //isClear:false,
    //minDate:"2014-09-19 00:00:00",
    okfun:function(val){alert(val)}
});

//自定义过滤器
Vue.filter('type', function (value) {
    switch(value) {
        case '1':
            return '会员提成';
            break;
        case '2':
            return '代理提成';
            break;
    }
});


var vm = new Vue({
    el : 'body',
    data : {
        blog : ''
    },
    methods : {
        out : function(){
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/user/logout',
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        window.location.href = base_url + '&r=admin/user/login';
                    }
                }
            });
        },
        serblog : function () {
            var that = this;
            if ($('#id').val() == '') {
                Modal.alert({msg: '请填写用户ID'});
                return false;
            } else if ($('#dateinfo1').val() == '') {
                Modal.alert({msg: '请选择起始日'});
                return false;
            } else if ($('#dateinfo1').val() == '') {
                Modal.alert({msg: '请选择结束日'});
                return false;
            } else{
                $.ajax({
                    type: 'post',
                    url: base_url + '&r=admin/profit-log',
                    data: {player_index: $('#id').val(), start_time: $('#dateinfo1').val(), end_time: $('#dateinfo2').val()},
                    success: function (res) {
                        var json = res;
                        if( json.ret_code == 0 ){
                            if( json.data.length != 0 ){
                                that.blog = json.data;
                            }else{
                                Modal.alert({msg: '没有数据'});
                            }
                        }
                    }
                });
            }
        }
    }
});
