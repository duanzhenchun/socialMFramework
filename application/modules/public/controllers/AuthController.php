<?php
class AuthController extends MF_Controller{
	
	public function loginAction(){
		
		$request = MF_Request::getInstance();		
		$args = $request->getParamsPost();
		$response = MF_ApiCaller::call('Auth', 'login', $args);
		if($response['ok']){
			$this->redirect( array('controller'=>'users','action'=>'index') );
		}
		else{
			$this->view->addFlashMessage( array("error", $response["error"]['message']) );
		}
				
	}
	
	public function logoutAction(){
		$response = MF_ApiCaller::call('Auth', 'logout');
		if(!$response['ok']){
			$this->view->addFlashMessage( array("error", $response["error"]['message']) );
		}
		$this->redirect( array('controller'=>'auth','action'=>'login') );
	}
	
}