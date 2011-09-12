<?php

class Cache_Files implements Cache_Interface_Single
{
	const FOLDER = "files_cache";

	public $able_to_work = true;

	protected $igbinary = false;

	public function __construct ($config) {

		if (!file_exists(CACHE.SL.self::FOLDER) && is_writable(CACHE)) {
			mkdir(CACHE.SL.self::FOLDER);
			chmod(CACHE.SL.self::FOLDER, 0777);
		}

		if (!is_writable(CACHE.SL.self::FOLDER)) {
			$this->able_to_work = false;
		}

		if (isset($config["serialize"]) && $config["serialize"] == "igbinary") {
			$this->igbinary = true;
		}
	}

	protected function serialize ($value) {
		if ($this->igbinary) {
			return igbinary_serialize($value);
		}

		return serialize($value);
	}

	protected function unserialize ($value) {
		if ($this->igbinary) {
			return igbinary_unserialize($value);
		}

		return unserialize($value);
	}

	protected function get_filename ($key) {
		return CACHE.SL.self::FOLDER.SL.urlencode($key);
	}

	public function set ($key, $value, $expire = null) {
		$filename = $this->get_filename($key);
		$content = (time() + (int) $expire) . "\n" . $this->serialize($value);

		file_put_contents($filename, $content);
	}

	public function get ($key) {
		$filename = $this->get_filename($key);

		if (!file_exists($filename)) {
			return null;
		}

		$content = file_get_contents($filename);
		list($expire, $content) = preg_split("/\n/", $content, 2);

		if ((int) $expire > time()) {
			return $this->unserialize($content);
		} else {
			unlink($filename);
		}
	}

	public function delete ($key) {
		$filename = $this->get_filename($key);

		unlink($filename);
	}

	public function increment ($key, $value = 1) {
		$filename = $this->get_filename($key);

		$content = file_get_contents($filename);
		list($expire, $content) = preg_split("/\n/", $content, 2);

		$content = $this->unserialize($content);
		$content = (is_array($content) || is_object($content)) ? 0 : (int) $content;
		$new_content = $this->serialize($content + (int) $value);

		$content = "$expire\n$new_content";
		file_put_contents($filename, $content);
	}

	public function decrement ($key, $value = 1) {
		$filename = $this->get_filename($key);

		$content = file_get_contents($filename);
		list($expire, $content) = preg_split("/\n/", $content, 2);

		$content = $this->unserialize($content);
		$content = (is_array($content) || is_object($content)) ? 0 : (int) $content;
		$new_content = $this->serialize($content - (int) $value);

		$content = "$expire\n$new_content";
		file_put_contents($filename, $content);
	}
}
