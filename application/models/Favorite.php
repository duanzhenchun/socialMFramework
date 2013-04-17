<?php
class Favorite extends MF_Model{
	
	protected $reference_models = array(
		"user" => array(
			"column" => "users_id",
			"refModel" => "User",
			"refColumn" => "id",
			"order_param" => "`created_at` DESC"
		),
		"share" => array(
			"column" => "shares_id",
			"refModel" => "Share",
			"refColumn" => "id",
		)
	);
	
	public function __construct(){
		parent::__construct('favorites');
		$this->action_trigger = new FavoriteTrigger( $this );
	}
	
}