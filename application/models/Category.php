<?php
class Category extends MF_Model{
	
	protected $reference_models = array(
		"parent" => array(
			"column" => "categories_id",
			"refModel" => "Category",
			"refColumn" => "id",
		)
	);
	
	public function __construct(){
		parent::__construct('categories');
	}
	
}