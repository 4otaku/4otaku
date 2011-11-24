<?php

class Read_Post_Update extends Read_Abstract
{
	protected $template = 'main/update';	
	protected $error_template = 'error/post';
	
	protected $side_modules = array(
		'head' => array('title', 'js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('comments','update','orders','tags'),
		'footer' => array('year')
	);
	
	public function __construct() {
		parent::__construct();		
		
		$this->per_page = sets::pp('updates_in_line');
	}
	
	protected function display_single_item($url) {
		var_dump($url); die;
	}
	
	protected function display_index($url) {
		
		$this->get_items();
	}
	
	protected function display_page($url) {
	
		$this->get_page($url, 2);		
		$this->get_items();	
	}
	
	protected function get_items() {
		
		$start = ($this->page - 1) * $this->per_page;
		
		$items = Database::set_counter()->order('pu.sortdate')
			->join('post', 'p.id = pu.post_id')->limit($this->per_page, $start)
			->get_table('post_update', array('pu.*', 'p.title', 'p.comment_count'));
/*			
		$images = Database::order('order', 'asc')->get_full_table('post_image', 
		Database::array_in('post_id', $keys), $keys);
				
		foreach ($images as $image) {
			$items[$image['post_id']]->add_image($image);
		}			
*/
		$this->count = Database::get_counter();

		$this->data['items'] = $items;
		$this->data['navi'] = $this->get_bottom_navi();			
	}
		
	protected function get_bottom_navi() {
		$return = array();		
		
		$return['curr'] = $this->page;
			
		$return['last'] = ceil($this->count / $this->per_page);
		
		$return['start'] = max($return['curr'] - 5, 2);
		$return['end'] = min($return['curr'] + 6, $return['last'] - 1);

		$return['base'] = '/post/updates/';
		
		return $return;
	}	
}
