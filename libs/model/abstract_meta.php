<?php

abstract class Model_Abstract_Meta extends Model_Abstract
{      
	protected $meta_fields= array();
	
	public function __construct($data = array()) {

		parent::__construct($data);
		
		$meta = array();
		foreach ($this->meta_fields as $field) {
			$data = $this->get($field);
			if (is_string($data)) {
				$data = array_unique(array_filter(explode('|', $data)));
			}
			
			$meta[$field] = array();
			foreach ((array) $data as $item) {
				$meta[$field][$item] = array();
			}
		}
		
		$this->set('meta', $meta);
	}
}

