<?php
class Configuration{
	
	
	// set here the domains and IP's
	protected $_hosts = array(
		'development' => array('localhost.com', 'www.localhost.com','192.168.0.15','192.168.0.19','192.168.1.100'),
		'production' => array('174.121.162.130', 'satoriwd.com', 'www.satoriwd.com') // you can add here any domains into an array
	);
	
	// set here the global configurations
	protected $_default_layout = 'layout';
	
	
	// Do nothing here
	public function loadConfiguration(){
		
		$host = $_SERVER['HTTP_HOST'];
		
		foreach( $this->_hosts as $e => $h ){
			if( is_array( $h ) ){
				if( in_array( $host , $h) ){
					$function = "{$e}Configurations";
					break;
				}
			}elseif( $h == $host ){
				$function = "{$e}Configurations";
				break;
			}
		}
		if( isset($function) ){
			define("DEFAULT_LAYOUT", $this->_default_layout);
			if( !method_exists($this, $function) ){
				die( "<i>{$e}</i> is not a valid envirotment" );
			}else{
				$this->$function();
				return true;
			}
		}
		die( "{$host} is not a valid host" );
		
	}
	
	// set here the development configurations
	protected function developmentConfigurations(){
		define('ENVIROTMENT','development');
		
		// set the base URL
		define('BASE_URL','appsaway');
		
		// set the database
		define("DB_HOST", "localhost");
		define("DB_USER", "root");
		define("DB_PASS", "");
		//define("DB_PASS", "ynpmnabsqs");
		define("DB_NAME", "appsaway");
		
		define('GOOGLE_EMAIL','admin@ikongroup.net');
		define('GOOGLE_PASSWD','1K0N-GrouP!');
		define('ANDROID_DEVICEID','3B1A29C6F249DB5D');
	}
	
	// set here the production configurations
	protected function productionConfigurations(){
		define('ENVIROTMENT','development');
		
		// set the base URL
		define('BASE_URL','~satoriwd/appsaway');
		
		// set the database
		define("DB_HOST", "localhost");
		define("DB_USER", "satoriwd_apps");
		define("DB_PASS", "}z,!~M~9UU}3");
		define("DB_NAME", "satoriwd_apps");
		
		define('GOOGLE_EMAIL','admin@ikongroup.net');
		define('GOOGLE_PASSWD','1K0N-GrouP!');
		define('ANDROID_DEVICEID','3B1A29C6F249DB5D');
	}
	
}