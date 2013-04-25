<?php
class ApplicationDelegate extends MF_ApiDelegate{
	
	public function __construct(){
		parent::__construnct();
	}
	
	public function view( $args ){
		if( !$this->validateRequiredArgs($args, array('package_name')) ){
			return;
		}
		$app = new Application();
		if( $app->select( $args['package_name'], 'package_name' ) ){
			$app_data = $app->getArrayData();
			$app_data['exists'] = 1;
		}else{
			$app_data = Application::getGooglePlayData( $args['package_name'] );
			$app_data['exists'] = 0;
		}
		$this->_api_response->setResponse( array('application'=>$app_data) );
	}
	
	public function viewShared( $args ){
		if( !$this->validateRequiredArgs($args, array('share')) ){
			return;
		}
		$share = new Share();
		if( !$share->select( $args['share'] ) ){
			$this->_api_response->setErrorCode( '6000' );
			return;
		}
		$this->_api_response->setResponse( array('share'=>$share->getArrayData()) );
	}
	
	public function share( $args ){
		if( !$this->validateRequiredArgs($args, array('package_name', 'application_name')) ){
			return;
		}
		$auth = MF_Auth::getInstance();
		$googleplay_founded = 1;
		$app = new Application();
		if( $app->select( $args['package_name'], 'package_name' ) && $app->google_play_id ){
			$share = new Share();
			if( $share->selectSpecific( $app->id, $auth->user->id ) ){
				$this->_api_response->setErrorCode( '3002' );
				return;
			}
		}else{
			if( !$app->loadGooglePlayData( $args['package_name'] ) ){
				$app->google_play_id = null;
				$app->package_name = $args['package_name'];
				$app->application_name = $args['application_name'];
				$googleplay_founded = 0;
			}
			$app->save();
		}
		$share = new Share();
		$share->users_id = $auth->user->id;
		$share->applications_id = $app->id;
		$share->save();
		$this->_api_response->setResponse( array('googleplay_founded'=>$googleplay_founded) );
	}
	
	public function unshare( $args ){
		if( !$this->validateRequiredArgs($args, array('package_name')) ){
			return;
		}
		$auth = MF_Auth::getInstance();
		$app = new Application();
		$share = new Share();
		if( !$app->select( $args['package_name'], 'package_name' ) || ! $share->selectSpecific($app->id, $auth->user->id) ){
			$this->_api_response->setErrorCode( '3003' );
			return;
		}
		$share->delete();
		$this->_api_response->setResponse();
	}
	
	public function listShares( $args ){
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
		$shares = $user->getDependentRows( 'Share' );
		$shares_data = array();
		foreach( $shares as $key => $share ){
			$shares_data[] = $share->getArrayData();
		}
		$this->_api_response->setResponse( array('shares'=>$shares_data) );
	}

	public function listFavorites( $args ){
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
		$favorites = $user->getDependentRows( 'Favorite' );
		$favorites_data = array();
		foreach( $favorites as $key => $favorite ){
			$favorites_data[] = $favorite->getArrayData();
		}
		$this->_api_response->setResponse( array('favorites'=>$favorites_data) );
	}
	
	public function checkForShares( $args ){
		if( !$this->validateRequiredArgs($args, array('package_names')) ){
			return;
		}
		$auth = MF_Auth::getInstance();
		$packages_response = array();
		$packages = explode( ',', $args['package_names'] );
		foreach( $packages as $package ){
			$application_model = new Application();
			if( $application_model->select( $package, 'package_name' ) ){
				$share = new Share();
				if( $share->selectSpecific( $application_model->id, $auth->user->id ) ){
					$packages_response[] = $package;
				}
			}
		}
		$this->_api_response->setResponse( array('already_shared'=>$packages_response) );
	}
	
}