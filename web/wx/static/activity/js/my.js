function UrlSearch()
{
	var name,value;
	var str=location.href; //取得整个地址栏
	var num=str.indexOf("?")
	str=str.substr(num+1); //取得所有参数   stringvar.substr(start [, length ]

	var arr=str.split("&"); //各个参数放到数组里
	for(var i=0;i < arr.length;i++){
		num=arr[i].indexOf("=");
		if(num>0){
			name=arr[i].substring(0,num);
			value=arr[i].substr(num+1);
			this[name]=value;
		}
	}
}
var Request=new UrlSearch(); //实例化

var base_url = "http://dltest.sparkingfuture.com/basic/web/index.php?gid=" +Request.gid ;


var turnplate={
		restaraunts:[],				//大转盘奖品名称
		colors:[],					//大转盘奖品区块对应背景颜色
		srcs:[],                     //大转盘奖品区块对应的缩略图src
		outsideRadius:192,			//大转盘外圆的半径
		textRadius:155,				//大转盘奖品位置距离圆心的距离
		insideRadius:68,			//大转盘内圆的半径
		startAngle:0,				//开始角度
		bRotate:false				//false:停止;ture:旋转
};


//动态添加大转盘的奖品与奖品区域背景颜色


turnplate.restaraunts = ["2钻石A", "20金币B",  "5钻石C", "实物大奖D "];
turnplate.colors = ["#FFF4D6", "#FFFFFF","#FFF4D6", "#FFFFFF"];
turnplate.srcs = ['static/activity/images/chest-icon-zuan.png','static/activity/images/coin.png','static/activity/images/chest-icon-zuan.png'];


var rotateTimeOut = function (){    //超时函数
	$('#wheelcanvas').rotate({
		angle:0,
		animateTo:2160,  //这里是设置请求超时后返回的角度，所以应该还是回到最原始的位置，2160是因为我要让它转6圈，就是360*6得来的
		duration:8000,
		callback:function (){
			alert('网络超时，请检查您的网络设置！');
		}
	});
};

//旋转转盘 item:奖品位置; txt：提示语;
function rotateFn(item, txt){
	//alert(111);
	var angles = item * (360 / turnplate.restaraunts.length) - (360 / (turnplate.restaraunts.length*2));
	if(angles<270){
		angles = 270 - angles;
	}else{
		angles = 360 - angles + 270;
	}
	$('#wheelcanvas').stopRotate();
	$('#wheelcanvas').rotate({
		angle: 0,				//angle是图片上各奖项对应的角度
		animateTo:angles+1800,
		duration: 2000,
		callback:function (){
			// alert(txt);
			turnplate.bRotate = !turnplate.bRotate;
		}
	});

};


$('.pointer').click(function (){

	// $.ajax({
	// 	type : 'post',
	// 	url : base_url + '&r=activity/free-draw',
	// 	data : { user_id : 12345 },
	// 	success : function(res){
	// 		var res = res;
	// 		if( res.ret_code == 2070 ){
	// 			alert('您今日免费抽奖次数已用完，如需再次抽奖，需消耗10钻石/次，请确认是否继续？');
	// 		 	//后续操作
	// 		}else if( res.ret_code == 0 ){
				//第一次免费抽奖
				$('.stop').show();
				if(turnplate.bRotate) return;
				turnplate.bRotate = !turnplate.bRotate;

				$.ajax({
					type : 'get',
					url : base_url+ '&r=activity/lucky-draw',
					success : function(res){
						var res = res;
						var item = res.data[0];
						var gift = res.data[1];

						rotateFn(item, gift);


						$('.num').html(gift);
						var str = '<li> 恭喜 <span>123456</span> 获得了 <span class="present">'+ gift +'</span></li>';


						var timer;

						if( gift === 'A' ){
							$('.gift img').attr('src','static/activity/images/chest-icon-zuan.png');
							// $('.tip').show();           //抽奖完毕后显示奖品
							setTimeout(function(){
								$('.stop').hide();
								$('.tip').show();
								$('#tab1').append(str);
							},2000);
						}else if( gift === 'B' ){
							$('.gift img').attr('src','static/activity/images/coin.png');
							// $('.tip').show();           //抽奖完毕后显示奖品
							setTimeout(function(){
								$('.stop').hide();
								$('.tip').show();
								$('#tab1').append(str);
							},2000);
						}else if( gift === 'C' ){
							$('.gift img').attr('src','static/activity/images/coin.png');
							// $('.tip').show();
							setTimeout(function(){
								$('.stop').hide();
								$('.tip').show();
								$('#tab1').append(str);
							},2000);
						}else if( gift === 'D' ){       //获得实物大奖则显示输入框
							// $('.big').show();
							setTimeout(function(){
								$('.stop').hide();
								$('.big').show();
								$('#tab1').append(str);
							},2000);
						}
					}
				});
			// }
		// }
	// });
});


