<?php
class Share extends MF_Model{
	
	protected $reference_models = array(
		"user" => array(
			"column" => "users_id",
			"refModel" => "User",
			"refColumn" => "id",
			"order_param" => "`created_at` DESC"
		),
		"application" => array(
			"column" => "applications_id",
			"refModel" => "Application",
			"refColumn" => "id",
			"order_param" => "`created_at` DESC"
		)
	);
	
	public function __construct(){
		parent::__construct('shares');
		$this->action_trigger = new ShareTrigger( $this );
	}
	
	public function selectSpecific( $applications_id, $users_id ){
		$sql = "SELECT * FROM `{$this->tableName}` WHERE `applications_id` = $applications_id AND `users_id` = $users_id LIMIT 1";
		return $this->selectFromSQL( $sql );
	}
	
	public function isFavorite( $user = null ){
		return $this->getFavorite( $user )? true: false;
	}
	
	public function getFavorite( $user = null ){
		if( is_null( $user ) ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}
		$sql = "SELECT * FROM `favorites` WHERE `users_id`={$user->id} AND `shares_id`={$this->id}";
		$favorite = new Favorite();
		if( $favorite->selectFromSQL( $sql ) ){
			return $favorite;
		}else{
			return false;
		}
	}
	
}