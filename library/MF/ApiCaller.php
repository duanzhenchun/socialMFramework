<?php
class MF_ApiCaller{
	
	public static function call( $resource, $action, $args = array() ){
		$acl = MF_ACL::getInstance();
		if( $acl->isAllow($resource.'::'.$action) ){
			$delegate_class_name = $resource."Delegate";
			$delegate = new $delegate_class_name();
			$delegate->$action( $args );
			return $delegate->getResponse();
		}
		$response = new MF_ApiResponse();
		$response->setErrorCode( '2000' );
		return $response->getResponse();
	}
	
}
