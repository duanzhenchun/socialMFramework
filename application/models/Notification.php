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
		)
	);
	
	public function __construct(){
		parent::__construct('notifications');
		$this->action_trigger = new NotificationTrigger( $this );
	}
	
	public function makeReaded(){
		$this->status = self::STATUS_READED;
		$this->save();
	}
	
	public function parceTitle(){
		$type = $this->getParent( 'NotificationType' );
		$data = json_decode( $this->serialized_data );
		$title = $type->title;
		foreach( $data as $k => $d ){
			if( is_string($d) ){
				$t = $d;
			}else{
				$t = $d->text;
			}
			$title = str_replace( "%{$k}%", $t, $title);
		}
	}
	
	public function parceText(){
		$type = $this->getParent( 'NotificationType' );
		$data = json_decode( $this->serialized_data );
		$text = $type->text;
		foreach( $data as $k => $d ){
			if( is_string($d) ){
				$t = $d;
			}else{
				$t = $d->text;
			}
			$text = str_replace( "%{$k}%", $t, $text);
		}
	}
}