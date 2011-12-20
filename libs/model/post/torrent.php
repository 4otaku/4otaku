<?php

class Model_Post_Torrent extends Model_Abstract
{
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
	}

	public function insert() {

		$this->get_torrent_size();

		parent::insert();
		
		Database::db('tracker')->insert('xbt_files', array(
			'info_hash' => $this->get('hash'),
			'mtime' => time(),
			'ctime' => time(),
		));

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
}
