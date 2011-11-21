<?php

class Read_Post extends Read_Main
{
	protected $template = 'main/post';	
	protected $error_template = 'error/post';
	
	protected $side_modules = array(
		'head' => array('title', 'js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('comments','update','orders','tags'),
		'footer' => array('year')
	);
	
	public function __construct() {
		parent::__construct();		
		
		$cookie = new dynamic__cookie();
		$cookie->inner_set('visit.post', time(), false);
		
		$this->per_page = sets::pp('post');
	}	
	
	protected function get_item($id) {
		
	}
	
	protected function get_items() {
		
		$items = $this->load_batch('post');
		$keys = array_keys($items);
		
		foreach ($items as $id => &$item) {
			$item['id'] = $id;
			$item = new Model_Post($item);
		}
		
		$this->load_meta($items);
		
		$images = Database::order('order', 'asc')->get_full_table('post_image', 
				Database::array_in('post_id', $keys), $keys);
				
		foreach ($images as $image) {
			$items[$image['post_id']]->add_image($image);
		}
		
		$links = Database::join('post_link_url', 'plu.link_id = pl.id')
			->join('post_url', 'plu.url_id = pu.id')->order('pl.order', 'asc')
			->order('plu.order', 'asc')->get_full_vector('post_link', 
				Database::array_in('pl.post_id', $keys), $keys);
			
		foreach ($links as $link) {
			$items[$link['post_id']]->add_link($link);
		}
		
		$files = Database::order('order', 'asc')->get_full_table('post_file', 
				Database::array_in('post_id', $keys), $keys);

		foreach ($files as $file) {
			$items[$file['post_id']]->add_file($file);
		}
		
		$extras = Database::order('order', 'asc')->get_full_table('post_extra', 
				Database::array_in('post_id', $keys), $keys);
				
		foreach ($extras as $extra) {
			$items[$extra['post_id']]->add_extra($extra);
		}				

		$this->data['items'] = $items;
		$this->data['navi'] = $this->get_bottom_navi();	
	}
	
	protected function get_navigation() {
		return array(
			'tag' => $this->get_navi_tag('post'),
			'category' => $this->get_navi_category('post'),
			'language' => $this->get_navi_language()
		);
	}
	
	protected function display_index($url) {
		
		$this->get_items();
	}
	
	protected function display_single_item($url) {
		
		$this->get_item($url[2]);
	}
	
	protected function display_page($url) {
		
		$this->get_page($url, 3);		
		$this->get_items();
	}
	
	protected function display_tag($url) {
		
		$this->get_page($url, 5);		
		$this->get_meta($url, 3, 'tag');
		$this->get_items();
	}
	
	protected function display_author($url) {
		
		$this->get_page($url, 5);		
		$this->get_meta($url, 3, 'author');
		$this->get_items();		
	}
	
	protected function display_category($url) {
		
		$this->get_page($url, 5);		
		$this->get_meta($url, 3, 'category');
		$this->get_items();		
	}
	
	protected function display_language($url) {
		
		$this->get_page($url, 5);		
		$this->get_meta($url, 3, 'language');
		$this->get_items();		
	}
	
	protected function display_mixed($url) {
		
		$this->get_page($url, 5);		
		$this->get_mixed($url, 3);
		$this->get_items();		
	}
	
	protected function display_updates($url) {
		
		die("derp");
	}
	
	protected function display_gouf($url) {
		
		die("derp");
	}
}
