<?php

class Query_Meta
{
	protected $mode_add = true;
	protected $meta = array();
	protected $type = false;

	protected static $meta_plural_singular = array(
		'categories' => 'category',
		'tags' => 'tag',
		'languages' => 'language',
		'authors' => 'author'
	);

	protected static $meta_rus = array(
		'category' => 'Категория',
		'tag' => 'Тег',
		'language' => 'Язык',
		'author' => 'Автор'
	);

	public function __construct($data, $type, $sign = '+') {

		if ($sign == '-') {
			$this->mode_add = false;
		}

		$this->meta = (array) $data;

		if (array_key_exists($type, self::$meta_plural_singular)) {
			$this->type = self::$meta_plural_singular[$type];
		}

		if (in_array($type, self::$meta_plural_singular)) {
			$this->type = $type;
		}
	}

	public function get_condition() {
		if (empty($this->type)) {
			return '';
		}

		$return = ' and (';

		$data = array();
		for ($i = 0; $i < count($this->meta); $i++) {
			$data[] = ($this->mode_add ? '' : '!') .
				'locate(?, '.$this->type.')';
		}

		$return .= implode(' or ', $data);

		$return .= ')';

		return $return;
	}

	public function get_params() {
		if (empty($this->type)) {
			return array();
		}

		$data = $this->meta;
		foreach ($data as &$item) {
			$item = '|' . $item . '|';
		}

		return $data;
	}

	public function is_simple() {
		return $this->type && $this->mode_add && (count($this->meta) == 1);
	}

	public function get_type() {
		return $this->type;
	}

	public function get_type_rus() {
		if (!$this->is_simple()) {
			return false;
		}

		$meta = $this->get_type();

		return self::$meta_rus[$meta];
	}

	public function get_sign() {
		return $this->mode_add ? '+' : '-';
	}

	public function get_meta() {
		return implode(',', $this->meta);
	}

	public function have_meta($meta) {
		return in_array($meta, $this->meta);
	}
}
