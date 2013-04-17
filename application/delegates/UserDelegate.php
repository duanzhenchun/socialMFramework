<?php
class UserDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function setSettings( $args ){
		$auth = MF_Auth::getInstance();
		foreach( $args as $key => $value ){
			$auth->user->setSetting( $key, $value );
		}
		$this->_api_response->setResponse();
	}
	
	public function listTimeline(){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
		}
		$from_id = isset($args['from'])&&$args['from']? $args['from'] : false;
		$to_id = isset($args['to'])&&$args['to']? $args['to'] : false;
		$feeds = $user->getFeeds( $from_id, $to_id );
		$feeds_data = array();
		foreach( $feeds as $feed ){
			$text_acl_ov = $feed->users_id==$user->id? 'parceOwnText' : 'parceOtherText';
			$feeds_data[] = $feed->getArrayData( null, array('text'=>$text_acl_ov) );
		}
		$this->_api_response->setResponse( array('feeds' => $feeds_data) );
	}
	
	public function listSettings( $args ){
		$auth = MF_Auth::getInstance();
		$setting_types = MF_Model::glob( 'SettingType' );
		$settings_data = array();
		foreach( $setting_types as $k => $st ){
			$setting_value = $auth->user->getSettingValue( $st->key, true );
			if( is_null($setting_value) ) MF_Error::dieError( "{$st->key} is not a valid Key", 500 );
			$settings_data[] = array(
				'key' => $st->key,
				'value' => $setting_value
			);
		}
		$this->_api_response->setResponse( array('settings' => $settings_data) );
	}
	
	public function listFollowers( $args ){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
		}
		$followers = $user->getFollowers();
		$followers_data = array();
		foreach( $followers as $key => $follower ) {
			$followers_data[] = $follower->getArrayData();
		}
		$this->_api_response->setResponse( array( 'users' => $followers_data ) );
	}
	
	public function listFollowings( $args ){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
		}
		$followings = $user->getFollowings();
		$followings_data = array();
		foreach( $followings as $key => $following ) {
			$followings_data[] = $following->getArrayData();
		}
		$this->_api_response->setResponse( array( 'users' => $followings_data ) );
	}
	
}