<?php

class Model_Post_Update extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'username',
		'text',
		'pretty_text',
		'link',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'updates';
	
	public function insert() {
		
		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(0));
		
		parent::insert();

		Database::update('post', 
			array('update_count' => '++'), 
			$this->get('post_id'));
				
		return $this;
	}	
}
