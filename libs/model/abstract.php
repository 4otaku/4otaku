<?php

abstract class Model_Abstract implements ArrayAccess
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

	// Дополнительные данные
	private $additional_data = array();

	// Данные, которые сейчас в БД
	private $unchanged_data = array();

	// Знак того, что записи нет в базе
	private $is_phantom = false;

	// Знак того, что загрузка уже выполнялась
	private $loaded = false;

	public function __construct($data = array()) {

		if (is_numeric($data) && $this->primary == array('id')) {
			$this->set('id', $data);
		} else {
			$data = (array) $data;

			$this->set_array($data);
			$this->unchanged_data = $data;

			foreach ($this->primary as $key) {

				if (empty($data[$key])) {
					$this->set_phantom();
					break;
				}
			}

			if (count($this->fields) == count($this->data)) {
				$this->set_loaded();
			}
		}

		$class = get_called_class();
		$class = substr(strtolower($class), 6);
		$this->set('model_type', $class);
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

	public function is_phantom() {
		return $this->is_phantom;
	}

	public function set_loaded() {
		$this->loaded = true;
	}

	public function is_loaded() {
		return $this->loaded;
	}

	public function load() {
		if ($this->is_loaded() || $this->is_phantom()) {
			return $this;
		}

		list($condition, $params) = $this->build_condition();

		if (!empty($condition)) {
			$data = Database::get_full_row($this->table,
				$condition, $params);
			if (is_array($data)) {
				$this->set_array($data);
				$this->unchanged_data = $data;
			} else {
				$this->set_phantom();
			}

			$this->set_loaded();
		}

		return $this;
	}

	public function get($key, $silent = false) {
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}

		if (array_key_exists($key, $this->additional_data)) {
			return $this->additional_data[$key];
		}

		if (!$silent && $this->needs_load($key) && !$this->is_loaded()) {

			$this->load();

			return $this->get($key, true);
		}

		return null;
	}

	protected function needs_load($key) {
		return in_array($key, $this->fields);
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
			$this->set($key, $value);
		}

		return $this;
	}

	public function set($key, $value = null) {

		if (in_array($key, $this->fields)) {

			if ($value !== null) {
				$this->data[$key] = $value;
			} else {
				unset($this->data[$key]);
			}
		} else {

			if ($value !== null) {
				$this->additional_data[$key] = $value;
			} else {
				unset($this->additional_data[$key]);
			}
		}

		return $this;
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
			return $this->insert();
		}

		list($condition, $params) = $this->build_condition();

		if (empty($condition)) {
			return $this;
		}

		if (empty($this->unchanged_data)) {
			$update = $this->data;
		} else {
			$update = array();
			foreach ($this->data as $key => $value) {
				if ($this->unchanged_data[$key] != $value) {
					$update[$key] = $value;
				}
			}
		}

		Database::update($this->table, $update, $condition, $params);

		return $this;
	}

	/* Реализация ArrayAccess */

	public function offsetSet($offset, $value) {
		if (!is_null($offset)) {
			$this->set($offset, $value);
		}
	}

	public function offsetUnset($offset) {
		$this->set($offset, null);
	}

	public function offsetExists($offset) {
		if (!$this->data[$offset] &&
			!$this->additional_data[$offset] && !$this->is_loaded()) {

			$this->load();
		}

		return isset($this->data[$offset]) ||
			isset($this->additional_data[$offset]);
	}

	public function offsetGet($offset) {
		return $this->get($offset);
	}
}

