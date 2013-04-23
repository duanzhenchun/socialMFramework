<?php
class ApplicationsController extends MF_Controller{
	
	public function _init(){
		//$this->bridge = new ApplicationsBridge();
	}
	public function indexAction(){
		//$this->view->javascript = "show_shared_apps();";
	}
	public function getDataAction(){
		/*$request = MF_Request::getInstance();
		$package_name = $request->getParam( 'package_name', false );
		$shared_id = $request->getParam( 'shared_id', false );
		$this->view->shared_id=$shared_id;
		$response = $this->bridge->getData( $package_name, $shared_id );
		$this->view->response = $response ;*/
	}
	
	public function listSharesAction(){
		$this->disableLayout();
		$request = MF_Request::getInstance();
		$args = array();
		$args['user_id'] = $request->getParam( 'user', 'me' );
		$response = MF_ApiCaller::call('Application', 'listShares', $args);
		$this->view->response= $response ;
	}
	public function listFavoritesAction(){
		$this->disableLayout();
		$request = MF_Request::getInstance();
		$args = array();
		$args['user_id'] = $request->getParam( 'user', 'me' );
		$response = MF_ApiCaller::call('Application', 'listFavorites', $args);
		$this->view->response= $response ;
	}
	public function shareAction(){
		$this->disableLayout();
		$request = MF_Request::getInstance();
		$args = array();
		$args['package_name'] = $request->getParam( 'package_name', false );
		$args['application_name'] = $request->getParam( 'application_name', false );
		$response = MF_ApiCaller::call('Application', 'share', $args);
		$this->view->response= $response ;
	}
	public function favoriteAction(){
		$this->disableLayout();
		$request = MF_Request::getInstance();
		$args = array();
		$args['shared_id'] = $request->getParam( 'shared_id', false );
		$args['favorite'] = $request->getParam( 'favorite', false );
		$response = MF_ApiCaller::call('Favorite', 'changeStatus', $args);
		$this->view->response= $response ;
	}
}