<?

class Dynamic_Tag extends Dynamic_Abstract
{
	protected $tag_areas = array(
		'post', 'video', 'art'
	);

	protected $empty_reply = array();

	public function get_all () {
		$where = query::$get['where'];

		if (!is_numeric(query::$get['count']) || !in_array($where, $this->tag_areas)) {
			$this->reply($this->empty_reply);
		}

		$min = $this->tag_areas[$where];

		$tags = Database::order($where.'_main')->limit(query::$get['count'])
			->get_vector('tag', 'name', $where.'_main > 0');

		$tags = array_values((array) $tags);

		foreach ($tags as &$tag) {
			$tag = html_entity_decode($tag);
			$tag = undo_safety($tag);
		}

		sort($tags);

		$this->reply($tags);
	}
}

class dynamic__tag extends Dynamic_Tag {}
