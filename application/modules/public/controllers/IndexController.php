<?php
class IndexController extends MF_Controller{
	
	public function _init(){
	}
	public function indexAction(){
		$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$this->redirect( array('controller'=>'users','action'=>'index') );
		}
	}
}