//页面所有元素加载完毕后执行drawRouletteWheel()方法对转盘进行渲染
window.onload=function(){
	drawRouletteWheel();
};

function drawRouletteWheel() {
  var canvas = document.getElementById("wheelcanvas");
  if (canvas.getContext) {
	  //根据奖品个数计算圆周角度
	  var arc = Math.PI / (turnplate.restaraunts.length/2);
	  var ctx = canvas.getContext("2d");
	  //在给定矩形内清空一个矩形
	  ctx.clearRect(0,0,422,422);
	  //strokeStyle 属性设置或返回用于笔触的颜色、渐变或模式
	  ctx.strokeStyle = "#FFBE04";
	  //font 属性设置或返回画布上文本内容的当前字体属性
	  ctx.font = '20px Microsoft YaHei';
	  for(var i = 0; i < turnplate.restaraunts.length; i++) {
		  var angle = turnplate.startAngle + i * arc;
		  ctx.fillStyle = turnplate.colors[i];
		  ctx.beginPath();
		  //arc(x,y,r,起始角,结束角,绘制方向) 方法创建弧/曲线（用于创建圆或部分圆）
		  ctx.arc(211, 211, turnplate.outsideRadius, angle, angle + arc, false);
		  ctx.arc(211, 211, turnplate.insideRadius, angle + arc, angle, true);
		  ctx.stroke();
		  ctx.fill();
		  //锁画布(为了保存之前的画布状态)
		  ctx.save();

		  //----绘制奖品开始----
		  ctx.fillStyle = "#E5302F";
		  var text = turnplate.restaraunts[i];
		  var line_height = 17;
		  //translate方法重新映射画布上的 (0,0) 位置
		  ctx.translate(211 + Math.cos(angle + arc / 2) * turnplate.textRadius, 211 + Math.sin(angle + arc / 2) * turnplate.textRadius);

		  //rotate方法旋转当前的绘图
		  ctx.rotate(angle + arc / 2 + Math.PI / 2);

		  /** 下面代码根据奖品类型、奖品名称长度渲染不同效果，如字体、颜色、图片效果。(具体根据实际情况改变) **/
		  // if(text.indexOf("M")>0){    //流量包
			//   var texts = text.split("M");
			//   for(var j = 0; j<texts.length; j++){
			// 	  ctx.font = j == 0?'bold 20px Microsoft YaHei':'16px Microsoft YaHei';
			// 	  if(j == 0){
			// 		  ctx.fillText(texts[j]+"M", -ctx.measureText(texts[j]+"M").width / 2, j * line_height);
			// 	  }else{
			// 		  ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
			// 	  }
			//   }
		  // }else if(text.indexOf("M") == -1 && text.length>6){     //奖品名称长度超过一定范围
			//   text = text.substring(0,6)+"||"+text.substring(6);
			//   var texts = text.split("||");
			//   for(var j = 0; j<texts.length; j++){
			// 	  ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
			//   }
		  // }else{
			  //在画布上绘制填色的文本。文本的默认颜色是黑色
			  //measureText()方法返回包含一个对象，该对象包含以像素计的指定字体宽度
			  ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
		  // }

		  var imgs = document.getElementById("imgs").getElementsByTagName('img');
		  var img = imgs[i];
		  imgs.onload=function(){
			  ctx.drawImage(img,-15,10);
		  };
		  //添加对应图标
		  if(text.indexOf("金币")>0){
			  var img= document.getElementById("gold-img");
			  img.onload=function(){
				  ctx.drawImage(img,-15,10);
			  };
			  ctx.drawImage(img,-15,10);
		  }else if(text.indexOf("谢谢参与")>=0){
			  var img= document.getElementById("sorry-img");
			  img.onload=function(){
				  ctx.drawImage(img,-15,10);
			  };
			  ctx.drawImage(img,-15,10);
		  }else if(text.indexOf("钻石")>=0){
		  	  var img= document.getElementById("diamond-img");
			  img.onload=function(){
				  ctx.drawImage(img,-15,10);
			  };
			  ctx.drawImage(img,-15,10);
		  }
		  //把当前画布返回（调整）到上一个save()状态之前
		  ctx.restore();
		  //----绘制奖品结束----


	  }
  }
}




