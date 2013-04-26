<?php
class ShareTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Share $model ){
		parent::__construct( $model );
	}
	
	public function afterInsert(){
		$data = array(
			'shares_id' =>$this->model->id
		);
		MF_FeedNotification::sendFeed($this->model->users_id, $data, 2);
	}
	
	public function afterDelete(){
		/*$user = $this->model->getParent( 'User' );
		$application = $this->model->getParent( 'Application' );
		$data = array(
			'application' => array( 'text'=>$application->application_name, 'package_name'=>$application->package_name, 'shared_id'=>$this->model->id+"" )
		);
		$encoded_data = json_encode( $data );
		$db = MF_Database::getDatabase();
		$sql = "SELECT * FROM `feeds` WHERE `users_id`={$user->id} AND `feed_types_id`=2 AND `serialized_data`='".$db->escape($encoded_data)."'";
		$feed = new Feed();
		if( $feed->selectFromSQL( $sql ) ){
			$feed->delete();
		}*/
	}
	
}
