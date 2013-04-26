<?php
class Feed extends MF_Model{
	
	protected $reference_models = array(
		"FeedType" => array(
			"column" => "feed_types_id",
			"refModel" => "FeedType",
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
		parent::__construct('feeds');
		$this->action_trigger = new FeedTrigger( $this );
	}
	
	public function getText(){
		$auth = MF_Auth::getInstance();
		$type = $this->getParent( 'FeedType' );
		if( $auth->isLogged() ){
			if( $this->users_id==$auth->user->id ){
				return $type->own_text;
			}elseif( $this->mentioned_user_id==$auth->user->id ){
				return $type->mentioned_text;
			}
		}
		return $type->other_text;
	}
	
	public function getThumb(){
		$type = $this->getParent( 'FeedType' );
		$thumb = $type->thumb;
		if( empty($thumb) ) return false;
		if( $thumb == '%application%' && $share = $this->getParent( 'Share' ) ){
			$application = $share->getParent('Application');
			return $application->getIconUrl();
		}elseif( $thumb == '%user%' ){
			$auth = MF_Auth::getInstance();
			if( $this->mentioned_user_id ){
				$user =  $auth->user->id==$this->mentioned_user_id? $this->getParent('User', 'User'):$this->getParent('User', 'MentionedUser');
			}else{
				$user =  $this->getParent('User', 'User');
			}
			return $user->getPictureUrl();
		}
	}
}