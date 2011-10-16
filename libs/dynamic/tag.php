<?

class Dynamic_Tag extends Dynamic_Abstract
{
	protected $tag_areas = array(
		'post' => 4,
		'video' => 4,
		'art' => 49,		
	);
	
	protected $empty_reply = array();
	
	public function get_all () {
		$where = query::$get['where'];
		
		if (!array_key_exists($where, $this->tag_areas)) {
			$this->reply($this->empty_reply);
		}
		
		$min = $this->tag_areas[$where];
		
		$tags = Database::set_order('name', 'ASC')->get_vector('tag', 
			'name', $where.'_main > ?', $min);

		$tags = array_values((array) $tags);
		
		foreach ($tags as &$tag) {
			$tag = html_entity_decode($tag);
		}
		
		$this->reply($tags);
	}
}

class dynamic__tag extends Dynamic_Tag {}
