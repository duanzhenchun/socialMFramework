<?php
class MF_ApiResponse{
	
	private $error_code	= false;
	private $response	= null;
	
	public function __construnct(){}
	
	public function setErrorCode( $error_code ){
		$this->response['ok'] = 0;
		$this->response['error'] = $this->parseError($error_code);
	}
	
	public function setResponse( $response = array() ){
		$response['ok'] = 1;
		$this->response = $response;
	}
	
	public function getResponse(){
		if( $this->response !== null ){
			return $this->response;
		}
		MF_Error::dieError( "No error or response setted", 500 );
	}
	
	private function parseError( $error_code ){
		global $_api_errors;
		return array(
			'code' => $error_code,
			'message' => $_api_errors[$error_code]
		);
	}
	
}
