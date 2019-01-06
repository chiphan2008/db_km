<script>
	var url_node = 'https://node.kingmap.vn:2309';
	var key_node = 'NKbqe8ovfMetW8WYimVN7MtNHSsy6tCo6mm7WU9Y';
	var client_id = {{Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0}};


	$(function(){
		getListFriend(1);
		getListExcept(1);
	});

	function getListFriend(page){
		var limit = 20;
		var skip = (page-1)*limit;
		$.ajax({
			url : get_url_node('api/list-friend/'+client_id+'?skip='+skip+'&limit='+limit),
			type: 'GET',
			dataType: 'json',
			headers: {
				'Authorization':key_node
			},
			success: function(res){
				renderListFriend(res.data);
			}
		});
	}

	function renderListFriend(data){
		var html = '';
		data.forEach(function(value){
			html += '<li>';
  		html += '	<a href="">';
  		html += '		<img onerror="this.src = \'/img_user/default.png\' " src="'+value.urlhinh+'" alt="'+value.name+'">';
	  	html += '		<div class="name">';
	  	html += '			'+value.name+'';
	  	html += '		</div>';
  		html += '	</a>';
  		html += '</li>';
		});
		$("#chat-box #contact-mess ul").html(html);
	}

	function getListExcept(page){
		var limit = 20;
		var skip = (page-1)*limit;
		$.ajax({
			url : get_url_node('api/except-person/'+client_id+'?skip='+skip+'&limit='+limit),
			type: 'GET',
			dataType: 'json',
			headers: {
				'Authorization':key_node
			},
			success: function(res){
				renderListExcept(res.data);
			}
		});
	}

	function renderListExcept(data){
		var html = '';
		data.forEach(function(value){
			html += '<li>';
  		html += '	<a href="">';
  		html += '		<img onerror="this.src = \'/img_user/default.png\' " src="'+value.urlhinh+'" alt="'+value.name+'">';
	  	html += '		<div class="name">';
	  	html += '			'+value.name+'';
	  	html += '		</div>';
  		html += '	</a>';
  		html += '</li>';
		});
		$("#chat-box #user-mess ul").html(html);
	}


	function get_url_node(path){
		return url_node+'/'+path;
	}
</script>