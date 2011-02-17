<?

class Database_Firebird extends Database_Complex implements Database_Interface
{
	// Хранит соединение с БД	
	private $connection;
	
	// Последний результат запроса к БД
	private $result;
	
	// Последний запрос
	private $last_query = array();
	
	// Находимся ли мы в состоянии транзакции
	protected $transaction = true;
	
	// Префикс перед табличками
	protected $prefix = '';	
	
	public function __construct($config) {
		if (
			strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' &&
			$config['Server'] == 'localhost'
		) {
			$database = $config['Database'];
		} else {
			$database = $config['Server'].':'.$config['Database'];
		}
		
		$this->connection =	ibase_connect(
			$database, 
			$config['User'], 
			$config['Password'],
			'UTF-8'
		) or Error::fatal(ibase_errmsg());
		
		if (!empty($config['Prefix'])) {
			$this->prefix =	$config['Prefix'];
		}	
	}
	
	public function __destruct() {
		$this->commit();
	}
	
	protected function query($query, $params = array()) {
		$params = (array) $params;
		
		$this->last_query = array(
			'query' => $query,
			'params' => $params,
		);

		$result = ibase_query($this->connection, $query, $params);
		
		return $result === true ? false : $result;
	}
	
	public function sql($query, $params = array()) {
		$query = str_replace('{pr}', $this->prefix, $query);
		
		$this->result = $this->query($query, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}
		
		$return = array();
		while ($row = ibase_fetch_assoc($this->result, IBASE_TEXT)) {
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
		while ($row = ibase_fetch_assoc($this->result, IBASE_TEXT)) {
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
		while ($row = ibase_fetch_assoc($this->result, IBASE_TEXT)) {
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

		$return = ibase_fetch_assoc($this->result, IBASE_TEXT);
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
		
		$return = ibase_fetch_assoc($this->result, IBASE_TEXT);
		return reset($return);
	}
	
	protected function make_insert_statement($table, $values, $keys = false) {
		$values = (array) $values;
		$keys = (array) $keys;
		
		$query = "INSERT INTO {$this->prefix}$table";
		
		if (count($values) === count($keys)) {
			$query .= " (".implode(',',$keys).")";
		}
		
		$query .= " VALUES(".rtimr(str_repeat("?,",count($values)),",").")";
		
		return $query;
	}
	
	public function insert($table, $values, $keys = false) {
		$query = $this->make_insert_statement($table, $values, $keys);
		
		$this->query($query, $values);
		
		return ibase_affected_rows($this->connection);
	}
	
	public function conditional_insert($table, $values, $keys = false, $deny_condition = false, $deny_params = array()) {
		if (!empty($this->get_row($table, $deny_condition, '*', $deny_params))) {
			return 0;
		}
		
		return $this->insert($table, $values, $keys);
	}

	public function bulk_insert($table, $rows, $keys = false) {
		$query = "set term ^ ;
			EXECUTE BLOCK AS BEGIN";
		
		$params = array();
		foreach ($rows as $row) {
			$query .= "\n".$this->make_insert_statement($table, $row, $keys).";";
			$params = array_merge($params, $row);
		}
		
		$query .= "\nEND^";
		
		$this->query($query, $params);
		
		return ibase_affected_rows($this->connection);
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
		
		return ibase_affected_rows($this->connection);	
	}	
	
	public function delete($table, $condition = false) {
		$query = "DELETE FROM {$this->prefix}$table";
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->query($query);
		
		return ibase_affected_rows($this->connection);
	}
	
	public function last_id($generator) {
		return ibase_gen_id($generator, 0);
	}
	
	public function debug($print = true) {
		$number = ibase_errcode();
		$params = implode(',', $this->last_query['params']);
		
		if (empty($number)) {
			$return = "Запрос: \"{$this->last_query['query']}\" с параметрами $params был выполнен успешно\n";
		} else {
			$return = "Запрос: \"{$this->last_query['query']}\" с параметрами $params \n".
				"Вызвал ошибку №".$number.": ".ibase_errmsg()."\n";
		}
			
		if ((bool) $print) {
			echo nl2br($return);
		}
		
		return $return;
	}
	
	public function free_result() {
		ibase_free_result($this->result);
	}
	
	public function begin() {
		if ((bool) $this->transaction) {
			Error::warning('Попытка начать транзакцию, уже находясь в одной. 
				Firebird начинает в состоянии транзакции, возможно дело в этом.');
			return false;
		}
		
		ibase_trans($this->connection);
		$this->transaction = true;
		
		return true;		
	}
	
	public function commit() {
		if (empty($this->transaction)) {
			Error::warning('Попытка закоммитить транзакцию, не запустив ее предварительно');
			return false;
		}
		
		ibase_commit($this->connection);
		$this->transaction = false;
		
		return true;
	}
	
	public function rollback() {
		if (empty($this->transaction)) {
			Error::warning('Попытка откатить транзакцию, не запустив ее предварительно');
			return false;
		}
		
		ibase_rollback($this->connection);
		$this->transaction = false;
		
		return true;
	}
}
