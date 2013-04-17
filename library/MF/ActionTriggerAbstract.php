<?php
class MF_ActionTriggerAbstract{
	
	protected $model;
	
	public function __construct( MF_Model $model ){
		$this->model = $model;
	}
	
	public function beforeSave(){}
	public function afterSave(){}
	
	public function beforeInsert(){}
	public function afterInsert(){}
	
	public function beforeUpdate(){}
	public function afterUpdate(){}
	
	public function beforeDelete(){}
	public function afterDelete(){}
	
}
