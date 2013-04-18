<?php
class AuthController extends MF_Controller{
	
	public function loginAction(){
		$request = MF_Request::getInstance();
		$auth = MF_Auth::getInstance();
		if( $auth->isLogged() ){
			$this->redirect( array() );
			exit;
		}
		$do = $request->getParam('do', false);
		if( $request->isPost() && $do && $do == 'save' ){
			$email = $request->getParamPost( 'email', false );
			$password = $request->getParamPost( 'password', false );
			if( empty($email) || empty($password) ){
				$this->view->addFlashMessage( array("error", "Incorrect user or password") );
				$this->redirect( array('controller'=>'auth', 'action'=>'login') );
			}else{
				if( $auth->login( $email, $password ) ){
					$this->redirect( array('controller'=>'users','action'=>'index') );
				}else{
					$this->view->addFlashMessage( array("error", "Incorrect user or password") );
					$this->redirect( array('controller'=>'auth', 'action'=>'login') );
				}
			}
		}
		
	}
	
	public function logoutAction(){
		$auth = MF_Auth::getInstance();
		$auth->logout();
		$this->redirect( array('controller'=>'auth', 'action'=>'login') );
	}
	
}