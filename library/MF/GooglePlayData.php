<?php
class MF_GooglePlayData{
	
	private function apiLogin(){
		$session = new MarketSession();
		$session->login(GOOGLE_EMAIL, GOOGLE_PASSWD);
		$session->setAndroidId(ANDROID_DEVICEID);
		
		return $session;
	}
	
	public static function getIconsPath(){
		return APPLICATION_PATH.'/../apps_icons/';
	}
	
	public function getApplicationData( $package_name ){
		$session = $this->apiLogin();
		
		$ar = new MK_AppsRequest();
		$ar->setQuery("pname:".$package_name);
		$ar->setStartIndex(0);
		$ar->setEntriesCount(1);
		
		$ar->setWithExtendedInfo(true);
		
		$reqGroup = new MK_Request_RequestGroup();
		$reqGroup->setAppsRequest($ar);
		
		$response = $session->execute($reqGroup);
		
		$groups = $response->getResponsegroupArray();
		$rg = $groups[0];
		$appsResponse = $rg->getAppsResponse();
		$apps = $appsResponse->getAppArray();
		if( count($apps)>0 ){
			$app_data = array();
			$app = $apps[0];
			$app_data['package_name'] = $package_name;
			$app_data['google_play_id'] = $app->getId();
			$app_data['application_name'] = $app->getTitle();
			$app_data['category'] = $app->getExtendedInfo()->getCategory();
			$app_data['description'] = $description = preg_replace( "/\\n/", '<br />', $app->getExtendedInfo()->getDescription() );
			$app_data['price'] = str_replace('US', '', $app->getPrice());
			return $app_data;
		}else{
			return false;
		}
	}
	
	public function saveIconFromGooglePlay( Application $app ){
		$session = $this->apiLogin();
		$gir = new MK_GetImageRequest();
		$gir->setAppId( $app->google_play_id );
		$gir->setImageUsage( MK_GetImageRequest_AppImageUsage::ICON );
		$gir->setImageId(1);
		$reqGroup = new MK_Request_RequestGroup();
		$reqGroup->setImageRequest($gir);
		$response = $session->execute($reqGroup);
		$groups = $response->getResponsegroupArray();
		$rg = $groups[0];
		$imageResponse = $rg->getImageResponse();
		$img_folder = self::getIconsPath();
		$img_name = $app->package_name.'.png';
		if( file_exists( $img_folder.$img_name ) ){
			unlink( $img_folder.$img_name );
		}
		file_put_contents($img_folder.$img_name, $imageResponse->getImageData());
		return $img_name;
	}
	
}