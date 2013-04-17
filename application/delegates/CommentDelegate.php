<?php
class CommentDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function publish( $args ){
		if( !$this->validateRequiredArgs($args, array('shared_id','comment')) ){
			return;
		}
		$share = new Share();
		if( !$share->select( $args['shared_id'] ) ){
			$this->_api_response->setErrorCode( '1002' );
			return;
		}
		$auth = MF_Auth::getInstance();
		
		$comment = new Comment();
		$comment->users_id = $auth->user->id;
		$comment->shares_id = $share->id;
		$comment->comment = $args['comment'];
		$comment->save();
		$this->_api_response->setResponse( array() );
	}
	
}