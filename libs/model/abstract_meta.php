<?php

abstract class Model_Abstract_Meta extends Model_Abstract_Logged
{
	protected $meta_fields= array();

	public function set($key, $value = null) {

		parent::set($key, $value);

		if (in_array($key, $this->meta_fields)) {
			$meta = (array) $this->get('meta', true);

			if (is_string($value)) {
				$value = array_unique(array_filter(explode('|', $value)));
			}

			$meta[$key] = array();
			foreach ((array) $value as $item) {
				$meta[$key][$item] = array();
			}

			parent::set('meta', $meta);
		}

		return $this;
	}

	protected function needs_load($key) {
		return in_array($key, $this->fields) || $key == 'meta';
	}
}

