<?php

abstract class Model_Abstract_Meta extends Model_Abstract_Logged
{
	protected $meta_fields= array();

	public function set($key, $value = null) {

		parent::set($key, $value);

		$meta = (array) $this->get('meta');

		if (in_array($key, $this->meta_fields)) {
			if (is_string($value)) {
				$value = array_unique(array_filter(explode('|', $value)));
			}

			$meta[$key] = array();
			foreach ((array) $value as $item) {
				$meta[$key][$item] = array();
			}
		}

		parent::set('meta', $meta);

		return $this;
	}
}

