<?php
class Setting extends MF_Model{
	
	protected $reference_models = array(
		"user" => array(
			"column" => "users_id",
			"refModel" => "User",
			"refColumn" => "id",
		),
		"type" => array(
			"column" => "setting_types_id",
			"refModel" => "SettingType",
			"refColumn" => "id",
		)
	);
	
	public function __construct(){
		parent::__construct('settings');
	}
	
}