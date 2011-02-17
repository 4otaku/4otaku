<?

abstract class Database_Complex
{
	public function data_insert($item_type, $item_id, $types, $values) {
		$item_id = (int) $item_id;
		$item_type = preg_replace('/^[a-z\d_]/', '', $item_type);
		
		$types = (array) $types;
		$values = (array) $values;		
		
		if (!empty($values)) {		
			if (count($types) !== count($values)) {
				$t_count = count($types);
				$v_count = count($values);
				Error::warning(
					"При использовании функции insert_data количество полей и значений должно совпадать!\n".
					"Было полей: $t_count (".implode(", ",$types).");\n".
					"Было значений: $v_count (".implode(", ",$values).");"
				);
			}
			
			$data = array_combine($types, $values);
		} else {
			$data = $types;
		}
		
		while (list($type, $value) = each($data)) {
			$type = preg_replace('/^[a-z\d_]/', '', $type);
			$condition = "item_id = $item_id and item_type = '$item_type' and type = '$type'";
			
			if (!$this->update('data', $condition, 'data', $value)) {
				$this->conditional_insert(
					'data', 
					array($type,$item_id,$item_type,$value),
					array('type','item_id','item_type','data'), 
					$condition
				);
			}
		}
	}
	
	public function conditional_insert($table, $values, $keys = false, $deny_condition = false, $deny_params = array()) {
		if (!empty($this->get_row($table, $deny_condition, '*', $deny_params))) {
			return 0;
		}
		
		return $this->insert($table, $values, $keys);
	}	
}
