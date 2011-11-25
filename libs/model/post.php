<?php

class Model_Post extends Model_Abstract_Meta
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'title',
		'text',
		'pretty_text',
		'author',
		'category',
		'language',
		'tag',
		'comment_count',
		'last_comment',
		'update_count',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'post';
	
	protected $meta_fields= array('tag', 
		'category', 'author', 'language');
		
	protected $sizetypes = array('кб', 'мб', 'гб');
	protected $filetypes = array('plain', 'image', 'audio');
		
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
	
	public function add_file($file) {

		// Картинка
		if ($file['type'] == 1) {
			Cache::$prefix = 'post_file_image_';
			
			$file['height'] = Cache::get($file['id']);
			if (empty($file['height'])) {
				$file['height'] = $this->get_file_image_height($file);
			}
		}

		$file['type'] = $this->filetypes[$file['type']];	
		$file['size'] = round($file['size'], 2);
		$file['sizetype'] = $this->sizetypes[$file['sizetype']];
		
		$files = (array) $this->get('file');		
		$files[] = $file;		
		$this->set('file', $files);
	}	
	
	protected function get_file_image_height($file) {
		$path = FILES . SL . 'post' . SL . $file['folder'] . SL . 'thumb_' . $file['file'];
		
		$sizes = getimagesize($path);
		Cache::set($file['id'], $sizes[1]);
		
		return $sizes[1];
	}
	
	public function add_extra($extra) {

		$extras = (array) $this->get('extra');		
		$extras[] = $extra;		
		$this->set('extra', $extras);
	}	
}
