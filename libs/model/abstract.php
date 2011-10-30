<?php

abstract class Model_Abstract
{      
	// Поля таблицы
	protected $fields = array(
		'id'
	);

	// Поля таблицы представляющие из себя первичный ключ
	protected $primary = array(
		'id'
	);

	// Данные записи
	private $data = array();

	// Название таблицы
	private $table;

	// Знак того, что записи нет в базе
	private $is_phantom = false;
	   
	public function __construct($data) {

		$this->set_array($data);
	}
	
	protected function build_condition() {
		$condition = array();
		$params = array();
		
		foreach ($primary as $key) {
			$param = $this->get($key);
			
			if (empty($param)) {
				return '';
			}
			
			$condition[] = $key.' = ?';			
			
			$params[] = $param;
		}
		
		$condition = implode(' and ', $condition);
		
		return array($condition, $params);
	}
	
	public function set_phantom() {
		$this->is_phantom = true;
	}

	protected function load() {
		list($condition, $params) = $this->build_condition();

		if (!empty($condition)) {
			$data = Database::get_row($this->table, $condition, $params);
		
			$this->set_array($data);
		}
	}

	public function get($key) {
		return array_key_exists($key, $this->data) ?
			$this->data[$key] : null;
	}

	public function get_id() {
		$return = array();

		foreach ($this->primary as $key) {
			$value = $this->get($key);

			if (empty($value)) {
				$return = array();
				break;
			}

			$return[$key] = $this->get($key);
		}
		
		if (count($return) == 1) {
			$return = reset($return);
		}

		return $return;
	}

	public function set_array(array $data) {

		foreach($data as $key => $value) {
			if (array_key_exists($key, $this->fields)) {
				$this->data[$key] = $value;
			}
		}
		
		return $this;
	}

	public function set($key, $value = null) {
		$data = array($key => $value);
		return $this->set_array($data);
	}

	public function clear($key)	{
		return $this->set($key);
	}

	public function get_data() {
		return $this->data;
	}

	public function insert() {
		
		if ($this->is_phantom) {
			Database::insert($this->table, $this->data);
			$id = Database::last_id();
			
			if (count($this->primary) == 1) {
				$key = reset($this->primary);
				$this->set($key, $id);
			}
			
			$this->is_phantom = false;
		}
				
		return $this;
	}

	public function delete() {
		
		if (!$this->is_phantom) {
			list($condition, $params) = $this->build_condition();

			if (!empty($condition)) {
				Database::delete($this->table, $condition, $params);
			}
		}
				
		return $this;
	}

	public function commit() {
		
		if ($this->is_phantom) {
			$this->insert();
		} else {
			list($condition, $params) = $this->build_condition();

			if (!empty($condition)) {
				Database::update($this->table, 
					$this->data, $condition, $params);
			}			
		}		

		return $this;
	}
}

