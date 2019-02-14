function setWXShare(_appid,_timestamp,_nonceStr,_signature,_desc)
{
	var title = $('title').text();
	var shareUrl = window.location.href;
	var  shareImg = 'img/show.jpg';
	var desc =  typeof(_desc) == 'undefined' ? $('meta[name=description]').attr('content') : _desc;
	console.log(title);
	console.log(shareUrl);
    console.log(shareImg);
    console.log(desc);
    
    //  微信浏览器里
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: _appid, // 必填，口袋妖怪日月的唯一标识
        timestamp:_timestamp , // 必填，生成签名的时间戳
        nonceStr: _nonceStr, // 必填，生成签名的随机串
        signature: _signature,// 必填，签名，见附录1
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.error(function(res){
    });
    wx.ready(function(){
        
        wx.onMenuShareTimeline({    //  分享到朋友圈
            title: title, // 分享标题
            link: shareUrl, // 分享链接
            imgUrl: shareImg, // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            	
                console.log('share success ...');

            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareAppMessage({  //  分享到朋友
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: shareUrl, // 分享链接
            imgUrl: shareImg, // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () { 
                // 用户确认分享后执行的回调函数
                console.log('share success ...');
                
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQQ({  //  分享到QQ
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: shareUrl, // 分享链接
            imgUrl: shareImg, // 分享图标
            success: function () { 
               // 用户确认分享后执行的回调函数
               console.log('share success ...');
              
            },
            cancel: function () { 
               // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareWeibo({   //  分享到腾讯微博
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: shareUrl, // 分享链接
            imgUrl: shareImg, // 分享图标
            success: function () { 
               // 用户确认分享后执行的回调函数
               console.log('share success ...');
              
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQZone({   //  分享到QQ空间
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: shareUrl, // 分享链接
            imgUrl: shareImg, // 分享图标
            success: function () { 
               // 用户确认分享后执行的回调函数
               console.log('share success ...');
               
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        }); 
    });
}
/*  end of 微信分享  *******************************************************************************************************************************************************************************/