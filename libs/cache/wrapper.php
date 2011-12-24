<?php

class Cache
{
	// Базовый префикс, нужен чтобы избежать коллизий с кешем других сайтов
	public static $base_prefix = "";

	// Текущий префикс, нужный для конкретной операции
	public static $prefix = "";
	
	
	public static $defined_driver = false;

	// Список классов которые можно использовать
	// в роли кеширующих, в порядке приоритета

	public static $drivers_list = array(
		"Cache_Memcached",
		"Cache_Memcache",
		"Cache_Database",
		"Cache_Files",
		"Cache_Dummy",
	);

	// Для хранения объекта выполняющего собственно кеширование
	protected static $worker = false;

	protected static function get_worker () {
		if (is_object(self::$worker)) {
			return self::$worker;
		}

		if (!empty(self::$defined_driver)) {
			$defined_driver = "Cache_".ucfirst(self::$defined_driver);

			if (class_exists($defined_driver)) {
				self::$worker = new $defined_driver();

				if (
					self::$worker instanceOf Cache_Interface_Single &&
					self::$worker->able_to_work
				) {
					return self::$worker;
				} else {
					self::$worker = false;
				}
			}
		}

		foreach (self::$drivers_list as $driver) {

			if (class_exists($driver)) {				
				self::$worker = new $driver();

				if (
					self::$worker instanceOf Cache_Interface_Single &&
					self::$worker->able_to_work
				) {
					break;
				} else {
					self::$worker = false;
				}
			}
		}

		if (empty(self::$worker)) {
			die("Не найден подоходящий класс для кеширования");
		}

		return self::$worker;
	}

	protected static function call_worker_function (
		$function,
		$array_call,
		$keys,
		$values = null,
		$expire = null
	) {
		$array_call = (bool) $array_call;

		if (is_array($keys)) {
			$array_call = true;
		}

		$worker = self::get_worker();

		if (!$array_call) {
			$key = self::$base_prefix . self::$prefix . $keys;
			return $worker->$function($key, $values, $expire);
		}

		if (!is_array($keys)) {
			$keys = (array) $keys;
		}

		foreach ($keys as &$key) {
			$key = self::$base_prefix . self::$prefix . $key;
		}
		unset($key);

		if (!isset($values) || !is_array($values)) {
			$values = array_fill(0, count($keys), $values);
		}

		if ($worker instanceOf Cache_Interface_Array) {
			$function .= "_array";
			$tmp_return = $worker->$function($keys, $values, $expire);
		} else {
			$values = array_combine($keys, $values);
			$tmp_return = array();

			foreach ($values as $key => $value) {
				$tmp_return[$key] = $worker->$function($key, $value, $expire);
			}
		}

		if (empty($tmp_return) || !is_array($tmp_return)) {
			return array();
		}

		array_filter($tmp_return);

		$prefix_length = strlen(self::$base_prefix . self::$prefix);

		$return = array();
		foreach ($tmp_return as $key => $value) {
			$key = substr($key, $prefix_length);
			$return[$key] = $value;
		}

		return $return;
	}

	// Набор функций, при помощи которых и подразумевается взаимодействие с кешем.
	// Выставляем публичный Database::$prefix и вперед

	public static function set ($key, $value, $expire = null) {
		return self::call_worker_function("set", false, $key, $value, $expire);
	}

	public static function set_array ($keys, $values, $expire = null) {
		return self::call_worker_function("set", true, $keys, $values, $expire);
	}

	public static function get ($key) {
		return self::call_worker_function("get", false, $key);
	}

	public static function get_array ($keys) {
		return self::call_worker_function("get", true, $keys);
	}

	public static function delete ($key) {
		return self::call_worker_function("delete", false, $key);
	}

	public static function delete_array ($keys) {
		return self::call_worker_function("delete", true, $keys);
	}

	public static function increment ($key, $value = 1) {
		return self::call_worker_function("increment", false, $key, $value);
	}

	public static function increment_array ($keys, $values = 1) {
		return self::call_worker_function("increment", true, $keys, $values);
	}

	public static function decrement ($key, $value = 1) {
		return self::call_worker_function("decrement", false, $key, $value);
	}

	public static function decrement_array ($keys, $values = 1) {
		return self::call_worker_function("decrement", true, $keys, $values);
	}
}
