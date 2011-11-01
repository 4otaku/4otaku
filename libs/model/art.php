<?php

class Model_Art extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'md5',
		'thumb',
		'extension',
		'resized',
		'animated',
		'author',
		'category',
		'tag',
		'rating',
		'translator',
		'source',
		'comment_count',
		'last_comment',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'art';

	// Последний номер similar пикчи
	protected $last_order = null;
	// Отметка о том, брали ли мы его уже из базы
	protected $last_order_known = false;	
	
	public function insert() {
		
		if (
			!Check::is_hash($this->get('md5')) ||
			!Check::is_hash($this->get('thumb')) ||
			!$this->get('extension') ||
			Database::get_count('art', 'md5 = ?', $this->get('md5'))
		) {
			return $this;
		}
		
		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(1));
		
		parent::insert();

		Database::insert('versions', array(
			'type' => 'art',
			'item_id' => $this->get_id(),
			'data' => base64_encode(serialize($this->get_data())),
			'time' => $this->get('sortdate'),
			'author' => sets::user('name'),
			'ip' => $_SERVER['REMOTE_ADDR']
		));			

		if (
			function_exists('puzzle_fill_cvec_from_file') && 
			function_exists('puzzle_compress_cvec')
		) {
			$imagelink = IMAGES.SL.'booru'.SL.'thumbs'.
				SL.'large_'.$this->get('thumb').'.jpg';
				
			$vector = puzzle_fill_cvec_from_file($imagelink);
			$vector = base64_encode(puzzle_compress_cvec($vector));

			Database::insert('art_similar', array(
				'id' => $this->get_id(), 
				'vector' => $vector
			));
		}
				
		return $this;
	}
	
	function add_similar($data) {
		if (!$this->last_order_known) {
			$this->last_order = Database::get_field('art_variation', 
				'max(`order`)', 'art_id = ?', $this->get_id());
				
			$this->last_order_known = true;
		}
		
		if (is_numeric($this->last_order)) {
			$insert_order = $this->last_order + 1;
		} else {
			$insert_order = 0;
		}
		
		Database::insert('art_variation', array(
			'art_id' => $this->get_id(),
			'md5' => $data['md5'],
			'thumb' => $data['thumb'],
			'extension' => $data['extension'],
			'is_resized' => !empty($data['resized']),
			'order' => $insert_order,
			'animated' => $data['animated'],
		));
		
		$this->last_order = $insert_order;
	}
}
