<?php
class UsersController extends MF_Controller{
	
	public function _init(){
		//$this->bridge = new UsersBridge();
	}
	public function indexAction(){
		$args = array();
		$time_line = MF_ApiCaller::call('User', 'listTimeline', $args);
		var_dump($time_line);
		/*$notification_bridge = new NotificationsBridge();
		$response = $notification_bridge->getNotifications( 'array' );
		$this->view->response = $response ;*/
	}	
	public function getTimelineAction(){
		$args = array();
		$time_line = MF_ApiCaller::call('User', 'listTimeline', $args);
		var_dump($time_line);
		/*$response = $this->bridge->getTimeline( 'me', 'array' );
		$this->view->response = $response ;*/
	}	
	public function profileAction(){
		$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$args = array();
			$user = MF_ApiCaller::call('User', 'listTimeline', $args);
			/*$this->bridge = new UsersBridge();
			$request = MF_Request::getInstance();
			$user_id = $request->getParam('user_id', 'me');
			$response = $this->bridge->getData($user_id);*/
			$this->view->response = "profile";
			/*$notification_response = $this->bridge->getTimeline($user_id, 'array' );
			$this->view->notification_response = $notification_response ;*/
		}
		else{ 
			$this->redirect( array('controller'=>'auth', 'action'=>'login') );
		}
	}
	public function followAction(){
		/*$request = MF_Request::getInstance();
		$id = $request->getParam( 'id', false );
		$follow = $request->getParam( 'follow', false );
		$response = $this->bridge->follow( $id, $follow );
		$this->redirect( array('controller'=>'users', 'action'=>'profile','user_id'=>$id) );*/
	}
	
	public function editAction(){
		/*$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$this->bridge = new UsersBridge();
			$response = $this->bridge->getData($auth->user->id);
			$this->view->response = $response;
		}*/
	}
	public function getFollowersFollowingAction(){
		$this->disablelayout();
		$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$request = MF_Request::getInstance();
			$class = $request->getParam('class', false);
			$user = new User();
			$user->select($auth->user->id);
			
			$args = array();
			$args['user'] = $request->getParam( 'user', 'me' );
			
			if($class=="followers"){
				$this->view->follow  = MF_ApiCaller::call('User', 'listFollowers', $args);
				$this->view->title ="Followers";
			}
			else{
				$this->view->follow  = MF_ApiCaller::call('User', 'listFollowings', $args);
				$this->view->title ="Followings";
			}
		}
		else{ $this->redirect( array('controller'=>'auth', 'action'=>'login') );}
		
	}
	public function registerAction(){
		/*$request = MF_Request::getInstance();
		$user_data = $request->getParamsPost();
		$type = $request->getParamGet( 'type', false );
		$response = $this->bridge->register( $type, $user_data );
		$this->renderJSON( $response );*/
	}
}

