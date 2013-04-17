<?php
class Api_UsersController extends MF_Controller{
	
	public function listTimelineAction(){
		$args = array();
		$response = MF_ApiCaller::call('User', 'listTimeline', $args);
		$this->renderJSON( $response );
	}
	
	public function listSettingsAction(){
		$response = MF_ApiCaller::call('User', 'listSettings');
		$this->renderJSON( $response );
	}
	
	public function setSettingsAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams(); // TODO change to POST !!!!
		$response = MF_ApiCaller::call('User', 'setSettings', $args);
		$this->renderJSON( $response );
	}
	
	public function listFollowingsAction(){
		$request = MF_Request::getInstance();
		$args = array();
		$args['user'] = $request->getParam( 'user', 'me' );
		$response = MF_ApiCaller::call('User', 'listFollowings', $args);
		$this->renderJSON( $response );
	}
	
	public function listFollowersAction(){
		$request = MF_Request::getInstance();
		$args = array();
		$args['user'] = $request->getParam( 'user', 'me' );
		$response = MF_ApiCaller::call('User', 'listFollowers', $args);
		$this->renderJSON( $response );
	}
	
}