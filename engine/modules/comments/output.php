<?

class Comments_Output extends Output implements Plugins
{
	public function main ($query) {

		$perpage = Config::settings('comment_roll', 'per_page');
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
						Config::settings('comment_roll', 'display')
					);
				}
			}
			
			$this->items[] = new Item_Comment_Block(array(
				'limit' =>  Config::settings('comment_roll', 'last_comments'),
				'place' => $item['place'],
				'id' => $item['item_id'],
				'items' => $item_comments,
			));
		}
		
		$this->get_navi($query, Database::get_counter(), $page, $perpage);
	}
	
	public function section ($query) {

		$perpage = Config::settings('comment_roll', 'per_page');
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
						Config::settings('comment_roll', 'display')
					);
				}
			}
			
			$this->items[] = new Item_Comment_Block(array(
				'limit' =>  Config::settings('comment_roll', 'last_comments'),
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
}
