<?php
class SettingType extends MF_Model{
	
	protected $reference_models = array();
	
	public function __construct(){
		parent::__construct('setting_types');
	}
	
}