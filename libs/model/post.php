<?php

class Model_Post extends Model_Abstract_Meta
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'title',
		'text',
		'pretty_text',
		'author',
		'category',
		'language',
		'tag',
		'comment_count',
		'last_comment',
		'update_count',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'post';
	
	protected $meta_fields= array('tag', 
		'category', 'author', 'language');
		
	public function insert() {
		
		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(1));
		
		parent::insert();
		
		$order = 0;
		$images = $this->get('image');
		foreach ($images as $image) {
			$image->set('post_id', $this->get_id());
			$image->set('order', $order);
			$image->insert();
			$order++;
		}		

		$order = 0;
		$links = $this->get('link');
		foreach ($links as $link) {
			$link->set('post_id', $this->get_id());
			$link->set('order', $order);
			$link->insert();
			$order++;
		}
		
		$order = 0;
		$files = $this->get('file');
		foreach ($files as $file) {
			$file->set('post_id', $this->get_id());
			$file->set('order', $order);
			$file->insert();
			$order++;
		}
		
		$order = 0;
		$extras = $this->get('extra');
		foreach ($extras as $extra) {
			$extra->set('post_id', $this->get_id());
			$extra->set('order', $order);
			$extra->insert();
			$order++;
		}	

		Database::insert('versions', array(
			'type' => 'post',
			'item_id' => $this->get_id(),
			'data' => base64_encode(serialize($this->get_data())),
			'time' => $this->get('sortdate'),
			'author' => sets::user('name'),
			'ip' => $_SERVER['REMOTE_ADDR']
		));
				
		return $this;
	}	
	// @TODO: вынести в trait, как они появятся
	public function add_link(Model_Post_Link $link) {

		$links = (array) $this->get('link');
		
		foreach ($links as $post_link) {
			if ($post_link->is_same($link)) {
				$post_link->merge($link);
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
	
	public function add_file(Model_Post_File $file) {
		
		$this->add_common('file', $file);
	}
	
	public function add_extra(Model_Post_Extra $extra) {

		$this->add_common('extra', $extra);
	}
	
	protected function add_common($type, $item) {
		$items = (array) $this->get($type);
		$items[] = $item;		
		$this->set($type, $items);
	}	
}