// 中奖区的高度和大转盘一致
var h = $('.turnplate').height();
console.log(h);

$('.txt').height(h);

// 关闭中奖提示框
$('.off').click(function(){
	$('.box').hide();
});


// 选项卡切换
$("#content ul").hide(); // Initially hide all content
$("#tabs li:first").attr("id","current"); // Activate first tab
$("#content ul:first").fadeIn(); // Show first tab content

$('#tabs a').click(function(e) {
    e.preventDefault();        
    $("#content ul").hide(); //Hide all content
    $("#tabs li").attr("id",""); //Reset id's
    $(this).parent().attr("id","current"); // Activate this
    $('#' + $(this).attr('title')).fadeIn(); // Show content for current tab
});

$('.five').click(function(){
	// $('.promot .num').html('五');
	// $('.promot .count').html('10');
	$('.fivebox').show();

	$('.no1').click(function(){
		$('.fivebox').hide();
	});

	$('.yes1').click(function(){
		$('.fivebox').hide();
		$('.stop').show();
		if(turnplate.bRotate)return;
		turnplate.bRotate = !turnplate.bRotate;

		$.ajax({
			type : 'post',
			url : base_url+ '&r=activity/gold-draw',
			data : { user_id : 12345 , times : 5 },

			success : function(res){
				var res = res;
				console.log(res);
				//五连抽
				var arr = [];

				var i = 0 ;
				var item = res.data[i][0];  //奖品的id
				var gift = res.data[i][1];  //奖品的名称
				arr.push(gift);
				rotateFn(item, turnplate.restaraunts[item-1]);

				var timer = setInterval(function(){
					i ++ ;
					item = res.data[i][0];  //奖品的id
					gift = res.data[i][1];  //奖品的名称
					arr.push(gift);
					rotateFn(item, turnplate.restaraunts[item-1]);
					if(i>=4){
						clearInterval(timer) ;
						console.log(arr);

					}
				},2000);

				setTimeout(function(){
					$('.stop').hide();
					var five_gift = arr[0] + ' ' + arr[1] + ' ' + arr[2] + ' ' + arr[3] + ' ' + arr[4];
					$('.num').html(five_gift);
					var str = '<li> 恭喜 <span>123456</span> 获得了 <span class="present">'+ five_gift +'</span></li>';
					$('.gifts').show();
					$('#tab1').append(str);
				},10000)
			}

		});
	});
});

$('.ten').click(function(){
	// $('.promot .num').html('十');
	// $('.promot .count').html('30');
	$('.tenbox').show();

	$('.no2').click(function(){
		$('.tenbox').hide();
	});

	$('.yes2').click(function() {
		$('.tenbox').hide();
		$('.stop').show();
		if (turnplate.bRotate)return;
		turnplate.bRotate = !turnplate.bRotate;

		$.ajax({
			type : 'post',
			url : base_url+ '&r=activity/gold-draw',
			data : { user_id : 12345 , times : 10 },
			success : function(res){
				var res = res;
				console.log(res);
				//十连抽
				var arr = [];

				var i = 0 ;
				var item = res.data[i][0];  //奖品的id
				var gift = res.data[i][1];  //奖品的名称
				arr.push(gift);
				rotateFn(item, turnplate.restaraunts[item-1]);

				var timer = setInterval(function(){
					i ++ ;
					item = res.data[i][0];  //奖品的id
					gift = res.data[i][1];  //奖品的名称
					arr.push(gift);
					rotateFn(item, turnplate.restaraunts[item-1]);
					if(i>=9){
						clearInterval(timer) ;
						console.log(arr);
					}
				},2000);

				setTimeout(function(){
					$('.stop').hide();
					var ten_gift = arr[0] + ' ' + arr[1] + ' ' + arr[2] + ' ' + arr[3] + ' ' + arr[4] + ' ' + arr[5] + ' ' + arr[6] + ' ' + arr[7] + ' ' + arr[8] + ' ' + arr[9];
					$('.num').html(ten_gift);
					var str = '<li> 恭喜 <span>123456</span> 获得了 <span class="present">'+ ten_gift +'</span></li>';
					$('.gifts').show();
					$('#tab1').append(str);
				},20000)
			}
		});
	});
});
