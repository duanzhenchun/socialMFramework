<?php
class FeedTrigger extends MF_ActionTriggerAbstract{
	
	public function __construct( Feed $model ){
		parent::__construct( $model );
	}
	
}