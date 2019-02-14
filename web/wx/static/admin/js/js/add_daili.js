/**
 * Created by lenovo on 2017/3/25.
 */

$('#distpicker').distpicker({
    autoSelect: false
});


//创建代理
var vm = new Vue({
    el : 'body',
    data : {
        userid : '',
        daili_level : 3,
        username : '',
        truename : '',
        tel : '',
        province : '',
        city : '',
        district : '',
        address : ''
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
        addbtn : function(){
            if ( this.userid == '') {
                $('#userid').attr('placeholder', '用户ID不能为空');
                $('#userid').focus();
                return false;
            } else if ( this.username == '') {
                $('#username').attr('placeholder', '用户昵称不能为空');
                $('#username').focus();
                return false;
            } else if ( this.truename == '') {
                $('#truename').attr('placeholder', '真实姓名不能为空');
                $('#truename').focus();
                return false;
            } else if ( this.tel == '') {
                $('#tel').attr('placeholder', '手机号码不能为空');
                $('#tel').focus();
                return false;
            } else if (!/^1[3|4|5|7|8][0-9]\d{4,8}$/.test(this.tel)) {
                $('#tel').val('');
                $('#tel').attr('placeholder', '请输入正确的手机号码格式(11位)');
                $('#tel').focus();
                return false;
            } else if ( this.province == '') {
                Modal.alert({msg: '请选择地址'}).on(function(){
                    $('#province').focus();
                });
                return false;
            } else{
                $('#addbtn').attr('data-target','#add');
            }
        },
        add_daili : function(){
            $.ajax({
                type: 'post',
                url: base_url + '&r=admin/create-sub-daili',
                data: {
                    PLAYER_INDEX: this.userid,
                    DAILI_LEVEL: this.daili_level,
                    NAME: this.username,
                    TRUE_NAME: this.truename,
                    TEL: this.tel,
                    ADDRESS: this.province + this.city + this.district + this.address
                },
                success: function (res) {
                    var json = res;
                    if (json.ret_code == 0) {
                        Modal.alert({msg: '创建成功,请在代理列表查看'}).on(function(){
                            location.reload();
                        });
                    }else{
                        Modal.alert({msg: json.ret_msg});
                    }
                },
                error : function(){
                    Modal.alert({msg: '请求失败'});
                }
            });
        }
    }
});