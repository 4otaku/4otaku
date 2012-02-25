<?php

class Model_Post_Link extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'name',
		'size',
		'sizetype',
		'order',
	);

	// Название таблицы
	protected $table = 'post_link';
		
	protected $sizetypes = array('кб', 'мб', 'гб');
	
	public function __construct($data = array()) {

		parent::__construct($data);
		
		if (!$this->get('link_id') && $this->get_id()) {
			throw new Error("Model_Post_Link data should be joined".
				" with post_link_url and post_url table");
		}
		
		if ($this->get('url')) {
			$this->set('url', array($this->build_url_array()));
		} else {
			$this->set('url', array());
		}

		$this->set('display_size', round($this->get('size'), 2));
		$this->set('display_sizetype', $this->sizetypes[$this->get('sizetype')]);
	}
	
	public function insert() {
		
		if ($this->get('size') === null) {
			$this->set('size', 0);
		}
		
		if ($this->get('sizetype') === null) {
			$this->set('sizetype', 1);
		}		
		
		parent::insert();
		
		$urls = $this->get('url');
		$this->insert_link_urls($urls);

		return $this;
	}	
	
	public function is_same(Model_Post_Link $link) {
		if ($this->get('link_id')) {
			return $this->get('link_id') == $link->get('link_id');
		}
		
		return $this->get('name') == $link->get('name') &&
			$this->get('size') == $link->get('size') &&
			$this->get('sizetype') == $link->get('sizetype');
	}
	
	public function merge(Model_Post_Link $link) {
		$urls = $this->get('url');
		$merge_urls = $link->get('url');
		$this->set('url', array_merge($urls, $merge_urls));
	}
	
	protected function insert_link_urls($urls) {
		$order = 0;
		
		foreach ($urls as $link) {
			
			$url_id = $this->insert_url($link['url']);
		
			Database::insert('post_link_url', array(
				'url_id' => $url_id,
				'link_id' => $this->get_id(),
				'alias' => $link['alias'],
				'order' => $order,
			));
			$order++;
		}
	}
	
	protected function insert_url($url) {
		$id = Database::get_field('post_url', 'id', 'url = ?', $url);
		
		if (empty($id)) {
			Database::insert('post_url', array(
				'url' => $url
			));
			$id = Database::last_id();
		}
		
		return $id;
	}
	
	protected function build_url_array() {		
		$time = strtotime($this->get('lastcheck'));
		
		return array(
			'url' => $this->get('url'),
			'alias' => $this->get('alias'),
			'status' => $this->get('status'),
			'check' => Transform_Time::rudate($time, true),
			'lastcheck' => $this->get('lastcheck'),
		);
	}
}
