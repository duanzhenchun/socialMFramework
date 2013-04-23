<?php
class AuthDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function login( $args ){
		if( !$this->validateRequiredArgs($args, array('email','password')) ){
			return;
		}
		$auth = MF_Auth::getInstance();
		if( $auth->login( $args['email'], $args['password'] ) ){
			$this->_api_response->setResponse( array( 'profile_complete'=>1, 'user'=>$auth->user->getArrayData( $auth->user ) ) );
		}else{
			$this->_api_response->setErrorCode( '2001' );
		}
	}
	
	public function logout( $args ){
		$auth = MF_Auth::getInstance();
		$auth->logout();
		$this->_api_response->setResponse( array() );
	}
	
	public function facebookLogin( $args ){
		if( !$this->validateRequiredArgs($args, array('access_token')) ){
			return;
		}
		
		$facebook = new Facebook();
		$facebook->setAccessToken( $args['access_token'] );
		$fb_user = $facebook->getUser();
		if( !empty($fb_user) ){
			$user = new User();
			$auth = MF_Auth::getInstance();
			$me = $facebook->api('/me');
			$email = $me['email'];
			$save = false;
			if( !$user->select( $email, 'email' ) || !$user->isProfileComplete() ){
				$this->_api_response->setResponse( array( 'profile_complete'=>0 ) );
				return;
			}
			if( empty( $user->first_name ) ){
				$user->first_name = $me['first_name'];
				$save = true;
			}
			if( empty( $user->last_name ) ){
				$user->last_name = $me['last_name'];
				$save = true;
			}
			if( empty( $user->facebook_id ) ){
				$user->facebook_id = $fb_user;
				$save = true;
			}
			if( $save ){
				$user->save();
			}
			$auth->impersonate( $user->email );
			$this->_api_response->setResponse( array( 'profile_complete'=>1, 'user'=>$auth->user->getArrayData( $auth->user ) ) );
			return;
		}
		$this->_api_response->setErrorCode( '1005' );
	}
	
}