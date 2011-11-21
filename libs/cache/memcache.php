<?php

class Cache_Memcache implements Cache_Interface_Single
{
	const	COMPRESS = true,
			MIN_COMPRESSION_RATIO = 0.9,
			MIN_COMPRESS_SIZE = 10240;

	public $able_to_work = true;

	// Для хранения объекта Memcache
	protected $memcache;

	public function __construct ($config) {

		if (class_exists("Memcache", false)) {
			$this->memcache = new Memcache();

			$this->memcache->connect("127.0.0.1");

			if (self::COMPRESS) {
				$this->memcache->setCompressThreshold(
					self::MIN_COMPRESS_SIZE,
					1 - self::MIN_COMPRESSION_RATIO
				);
			}
		} else {
			$this->able_to_work = false;
		}
	}

	public function set ($key, $value, $expire = null) {
		$compression = self::COMPRESS ? MEMCACHE_COMPRESSED : 0;

		$this->memcache->set($key, $value, $compression, $expire);
	}

	public function get ($key) {
		return $this->memcache->get($key);
	}

	public function delete ($key) {
		$this->memcache->delete($key);
	}

	public function increment ($key, $value = 1) {
		$value = (int) $value;

		$data = $this->get($key);

		if (!is_numeric($data)) {
			$data = (is_array($data) || is_object($data)) ? 0 : (int) $data;
			$this->set($key, $data + $value);
		} else {
			$this->memcache->increment($key, $value);
		}
	}

	public function decrement ($key, $value = 1) {
		$value = (int) $value;

		$data = $this->get($key);

		if (!is_numeric($data)) {
			$data = (is_array($data) || is_object($data)) ? 0 : (int) $data;
			$this->set($key, $data - $value);
		} else {
			$this->memcache->decrement($key, $value);
		}
	}
}
