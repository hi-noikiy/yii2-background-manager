/* HTML结构:
	<div class="tabWrap">
		<div class="tabBtnBox">
			<span class="tabBtn on">选项卡一</span>
			<span class="tabBtn">选项卡二</span>
			<span class="tabBtn">选项卡三</span>
		</div>
		<div class="tabContBox">
			<div class="tabCont on">选项卡内容一</div>
			<div class="tabCont">选项卡内容一</div>
			<div class="tabCont">选项卡内容一</div>
		</div>
	</div>
*/
/* CSS:
	.tabContBox .tabCont{display:none;}
	.tabContBox .on.tabCont{display:block;}
*/

(function($){
	$.fn.extend({
		tab:function(options){
			var param = {
				tabHead: '.tabHead div',
				tabBody: '.tabBody div',
				tabHead_hotClass: 'hot',
				tabBody_hotClass: 'hot',
				event: 'click',
				defaultShow: 1,
				callbackFunc: null
			}
			$.extend(param,options);

			var J_head = $(param.tabHead);
			var J_body = $(param.tabBody);
			var J_headClass = param.tabHead_hotClass;
			var J_bodyClass = param.tabBody_hotClass;

			//tab标签切换事件
			J_head.each(function(i){
				$(this).bind(param.event,function(){
					$(this).addClass(J_headClass).siblings().removeClass(J_headClass);
					J_body.eq(i).show().addClass(J_bodyClass).siblings().removeClass(J_bodyClass).hide();
				});
			});
		}
	});
})(jQuery);

// html引用:
/*
<script>
	$(function(){
		$('.tabWrap').tab({
			tabHead: '.tabBtnBox .tabBtn',
			tabBody: '.tabContBox .tabCont',
			tabHead_hotClass: 'on',
			event: 'click',
			defaultShow: 0
		});
	});
</script>
*/