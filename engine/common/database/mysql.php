<?

class Database_Mysql extends Database_Abstract
{
	// Хранит соединение с БД	
	private $connection;
	
	// Хранит ассоциативный массив баз
	private $bases = array();
	
	// Хранит информацию о том, к какой базе сейчас подключены
	private $curr_base;
	
	// Последний результат запроса к БД
	private $result;
	
	private $clean_threshold = 100;
	
	public function __construct($config) {
		$this->connect($config);
		$this->add_database('main', $config['Database']);
		$this->init_database('main');
		
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
		
		$this->prefix =	$config['Prefix'];
	}
	
	public function add_database($key, $name) {
		$this->bases[$key] = $name;
	}	
	
	public function init_database($name) {
		if ($this->curr_base != $name && array_key_exists($name, $this->bases)) {
			mysql_select_db($this->bases[$name], $this->connection);
			mysql_query('SET NAMES \'UTF8\'');
			$this->curr_base = $name;
		}
	}
	
	protected function query($query, $params = array()) {
		if (!empty($params)) {
			$params = (array) $params;
			
			foreach ($params as &$param) {
				mysql_real_escape_string($param, $this->connection);
			}
			
			$query = vsprintf(str_replace("??","'%s'",$query), $params);
		}

		return mysql_query($query, $this->connection);
	}
	
	public function sql($query, $params = array()) {
		$result = $this->query($query, $params);
		
		if (!is_resource($result)) {
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

	public function get($table, $condition = false, $values = '*', $params = false) {
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
	
	public function get_vector($table, $key, $condition = false, $values = '*', $params = false) {
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
	
	public function get_row($table, $values, $condition, $params = false) {
		$this->get_common($table, $condition, $values, $params);
		
		if (!is_resource($this->result)) {
			return array();
		}

		$return = mysql_fetch_assoc($this->result);
		return $return;
	}
	
	public function get_field($table, $value, $condition, $params = false) {
		$this->get_common($table, $condition, $value, $params);
		
		if (!is_resource($this->result)) {
			return false;
		}
		
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
			is_resource($this->result) &&
			!empty($this->clean_threshold) &&
			(mysql_num_rows($this->result) > $this->clean_threshold)
		) {
			mysql_free_result($this->result);
		}
	}

}
