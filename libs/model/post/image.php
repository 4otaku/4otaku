<?php

class Model_Post_Image extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'file',
		'extension',
		'width',
		'height',
		'weight',
		'order',
	);

	// Название таблицы
	protected $table = 'post_image';
	
	public function insert() {
		
		if (
			!$this->get('width') || 
			!$this->get('height') ||
			!$this->get('weight')
		) {
			$this->get_measures();
		}
		
		parent::insert();
				
		return $this;
	}
	
	protected function get_measures() {
		$file = $this->get('file');
		$extension = $this->get('extension');
		
		if (empty($file) || empty($extension)) {
			throw new Error("Inserting empty Model_Post_Image");
		}
		
		$path = IMAGES . SL . 'post' . SL . 'full' . SL . $file . '.' . $extension;
		
		$sizes = getimagesize($path);
		$weight = filesize($path);
		
		$this->set('width', $sizes[0]);
		$this->set('height', $sizes[1]);
		$this->set('weight', $weight);
	}
}
