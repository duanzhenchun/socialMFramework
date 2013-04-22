<?php
	$_acl_resources = array(
		'Auth::login' => 'all',
		'Auth::facebookLogin' => 'all',
		'Auth::logout' => 'all',
		'Application::view' => 'all',
		'Application::viewShared' => array('user','admin'),
		'Application::share' => array('user','admin'),
		'Application::listShares' => array('user','admin'),
		'Application::listFavorites' => array('user','admin'),
		'Application::checkForShares' => array('user','admin'),
		'Comment::publish' => array('user','admin'),
		'Favorite::changeStatus' => array('user','admin'),
		'Notification::listPush' => array('user','admin'),
		'User::listSettings' => array('user','admin'),
		'User::setSettings' => array('user','admin'),
		'User::listFollowings' => array('user','admin'),
		'User::listTimeline' => array('user','admin'),
		'User::listFollowers' => array('user','admin'),
		'User::register' => 'all',
		'User::getData' => array('user','admin'),
		'User::follow' => array('user','admin'),
		'User::search' => array('user','admin'),
	);
	
	$_acl_models_data_allowed = array(
		'User' => array(
			'guest' => array(
				'first_name'			=> 'attr',
				'last_name'				=> 'attr',
				'picture'				=> 'getPictureUrl',
				'shares_counter'		=> 'counter::Share',
				'favorites_counter'		=> 'counter::Favorite',
				'followers_counter'		=> 'counter::Follower::user2',
				'followings_counter'	=> 'counter::Follower::user1',
				'bio'					=> 'attr',
				'sex'					=> 'attr',
				'website'				=> 'attr',
				'profession'			=> 'attr',				
			),
			'user' => array(
				'facebook_id'		=> 'attr',
				'following'			=> 'isCurrentUserFollowing'
			),
			'own' => array(
				'email'				=> 'attr',
				'phone_number'		=> 'attr'
			),
		),
		'Application' => array(
			'guest' => array(
				'package_name'		=> 'attr',
				'category'			=> 'parent::Category',
				'google_play_id'	=> 'attr',
				'application_name'	=> 'attr',
				'description'		=> 'attr',
				'price'				=> 'attr',
				'icon'				=> 'getIconUrl',
				'shares_counter'	=> 'counter::Share'
			),
			'user' => array(
				'shared_by_user'	=> 'sharedByUser',
			),
		),
		'Category' => array(
			'guest' => array(
				'description'		=> 'attr',
			),
		),
		'Share' => array(
			'guest' => array(
				'created_at'		=> 'attr',
				'publisher'			=> 'parent::User',
				'application'		=> 'parent::Application',
				'favorites_counter'	=> 'counter::Favorite',
				'comments_counter'	=> 'counter::Comment',
				'comments'			=> 'dependents::Comment',
			),
			'user' => array(
				'is_favorite'		=> 'isFavorite',
			),
		),
		'Comment' => array(
			'guest' => array(
				'created_at'		=> 'attr',
				'publisher'			=> 'parent::User',
				'comment'			=> 'attr',
			),
		),
		'Favorite' => array(
			'guest' => array(
				'created_at'		=> 'attr',
				'user'				=> 'parent::User',
				'share'				=> 'parent::Share',
			),
		),
		'NotificationType' => array(
			'guest' => array()
		),
		'Notification' => array(
			'guest' => array(
				'created_at'		=> 'attr',
				'text'				=> 'parceText',
				'type'				=> 'parent::NotificationType',
			),
		),
		'FeedType' => array(
			'guest' => array()
		),
		'Feed' => array(
			'guest' => array(
				'created_at'		=> 'attr',
				'text'				=> 'getText',
				'thumb'				=> 'getThumb',
				'type'				=> 'parent::FeedType',
			),
		),
		
	);