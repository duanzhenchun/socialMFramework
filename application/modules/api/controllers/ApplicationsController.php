<?php
class Api_ApplicationsController extends MF_Controller{
	
	public function viewAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Application', 'view', $args);
		$this->renderJSON( $response );
	}
	
	public function viewSharedAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Application', 'viewShared', $args);
		$this->renderJSON( $response );
	}
	
	public function shareAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Application', 'share', $args);
		$this->renderJSON( $response );
	}
	
	public function listSharesAction(){
		$request = MF_Request::getInstance();
		$args = array();
		$args['user'] = $request->getParam( 'user', 'me' );
		$response = MF_ApiCaller::call('Application', 'listShares', $args);
		$this->renderJSON( $response );
	}
	
	public function listFavoritesAction(){
		$request = MF_Request::getInstance();
		$args = array();
		$args['user'] = $request->getParam( 'user', 'me' );
		$response = MF_ApiCaller::call('Application', 'listFavorites', $args);
		$this->renderJSON( $response );
	}
	
	public function checkForSharesAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Application', 'checkForShares', $args);
		$this->renderJSON( $response );
	}
	
}