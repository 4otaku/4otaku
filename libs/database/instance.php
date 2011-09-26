<?php

class Database_Instance extends Database_Abstract
{
	protected $worker;
	protected $statements = array();

	protected $counter = 0;
	protected $counter_lock = false;
	protected $counter_query = "SELECT FOUND_ROWS()";

	public function __construct($worker, $prefix = "") {
		$this->worker =	$worker;
		$this->prefix =	$prefix;
	}

	protected function query($query, $params = array()) {
		$params = (array) $params;
		$params = array_values($params);

		$md5 = md5($query);

		if (empty($this->statements[$md5])) {
			$this->statements[$md5] = $this->worker->prepare($query);
		}

		$this->statements[$md5]->execute($params);

		if ($query != $this->counter_query) {
			$this->last_query = $this->statements[$md5];
			$this->last_params = $params;
		}

		$data = array();

		while ($row = $this->statements[$md5]->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}

		return $data;
	}

	public function sql($query, $params = array()) {
		$query = str_replace("<pr>", $this->prefix, $query);

		if ($this->counter_lock) {
			$query = preg_replace("/^\s+SELECT/is", "$0 SQL_CALC_FOUND_ROWS ", $query);
		}

		$data = $this->query($query, $params);
		var_dump($data);
		if ($this->counter_lock) {
			$count = $this->query($this->counter_query);
			$this->counter = (int) current(current($count));
			$this->counter_lock = false;
		}

		return $data;
	}

	protected function get_common($table, $values = "*", $condition = false, $params = false) {
		if (!is_array($values)) {
			$values = preg_split('/\s*,\s*/', $values);
		}

		foreach ($values as &$value) {
			if (!strpos($value, "`")) {
				$value = "`$value`";
			}
		}
		$values = implode(",", $values);

		$query = "SELECT ";
		if ($this->counter_lock) {
			$query .= "SQL_CALC_FOUND_ROWS ";
		}
		$query .= "$values FROM `{$this->prefix}$table`";

		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}

		$data = $this->query($query, $params);

		if ($this->counter_lock) {
			$count = $this->query("SELECT FOUND_ROWS()");
			$this->counter = (int) current(current($count));
			$this->counter_lock = false;
		}

