<?

class Comments_Output extends Output implements Plugins
{
	public function main ($query) {

		$perpage = Config::settings('per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		
		$params = array('deleted');
		$condition = "area != ? group by place, item_id order by max(date) desc limit $start, $perpage";

		$items = Database::set_counter()->
			get_table('comment', array('place', 'item_id'), $condition, $params);

		$condition = "";
		foreach ($items as $item) {
			$condition .= " or (place = ? and item_id = ?)";
			$params[] = $item['place'];
			$params[] = $item['item_id'];
		}
		$condition = "area != ? and (".substr($condition,4).") order by date";
		
		$comments = Database::get_full_vector('comment', $condition, $params);
	
		foreach ($items as $item) {
			$item_comments = array();
			
			foreach ($comments as $id => $comment) {
				if (
					$comment['item_id'] == $item['item_id'] && 
					$comment['place'] == $item['place']
				) {
					$item_comments[$id] = new Item_Comment(
						$comment, 
						Config::settings('display')
					);
				}
			}
			
			$this->items[] = new Item_Comment_Block(array(
				'limit' =>  Config::settings('last_comments'),
				'place' => $item['place'],
				'id' => $item['item_id'],
				'items' => $item_comments,
			));
		}
		
		$this->get_navi($query, Database::get_counter(), $page, $perpage);
	}
	
	public function section ($query) {

		$perpage = Config::settings('per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		
		$params = array($query['section'], 'deleted');
		$condition = "place = ? and area != ? group by place, item_id order by max(date) desc limit $start, $perpage";

		$items = Database::set_counter()->
			get_vector('comment', array('id', 'item_id'), $condition, $params);

		$condition = "place = ? and area != ? and ".Database::array_in('item_id', $items)." order by date";
		$params = array_merge($params, array_values($items));
		
		$comments = Database::get_full_vector('comment', $condition, $params);

		foreach ($items as $item) {
			$item_comments = array();
			
			foreach ($comments as $id => $comment) {
				if ($comment['item_id'] == $item) {				
					$item_comments[$id] = new Item_Comment(
						$comment, 
						Config::settings('display')
					);
				}
			}
			
			$this->items[] = new Item_Comment_Block(array(
				'limit' =>  Config::settings('last_comments'),
				'place' => $query['section'],
				'id' => $item,
				'items' => $item_comments,
			));
		}
		
		$this->get_navi($query, Database::get_counter(), $page, $perpage);
	}
	
	protected function get_navi ($query, $count, $page, $perpage) {
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'comments',
		));		
	}
	
	public function make_subquery ($query, $module) {
		Config::load(__DIR__.SL.'sub_settings.ini');
		
		if ($module != 'sidebar' && !empty($query['id'])) {
			return array(
				'function' => 'single', 
				'place' => $module, 
				'item_id' => $query['id'],
				'page' => empty($query['comment_page']) ? 1 : $query['comment_page'], 
			);
		}
		
		return array('function' => 'sidebar', 'section' => $query['section']);
	}
	
	public function single ($query) {

		$perpage = Config::sub_settings('single_item', 'per_page');
		$display = Config::sub_settings('single_item', 'display');

		if ($query['page'] != 'all') {			
			$page = $query['page'];
			$start = ($page - 1) * $perpage;
			$limit = " limit $start, $perpage";	
		} else {
			$limit = "";
		}
		
		if ($display == 'ladder') {
			$root = "and root = 0";
		} else {
			$root = "";
		}
		
		$params = array('deleted', $query['place'], $query['item_id']);
		$condition = "area != ? and place = ? and item_id = ? $root order by date desc $limit";

		$comments = Database::set_counter()->get_full_vector('comment', $condition, $params);
			
		if ($display == 'ladder') {
			$roots = array_keys($comments);
			
			$condition = "area != ? and place = ? and item_id = ? ".
				Database::array_in('root', $roots)." order by date";
			$params = array_merge($params, $roots);
			
			$children = Database::get_full_vector('comment', $condition, $params);
		} else {
			$children = array();
		}

		foreach ($comments as $id => $comment) {
			$item_children = array();
			
			foreach ($children as $child_id => $child) {
				if ($id == $child['root']) {				
					$item_children[$child_id] = new Item_Comment($child, $display);
				}
			}
			
			$this->items[] = new Item_Comment(
				array_merge($comment, array('items' => $item_children)),
				$display
			);
		}
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil(Database::get_counter() / $perpage),
			'query' => $query,
			'module' => 'comments',
		));			
	}	
}
