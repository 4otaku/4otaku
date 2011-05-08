<?

class Database_Mysql extends Database_Common implements Database_Interface
{
	const null_replacement = '_4o_NULL_replace_';
	
	protected $counter = 0;
	protected $counter_lock = false;
	
	public function __construct($config) {
		$this->connection =	mysql_connect(
			$config['Server'], 
			$config['User'], 
			$config['Password'],
			true
		) or Error::fatal(mysql_error());

		mysql_select_db($config['Database'], $this->connection)
			or Error::fatal(mysql_error());			
		mysql_query("SET NAMES 'UTF8'");
		
		if (!empty($config['Prefix'])) {
			$this->prefix =	$config['Prefix'];
		}
	}
	
	protected function query($query, $params = array()) {
		if (!empty($params)) {
			$params = (array) $params;
			
			foreach ($params as &$param) {
				$param = is_null($param) ? 
					self::null_replacement : 
					mysql_real_escape_string($param, $this->connection);
			}		

			$query = vsprintf(str_replace("?","'%s'",$query), $params);
			$query = str_replace("'".self::null_replacement."'", 'NULL', $query);
		}
		
		$this->last_query = $query;

		return mysql_query($query, $this->connection);
	}
	
	public function sql($query, $params = array()) {
		$query = str_replace('<pr>', $this->prefix, $query);
		
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
	
	protected function get_common($table, $values = '*', $condition = false, $params = false) {
		if (is_array($values)) {
			$values = "`".implode("`,`", $values)."`";
		}
		
		$query = "SELECT ";
		if ($this->counter_lock) {
			$query .= "SQL_CALC_FOUND_ROWS ";
		}
		$query .= "$values FROM `{$this->prefix}$table`";
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->result = $this->query($query, $params);
		
		if ($this->counter_lock) {
			$count = $this->query("SELECT FOUND_ROWS()");
			$this->counter = current(mysql_fetch_assoc($count));
			$this->counter_lock = false;
		}
	}

	public function get_table($table, $values = '*', $condition = false, $params = false) {
		$this->get_common($table, $values, $condition, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}
		
		$return = array();
		while ($row = mysql_fetch_assoc($this->result)) {
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function get_vector($table, $values = '*', $condition = false, $params = false, $unset = true) {
		if (is_array($values)) {
			$key = reset($values);
		} else {
			$key = 'id';
		}
		
		$this->get_common($table, $values, $condition, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}

		$return = array();
		$i = 0;
		while ($row = mysql_fetch_assoc($this->result)) {
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
	
	public function get_row($table, $values = '*', $condition = false, $params = false) {
		if (is_numeric($condition)) {
			$condition = "id = $condition";
		}
		
		$this->get_common($table, $values, $condition." LIMIT 1", $params);
		
		if (!is_resource($this->result)) {
			return array();
		}

		$return = mysql_fetch_assoc($this->result);
		return $return;
	}
	
	public function get_field($table, $value, $condition, $params = false) {
		if (is_numeric($condition)) {
			$condition = "id = $condition";
		}
		
		if (empty($condition)) {
			$condition = "1";
		}		
		
		$this->get_common($table, $value, $condition." LIMIT 1", $params);
		
		if (!is_resource($this->result)) {
			return false;
		}
		
		$return = mysql_fetch_assoc($this->result);
		return is_array($return) ? reset($return) : $return;
	}
	
	public function insert($table, $values) {
		return $this->conditional_insert($table, $values);
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
		
			$query = rtrim($query,',');	
			$values = array_merge($values, $update_values);
		}
		
		$this->query($query, $values);
		
		return mysql_affected_rows($this->connection);		
	}
	
	public function conditional_insert($table, $values, $deny_condition = false, $deny_params = array()) {
		$query = "INSERT INTO `{$this->prefix}$table` ";	
		
		$insert = $this->format_insert_values($values);
		
		if (empty($deny_condition)) {
			$query .= $insert;
		} else {
			$query .= preg_replace('/VALUES\s*\((.*?)\)/', 'SELECT $1', $insert, 1);
			$query .=  " FROM helper WHERE NOT EXISTS (SELECT * FROM `$table` WHERE $deny_condition)";
			$values = array_merge($values, $deny_params);
		}
		
		$this->query($query, $values);
		
		return mysql_affected_rows($this->connection);		
	}
	
	public function bulk_insert($table, $rows, $keys = false) {
		$keys = (array) $keys;
		
		$query = "INSERT INTO `{$this->prefix}$table`";
		
		if (count(current($rows)) === count($keys)) {
			foreach ($keys as &$key) $key = '`'.trim($key,'`').'`';
			$query .= " (".implode(',',$keys).")";
			$prepend = '';
		} else {
			$prepend = "NULL,";
		}
		
		$query .= " VALUES ";
		
		$params = array();
		foreach ($rows as $row) {
			$query .= "(".$prepend.ltrim(str_repeat(',?',count($row)),',')."),";
			$params = array_merge($params, $row);
		}
		
		$query = rtrim($query,',');
		
		$this->query($query, $params);
		
		return mysql_affected_rows($this->connection);		
	}
	
	public function update($table, $condition, $values, $condition_params = array()) {
		$values = (array) $values;
		$condition_params = (array) $condition_params;
		
		$keys = array_keys($values);
		$values = array_values($values);			
		
		if (is_numeric($condition)) {
			$condition = 'id = '.$condition;
		}
		
		$query = "UPDATE `{$this->prefix}$table` SET ";

		foreach ($keys as $key) {
			$query .= "`$key` = ?,";
		}
		
		$query = rtrim($query,',');
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$params = array_merge(array_values($values), $condition_params);
		
		$this->query($query, $params);
		
		return mysql_affected_rows($this->connection);	
	}	
	
	public function delete($table, $condition = false, $params = false) {
		$query = "DELETE FROM `{$this->prefix}$table`";
		
		if (is_numeric($condition)) {
			$condition = 'id = '.$condition;
		}		
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->query($query, $params);
		
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
	
	public function make_search_condition($field, $search_values) {
		$return = 'match (`'.$field.'`) against ("+index';
		
		foreach ($search_values as $value) {
			list($sign, $word, $prefix) = $value;
			
			$value = ' ' . $sign . ($prefix ? $prefix . '__' : '') . $word;
			$return .= mysql_real_escape_string($value, $this->connection);
		}
		
		$return .= '" in boolean mode)';
		
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