		return $data;
	}

	public function get_table($table, $values = "*", $condition = false, $params = false) {
		return $this->get_common($table, $values, $condition, $params);
	}

	public function get_vector($table, $values = "*", $condition = false, $params = false, $unset = true) {
		if (is_array($values)) {
			$key = reset($values);
		} elseif (strpos($values, ',')) {
			$values = preg_split('/\s*,\s*/', $values);
			$key = reset($values);
		} else {
			$key = "id";
		}

		$data = $this->get_common($table, $values, $condition, $params);
		$return = array();

		$i = 0;
		foreach ($data as $row) {
			if (isset($row[$key])) {
				$id = $row[$key];

				if ($unset) {
					unset($row[$key]);
				}
			} else {
				$id = $i++;
			}

			if (count($row) == 1) {
				$row = reset($row);
			}

			$return[$id] = $row;
		}

		return $return;
	}

	public function get_row($table, $values = "*", $condition = false, $params = false) {
		if (is_numeric($condition)) {
			$condition = "id = $condition";
		}

		$data = $this->get_common($table, $values, $condition." LIMIT 1", $params);

		return current($data);
	}

	public function get_field($table, $value, $condition, $params = false) {
		if (is_numeric($condition)) {
			$condition = "id = $condition";
		}

		if (empty($condition)) {
			$condition = "1";
		}

		$data = $this->get_common($table, $value, $condition." LIMIT 1", $params);

		$data = current($data);
		return is_array($data) ? reset($data) : $data;
	}

	public function insert($table, $values) {
		$query = "INSERT INTO `{$this->prefix}$table` ";

		$query .= $this->format_insert_values($values);

		$this->query($query, $values);

		return $this->last_query->rowCount();
	}

	public function replace($table, $values, $dont_update = false) {
		$update_values = $values;
		if (!empty($dont_update)) {
			$dont_update = (array) $dont_update;

			foreach ($dont_update as $one) {
				if (isset($update_values[$one])) {
					unset($update_values[$one]);
				}
			}
		}

		$insert = $this->format_insert_values($values);

		$query = "INSERT INTO `{$this->prefix}$table` {$insert}";

		if (!empty($update_values)) {
			$query .= " ON DUPLICATE KEY UPDATE ";

			$update_keys = array_keys($update_values);
			$update_values = array_values($update_values);

			foreach ($update_keys as $update_key) {
				$query .= "`$update_key` = ?,";
			}

			$query = rtrim($query,",");
			$values = array_merge($values, $update_values);
		}

		$this->query($query, $values);

		return $this->last_query->rowCount();
	}

	public function bulk_insert($table, $rows, $keys = false) {
		if ($keys === true) {
			$keys = array_keys(current($rows));
		}

		$keys = (array) $keys;

		$query = "INSERT INTO `{$this->prefix}$table`";

		if (count(current($rows)) === count($keys)) {
			foreach ($keys as &$key) {
				$key = "`".trim($key,"`")."`";
			}
			$query .= " (".implode(",",$keys).")";
			$prepend = "";
		} else {
			$prepend = "NULL,";
		}

		$query .= " VALUES ";

		$params = array();
		foreach ($rows as $row) {
			$query .= "(".$prepend.ltrim(str_repeat(",?",count($row)),",")."),";
			$params = array_merge($params, array_values($row));
		}

		$query = rtrim($query,",");

		$this->query($query, $params);

		return $this->last_query->rowCount();
	}

	public function update($table, $values, $condition, $condition_params = array()) {
		$values = (array) $values;
		$condition_params = (array) $condition_params;

		$keys = array_keys($values);
		$values = array_values($values);

		if (is_numeric($condition)) {
			$condition = "id = ".$condition;
		}

		$query = "UPDATE `{$this->prefix}$table` SET ";

		foreach ($keys as $id => $key) {
			if ($values[$id] === "++") {
				$query .= "`$key` = `$key`+1,";
				unset($values[$id]);
			} else {
				$query .= "`$key` = ?,";
			}
		}

		$query = rtrim($query,",");

		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}

		$params = array_merge(array_values($values), $condition_params);

		$this->query($query, $params);

		return $this->last_query->rowCount();
	}

	public function delete($table, $condition = false, $params = false) {
		$query = "DELETE FROM `{$this->prefix}$table`";

		if (is_numeric($condition)) {
			$condition = "id = ".$condition;
		}

		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}

		$this->query($query, $params);

		return $this->last_query->rowCount();
	}

	public function last_id() {
		return $this->worker->lastInsertId();
	}

	public function debug($print = true) {
		$query = $this->last_query;

		$number = $query->errorCode();

		$query_string = vsprintf(str_replace("?","'%s'",$query->queryString),$this->last_params);

		if ($number == 0) {
			$return = "Запрос: $query_string; был выполнен успешно\n";
		} else {
			$return = "Запрос: $query_string; \n".
				"Вызвал ошибку №".$number.": ".array_pop($query->errorInfo())."\n";
		}

		if ((bool) $print) {
			echo nl2br($return);
		}

		return $return;
	}

	public function set_counter() {
		$this->counter_lock = true;

		return $this;
	}

	public function get_counter() {
		if ($this->counter_lock) {
			Error::warning("Попытка получить counter до запроса");
			return 0;
		}
		return $this->counter;
	}

	public function begin() {
		if ((bool) $this->transaction) {
			Error::warning("Попытка начать транзакцию, уже находясь в одной");
			return false;
		}

		$this->worker->beginTransaction();
		$this->transaction = true;

		return true;
	}

	public function commit() {
		if (empty($this->transaction)) {
			Error::warning("Попытка закоммитить транзакцию, не запустив ее предварительно");
			return false;
		}

		$this->worker->commit();
		$this->transaction = false;

		return true;
	}

	public function rollback() {
		if (empty($this->transaction)) {
			Error::warning("Попытка откатить транзакцию, не запустив ее предварительно");
			return false;
		}

		$this->worker->rollBack();
		$this->transaction = false;

		return true;
	}
}
