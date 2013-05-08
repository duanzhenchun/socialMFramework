var show_more_description = function(){
    if($( '.app_description' ).css("height") == "50px"){
	    $('.app_description').css({
	        'height': '200px','overflow':'auto',
	    })
		$('.readmore').html('less text')
	}
	else{
		 $('.app_description').css({
	        'height': '50px','overflow':'hidden',
	    })
	    $('.readmore').html('Read more')
	}
}var user_edit_action = function(){
	$.ajax({
	    url: base_url+'users/edit/',
	    success: function(data) {
	        $('#profile_data').html(data);
	    }
	}); 
}
var list_shared_apps= function (user){
	$(".my_profile_apps_menu_link_shr").css("color", "#fff");
	$(".my_profile_apps_menu_link_fav").css("color", "#377570");
	$.ajax({
	    url: base_url+'applications/listSharesProfile/',
     	data: 'user='+user,
		type:'POST',
	    success: function(data) {
	        $('#apps_in_profile_menu').html(data);
	    }
	}); 
}

var list_favorites_apps= function (user){
	$(".my_profile_apps_menu_link_shr").css("color", "#377570");
	$(".my_profile_apps_menu_link_fav").css("color", "#fff");
	$.ajax({  
	    url: base_url+'applications/listFavoritesProfile/',  
	    data: 'user='+user,
		type:'POST',
	    success: function(data) {  
	        $('#apps_in_profile_menu').html(data);  
	    }  
	}); 
}
var load_followers_following= function (typef){
	$.ajax({  
	    url: base_url+'users/getFollowersFollowing/class/'+typef, 
	    success: function(data) {  
	        $('#profile_data').html(data);  
	    }  
	}); 
}var send_fav_action = function(shared_id,favorite,i){
	var counter = parseInt($('.counter_'+i+':first').text());
	$.ajax({  
	    url: base_url+'applications/favorite/shared_id/'+shared_id+'/favorite/'+favorite, 
	    success: function() {
	    	if(favorite == 1){
	    		/*alert("You mark the application how Favorite");*/
	    		$('#fav_icon_'+i).hide();
	    		$('#unfav_icon_'+i).show();
	    		$('.counter_'+i).text( ++counter );
	    	}
	    	else{
	    		/*alert("You mark the application how UnFavorite");*/
	    		$('#unfav_icon_'+i).hide();
	    		$('#fav_icon_'+i).show();
	    		$('.counter_'+i).text( --counter );
	    	}
	    }  
	}); 
}
var send_share_action = function(shared_id,favorite){
	alert("share");
}
var send_comment = function(shared_id){
	var comment = $('#comment_input').attr("value"); 
	$.ajax({  
	    url: base_url+'comments/publish/shared_id/'+shared_id+'/comment/'+comment, 
	    success: function() {
	    	alert("Your comment has already been published");
	    }  
	}); 
}var initFB = function(){	FB.init({      appId      : fb_app_id,                        // App ID from the app dashboard      status     : true,                             // Check Facebook Login status      xfbml      : true                              // Look for social plugins on the page    }); 	FB.login(function(response){    	if (response.status === 'connected') {    		var accessToken = response.authResponse.accessToken;
    		$.ajax({  			    url: base_url+'auth/facebook-login/',
			    //data: 'access_token=BAABsfHkvuU4BAAhq7ZB43i52xtmBMIHmKiZCQuu1AjJdOqJfx3nPsejD7ZC1LpGDJHYnOuHz9hjmWFZAY9QTdk0dxuasRvZBZB6nIqwTBIOPoqSB1BKJZCDw7v9lO67Lzbiz6V3E3imniWhVb1vLJj52P7g9QZCOG115BBuscYA7JB7Q1pyt0ql62w3j2uv3f2sZD',
			    data: 'access_token='+accessToken,
			    type:'POST',				success: function() {
			    }  			}); 		}else{			alert("Not yet :()");		}    },{scope: 'email,read_friendlists,publish_stream'});}
var openFeddDetail = function(i,show){
	
	if($('#notification_box_detail_tl'+i).css('display') == 'none'){
		$('#notification_box_detail_tl'+i).show();
		$('#openFeddDetailIcon'+i).text("<<");		
	}
	else{
		$('#notification_box_detail_tl'+i).css("display", "none");
		$('#openFeddDetailIcon'+i).text(">>");
	}
}