/** 刷新当前页面的iframe */
function reloadIframe(iframeId){
    var _body = window.parent;
    var _iframe1 = document.getElementById(iframeId);
    _iframe1.contentWindow.location.reload(true);
}

/** 刷新当前页面的iframe */
function reloadIframeWeb(iframeId){
    var _iframe1 = document.getElementById(iframeId);
    _iframe1.contentWindow.location.reload(true);
}

/** 重定义微信公众号返回按钮的跳转地址 */
function pushHistory(url) {
    var state = {title: "", url: url};
    window.history.pushState(state, state.title, state.url);
}

/** 加载层 */
function ityzl_SHOW_LOAD_LAYER($msg,$seconds) {
    return layer.msg($msg, { icon: 16, shade: [0.5, '#f5f5f5'], scrollbar: false, offset: '50%', time: $seconds });
}
/** 关闭加载层 */
function ityzl_CLOSE_LOAD_LAYER(index) {
    layer.close(index);
}
/** 加载完成信息 */
function ityzl_SHOW_TIP_LAYER($msg) {
    layer.msg($msg, { time: 1000, offset: '50%' });
}
