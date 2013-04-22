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
}
var list_shared_apps= function (){
	$.ajax({  
	    url: base_url+'applications/listShares/', 
	    success: function(data) {  
	        $('#appssections').html(data);  
	    }  
	}); 
}
var list_favorites_apps= function (){
	$.ajax({  
	    url: base_url+'applications/listFavorites/',  
	    success: function(data) {  
	        $('#appssections').html(data);  
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
}
var send_comment = function(shared_id){
	var comment = $('#comment_input').attr("value"); 
	$.ajax({  
	    url: base_url+'comments/publish/shared_id/'+shared_id+'/comment/'+comment, 
	    success: function() {
	    	alert("Your comment has already been published")  
	    }  
	}); 
}
var application_fav = function ( shared_id, fav, package_name){
	$.ajax({  
	    url: base_url+'favorites/favUnfav/shared_id/'+shared_id+'/favorite/'+fav+'/package_name/'+package_name, 
	    success: function() {
	    	if(fav =="1"){
	    		alert("the application has marked as favorite")
	    	}
	    	if(fav =="0"){
	    		alert("the application has Unmarked as favorite")
	    	}    
	    }  
	});  
}var initFB = function(){	FB.init({      appId      : fb_app_id,                        // App ID from the app dashboard      status     : true,                                 // Check Facebook Login status      xfbml      : true                                  // Look for social plugins on the page    }); 	FB.login(function(response){    	if (response.status === 'connected') {    		var accessToken = response.authResponse.accessToken;		    alert( accessToken );		  }else{		  	alert("Not yet :()");		  }    });}
