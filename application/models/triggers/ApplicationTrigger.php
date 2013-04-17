<?php
class ApplicationTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Application $model ){
		parent::__construct( $model );
	}
	
	public function afterInsert(){
		if( $this->model->google_play_id ){
			$gpd = new MF_GooglePlayData();
			$gpd->saveIconFromGooglePlay( $this->model );
		}
	}
	
}
