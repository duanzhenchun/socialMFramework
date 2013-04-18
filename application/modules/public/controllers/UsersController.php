<?php
class UsersController extends MF_Controller{
	
	public function _init(){
		//$this->bridge = new UsersBridge();
	}
	public function indexAction(){
		$time_line = MF_ApiCaller::call('User', 'listTimeline');
		$this->view->response = $time_line;
	}	
	public function getTimelineAction(){
		$time_line = MF_ApiCaller::call('User', 'listTimeline');
		$this->view->response = $time_line ;
	}	
	public function profileAction(){
		$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$user = MF_ApiCaller::call('User', 'getData');
			$this->view->response = $user;
			$get_timeline_response = MF_ApiCaller::call('User', 'listTimeline');
			$this->view->get_timeline_response = $get_timeline_response ;
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

