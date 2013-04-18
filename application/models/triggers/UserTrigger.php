<?php
class UserTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( User $model ){
		parent::__construct( $model );
	}
	
	public function beforeSave(){
		if( isset($this->model->facebook_id) && $this->model->facebook_id && !$this->model->picture ){
			$this->model->gotFacebookImage();
		}
	}
	
}
