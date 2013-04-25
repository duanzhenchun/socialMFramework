<?php
class UserDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function search( $args ){
		if( !$this->validateRequiredArgs($args, array('type')) ){
			return;
		}
		$following = isset($args['following'])&&$args['following']?$args['following']:false;
		if( $args['type'] == 'search' ){
			if( !$this->validateRequiredArgs($args, array('term')) ){
				return;
			}
			$users = $this->searchUsers($args['term'], $following);
			$this->_api_response->setResponse( array( 'users' => $users ) );
		}elseif( $args['type'] == 'facebook' ){
			if( !$this->validateRequiredArgs($args, array('access_token')) ){
				return;
			}
			$users = $this->searchFacebookUsers($args['access_token'], $following);
			$this->_api_response->setResponse( array( 'users' => $users ) );
		}else{
			$this->_api_response->setErrorCode( '1002' );
			return;
		}
	}
	
	public function getData( $args ){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
		}
		$this->_api_response->setResponse( array( 'user' => $user->getArrayData( $user ) ) );
	}
	
	public function follow( $args ){
		if( !$this->validateRequiredArgs($args, array('user','follow')) ){
			return;
		}
		$auth = MF_Auth::getInstance();
		$user = new User();
		if( !$user->select( $args['user'] ) || $auth->user->id==$user->id ){
			$this->_api_response->setErrorCode( '1002' );
			return;
		}
		if( $args['follow'] ){
			if( $auth->user->getFollowing( $user ) ){
				$this->_api_response->setErrorCode( '4001' );
				return;
			}
			$follower = new Follower();
			$follower->users1_id = $auth->user->id;
			$follower->users2_id = $user->id;
			$follower->save();
			$this->_api_response->setResponse();
		}else{
			$follower = $auth->user->getFollowing( $user );
			if( !$follower ){
				$this->_api_response->setErrorCode( '4002' );
				return;
			}
			$follower->delete();
			$this->_api_response->setResponse();
		}
	}
	
	public function setSettings( $args ){
		$auth = MF_Auth::getInstance();
		foreach( $args as $key => $value ){
			$auth->user->setSetting( $key, $value );
		}
		$this->_api_response->setResponse();
	}
	
	public function register( $args ){
		if( !$this->validateRequiredArgs($args, array('email','password','confirm_password','first_name','last_name')) ){
			return;
		}
		if( $args['password'] != $args['confirm_password'] ){
			$this->_api_response->setErrorCode( 1003 );
			return;
		}
		$user = new User();
		if( $user->select( $args['email'], 'email' ) ){
			$this->_api_response->setErrorCode( 1001 );
			return;
		}
		$auth = MF_Auth::getInstance();
		$args['password'] = $auth->createHashedPassword( $args['password'] );
		unset( $args['confirm_password'] );
		$user->load( $args );
		$user->save();
		$user->select( $user->id );
		if( $auth->impersonate( $user->email ) ){
			$this->_api_response->setResponse( array('user'=>$user->getArrayData( $user )) );
		}else{
			$this->_api_response->setErrorCode( 1004 );
		}
	}
	
	public function edit( $args ){
		if( !$this->validateRequiredArgs($args, array('current_password')) ){
			return;
		}
		$auth = MF_Auth::getInstance();
		if( $auth->createHashedPassword($args['current_password']) != $auth->user->password ){
			$this->_api_response->setErrorCode( 2003 );
			return;
		}
		if( $args['password'] != $args['confirm_password'] ){
			$this->_api_response->setErrorCode( 1003 );
			return;
		}
		unset($args['confirm_password']);
		unset($args['current_password']);
		$db = MF_Database::getDatabase();
		$email_sql = "SELECT * FROM `users` WHERE `email` LIKE ".$db->quote( $args['email'] )." AND `id`!={$auth->user->id}";
		$user = new User();
		if( strlen($args['email'])>0 && $user->selectFromSQL( $email_sql ) ){
			$this->_api_response->setErrorCode( 1001 );
			return;
		}
		$user_change = false;
		foreach( $args as $k => $a ){
			if( strlen($a) > 0 ){
				if( $k == 'password' ){
					$plain_password = $args['password'];
					$auth->user->password = $auth->createHashedPassword( $args['password'] );
				}else{
					$auth->user->$k = $a;
				}
				$user_change = true;
			}
		}
		if($user_change) $auth->user->save();
		$this->_api_response->setResponse( array('user'=>$auth->user->getArrayData( $auth->user )) );
	}
	
	public function listTimeline( $args ){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
			$own = false;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
			$own = true;
		}
		$from_id = isset($args['from'])&&$args['from']? $args['from'] : false;
		$to_id = isset($args['to'])&&$args['to']? $args['to'] : false;
		$feeds = $user->getFeeds( $from_id, $to_id, $own );
		$feeds_data = array();
		foreach( $feeds as $feed ){
			$feeds_data[] = $feed->getArrayData();
		}
		$this->_api_response->setResponse( array('feeds' => $feeds_data) );
	}
	
	public function listSettings( $args ){
		$auth = MF_Auth::getInstance();
		$setting_types = MF_Model::glob( 'SettingType' );
		$settings_data = array();
		foreach( $setting_types as $k => $st ){
			$setting_value = $auth->user->getSettingValue( $st->key, true );
			if( is_null($setting_value) ) MF_Error::dieError( "{$st->key} is not a valid Key", 500 );
			$settings_data[] = array(
				'key' => $st->key,
				'value' => $setting_value
			);
		}
		$this->_api_response->setResponse( array('settings' => $settings_data) );
	}
	
	public function listFollowers( $args ){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
		}
		$followers = $user->getFollowers();
		$followers_data = array();
		foreach( $followers as $key => $follower ) {
			$followers_data[] = $follower->getArrayData();
		}
		$this->_api_response->setResponse( array( 'users' => $followers_data ) );
	}
	
	public function listFollowings( $args ){
		if( !isset($args['user']) || !$args['user'] || $args['user']=='me' ){
			$auth = MF_Auth::getInstance();
			$user = $auth->user;
		}else{
			$user = new User();
			if( !$user->select( $args['user'] ) ){
				$this->_api_response->setErrorCode( '1002' );
				return;
			}
		}
		$followings = $user->getFollowings();
		$followings_data = array();
		foreach( $followings as $key => $following ) {
			$followings_data[] = $following->getArrayData();
		}
		$this->_api_response->setResponse( array( 'users' => $followings_data ) );
	}
	
	protected function searchFacebookUsers( $access_token, $following ){
		$facebook = new Facebook();
		$facebook->setAccessToken( $access_token );
		$auth = MF_Auth::getInstance();
		$fb_user = $facebook->getUser();
		if( !$fb_user ){
			$this->_api_response->setErrorCode( '1005' );
			return;
		}
		$friends = $facebook->api('/me/friends');
		if( $following ){
			$sql = "SELECT `u`.* FROM `users` AS `u` INNER JOIN `followers` AS `f` ON `f`.`users2_id`=`u`.`id` WHERE (`u`.`id`<>{$auth->user->id}) AND (`f`.`users1_id`={$auth->user->id})";
		}else{
			$sql = "SELECT `u`.* FROM `users` AS `u` WHERE (`u`.`id`<>{$auth->user->id}) AND id NOT IN (SELECT u.id FROM users AS u LEFT OUTER JOIN `followers` AS `f` ON `f`.`users2_id`=`u`.`id` WHERE f.users1_id={$auth->user->id}) ";
		}
		$sql_query = '';
		foreach( $friends['data'] as $i => $friend ){
			if( $i != 0 ) $sql_query .= " OR";
			$sql_query .= " `facebook_id`='{$friend['id']}'";
		}
		$sql .= " AND ($sql_query) ORDER BY `first_name` DESC";
		$users = MF_Model::glob( 'User', $sql );
		$users_data = array();
		foreach( $users as $k => $u ) {
			$users_data[] = $u->getArrayData();
		}
		return $users_data;
	}
	
	protected function searchUsers( $term, $following, $result_limit = 20 ){
		$auth = MF_Auth::getInstance();
		$db = MF_Database::getDatabase();
		if( $following ){
			$sql = "SELECT `u`.* FROM `users` AS `u` INNER JOIN `followers` AS `f` ON `f`.`users2_id`=`u`.`id` WHERE (`u`.`id`<>{$auth->user->id}) AND (`f`.`users1_id`={$auth->user->id})";
		}else{
			$sql = "SELECT `u`.* FROM `users` AS `u` WHERE (`u`.`id`<>{$auth->user->id}) AND id NOT IN (SELECT u.id FROM users AS u LEFT OUTER JOIN `followers` AS `f` ON `f`.`users2_id`=`u`.`id` WHERE f.users1_id={$auth->user->id}) ";
		}
		$terms = explode( ' ', $term );
		$sql_search = array();
		if( count($terms)>1 ){
			$sql_search[] = "`u`.`first_name` LIKE ".$db->quote($term);
			$sql_search[] = "`u`.`last_name` LIKE ".$db->quote($term);
			$sql_search_item = '';
			foreach( $terms as $k => $t ){
				if( $k != 0 ){
					$sql_search_item .= ' OR';
				}
				$sql_search_item .= "(`u`.`last_name` LIKE ".$db->quote($t)." OR `u`.`last_name` LIKE ".$db->quote($t.' %')." OR `u`.`last_name` LIKE ".$db->quote('% '.$t)." OR `u`.`last_name` LIKE ".$db->quote('% '.$t.' %');
				$sql_search_item .= " OR `u`.`first_name` LIKE ".$db->quote($t)." OR `u`.`first_name` LIKE ".$db->quote($t.' %')." OR `u`.`first_name` LIKE ".$db->quote('% '.$t)." OR `u`.`first_name` LIKE ".$db->quote('% '.$t.' %').')'; 
			}
			$sql_search[] = "($sql_search_item)";
		}else{
			$sql_search[] = "`u`.`email` LIKE ".$db->quote($term);
			$sql_search[] = "`u`.`first_name` LIKE ".$db->quote($term);
			$sql_search[] = "`u`.`last_name` LIKE ".$db->quote($term);
			$sql_search[] = "`u`.`phone_number` LIKE ".$db->quote($term);
			$sql_search[] = "`u`.`first_name` LIKE ".$db->quote($term.' %');
			$sql_search[] = "`u`.`last_name` LIKE ".$db->quote($term.' %');
			$sql_search[] = "`u`.`first_name` LIKE ".$db->quote('% '.$term.' %');
			$sql_search[] = "`u`.`last_name` LIKE ".$db->quote('% '.$term.' %');
			$sql_search[] = "`u`.`first_name` LIKE ".$db->quote('% '.$term.'%');
			$sql_search[] = "`u`.`last_name` LIKE ".$db->quote('% '.$term.'%');
			$sql_search[] = "`u`.`email` LIKE ".$db->quote($term.'@%');
		}
		
		$sql_query = '';
		$sql_order = '';
		foreach( $sql_search as $k => $s ){
			if( $k != 0 ){
				$sql_query .= ' OR';
				$sql_order .= ' ,';
			}
			$sql_query .= ' '.$s;
			$sql_order .= ' '.$s.' DESC';
		}
		
		$sql .= " AND ($sql_query) ORDER BY $sql_order LIMIT {$result_limit}";
		$users = MF_Model::glob( 'User', $sql );
		$users_data = array();
		foreach( $users as $k => $u ) {
			$users_data[] = $u->getArrayData();
		}
		return $users_data;
	}
	
}