<?php
class Follower extends MF_Model{
	
	protected $reference_models = array(
		"user1" => array(
			"column" => "users1_id",
			"refModel" => "User",
			"refColumn" => "id",
			"order_param" => "`created_at` DESC"
		),
		"user2" => array(
			"column" => "users2_id",
			"refModel" => "User",
			"refColumn" => "id",
			"order_param" => "`created_at` DESC"
		)
	);
	
	public function __construct(){
		parent::__construct('followers');
		$this->action_trigger = new FollowerTrigger( $this );
	}
	
}