<?php
class FavoriteTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Favorite $model ){
		parent::__construct( $model );
	}
	
	public function afterInsert(){
		$user = $this->model->getParent('User');
		$share = $this->model->getParent( 'Share' );
		$application = $share->getParent( 'Application' );
		
		$data = array(
			'application' => array( 'text'=>$application->application_name, 'package_name'=>$application->package_name, 'shared_id'=>$share->id ),
			'user' => array( 'id'=>$user->id, 'text'=>$user->getFullName() ),
		);
		
		MF_FeedNotification::sendFeed($user, $data, 4);
		
		$publisher = $share->getParent( 'User' );
		if( $publisher->id != $user->id ){
			MF_FeedNotification::sendNotification($publisher, $data, 3);
		}
	}
	
}
