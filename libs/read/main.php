<?php

// Класс для основных контентых разделов

abstract class Read_Main extends Read_Abstract
{
	protected $count = 0;
	protected $page = 1;
	protected $per_page = 1;	
	protected $area = 'main';
	protected $meta = array();
	
	protected $possible_areas = array(
		'main', 'workshop', 'flea_market'
	);
	
	abstract protected function get_item($id);
	abstract protected function get_items();
	abstract protected function get_navigation();
	
	public function process($url) {
		if (isset($url[2]) && in_array($url[2], $this->possible_areas)) {
			
			$this->area = $url[2];
			query::$url['area'] = $url[2];
			
			array_splice($url, 1, 1);
		}

		parent::process($url);
	}
	
	protected function load_batch($table) {
		
		$start = ($this->page - 1) * $this->per_page;
		
		$condition = 'area = ?';
		$params = array($this->area);
		
		foreach ($this->meta as $type => $values) {
			foreach ($values as $value) {
				$condition .= ' and locate(?, ' . $type . ')';
				$params[] = '|' . $value . '|';
			}
		}
		
		$return = Database::set_counter()->set_order('sortdate')
			->set_limit($this->per_page, $start)
			->get_full_vector($table, $condition, $params);
	
		$this->count = Database::get_counter();
			
		foreach ($return as &$item) {
			$item['in_batch'] = true;
		}
		
		return $return;
	}
	
	protected function do_output($template, $data = array()) {		
		$data['navigation'] = $this->get_navigation();
		
		parent::do_output($template, $data);
	}
	
	protected function meta($type, $url) {
		if (!empty($url[5]) && $url[5] > 0) {
		
			$this->page = (int) $url[5];
		}
		
		if (!empty($url[3])) {
			$this->add_meta($type, $url[3]);
		}
		
		$this->get_items();		
	}
	
	protected function add_meta($type, $value) {
		if (!ctype_alnum($type)) {
			return;
		}
		
		if (empty($this->meta[$type])) {
			$this->meta[$type] = array();
		}
		
		$this->meta[$type][] = $value;
	}
	
	protected function get_navi_category($type) {

		return Database::set_order('id', 'asc')->get_vector('category', 
			array('alias', 'name'), 'locate(?, area)', $type);
	}
	
	protected function get_navi_language() {

		return Database::set_order('id', 'asc')->
			get_vector('language', array('alias', 'name'));
	}
	
	protected function get_navi_tag($type) {

		if ($this->area != def::area(2)) {
			$area = $type.'_'.def::area(0);
		} else {
			$area = $type.'_'.def::area(2);
		}

		return Database::set_order($area)->set_limit(70)
			->get_vector('tag', array('alias', 'name'));
	}
	
	protected function get_bottom_navi() {
		$return = array();		
		
		$return['curr'] = $this->page;
			
		$return['last'] = ceil($this->count / $this->per_page);
		
		$return['start'] = max($return['curr'] - 5, 2);
		$return['end'] = min($return['curr'] + 6, $return['last'] - 1);

		if (count($this->meta, COUNT_RECURSIVE) == 2) {
			
			reset($this->meta);			
			$return['meta'] = key($this->meta) . '/' . 
				reset(current($this->meta)) .'/';
		} else {
			$return['meta'] = '';
		}

		$area = $this->area != def::area(0) ? '/' . $this->area : '';
		$return['base'] = '/post' . $area . '/';
		
		return $return;		
	}
}
