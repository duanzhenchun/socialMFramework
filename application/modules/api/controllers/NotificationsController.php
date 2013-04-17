<?php
class Api_NotificationsController extends MF_Controller{
	
	public function listPushAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Notification', 'listPush', $args);
		$this->renderJSON( $response );
	}
	
}