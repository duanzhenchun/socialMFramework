<?php
class Api_FavoritesController extends MF_Controller{
	
	public function changeStatusAction(){
		$request = MF_Request::getInstance();
		$args = $request->getParams();
		$response = MF_ApiCaller::call('Favorite', 'changeStatus', $args);
		$this->renderJSON( $response );
	}
	
}