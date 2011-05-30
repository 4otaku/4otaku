<?

class News_Output extends Output_Main implements Plugins
{
	public function single ($query) {
		
		if (!empty($query['id'])) {
			$data = Database::get_full_row('news', $query['id']);
		} elseif (!empty($query['name'])) {
			$data = Database::get_full_row('news', '`url` = ?', $query['name']);
		} else {
			Error::warning("Некорректный адрес страницы с новостью");
		}
		
		$id = $data['id'];		
		$this->test_area($data['area']);
		
		$this->items[$id] = new Item_News($data);
	}

	public function get_content ($query, $perpage, $page, $start) {
		
		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Database::get_full_vector('news', $condition);
		
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_News($item);
		}
		unset ($items);
		
		$count = Database::get_count('news', $listing_condition);
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'news',
		));
	}
}
