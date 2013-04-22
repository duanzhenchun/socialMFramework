<?php
	session_name("MF");
	session_start();
	
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	
    // Define path to application directory
	defined('APPLICATION_PATH')
	    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));
		
	defined('MAIL_TEMPLATES_PATH')
	    || define('MAIL_TEMPLATES_PATH', APPLICATION_PATH . '/mail-templates');
	
	// Ensure library/ is on include_path
	set_include_path(implode(PATH_SEPARATOR, array(
	    realpath(APPLICATION_PATH . '/../library'),
	    get_include_path(),
	)));
	
	require_once APPLICATION_PATH.'/.config/Configuration.php';
	require_once APPLICATION_PATH.'/.config/error_messages.php';
	require_once APPLICATION_PATH.'/.config/acl_configurations.php';
	
	define('FB_APP_ID','119281864915278');
	define('FB_APP_SECRET','e495d3bd43609c21cc9aacb0d41d41ef');
	define('FB_URI_LOGIN','www.localhost.com');
	
	require_once(APPLICATION_PATH.'/../src/facebook.php');
	include(APPLICATION_PATH."/../android-market-api-php/proto/protocolbuffers.inc.php");
	include(APPLICATION_PATH."/../android-market-api-php/proto/market.proto.php");
	include(APPLICATION_PATH."/../android-market-api-php/Market/MarketSession.php");
	
	$configuration = new Configuration();
	$configuration->loadConfiguration();
	
	require_once 'MF/Application.php';
	
    $application = new MF_Application();
	$application->getBootstrap()->setModules( array('public', 'admin', 'api') );
	$application->getBootstrap()->addRoute( 'login', array( 'controller'=>'auth', 'action'=>'login' ) );
	$application->getBootstrap()->addRoute( 'logout', array( 'controller'=>'auth', 'action'=>'logout' ) );
    $application->getBootstrap()->_run();
?>
