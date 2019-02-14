/**
 * Created by lenovo on 2017/3/27.
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
        editPwd : function(){
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
                Modal.alert({msg: '两次输入密码不一致，请检查'});
                $('#pwd_new_ag').focus();
                return false;
            } else {
                $.ajax({
                    type: 'post',
                    url: base_url + '&r=admin/user/change-password',
                    data: {
                        oldPassword: $('#pwd').val(),
                        newPassword: $('#pwd_new_ag').val(),
                        retypePassword: $('#pwd_new_ag').val()
                    },
                    success: function (res) {
                        var json = res;
                        if (json.ret_code == 0) {
                            Modal.alert({msg: '密码修改成功'});
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
