<?php
class MF_ApiDelegate{
	
	protected $_api_response;
	
	public function __construnct(){
		$this->_api_response = new MF_ApiResponse();
	}
	
	protected function validateRequiredArgs( array $args, array $required ){
		foreach( $required as $key => $r ) {
			if( !isset($args[$r]) ){
				$this->_api_response->setErrorCode( '1002' );
				return false;
			};
		}
		return true;
	}
	
	public function getResponse(){
		return $this->_api_response->getResponse();
	}
	
}
