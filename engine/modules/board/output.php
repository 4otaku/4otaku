<?

class Board_Output extends Output_Main implements Plugins
{
	public function single ($query) {
		$id = $query['id'];
		$thread = Database::get_full_row('board', $id);
		
		if (!empty($thread['thread'])) {
			// TODO: ошибка, зашли не туда, воткнуть когда будет реализовано
		}
		
		$this->test_area($thread['area']);
		
		$this->items[$id] = new Item_Board($thread);

		$meta = current(Meta::prepare_meta(array($id => $thread['meta'])));

		$this->items[$id] = Transform_Item::merge($this->items[$id], $meta);;		
		$this->items[$id]['current_board'] = $this->random_board($meta['meta']['category']);
	}

	public function main ($query) {
		
		$this->flags['skip_message'] = Globals::user('board', 'skip_message');
		$this->flags['thread_list'] = true;
		
		if ($this->is_message($query)) {
			return;
		}

		$perpage = Config::settings('threads_per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		
		$listing_condition = $this->build_listing_condition($query) . " and `thread` = 0";
		$condition = $listing_condition . " order by `updated` desc limit $start, $perpage";

		$items = Database::get_full_vector('board', $condition);
	
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Board($item);
			$index[$id] = $item['meta'];
		}
		unset ($items);
		
		$meta = Meta::prepare_meta($index);

		foreach ($this->items as $id => & $item) {

			$current_board = isset($query['alias']) ? 
				$query['alias'] : 
				$this->random_board($meta[$id]['meta']['category']);
			$item['current_board'] = $current_board;				
			
			$item = Transform_Item::merge($item, $meta[$id]);
		}
		
		$count = Database::get_count('board', $listing_condition);

		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'board',
		), 'short_base');
	}
	
	protected function random_board ($categories) {
		if (empty($categories)) {
			Error::warning("Тред, не причиленный ни к одной доске");
			return '';
		}
				
		return $categories[array_rand($categories)]['alias'];
	}
	
	public function is_message ($query) {
				
		return (bool) (empty($query) && !$this->flags['skip_message']);
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
