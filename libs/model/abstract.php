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

	// Название таблицы
	protected $table;

	// Данные записи
	private $data = array();

	// Знак того, что записи нет в базе
	private $is_phantom = false;
	   
	public function __construct($data = array()) {

		if (is_numeric($data) && $this->primary == array('id')) {
			$this->set('id', $data);
		} else {
			$data = (array) $data;
			
			$this->set_array($data);
			
			foreach ($this->primary as $key) {

				if (empty($data[$key])) {
					$this->set_phantom();
					break;
				}
			}
		}
	}
	
	protected function build_condition() {
		$condition = array();
		$params = array();

		foreach ($this->primary as $key) {
			$param = $this->get($key, true);
			
			if (empty($param)) {
				return array(false, false);
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
			$data = Database::get_full_row($this->table, 
				$condition, $params);

			if (is_array($data)) {
				$this->set_array($data);
			}
		}
	}

	public function get($key, $silent = false) {
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		
		if (!$silent && in_array($key, $this->fields)) {
			
			$this->load();
			
			return $this->data[$key];
		}
		
		return null;
	}

	public function get_id() {
		$return = array();

		foreach ($this->primary as $key) {
			$value = $this->get($key, true);

			if (empty($value)) {
				$return = array();
				break;
			}

			$return[$key] = $this->get($key);
		}
		
		if (count($this->primary) == 1) {
			$return = reset($return);
		}

		return $return;
	}

	public function set_array(array $data) {

		foreach($data as $key => $value) {
			if (in_array($key, $this->fields)) {
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
		if (count($this->data) < count($this->fields)) {
			$this->load();
		}
		
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
