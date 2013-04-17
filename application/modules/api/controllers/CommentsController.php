<?php
class Api_CommentsController extends MF_Controller{
	
	public function publishAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Comment', 'publish', $args);
		$this->renderJSON( $response );
	}
	
}