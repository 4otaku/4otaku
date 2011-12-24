<?php

class Database_Sorter
{
	protected $field;
	protected $prefix = false;
	protected $direction = 'DESC';
	protected $operations = array();
	
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
	
	public function add_operation($type, $value = false) {
		
		$this->operations[] = array(
			'type' => $type,
			'value' => $value,
		);
		
		return $this;
	}
	
	public function apply_operation($string, $type, $value) {
		
		switch ($type) {
			case 'divide':
				$field = '`' . $value . '`';
				
				if (!empty($this->prefix)) {
					$field = '`' . $this->prefix . '`.' . $field;
				}
				
				$string .= '/' . $field;
			default:
				break;
		}
		
		return $string;
	}
	
	public function is_valid() {
		
		$direction = strtolower($this->direction);
		
		return ($direction == 'desc' || $direction == 'asc') && 
			!empty($this->field) && 
			!preg_match('/[^a-z_\d\.]/ui', $this->field);
	}
	
	public function get_sort() {
		// Backwards compatibility
		$return = '`' . str_replace('.', '`.`', $this->field) . '`';
		
		if (!empty($this->prefix)) {
			$return = '`' . $this->prefix . '`.' . $return;
		}
		
		foreach ($this->operations as $operation) {
			$return = $this->apply_operation($return, 
				$operation['type'], $operation['value']);
		}
		
		return $return;
	}

	public function get_direction() {
		return $this->direction;
	}

	public function get_field() {
		return $this->field;
	}

	public function have_operations() {
		return count($this->operations) > 0;
	}
}
