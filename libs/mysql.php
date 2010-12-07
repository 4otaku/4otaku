<?

class mysql
{
	public $connection;	
	public $mode;
	public $debug = '';
	
	function __construct() {
		$this->connection = mysql_connect(def::db('host'), def::db('user'), def::db('pass'));
		$this->set_connection('main');
	}

	public function set_connection($base) {
		if ($this->mode != $base) {
			mysql_select_db(def::db($base.'_db'), $this->connection);
			mysql_query("SET NAMES 'UTF8'");
			$this->mode = $base;
		}
	}

	function base_sql($base,$sql,$i = 3) { 	
		$this->set_connection($base);
		$return = $this->sql($sql,$i);
		$this->set_connection('main');
		return $return;
	}
	
	function return_row($result) {
		$row = mysql_fetch_array($result);
		if (is_array($row)) 
			foreach ($row as $key => &$field) 
				if (is_numeric($key) || is_null($field)) unset($row[$key]);
				else $field = stripslashes($field);	
		return $row;
	}
	
	function return_single($result) {
		$row = mysql_fetch_array($result);
		if (is_array($row)) return stripslashes(current($row));
	}	
	
	function return_table($result,$index) {
		while ($row=mysql_fetch_array($result))
			if (is_array($row)) {
				foreach ($row as $key => &$field) 
					if (is_numeric($key) || is_null($field)) unset($row[$key]);
					else $field = stripslashes($field);	
				$return[] = $row; 
			}
		if (!is_numeric($index)) {
			foreach ($return as $row) {
				$new_key = $row[$index];
				foreach ($row as $key => &$field) if ($key == $index) unset($row[$key]);
				if (count($row) == 1) $new_return[$new_key] = end($row);
				else $new_return[$new_key] = $row;
			}
			$return = $new_return;
		}
		return $return;
	}
	
	function sql($sql,$i = 3) {
		$result = mysql_query($sql, $this->connection);
		$this->debug = $sql;
		
		if ($i && $result && mysql_num_rows($result) != 0) 
			if ($i == 1) return $this->return_row($result);
			elseif ($i == 2) return $this->return_single($result);
			else return $this->return_table($result,$i);
	}	
	
	function debug() { 
		echo $this->debug;
	}

	function insert($table, $values) {
		foreach ($values as &$value) $value = mysql_real_escape_string($value);
		$values = '"","'.implode('","',$values).'"';
		$this->sql('insert into '.$table.' values('.$values.')',0);
	}
	
	function update($table, $valueskeys, $values, $keyvalue, $key = 'id') {
		if (is_array($values)) foreach ($values as $num => &$value) $value = '`'.$valueskeys[$num].'`="'.mysql_real_escape_string($value).'"';
		else $values = array('`'.$valueskeys.'`="'.mysql_real_escape_string($values).'"');		
		$update = implode(', ',$values);
		$this->sql('update '.$table.' set '.$update.' where '.$key.'="'.$keyvalue.'"',0);
	}

}
