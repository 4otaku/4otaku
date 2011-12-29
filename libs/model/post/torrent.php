<?php

class Model_Post_Torrent extends Model_Abstract
{
	const MAX_DISPLAY_FILENAME = 40;

	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'name',
		'hash',
		'file',
		'size',
		'sizetype',
		'order'
	);

	// Название таблицы
	protected $table = 'post_torrent';

	protected $sizetypes = array('байт', 'кб', 'мб', 'гб');

	public function __construct($data = array()) {

		parent::__construct($data);

		$this->set('display_size', round($this->get('size'), 2));
		$this->set('display_sizetype', $this->sizetypes[$this->get('sizetype')]);
		$this->set('display_file', $this->get_display_filename());
	}

	public function insert() {

		$this->get_torrent_size();

		parent::insert();

		return $this;
	}

	protected function get_torrent_size() {
		$file = $this->get('file');
		$hash = $this->get('hash');

		if (empty($file) || empty($hash)) {
			return 0;
		}

		$path = FILES . SL . 'torrent' . SL . $hash . SL . $file;
		$torrent = new Transform_Torrent($path);

		if ($torrent->get_hash() != $hash) {
			throw new Error('Incorrect torrent hash');
		}

		$size = $torrent->get_size();
		$type = 0;

		while ($size > 1024) {
			$type++;
			$size = $size / 1024;
		}

		$this->set('size', $size);
		$this->set('sizetype', $type);
	}

	protected function get_display_filename() {
		$file = $this->get('file');
		if (strlen($file) <= self::MAX_DISPLAY_FILENAME + 4) {
			return $file;
		}

		$part_length = ceil(self::MAX_DISPLAY_FILENAME / 2);

		$begin = substr($file, 0, $part_length);
		$end = substr($file, -1 * $part_length);

		return $begin . '...' . $end;
	}
}
