<?php
class Api_AuthController extends MF_Controller{
	
	public function loginAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParamsPost();
		$response = MF_ApiCaller::call('Auth', 'login', $args);
		$this->renderJSON( $response );
	}
	
	public function logoutAction(){
		$response = MF_ApiCaller::call('Auth', 'logout');
		$this->renderJSON( $response );
	}
	
	public function facebookLoginAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParamsPost();
		$response = MF_ApiCaller::call('Auth', 'facebookLogin', $args);
		$this->renderJSON( $response );
	}
	
}