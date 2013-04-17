<?php
class NotificationType extends MF_Model{
	
	protected $reference_models = array(		"PushSettingType" => array(			"column" => "push_setting_types_id",			"refModel" => "SettingType",			"refColumn" => "id",		),
		"EmailSettingType" => array(
			"column" => "email_setting_types_id",
			"refModel" => "SettingType",
			"refColumn" => "id",
		)	);
	
	public function __construct(){
		parent::__construct('notification_types');
	}
	
}