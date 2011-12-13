<?php

class Database_Sorter
{
	protected $field;
	protected $prefix = false;
	protected $direction = 'DESC';
	
	public function __construct($field, $direction = false) {
		
		$this->field = $field;
		
		if ($direction) {
			$this->direction = $direction;
		}
	}
	
	public function set_direction($direction) {
		
		$this->direction = $direction;
		
		return $this;
	}
	
	public function set_prefix($prefix) {
		
		$this->prefix = $prefix;
		
		return $this;
	}
	
	public function is_valid() {
		
		$direction = strtolower($this->direction);
		
		return ($direction == 'desc' || $direction == 'asc') && 
			!empty($this->field) && 
			!preg_match('/[^a-z_\d\.]/ui', $this->field);
	}
	
	public function get_field() {
		// Backwards compatibility
		$return = '`' . str_replace('.', '`.`', $this->field) . '`';
		
		if (!empty($this->prefix)) {
			$return = '`' . $this->prefix . '`.' . $return;
		}
		
		return $return;
	}
		
	public function get_direction() {
		return $this->direction;
	}	
}
