<?php
class NotificationTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Notification $model ){
		parent::__construct( $model );
	}
	
}
