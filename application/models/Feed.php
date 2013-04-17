<?php
class Feed extends MF_Model{
	
	protected $reference_models = array(
		"FeedType" => array(
			"column" => "feed_types_id",
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
		parent::__construct('feeds');
		$this->action_trigger = new FeedTrigger( $this );
	}
	
	public function getThumb(){
		$type = $this->getParent( 'FeedType' );
		$thumb = $type->thumb;
		if( empty($thumb) ) return false;
		$data = json_decode( $this->serialized_data );
		if( $thumb == '%application%' ){
			$application = new Application();
			$application->select( $data->application->package_name, 'package_name' );
			return $application->getIconUrl();
		}elseif( $thumb == '%user%' ){
			$user = new User();
			$user->select( $data->user->id );
			return $user->getPictureUrl();
		}
	}
	
}