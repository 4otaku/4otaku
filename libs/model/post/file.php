<?php

class Model_Post_File extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'type',
		'name',
		'folder',
		'file',
		'size',
		'sizetype',
		'order'
	);

	// Название таблицы
	protected $table = 'post_file';
		
	protected $sizetypes = array('байт', 'кб', 'мб', 'гб');
	protected $filetypes = array('plain', 'image', 'audio');
	
	public function __construct($data = array()) {
		if (empty($data['file']) && !empty($data['filename'])) {
			$data['file'] = $data['filename'];
		}

		parent::__construct($data);
		
		// Картинка
		if ($this->get('type') == 1) {
			Cache::$prefix = 'post_file_image_';
			
			$height = Cache::get($this->get_id());
			if (empty($height)) {
				$height = $this->get_file_image_height();
			}
			$this->set('height', $height);
		}
		
		$this->set('display_type', $this->filetypes[$this->get('type')]);
		$this->set('display_size', round($this->get('size'), 2));
		$this->set('display_sizetype', $this->sizetypes[$this->get('sizetype')]);
	}	
	
	public function insert() {
		
		$type = $this->get('type');
		if (in_array($type, $this->filetypes)) {
			$this->set('type', array_search($type, $this->filetypes));
		}
		
		$this->get_file_size();
		
		parent::insert();

		return $this;
	}
	
	protected function get_file_image_height() {
		$file = $this->get('file');
		$folder = $this->get('folder');
		
		if (empty($file) || empty($folder)) {
			return 0;
		}
		
		$path = FILES . SL . 'post' . SL . $folder . SL . 'thumb_' . $file;
		
		$sizes = getimagesize($path);
		Cache::set($this->get_id(), $sizes[1]);
		
		return $sizes[1];
	}
	
	protected function get_file_size() {
		$file = $this->get('file');
		$folder = $this->get('folder');
		
		if (empty($file) || empty($folder)) {
			return 0;
		}
		
		$path = FILES . SL . 'post' . SL . $folder . SL . $file;
		
		$size = filesize($path);
		$type = 0;
		while ($size > 1024) {
			$type++;
			$size = $size / 1024;			
		}
				
		$this->set('size', $size);
		$this->set('sizetype', $type);
	}
}
