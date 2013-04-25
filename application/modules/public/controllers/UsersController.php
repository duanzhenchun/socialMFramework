<?php
class UsersController extends MF_Controller{
	
	public function _init(){
		//$this->bridge = new UsersBridge();
	}
	public function indexAction(){
		/*$time_line = MF_ApiCaller::call('User', 'listTimeline');
		$this->view->response = $time_line;*/
			$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$request = MF_Request::getInstance();
			$args = $request->getParams();
			$args['user'] = $request->getParam( 'id', 'me' );
			$user = MF_ApiCaller::call('User', 'getData', $args);
			$this->view->response = $user;
			$get_timeline_response = MF_ApiCaller::call('User', 'listTimeline',$args);
			$this->view->get_timeline_response = $get_timeline_response ;
		}
		else{ 
			$this->redirect( array('controller'=>'auth', 'action'=>'login') );
		}
	}	
	public function getTimelineAction(){
		$time_line = MF_ApiCaller::call('User', 'listTimeline');
		$this->view->response = $time_line ;
	}	
	public function followAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$args['user'] = $request->getParam( 'user', 'me' );
		$follow = MF_ApiCaller::call('User', 'follow',$args);
		$this->redirect( array('controller'=>'users', 'action'=>'index') );
	}
	
	public function editAction(){
		$this->disablelayout();
		$auth = MF_Auth::getInstance();
		if($auth->isLogged()){
			$request = MF_Request::getInstance();
			$save = $request->getParam('edit');
			if($save != 'ok'){
				$args = $request->getParams();
				$args['user'] = $request->getParam( 'id', 'me' );
				$user = MF_ApiCaller::call('User', 'getData', $args);
				$this->view->response = $user;
			}
			else{
				//die("save");
				$this->view->addFlashMessage( array("error", "dont work the edit delegate!") );
				$args = $request->getParams();
				$args['user'] = $request->getParam( 'id', 'me' );
				$user = MF_ApiCaller::call('User', 'getData', $args);
				$this->view->response = $user;
			}
		}
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

