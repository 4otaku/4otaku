<?

class Comments_Output extends Output implements Plugins
{
	public function main ($query) {

		$perpage = Config::settings('comment_roll', 'per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		
		$condition = "order by date desc limit $start, $perpage";

		$items = Database::get_full_vector('post', $condition);
		
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Post($item);
		}
		unset ($items);
		
		$count = Database::get_count('post', $listing_condition);
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'post',
		));
	}
	
	public function section ($query) {

		return $this->main($query);
	}	
}
