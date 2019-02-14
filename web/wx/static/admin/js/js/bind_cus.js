/**
 * Created by lenovo on 2017/8/7.
 */

var vm = new Vue({
    el : 'body',
    data : {
    },
    methods :{
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
        postID : function(){
            if ($('#id').val() == '') {
                $('#id').attr('placeholder', '不能为空');
                $('#id').focus();
                return false;
            } else {
                $.ajax({
                    type: 'post',
                    url: base_url + '&r=admin/bind-manager',
                    data: {
                        PLAYER_INDEX : $('#id').val()
                    },
                    success: function (res) {
                        var json = res;
                        if (json.ret_code == 0) {
                            Modal.alert({msg: '绑定成功'});
                        } else {
                            Modal.alert({msg: json.ret_msg});
                        }
                    },
                    error : function(){
                        Modal.alert({msg: '请求失败'});
                    }
                });
            }
        }
    }
});
