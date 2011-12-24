<?php

abstract class Database_Abstract
{
	// Последний запрос и параметры от него
	protected $last_query;
	protected $last_params = array();

	// Находимся ли мы в состоянии транзакции
	protected $transaction = false;

	// Префикс перед табличками
	protected $prefix = "";

	const PACKED_MARK = "=packed=";

	public static function pack ($data) {
		if (function_exists("igbinary_serialize")) {
			$data = igbinary_serialize($data);
		} else {
			$data = serialize($data);
		}

		return self::PACKED_MARK.rtrim(base64_encode($data),"=");
	}

	public static function unpack ($input_string) {
		$mark_length = strlen(self::PACKED_MARK);

		if (substr($input_string, 0, $mark_length) != self::PACKED_MARK) {
			return $input_string;
		}

		$string = substr($input_string, $mark_length);
		$string = base64_decode($string);

		if (empty($string)) {
			return $input_string;
		}

		if (function_exists("igbinary_unserialize")) {
			return igbinary_unserialize($string);
		} else {
			return unserialize($string);
		}
	}

	public function conditional_insert ($table, $values, $keys = false, $deny_condition = false, $deny_params = array()) {
		if ($this->get_row($table, $deny_condition, "*", $deny_params)) {
			return 0;
		}

		return $this->insert($table, $values, $keys);
	}

	public function array_in ($field, $array, $binary = false) {
		$field = str_replace(".", "`.`", $field);

		if (!$binary) {
			if (!empty($array) && is_array($array)) {
				return "`$field` in (".str_repeat("?,",count($array)-1)."?)";
			} else {
//				Error::warning("Пустой массив при обращении к БД");
				return "FALSE";
			}
		} else {
			return "`$field` in (0x" . implode(",0x", $array) . ")";
		}
	}

	public function format_insert_values (& $values) {
		if (empty($values)) {
//			Error::warning("Пустой массив для вставки в БД");
			return;
		}

		$values = (array) $values;

		$keys = array_keys($values);

		if (count(array_diff_key($values, $keys)) === 0) {
			return "VALUES(NULL".str_repeat(",?",count($values)).")";
		}

		$values = array_values($values);

		foreach ($keys as &$key) {
			$key = "`".trim($key,"`")."`";
		}
		return "(".implode(",",$keys).") VALUES(?".str_repeat(",?",count($values) - 1).")";
	}

	public function date_to_unix ($date) {
		return strtotime($date);
	}

	public function unix_to_date ($time = false) {
		if (empty($time)) {
			$time = time();
		}

		return date("Y-m-d H:i:s", $time);
	}

	public function get_full_table ($table, $condition = false, $params = false) {
		return $this->get_table($table, "*", $condition, $params);
	}

	public function get_full_vector ($table, $condition = false, $params = false, $unset = true) {
		return $this->get_vector($table, "*", $condition, $params, $unset);
	}

	public function get_full_row ($table, $condition = false, $params = false) {
		return $this->get_row($table, "*", $condition, $params);
	}

	public function get_count ($table, $condition = false, $params = false) {
		return $this->get_field($table, "count(*)", $condition, $params);
	}
}
