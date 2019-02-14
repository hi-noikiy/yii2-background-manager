
var  admin_left_dom  =  $('.'+no_page).parent();
admin_left_dom.addClass("active");
var dom = admin_left_dom.parent().parent(".treeview").addClass("active");

function UrlSearch() {
    var name, value;
    var str = location.href; //取得整个地址栏
    var num = str.indexOf("?");
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
// var base_url = "http://dlhg.xiaoxiongyouxi.com/index.php?gid=" +Request.gid ;
var base_url = "http://" + location.host + "/index.php?gid=" + Request.gid;
// var base_url = "/?gid=" + Request.gid;

//客户经理跳转
$('.cusbtn').on('click',function() {
    // window.location.href = 'http://hjdlyn.xiaoxiongyouxi.com/index.php?r=manager-daili';
    window.location.href = base_url + '&r=manager-daili';
});


//logo跳转
$('.main-header').on('click', '.logo',function(event) {
    window.location.href = base_url + '&r=admin/route&url=index';
});

// 二级链接跳转
$('.treeview-menu').on('click', 'a', function(event) {
 	var cur_class = this.getAttribute("class");
	window.location.href = base_url + '&r=admin/route&url=' + cur_class;
});
//右上角修改密码 跳转
$('.user-footer .pull-left').on('click', 'a', function(event) {
	window.location.href = base_url + '&r=admin/route&url=edit_pwd';
});

//封装alert
window.Modal = function () {
    var reg = new RegExp("\\[([^\\[\\]]*?)\\]", 'igm');
    var alr = $("#myalert");
    var ahtml = alr.html();

    var _alert = function (options) {
        alr.html(ahtml);    // 复原
        _dialog(options);

        return {
            on: function (callback) {
                if (callback && callback instanceof Function) {
                    alr.find('.ok').click(function () { callback(true) });
                }
            }
        };
    };

    var _dialog = function (options) {
        var ops = {
            msg: "提示内容"
        };

        $.extend(ops, options);

        var html = alr.html().replace(reg, function (node, key) {
            return {
                Message: ops.msg
            }[key];
        });

        alr.html(html);
        alr.modal({
            width: 500,
            backdrop: 'static'
        });
    };

    return {
        alert: _alert
    }
}();



