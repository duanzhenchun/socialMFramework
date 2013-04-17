<?php
class FeedType extends MF_Model{
	
	protected $reference_models = array();
	
	public function __construct(){
		parent::__construct('feed_types');
	}
	
}