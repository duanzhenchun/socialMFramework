<?php
class MF_ACL{
	
	/**
     * 
     * Singleton object
     * @var MF_ACL
     */
    private static $_instance;
	
	private $_current_role = 'guest';
	private $_extended_role;
	
	private $_default_allow = false;
	
    private function __construct() {
        $auth = MF_Auth::getInstance();
		$this->_current_role = $auth->isLogged()? $auth->user->level : 'guest';
    }
	
	/**
     * Standard singleton
     * @return MF_ACL
     */
    public static function getInstance() {
        if( self::$_instance === null )
            self::$_instance = new MF_ACL();
        return self::$_instance;
    }
	
	public function isAllow( $resource ){
		global $_acl_resources;
		if( !isset($_acl_resources[$resource]) ) return $this->_default_allow;
		$auth = MF_Auth::getInstance();
		if( is_array($_acl_resources[$resource]) ){
			return in_array($this->_current_role, $_acl_resources[$resource]);
		}
		if( $_acl_resources[$resource] == 'all' ) {
			return true;
		}
		if( $_acl_resources[$resource] == 'admin' ) {
			return $auth->isAdmin();
		}
		MF_Error::dieError( "Ivalid resource value for $resource", 500 );
	}
	
	public function getAllowedDataForModel( $model_class, User $owner = null ){
		global $_acl_models_data_allowed;
		if( !isset($_acl_models_data_allowed[$model_class]) ) MF_Error::dieError( "$model_class don't have resources applied", 500 );
		$attrs = $_acl_models_data_allowed[$model_class]['guest'];
		$auth = MF_Auth::getInstance();
		if( $auth->isLogged() && isset($_acl_models_data_allowed[$model_class]['user']) ){
			$attrs = array_merge( $attrs, $_acl_models_data_allowed[$model_class]['user'] );
			if( $owner && isset($_acl_models_data_allowed[$model_class]['own']) ){
				if( $owner->id == $auth->user->id ){
					$attrs = array_merge( $attrs, $_acl_models_data_allowed[$model_class]['own'] );
				}
			}
		}
		return $attrs;
		
	}
	
}
