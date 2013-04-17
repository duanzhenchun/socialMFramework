<?php
class Score extends MF_Model{
	
	protected $reference_models = array(
		"user" => array(
			"column" => "users_id",
			"refModel" => "User",
			"refColumn" => "id",
		),
		"application" => array(
			"column" => "applications_id",
			"refModel" => "Application",
			"refColumn" => "id",
		)
	);
	
	public function __construct(){
		parent::__construct('scores');
	}
	
}