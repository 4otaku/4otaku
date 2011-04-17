<?

class Video_Output extends Output_Main implements Plugins
{
	public function single ($query) {		
		$id = $query['id'];
		$video = Database::get_full_row('video', $id);
		
		$this->test_area($video['area']);
		
		$this->items[$id] = new Item_Video($video, 'fullsize');	

		$meta = Meta::prepare_meta(array($id => $video['meta']));

		$this->items[$id] = Transform_Item::merge($this->items[$id], current($meta));
	}

	public function main ($query) {
		$return = array();

		$perpage = Config::settings('per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;

		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Database::get_full_vector('video', $condition);

		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Video($item, 'thumbsize');
			$index[$id] = $item['meta'];
		}
		unset ($items);

		$meta = Meta::prepare_meta($index);
		
		foreach ($this->items as $id => & $item) {
			$item = Transform_Item::merge($item, $meta[$id]);
		}
		
		$count = Database::get_count('video', $listing_condition);
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'video',
		));		
	}
}
