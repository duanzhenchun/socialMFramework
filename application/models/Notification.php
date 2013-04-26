<?php
class Notification extends MF_Model{
	
	const STATUS_PENDING = "pending";
	const STATUS_READED = "readed";
	
	protected $reference_models = array(
		"NotificationType" => array(
			"column" => "notification_types_id",
			"refModel" => "NotificationType",
			"refColumn" => "id",
		),
		"User" => array(
			"column" => "users_id",
			"refModel" => "User",
			"refColumn" => "id",
		),
		"MentionedUser" => array(
			"column" => "mentioned_user_id",
			"refModel" => "User",
			"refColumn" => "id",
		),
		"Share" => array(
			"column" => "shares_id",
			"refModel" => "Share",
			"refColumn" => "id",
		),
	);
	
	public function __construct(){
		parent::__construct('notifications');
		$this->action_trigger = new NotificationTrigger( $this );
	}
	
	public function makeReaded(){
		$this->push_status = self::STATUS_READED;
		$this->save();
	}
	
	public function getText(){
		$type = $this->getParent( 'NotificationType' );
		return $type->text;
	}
}