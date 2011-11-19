<?php

// Класс для основных контентых разделов

abstract class Read_Main extends Read_Abstract
{
	protected $page = 1;
	protected $area = 'main';
	
	abstract protected function get_item($id);
	abstract protected function get_items();
	abstract protected function get_navigation();
	
	protected function load_batch($table) {
		
		$per_page = sets::pp($table);
		$start = ($this->page - 1) * $per_page;
		
		$condition = 'area = ?';
		$params = array($this->area);
		
		$return = Database::set_order('sortdate')
			->set_limit($per_page, $start)
			->get_full_vector($table, $condition, $params);
			
		foreach ($return as &$item) {
			$item['in_batch'] = true;
		}
		
		return $return;
	}
	
	protected function do_output($template, $data = array()) {		
		$data['navigation'] = $this->get_navigation();
		
		parent::do_output($template, $data);
	}	
}
