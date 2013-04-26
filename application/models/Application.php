<?php
class Application extends MF_Model{
	
	protected $reference_models = array(
		"UserHasApplications" => array(
			"column" => "users_has_application_id",
			"refModel" => "UsersHasApplication",
			"refColumn" => "id",
		),
		"category" => array(
			"column" => "categories_id",
			"refModel" => "Category",
			"refColumn" => "id",
		)
	);
	
	public function __construct(){
		parent::__construct('applications');
		$this->action_trigger = new ApplicationTrigger( $this );
	}
	
	public function sharedByUser(){
		$auth = MF_Auth::getInstance();
		$share = new Share();
		if( $share->selectSpecific( $this->id, $auth->user->id ) ){
			return 1;
		}
	}
	
	public function loadGooglePlayData( $package_name ){
		$gpd = new MF_GooglePlayData();
		$gp_app_data = $gpd->getApplicationData( $package_name );
		if( !$gp_app_data ) return false;
		$category_label = $gp_app_data['category'];
		$category = new Category();
		if( $category->select( $category_label, 'description' ) ){
			$gp_app_data['categories_id'] = $category->id;
		}else{
			$category->description = $category_label;
			$gp_app_data['categories_id'] = $category->save();
		}
		unset( $gp_app_data['category'] );
		$this->load( $gp_app_data );
		return true;
	}
	
	public function getIconUrl(){
		if( $this->iconExists() ){
			return "http://{$_SERVER['HTTP_HOST']}/".BASE_URL."/apps_icons/{$this->package_name}.png";
		}
		return "http://{$_SERVER['HTTP_HOST']}/".BASE_URL."/apps_icons/default.png";
	}
	
	protected function iconExists(){
		return file_exists( MF_GooglePlayData::getIconsPath().$this->package_name.'.png' );
	}
	
	public static function getGooglePlayData( $package_name ){
		$acl = MF_ACL::getInstance();
		$attrs = $acl->getAllowedDataForModel( 'Application' );
		$app_data = array();
		foreach( $attrs as $attr => $type ) {
			$app_data[$attr] = null;
		}
		$gpd = new MF_GooglePlayData();
		$gp_app_data = $gpd->getApplicationData( $package_name );
		if( $gp_app_data ){
			$app_data = array_merge( $app_data, $gp_app_data );
			$app_data['googleplay_founded'] = 1;
		}else{
			$app_data['googleplay_founded'] = 0;
		}
		return $app_data;
	}
	
}