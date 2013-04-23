<?php
class AuthController extends MF_Controller{
	
	public function loginAction(){
		$request = MF_Request::getInstance();		
		$args = $request->getParamsPost();
		if($args){
			$response = MF_ApiCaller::call('Auth', 'login', $args);
			if($response['ok']){
				$this->redirect( array('controller'=>'users','action'=>'index') );
			}
			else{
				$this->view->addFlashMessage( array("error", $response["error"]['message']) );
			}
		}
	}
	public function facebookloginAction(){
		/*$facebook = new Facebook();
		$fb_user = $facebook->getUser();
		var_dump( $fb_user );*/
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$args['access_token'] = $request->getParam( 'access_token', false );
		$response = MF_ApiCaller::call('Auth', 'facebookLogin', $args);
		//var_dump($args);exit;
		if($response['ok']){
			die("hey");
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