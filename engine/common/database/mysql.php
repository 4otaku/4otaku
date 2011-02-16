<?

class Database_Mysql implements Database_Interface
{
	// Хранит соединение с БД	
	protected $connection;
	
	// Последний результат запроса к БД
	protected $result;
	
	// Последний запрос
	protected $last_query;
	
	// Находимся ли мы в состоянии транзакции
	protected $transaction = false;
	
	public function __construct($config) {
		$this->connection =	mysql_connect(
			$config['Server'], 
			$config['User'], 
			$config['Password']
		) or Error::fatal(mysql_error());
		
		mysql_select_db($config['Database'], $this->connection)
			or Error::fatal(mysql_error());			
		mysql_query('SET NAMES \'UTF8\'');
		
		$this->prefix =	$config['Prefix'];
	}
	
	protected function query($query, $params = array()) {
		if (!empty($params)) {
			$params = (array) $params;
			
			foreach ($params as &$param) {
				$param = mysql_real_escape_string($param, $this->connection);
			}
			
			$query = vsprintf(str_replace("?","'%s'",$query), $params);
		}
		
		$this->last_query = $query;

		return mysql_query($query, $this->connection);
	}
	
	public function sql($query, $params = array()) {
		$query = str_replace('{pr}', $this->prefix, $query);
		
		$this->result = $this->query($query, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}
		
		$return = array();
		while ($row = mysql_fetch_assoc($this->result)) {
			$return[] = $row;
		}
		
		return $return;		
	}	
	
	protected function get_common($table, $condition = false, $values = '*', $params = false) {
		if (is_array($values)) {
			$values = implode(',', $values);
		}
		
		$query = "SELECT $values FROM {$this->prefix}$table";
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->result = $this->query($query, $params);
	}

	public function get_table($table, $condition = false, $values = '*', $params = false) {
		$this->get_common($table, $condition, $values, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}
		
		$return = array();
		while ($row = mysql_fetch_assoc($this->result)) {
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function get_vector($table, $condition = false, $values = '*', $params = false) {
		if (is_array($values)) {
			$key = array_shift($values);
		} else {
			$key = 'id';
		}
		
		$this->get_common($table, $condition, $values, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}

		$return = array();
		while ($row = mysql_fetch_assoc($this->result)) {
			$id = $row[$key];
			
			unset($row[$key]);
			if (count($row) == 1) {
				$row = reset($row);
			}
			
			$return[$key] = $row;
		}
		
		return $return;
	}	
	
	public function get_row($table, $condition, $values = '*', $params = false) {
		if (is_numeric($condition)) {
			$condition = 'id = '.$condition;
		}
		
		$this->get_common($table, $condition.' LIMIT 1', $values, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}

		$return = mysql_fetch_assoc($this->result);
		return $return;
	}
	
	public function get_field($table, $condition, $value, $params = false) {
		if (is_numeric($condition)) {
			$condition = 'id = '.$condition;
		}		
		
		$this->get_common($table, $condition.' LIMIT 1', $value, $params);
		
		if (!is_resource($this->result)) {
			return false;
		}
		
		$return = mysql_fetch_assoc($this->result);
		return reset($return);
	}
	
	public function insert($table, $values, $keys = false) {
		$values = (array) $values;
		$keys = (array) $keys;
		
		$query = "INSERT INTO {$this->prefix}$table";
		
		if (count($values) === count($keys)) {
			$query .= " (".implode(',',$keys).")";
		}
		
		$query .= " VALUES(".rtimr(str_repeat("?,",count($values)),",").")";
		
		$this->query($query, $values);
		
		return mysql_affected_rows($this->connection);
	}
	
	public function bulk_insert($table, $rows, $keys = false) {
		$keys = (array) $keys;
		
		$query = "INSERT IGNORE INTO {$this->prefix}$table";
		
		if (count(current($rows)) === count($keys)) {
			$query .= " (".implode(',',$keys).")";
		}
		
		$query .= " VALUES ";
		
		$params = array();
		foreach ($rows as $row) {
			$query .= "(".implode(',',$row)."),";
			$params = array_merge($params, $row);
		}
		
		$query = rtrim($query,',');
		
		$this->query($query, $params);
		
		return mysql_affected_rows($this->connection);		
	}
	
	public function update($table, $condition, $fields, $values = false) {
		
		if (empty($values)) {
			// Если четвертый параметр пустой, значит вместо третьего ассоциативный массив
			$values = array_values($fields);
			$fields = array_keys($fields);
		} else {
			$fields = (array) $fields;
			$values = (array) $values;
		}
		
		if (is_numeric($condition)) {
			$condition = 'id = '.$condition;
		}
		
		$query = "UPDATE {$this->prefix}$table SET ";
		
		foreach ($fields as $field) {
			$query .= "$field = ??,";
		}
		
		$query = rtrim($query,',');
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->query($query, $values);
		
		return mysql_affected_rows($this->connection);	
	}	
	
	public function delete($table, $condition = false) {
		$query = "DELETE FROM {$this->prefix}$table";
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->query($query);
		
		return mysql_affected_rows($this->connection);
	}
	
	public function last_id() {
		return mysql_insert_id($this->connection);
	}
	
	public function debug($print = true) {
		$number = mysql_errno();
		
		if ($number === 0) {
			$return = "Запрос: {$this->last_query}; был выполнен успешно\n";
		} else {
			$return = "Запрос: {$this->last_query}; \n".
				"Вызвал ошибку №".$number.": ".mysql_error()."\n";
		}
			
		if ((bool) $print) {
			echo nl2br($return);
		}
		
		return $return;
	}
	
	public function free_result() {
		mysql_free_result($this->result);
	}
	
	public function begin() {
		if ((bool) $this->transaction) {
			Error::warning('Попытка начать транзакцию, уже находясь в одной');
			return false;
		}
		
		mysql_query("START TRANSACTION", $this->connection);
		$this->transaction = true;
		
		return true;
	}
	
	public function commit() {
		if (empty($this->transaction)) {
			Error::warning('Попытка закоммитить транзакцию, не запустив ее предварительно');
			return false;
		}
		
		mysql_query("COMMIT", $this->connection);
		$this->transaction = false;
		
		return true;
	}
	
	public function rollback() {
		if (empty($this->transaction)) {
			Error::warning('Попытка откатить транзакцию, не запустив ее предварительно');
			return false;
		}
		
		mysql_query("ROLLBACK", $this->connection);
		$this->transaction = false;
		
		return true;
	}
}
