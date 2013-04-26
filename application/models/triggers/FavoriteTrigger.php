<?php
class FavoriteTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Favorite $model ){
		parent::__construct( $model );
	}
	
	public function afterInsert(){
		$share = $this->model->getParent( 'Share' );
		
		$data = array(
			'shares_id' =>$share->id
		);
		
		MF_FeedNotification::sendFeed($this->model->users_id, $data, 4);
		
		if( $share->users_id != $this->model->users_id ){
			$user = $this->model->getParent( 'User' );
			MF_FeedNotification::sendNotification($user, $data, 3);
		}
		
	}
	
}
