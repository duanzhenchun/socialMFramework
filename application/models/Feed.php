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
		)
	);
	
	public function __construct(){
		parent::__construct('feeds');
		$this->action_trigger = new FeedTrigger( $this );
	}
	
	public function getText(){
		$auth = MF_Auth::getInstance();
		if( $auth->isLogged() && $this->users_id==$auth->user->id || $this->feed_types_id==5 ){
			return $this->parceOwnText();
		}
		return $this->parceOtherText();
	}
	
	public function parseText( $text ){
		$data = json_decode( $this->serialized_data );
		foreach( $data as $k => $d ){
			if( is_string($d) ){
				$t = $d;
			}else{
				$t = $d->text;
				$cont = "<span data-type=\"$k\"";
				$object_vars = get_object_vars( $d );
				foreach( $object_vars as $ko => $ov ){
					if( $ko != 'text' ){
						$cont .= " data-$ko=\"$ov\"";
					}
				}
				$cont .= '>';
				$t = $cont.$t.'</span>';
			}
			$text = str_replace( "%{$k}%", $t, $text);
		}
		$strpos = strpos( $text, "#user_fullname#");
		if( $strpos !== false ){
			$user = $this->getParent( 'User' );
			$cont = "<span data-type=\"user\" data-id=\"{$user->id}\">".$user->getFullName().'</span>';
			$user_name = $cont;
			$text = str_replace( "#user_fullname#", $user_name, $text);
		}
		return $text;
	}
	
	protected function parceOwnText(){
		$type = $this->getParent( 'FeedType' );
		return $this->parseText( $type->own_text );
	}
	
	protected function parceOtherText(){
		$type = $this->getParent( 'FeedType' );
		return $this->parseText( $type->other_text );
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