<?php

class Model_Post_Update extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'username',
		'text',
		'pretty_text',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'updates';
		
	protected $sizetypes = array('кб', 'мб', 'гб');
	
	public function insert() {
		
		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(0));
		
		parent::insert();

		Database::update('post', 
			array('update_count' => '++'), 
			$this->get('post_id'));
				
		return $this;
	}		
	
	// @TODO: вынести в trait, как они появятся
	public function add_link($link) {

		$links = (array) $this->get('link');

		if (empty($links[$link['link_id']])) {
			$links[$link['link_id']] = array(
				'name' => $link['name'],
				'url' => array($link['url'] => $link['alias']),
				'size' => round($link['size'], 2), 
				'sizetype' => $this->sizetypes[$link['sizetype']]
			);
		} else {
			$links[$link['link_id']]['url'][$link['url']] = $link['alias'];
		}
		
		$this->set('link', $links);
	}
	
	// @TODO: вынести в trait, как они появятся
	public function add_image($image) {
		
		$images = (array) $this->get('image');		
		$images[] = $image;		
		$this->set('image', $images);
	}
}
