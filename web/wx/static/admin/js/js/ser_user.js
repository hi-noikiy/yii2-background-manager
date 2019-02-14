
//查询代理
var vm = new Vue({
	el : 'body',
	data : {
		inputTxt : '',
		userData : null,
		res_msg : '',
		selected : '',
		sjid : null,
		EuserData : null,
		ser_user_id : ''
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
		ser_user : function(){
			var that = this;
			if( this.inputTxt != '' ){
				$.ajax({
					type: 'post',
		            url: base_url + '&r=admin-statis/get-lobby_player-info',
		            data: {player_index: this.inputTxt},
		            success : function(res){
		            	var json = res;
		            	if( json.ret_code == 0 ){
							// that.inputTxt = '';
		            		that.userData = json.data;
		            	}else{
							Modal.alert({msg: json.ret_msg});
		            	}
		            },
					error : function(){
						that.inputTxt = '';
						Modal.alert({msg: '请求失败'});
					}
				});
			}
		},
		edit_daili_level : function(id,nlevel){
			var that = this;
			if( this.selected != this.userData.DAILI_LEVEL ) {
				$.ajax({
					type: 'post',
					url: base_url + '&r=admin/updata-daili',
					data: {player_index: id, daili_level: nlevel},
					success: function (res) {
						var json = res;
						if (json.ret_code == 0) {
							Modal.alert({msg: '修改成功'}).on(function(){
								that.userData.DAILI_LEVEL = nlevel;
							});
						}else{
							Modal.alert({msg: json.ret_msg});
						}
					},
					error : function(){
						Modal.alert({msg: '请求失败'});
					}
				});
			}else{
				Modal.alert({msg: '修改内容未变，请重新选择'});
			}
		},
		edit_sj_id :function(id,pid){
			var that = this;
			if( this.sjid != this.userData.parent_info.PLAYER_INDEX ) {
				$.ajax({
					type: 'post',
					url: base_url + '&r=admin/updata-daili-parentindex',
					data: {player_index: id, parent_index: pid},
					success: function (res) {
						var json = res;
						if (json.ret_code === 0) {
							Modal.alert({msg: '修改成功'}).on(function(){
								that.userData.parent_info.PLAYER_INDEX = pid;
								that.sjid = '';
							});
						}else {
							Modal.alert({msg: json.ret_msg});
						}
					},
					error : function(){
						Modal.alert({msg: '请求失败'});
					}
				});
			}else{
				Modal.alert({msg: '修改内容未变，请重新填写'});
			}
		},
		clone : function(data){
			this.EuserData = JSON.parse(JSON.stringify(data));
		},
		edit_user_info : function(EuserData){
			var that = this;
			$.ajax({
				type: 'post',
				url: base_url + '&r=admin/updata-daili-info',
				data: {
					PLAYER_INDEX : this.userData.PLAYER_INDEX,
					NAME : this.EuserData.NAME,
					TRUE_NAME : this.EuserData.TRUE_NAME,
					TEL : this.EuserData.TEL,
					ADDRESS : this.EuserData.ADDRESS,
					BANK_ACCOUNT : this.EuserData.BANK_ACCOUNT,
					OP_CODE : this.EuserData.OP_CODE
				},
				success: function (res) {
					var json = res;
					if (json.ret_code == 0) {
						Modal.alert({msg: '修改成功'}).on(function(){
							that.userData = that.EuserData;
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
