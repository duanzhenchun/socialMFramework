<?php
class NotificationDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function listPush( $args ){
		$auth = MF_Auth::getInstance();
		$notifications = $auth->user->getUnreadNotifications();
		$notifications_data = array();
		foreach($notifications as $key => $notification ){
			$notifications_data[] = $notification->getArrayData();
			$notification->makeReaded();
		}
		$this->_api_response->setResponse( array('notifications'=>$notifications_data) );
	}
	
}