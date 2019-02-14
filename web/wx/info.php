<!DOCTYPE html>
<html lang="zh" class="feedback">
<head>
	<meta charset="UTF-8">
	<title>填写资料</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<meta http-equiv="content-language" content="zh-CN" />
	<meta name="format-detection" content="telephone=no" />   <!--禁用电话号码识别-->

	<link rel="stylesheet" href="css/css/mui.min.css">
	<link rel="stylesheet" href="css/css/info_style.css">

	<script type="text/javascript" src="js/js/jquery-1.7.2.js?v=2"></script>
	<script type="text/javascript" src="js/js/html5ImgCompress.min.js?v=2"></script>

</head>
<body>
<header class="mui-bar mui-bar-nav">
	<h1 class="mui-title" v-html="title">推广代理申请</h1>
</header>
<div id="apply-content" class="mui-content">
	<div class="content_hd">
		<p v-html="apply_title"></p>
		<p v-for="val in list" v-html="val"></p>
	</div>
	<div class="mui-content-padded" style="margin: 5px;">
		<div class="mui-input-group">
			<div class="mui-input-row">
				<label>手机号：</label>
				<input type="text" class="mui-input-clear" id="phone" placeholder="请输入手机号">
			</div>
			<div class="mui-input-row">
				<label>验证码：</label>
				<input type="text" class="mui-input-clear" id="checkCode" name="checkCode" size="6" placeholder="请输入"/>
				<button id='btnSendCode' class="mui-btn mui-btn-primary" v-on:tap="sendCode()">点击发送</button>
			</div>
			<div class="mui-input-row">
				<label>微信群截图：</label>
				<a href="#" id="a_multiple">
					<span>选择图片</span>
					<input type="file" multiple="multiple" id="multiple" v-on:change="onloadImg()"/>
				</a>
				<div id="box"></div>
			</div>
			<div class="mui-input-row">
				<label><span v-html="member_num"></span>名群成员ID：</label>
				<textarea id="member_num" name="" cols="30" rows="7" placeholder="要求:未绑定他人推荐码(格式:逗号或空格隔开)"></textarea>
			</div>
			<div class="mui-input-row">
				<label><span v-html="room_num"></span>局桌数房号：</label>
				<textarea id="room_num" name="" cols="30" rows="4" v-bind:placeholder="room_num_ph"></textarea>
			</div>
			<div class="mui-input-row">
				<label>拥有什么资源&优势：</label>
				<input type="text" class="mui-input-clear"  id="other" placeholder="选填">
			</div>
			<div class="mui-button-row">
				<button class="mui-btn" id="btn" style="background: #007aff;color:#fff"  v-on:tap="applyBtn()">申请</button>
			</div>
		</div>
	</div>
</div>
<div id="apply-status">
	<table>
		<tr>
			<th colspan="2">审核进度</th>
		</tr>
		<tr>
			<td>申请帐号</td>
			<td v-html="player_index"></td>
		</tr>
		<tr>
			<td>申请人帐号</td>
			<td v-html="from_index"></td>
		</tr>
		<tr>
			<td>申请状态</td>
			<td v-html="apply_status | status"></td>
		</tr>
		<tr v-show="remark">
			<td>原因</td>
			<td v-html="remark"></td>
		</tr>
		<tr v-show="apply_status==0">
			<td colspan="2">
				<button id="apply-btn" v-on:tap="applyAgn()">再次申请</button>
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript" src="js/js/mui.min.js?v=2"></script>
<script type="text/javascript" src="js/js/vue.min.js?v=2"></script>
<script type="text/javascript" src="js/js/public.js?v=2"></script>
<script type="text/javascript" src="js/js/md5.js?v=2"></script>
<script type="text/javascript" src="js/js/jscode.js?v=2"></script>
</body>
</html>