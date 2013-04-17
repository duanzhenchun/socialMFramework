<?php
class Comment extends MF_Model{
	
	protected $reference_models = array(
		"user" => array(
			"column" => "users_id",
			"refModel" => "User",
			"refColumn" => "id",
		),
		"share" => array(
			"column" => "shares_id",
			"refModel" => "Share",
			"refColumn" => "id",
			"order_param" => "`created_at` DESC"
		)
	);
	
	public function __construct(){
		parent::__construct('comments');
		$this->action_trigger = new CommentTrigger( $this );
	}
	
	protected function getPublisherArrayData(){
		$user = $this->getParent( 'User' );
		return $user->getArrayData();
	}
	
}