<?php
class User extends MF_Model{
	
	protected $reference_models = array(
		"referer" => array(
			"column" => "refered_by",
			"refModel" => "User",
			"refColumn" => "id",
		)
	);
		
	public function __construct(){
		parent::__construct('users');
		$this->action_trigger = new UserTrigger( $this );
	}
	
	public function isProfileComplete(){
		if( isset($this->password) && $this->password ){
			return 1;
		}
		return 0;
	}
	
	public function getFeeds( $from_id=false, $to_id=false, $limit=20 ){
		$sql_condition = "WHERE ((`f`.`users_id`={$this->id})";
		$following = $this->getFollowings();
		foreach( $following as $f ){
			$sql_condition .= " OR (`f`.`users_id`={$f->id})";
		}
		$sql_condition .= ")";
		if( $from_id ){
			$sql_condition .= " AND (`f`.`id`>{$from_id})";
		}
		if( $to_id ){
			$sql_condition .= " AND (`f`.`id`<{$to_id})";
		}
		$sql_limit = $from_id? "" : "LIMIT $limit";
		$sql_order = "ORDER BY `created_at` DESC";
		$sql = "SELECT `f`.* FROM `feeds` AS `f`";
		$sql .= " ".$sql_condition;
		$sql .= " ".$sql_order;
		$sql .= " ".$sql_limit;
		return MF_Model::glob( 'Feed', $sql );
	}
	
	public function getFullName(){
		return $this->first_name.' '.$this->last_name;
	}
	
	public function getFollowings(){
		$sql = "SELECT `u`.* FROM `users` AS `u` INNER JOIN `followers` AS `f` ON `f`.`users2_id`=`u`.`id` WHERE `f`.`users1_id`={$this->id}";
		return MF_Model::glob( 'User', $sql );
	}
	
	public function getFollowers(){
		$sql = "SELECT `u`.* FROM `users` AS `u` INNER JOIN `followers` AS `f` ON `f`.`users1_id`=`u`.`id` WHERE `f`.`users2_id`={$this->id}";
		return MF_Model::glob( 'User', $sql );
	}
	
	public function setSetting( $key, $value ){
		$setting = $this->getSetting( $key, true );
		if( $setting === null ){
			return false;
		}
		$setting->value = $value;
		$setting->save();
		return true;
	}
	
	public function getSettingValue( $key, $save_default=false ){
		if( $setting = $this->getSetting( $key, $save_default ) ){
			return $setting->value;
		}
		return null;
	}
	
	public function getSetting( $key, $save_default=false ){
		$setting_type = new SettingType();
		if( $setting_type->select( $key, 'key' ) ){
			$sql = "SELECT * FROM `settings` WHERE `setting_types_id`={$setting_type->id} AND `users_id`={$this->id}";
			$setting = new Setting();
			if( !$setting->selectFromSQL( $sql ) ){
				if( $save_default ){
					$setting = new Setting();
					$setting->users_id = $this->id;
					$setting->setting_types_id = $setting_type->id;
					$setting->value = $setting_type->default;
					$setting->save();
				}else{
					return null;
				}
			}
			return $setting;
		}else{
			return null;
		}
	}
	
	public function getPictureUrl(){
		if( $this->picture ){
			return "http://{$_SERVER['HTTP_HOST']}/".BASE_URL."/pictures/{$this->id}/{$this->picture}";
		}
		return '';
	}
	
	public function getFollowing( $to ){
		$sql = "SELECT * FROM `followers` WHERE `users1_id`={$this->id} AND `users2_id`={$to->id}";
		$following_model = new Follower();
		return $following_model->selectFromSQL( $sql )? $following_model: false;
	}
	
	public function getUnreadNotifications(){
		$sql = "SELECT * FROM `notifications` WHERE `users_id`={$this->id} AND `push_status`='".Notification::STATUS_PENDING."'";
		return MF_Model::glob( 'Notification', $sql );
	}
	
	public function isCurrentUserFollowing(){
		$auth = MF_Auth::getInstance();
		if( $auth->isLogged() ){
			if( $auth->user->id == $this->id ){
				return "own";
			}
			if( $auth->user->getFollowing( $this ) ){
				return 1;
			}
			return 0;
		}
		return 'no-logged';
	}
	
	public function getPictureFolder(){
		if( is_null($this->id) ){
			return false;
		}
		$pictures_path = APPLICATION_PATH.'/../pictures/'.$this->id.'/';
		if( !file_exists( $pictures_path ) ){
			mkdir( $pictures_path );
		}
		return $pictures_path;
	}
	
	public function gotFacebookImage( $save = false ){
		$facebook_id = $this->facebook_id;
		if( !empty($facebook_id ) && ( !isset($this->picture) || empty($this->picture) ) ){
			$pictures_path = $this->getPictureFolder();
			if( $pictures_path ){
				$picture_name = time().'.jpg';
				
				$img = file_get_contents("http://graph.facebook.com/{$this->facebook_id}/picture?width=400&height=400");
				$file = $pictures_path.$picture_name;
				file_put_contents($file, $img);
				chmod( $file, 0755 );
				$this->picture = $picture_name;
				if( $save ) $this->save();
			}
		}
	}
	
}