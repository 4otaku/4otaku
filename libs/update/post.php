<?php

class Update_Post extends Update_Abstract
{	
	protected $field_rights = array(
		'author' => 1
	);
	
	protected $model;
	
	public function __construct($data) {
		if (empty($data['id']) || !Check::id($data['id'])) {
			throw new Error_Update('Incorrect Id');
		}		
		
		$model = new Model_Post($data['id']);
		$model->load();
		
		if ($model->is_phantom()) {
			throw new Error_Update('Incorrect Id');
		}
		
		$this->model = $model;
		
		parent::__construct();
	}	
}
