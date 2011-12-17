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
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'post_update';
		
	protected $sizetypes = array('кб', 'мб', 'гб');
	
	public function insert() {
		
		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(0));
		
		parent::insert();
		
		Database::update('post', 
			array('update_count' => '++'), 
			$this->get('post_id'));
		
		$this->add_children();
		
		return $this;
	}	
	
	public function commit() {
		parent::commit();
		
		$this->add_children();
		
		return $this;
	}

	protected function add_children() {			
		$order = 0;
		$links = $this->get('link');
		foreach ($links as $link) {
			$link->set('update_id', $this->get_id());
			$link->set('order', $order);
			$link->insert();
			$order++;
		}
				
		return $this;
	}
	
	// @TODO: вынести в trait, как они появятся
	public function add_link(Model_Post_Update_Link $link) {

		$links = (array) $this->get('link');
		
		foreach ($links as $update_link) {
			if ($update_link->is_same($link)) {
				$update_link->merge($link);
				unset($link);
				break;
			}
		}
		
		if (!empty($link)) {
			$this->add_common('link', $link);
		}
	}
	
	public function add_image(Model_Post_Image $image) {		

		$this->add_common('image', $image);
	}
	
	protected function add_common($type, $item) {
		$items = (array) $this->get($type);
		$items[] = $item;		
		$this->set($type, $items);
	}
}
