<?

class Database_Mysql extends Database_Abstract
{
	// Хранит соединение с БД	
	private $connection;
	
	// Хранит ассоциативный массив баз
	private $bases = array();
	
	// Последний результат запроса к БД
	private $result;
	
	private $clean_threshold = 100;
	
	public function __construct($config) {
		$this->connect($config);
		$this->add_database('main', $config['Database']);
		
		if (!empty($config['Threshold'])) {
			$this->clean_threshold = $config['Threshold'];
		}
	}
	
	public function connect($config) {
		$this->connection =	mysql_connect(
			$config['Server'], 
			$config['User'], 
			$config['Password']
		);
	}
	
	public function add_database($key, $name) {
		$this->bases[$key] = $name;
	}	
	
	public function query($query, $params) {
		if (!empty($params)) {
			foreach ($params as &$param) {
				mysql_real_escape_string($param, $this->connection);
			}
			
			$query = vsprintf(str_replace("??","'%s'",$query), $params);
		}
		
		return mysql_query($query);		
	}
	
	protected function get_common($table, $condition = false, $values = '*', $params = false) {
		if (is_array($values)) {
			$values = implode(',', $values);
		}
		
		$query = "SELECT $values FROM $table";
		
		if (!empty($condition)) {
			$query .= " WHERE $condition";
		}
		
		$this->result = $this->query($query, $params);
	}

	public function get($table, $condition = false, $values = '*', $params = false) {
		$result = $this->get_common($table, $condition, $values, $params);
		
		$return = array();
		while ($row = mysql_fetch_assoc($this->result)) {
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function get_vector($table, $key, $condition = false, $values = '*', $params = false) {
		$result = $this->get_common($table, $condition, $values, $params);
		
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
	
	public function get_row($table, $values, $condition, $params = false) {
		$result = $this->get_common($table, $condition, $values, $params);
		
		$return = mysql_fetch_assoc($this->result);
		return $return;
	}
	
	public function get_field($table, $value, $condition, $params = false) {
		$result = $this->get_common($table, $condition, $values, $params);
		
		$return = mysql_fetch_assoc($this->result);
		return reset($return);
	}
	
	public function insert($table, $values, $keys = false) {
		
	}
	
	public function bulk_insert() {
		
	}
	
	public function update() {
		
	}
	
	public function update_field() {
		
	}
	
	public function delete($table, $condition) {
		
	}
	
	public function get_last_id($table) {
		
	}
	
	public function clear_results() {
		if (
			!empty($this->clean_threshold) &&
			(mysql_num_rows($this->result) > $this->clean_threshold)
		) {
			mysql_free_result($this->result);
		}
	}

}
