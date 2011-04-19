<?

class Board_Output extends Output_Main implements Plugins
{
	public function single ($query) {
		$id = $query['id'];
		$post = Database::get_full_row('post', $id);
		
		$this->test_area($post['area']);
		
		$this->items[$id] = new Item_Post($post);

		$subitems = current($this->get_subitems($id));
		$meta = current(Meta::prepare_meta(array($id => $post['meta'])));

		$this->items[$id] = Transform_Item::merge($this->items[$id], $meta, $subitems);
	}

	public function main ($query) {
		
		if ($this->is_message($query)) {
			return;
		}

		$perpage = Config::settings('per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		
		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Database::get_full_vector('post', $condition);
		
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Post($item);
			$index[$id] = $item['meta'];
		}
		unset ($items);

		$keys = array_keys($this->items);
	//	$post_subitems = $this->get_subitems($keys);
		
		$meta = Meta::prepare_meta($index);

		foreach ($this->items as $id => & $item) {
			$item = Transform_Item::merge($item, $post_subitems[$id], $meta[$id]);
		}
		
		$count = Database::get_count('post', $listing_condition);
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'post',
		));
	}
	
	public function is_message ($query) {
		$this->flag['skip_message'] = Globals::user('board', 'skip_message');
		
		return (bool) (empty($query) && !$this->flag['skip_message']);
	}
	
	public static function description ($query) {
		
		return array(
			'menu' => Database::get_vector('meta',
				array('alias', 'name'),
				"type='category' and area='board'"
			),
		);
	}
}
