<?

class Board_Output extends Output_Main implements Plugins
{
	// Хранит памятку о том, для каких постов на борде надо 
	// достать контент типа картинок/флешек и.т.п.
	// Формат: id поста => тред в котором он находится
	protected $items_needed = array();
	
	public function single ($query) {
		$id = $query['id'];
		$thread = Database::get_full_row('board', $id);
		
		if (!empty($thread['thread'])) {
			// TODO: ошибка, зашли не туда, воткнуть когда будет реализовано
		}
		
		$this->items_needed[$id] = $id;
		
		$this->items[$id] = new Item_Board($thread);

		$meta = current(Meta::prepare_meta(array($id => $thread['meta'])));
		$posts = current($this->get_answers($id));

		$this->items[$id] = Transform_Item::merge($this->items[$id], $meta, $posts);		
		$this->items[$id]['current_board'] = $this->random_board($meta['meta']['category']);
		
		$this->add_needed_content();
	}

	public function get_content ($query, $perpage, $page, $start) {
		$this->flags['skip_message'] = Globals::user('board', 'skip_message');
		
		if ($this->is_message($query)) {
			return;
		}		
		
		$listing_condition = $this->build_listing_condition($query) . " and `thread` = 0";
		$condition = $listing_condition . " order by `updated` desc limit $start, $perpage";

		$items = Database::set_counter()->get_full_vector('board', $condition);
	
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Board($item);
			
			$index[$id] = $item['meta'];
			$this->items_needed[$id] = $id;
		}
		unset ($items);
				
		$meta = Meta::prepare_meta($index);
		$posts = $this->get_answers(
			array_keys($this->items), 
			Config::settings('last_posts_count')
		);

		foreach ($this->items as $id => & $item) {

			$current_board = isset($query['alias']) ? 
				$query['alias'] : 
				$this->random_board($meta[$id]['meta']['category']);
			$item['current_board'] = $current_board;				
			
			$item = Transform_Item::merge($item, $meta[$id], $posts[$id]);
		}
		
		$this->add_needed_content();
		
		$count = Database::get_counter();

		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'board',
		), 'short_base');
	}
	
	protected function build_listing_condition ($query) {
		
		$condition = parent::build_listing_condition($query);
		
		if (!empty($query['type']) && !empty($query['date'])) {
			$date = Crypt::unpack_url_date($query['date']);
			
			$condition .= " and `".($query['type'] == 'new' ? "date" : "updated")."` > '$date'";
		}

		return $condition;
	}	
	
	protected function add_needed_content () {
		if (empty($this->items_needed)) {
			return;
		}
		
		$ids = array_keys($this->items_needed);
		$condition = Database::array_in('item_id', $ids)." order by `sort_number` desc";
		$items = Database::get_full_vector('board_items', $condition, $ids);

		if (empty($items)) {
			return;
		}
				
		$content = array();
		
		foreach ($items as $item) {
			$id = $item['item_id'];
		
			$content[$id][$item['type']][] = Crypt::unpack($item['data']);
		}
			
		foreach ($content as $id => $item) {	
			
			if ($this->items_needed[$id] == $id) {
				$this->items[$id]['content'] = $item;
			} else {
				$thread = $this->items_needed[$id];
				
				$this->items[$thread]['items'][$id]->add('content', $item);
			}
		}
	}
	
	protected function random_board ($categories) {
		if (empty($categories)) {
			Error::warning("Тред, не причиленный ни к одной доске");
			return '';
		}
				
		return $categories[array_rand($categories)]['alias'];
	}
	
	protected function get_answers ($ids, $limit = false) {
		$ids = (array) $ids;
		
		$condition = "`area` != 'deleted' and ".Database::array_in('thread', $ids);
		$condition .= " order by `date` desc";
		$posts = Database::get_full_vector('board', $condition, $ids);
		
		$return = array_fill_keys($ids, array());
		$count = array_fill_keys($ids, 0);
		
		foreach ($posts as $id => $post) {
			$thread = $post['thread'];
			
			if ($limit !== false) {
				if (++$count[$thread] > (int) $limit) {
					continue;
				}
			}
			
			$return[$thread]['items'][$id] = new Item_Board($post);
			$this->items_needed[$id] = $thread;
		}
		
		foreach ($return as & $thread) {
			if (!empty($thread['items'])) {
				$thread['items'] = array_reverse($thread['items'], true);
			}
		}
		unset($thread);

		if (!empty($limit)) {
			$sql = "
				select count(*) as count, <pr>board_items.type, <pr>board.thread 
				from <pr>board_items left join <pr>board on <pr>board_items.item_id = <pr>board.id 
				where ".Database::array_in("<pr>board.thread", $ids)." and <pr>board.area != 'deleted'
				group by <pr>board.thread, <pr>board_items.type";
				
			$totals = Database::sql($sql,$ids);
						
			foreach ($totals as $total) {
				$thread = $total['thread'];
				$type = $total['type'];

				$return[$thread]['totals'][$type] = $total['count'];
			}
			
			foreach (array_keys($return) as $thread) {
				$return[$thread]['totals']['post'] = $count[$thread];
			}
		}
		
		return $return;
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
