<?php

class Cache_Memcached implements Cache_Interface_Single, Cache_Interface_Array
{
	public $able_to_work = true;

	// Для хранения объекта Memcached
	protected $memcached;

	public function __construct () {

		if (class_exists("Memcached", false)) {
			$this->memcached = new Memcached("default_fateline");
			$this->memcached->addServer("localhost", 11211);

			$serializer = Memcached::SERIALIZER_PHP;

			$this->memcached->setOption(Memcached::OPT_SERIALIZER, $serializer);
		} else {
			$this->able_to_work = false;
		}
	}

	public function set ($key, $value, $expire = null) {
		$this->memcached->set($key, $value, (int) $expire);
	}

	public function set_array ($keys, $values, $expire = null) {
		$items = array_combine($keys, $values);

		$this->memcached->setMulti($items, $expire);
	}

	public function get ($key) {
		$value = $this->memcached->get($key);

		if ($this->memcached->getResultCode() === MEMCACHED::RES_NOTFOUND) {
			$value = false;
		}

		return $value;
	}

	public function get_array ($keys) {
		return $this->memcached->getMulti($keys);
	}

	public function delete ($key) {
		$this->memcached->delete($key);
	}

	public function delete_array ($keys) {
		foreach ($keys as $key) {
			$this->delete($key);
		}
	}

	public function increment ($key, $value = 1) {
		$value = (int) $value;

		$data = $this->get($key);

		if (!is_numeric($data)) {
			$data = (is_array($data) || is_object($data)) ? 0 : (int) $data;
			$this->set($key, $data + $value);
		} else {
			$this->memcached->increment($key, $value);
		}
	}

	public function increment_array ($keys, $value = 1) {
		if (is_array($value)) {
			$value = current($value);
		}

		foreach ($keys as $key) {
			$this->increment($key, $value);
		}
	}

	public function decrement ($key, $value = 1) {
		$value = (int) $value;

		$data = $this->get($key);

		if (!is_numeric($data)) {
			$data = (is_array($data) || is_object($data)) ? 0 : (int) $data;
			$this->set($key, $data + $value);
		} else {
			$this->memcached->decrement($key, $value);
		}
	}

	public function decrement_array ($keys, $value = 1) {
		if (is_array($value)) {
			$value = current($value);
		}

		foreach ($keys as $key) {
			$this->decrement($key, $value);
		}
	}
}
