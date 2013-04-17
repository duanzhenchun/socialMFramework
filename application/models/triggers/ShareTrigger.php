<?php
class ShareTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Share $model ){
		parent::__construct( $model );
	}
	
	public function afterInsert(){
		$user = $this->model->getParent( 'User' );
		$application = $this->model->getParent( 'Application' );
		$data = array(
			'application' => array( 'text'=>$application->application_name, 'package_name'=>$application->package_name, 'shared_id'=>$this->model->id )
		);
		
		MF_FeedNotification::sendFeed($user, $data, 2);
	}
	
}
