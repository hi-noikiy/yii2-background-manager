!function(i,t){"use strict";i.jQuery||i.$||console.log("请加入jQuery库");var n=i.jQuery||i.$,e=i.document,o=n(e),s=n("body"),a=n(i),c=(a.width(),a.height(),Object.prototype.hasOwnProperty,i.SKY=i.SKY||{}),r=c.Util={wheel:function(){var i=function(i){i.preventDefault()};return{prevent:function(){o.on("mousewheel DOMMouseScroll touchmove",i)},restore:function(){o.off("mousewheel DOMMouseScroll touchmove",i)}}}()};c.CommonUI={tab:function(i,t){i.on("click",function(){var i=n(this).index();n(this).addClass("on").siblings().removeClass("on"),t.eq(i).show().siblings().hide()}).eq(0).trigger("click")},popup:function(){var i=function(t){return i.hasMask||(i.hasMask=!0,s.append(i.$mask)),i.cache[t]||(i.cache[t]=new i.prototype.init(t))};return i.cache={},i.hasMask=!1,i.aniTime=250,i.$mask=n('<div class="mask-popup"></div>'),i.prototype.init=function(i){var t=this;t.$container=n(i),t.$btnClose=t.$container.find(".popup-btn-close"),t.initEvents()},i.prototype.initEvents=function(){var i=this;i.$btnClose.on("click",function(){i.hide()})},i.prototype.show=function(){i.$mask.fadeIn(i.aniTime),this.$container.fadeIn(i.aniTime),r.wheel.prevent()},i.prototype.hide=function(){i.$mask.fadeOut(i.aniTime),this.$container.fadeOut(i.aniTime),r.wheel.restore()},i.prototype.init.prototype=i.prototype,i}()},c.UI={},c.Page={init:function(){var i=this;n(document).ready(function(){i.default.init()})},default:{init:function(){for(var i in this)if(this.hasOwnProperty(i)){if("init"===i)continue;this[i].init()}},IEDetect:{isIe8:function(){var t=i.navigator.userAgent,n=/;\s*MSIE (\d+).*?;/.exec(t);return!!(n&&+n[1]<9)}(),compatibleHTML:['<div class="compatible-wrap" id="js-compatible">','<p class="tip"><i></i>您的浏览器版本过低。为保证最佳体验，<a href="http://www.imooc.com/static/html/browser.html">请点此更新高版本浏览器</a></p>','<a href="javascript:;" class="no">以后再说<i></i></a>',"<div>"].join(""),init:function(){this.isIe8&&(s.prepend(this.compatibleHTML),n("#js-compatible .no").on("click",function(){n("#js-compatible").remove()}))}},NavMain:{init:function(){this.initialize()},initialize:function(){var i=this;i.$nav=n(".nav-main"),i.$items=i.$nav.find(".item"),i.$itemTitles=i.$nav.find(".item h3"),i.$subTitles=i.$nav.find(".sub>.group>p"),i.aniTime=300;i.$items.each(function(){var t=n(this),e=t.has(".sub").length,o=t.hasClass("open");e||t.addClass("nosub"),o&&t.find(".sub").slideDown(i.aniTime)}),i.initEvent(),i.scroll()},scroll:function(){this.$nav.on("wheel mousewheel DOMMouseScroll",function(i){var t=i.originalEvent,n=(t.deltaY||-t.wheelDelta||t.delta)>0?30:-30;i.preventDefault(),this.scrollTop+=n})},initEvent:function(){var i=this;i.$itemTitles.on("click",function(){var t=n(this);!!t.nextAll(".sub").length&&(t.parent().toggleClass("open"),t.nextAll(".sub").stop(!0).slideToggle(i.aniTime))}),i.$subTitles.on("click",function(){i.$subTitles.removeClass("on"),n(this).addClass("on")})}},SearchOption:{init:function(){n(".form-search a").on("click",function(){n(".form-search-option").slideToggle(200)})}}},manage:{m11:function(i){({$switcher:n("#chart-switcher"),$chart:n("#chart"),init:function(i){var t=this;t.$switcher.on("change",function(){i.chart.type=n(this).val(),t.$chart.highcharts(i)}).trigger("change")}}).init(i)}}},c.Page.init()}(this);