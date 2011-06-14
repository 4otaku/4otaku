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
						Globals::user_settings('display')
					);
				}
			}
			
			$this->items[] = new Item_Comment_Block(array(
				'limit' =>  Globals::user_settings('last_comments'),
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
						Globals::user_settings('display')
					);
				}
			}
			
			$this->items[] = new Item_Comment_Block(array(
				'limit' =>  Globals::user_settings('last_comments'),
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
		Config::load(__DIR__.SL.'settings.ini', true);
		
		if (!empty($query['submodule']) && !empty($query['meta'])) {
			$item_id = $query['alias'];
			$place = $query['meta'];			
		} else {
			$item_id = $query['id'];
			$place = $module;
		}
	
		if ($module != 'sidebar' && !empty($item_id)) {
			return array(
				'function' => 'single', 
				'place' => $place, 
				'item_id' => $item_id,
				'page' => empty($query['comment_page']) ? 1 : $query['comment_page'], 
			);
		}
		
		return array('function' => 'sidebar', 'section' => $query['section']);
	}
	
	public function single ($query) {

		$config = Globals::user('comments', 'single_item');

		$perpage = $config['per_page'];
		$display = $config['display'];
		$inverted = $config['inverted'];

		if ($query['page'] != 'all') {			
			$page = $query['page'];
			$start = ($page - 1) * $perpage;
			$limit = " limit $start, $perpage";	
		} else {
			$limit = "";
			$page = 1;
			$start = 0;
		}
		
		$root = $display == 'ladder' ? "and root = 0" : "";		
		$direction = (bool) $inverted ? "desc" : "asc";
		
		$params = array('deleted', $query['place'], $query['item_id']);
		$condition = "area != ? and place = ? and item_id = ? $root order by date $direction $limit";

		$comments = Database::set_counter()->get_full_vector('comment', $condition, $params, false);
		
		$total = Database::get_counter();
		$current = (bool) $inverted ? $total - $start : $start;
			
		if ($display == 'ladder') {
			$roots = array_keys($comments);
			
			$condition = "area != ? and place = ? and item_id = ? and ".
				Database::array_in('root', $roots)." order by date";
			$params = array_merge($params, $roots);
			
			$children = Database::get_full_vector('comment', $condition, $params, false);
			$this->build_tree($children);
		} else {
			$children = array();
		}
		
		if ($display == 'quotation') {
			$parents = array();
			
			foreach ($comments as $comment) {
				$parents[] = $comment['parent'];
			}
			
			$condition = "area != ? and place = ? and item_id = ? and ".
				Database::array_in('id', $parents);
			$params = array_merge($params, $parents);
							
			$parents = Database::get_full_vector('comment', $condition, $params, false);
		} else {
			$parents = array();
		}		

		foreach ($comments as $id => $comment) {
			$item_children = array();
			$item_parent = null;
			
			foreach ($children as $child_id => $child) {
				if ($id == $child['root']) {				
					$item_children[$child_id] = new Item_Comment($child, $display);
				}
			}
			
			foreach ($parents as $parent_id => $parent) {
				if ($parent_id == $comment['parent']) {				
					$item_parent = new Item_Comment($parent);
				}
			}
			
			$this->items[$id] = new Item_Comment(
				array_merge(
					$comment, 
					array(
						'items' => $item_children,
						'quotation' => $item_parent,
						'index' => (bool) $inverted ? $current-- : ++$current,
					)
				),
				$display
			);
		}
		
		$this->items[] = new Item_Comment_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($total / $perpage),
			'query' => $query,
			'module' => 'comments',
		));
	}
	
	protected function build_tree(& $children) {
		foreach ($children as & $comment) {
			
			if ($comment['parent'] == $comment['root']) {
				$comment['tree'] = array((int) $comment['root']);
			}
		}			
		unset($comment);		
		
		$building = true;
		$iteration = 0;
		
		while ($building && ++$iteration < 100) {
			$building = false;
			
			foreach ($children as & $comment) {
				
				if (empty($comment['tree'])) {					
					$building = true;
					
					foreach ($children as $parent_id => $possible_parent) {
						if (
							$parent_id == $comment['parent'] &&
							!empty($possible_parent['tree'])
						) {
							$comment['tree'] = $possible_parent['tree'];
							$comment['tree'][] = (int) $parent_id;
						}
					}
				}
			}			
			unset($comment);
		}
	}
}
