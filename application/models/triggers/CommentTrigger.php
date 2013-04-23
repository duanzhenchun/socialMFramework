<?php
class CommentTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Comment $model ){
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
		
		MF_FeedNotification::sendFeed($user, $data, 3);
		
		$publisher = $share->getParent( 'User' );
		if( $publisher->id != $user->id ){
			MF_FeedNotification::sendNotification($publisher, $data, 2);
		}
	}
	
}