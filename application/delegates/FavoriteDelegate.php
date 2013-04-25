<?php
class FavoriteDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function changeStatus( $args ){
		if( !$this->validateRequiredArgs($args, array('shared_id','favorite')) ){
			return;
		}
		$share = new Share();
		if( !$share->select( $args['shared_id'] ) ){
			$this->_api_response->setErrorCode( '1002' );
			return;
		}
		$auth = MF_Auth::getInstance();
		
		if( $args['favorite'] ){
			if( $share->isFavorite() ){
				$this->_api_response->setErrorCode( '5001' );
				return;
			}
			$favorite = new Favorite();
			$favorite->users_id = $auth->user->id;
			$favorite->shares_id = $share->id;
			$favorite->save();
		}else{
			if( $favorite = $share->getFavorite() ){
				$favorite->delete();
			}else{
				$this->_api_response->setErrorCode( '5002' );
				return;
			}
		}
		
		$this->_api_response->setResponse();
	}
	
}