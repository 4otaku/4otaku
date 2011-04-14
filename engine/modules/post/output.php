<?

class Post_Output extends Output_Main implements Plugins
{
	public function single ($query) {
		$id = $query['id'];
		$post = Globals::db()->get_full_row('post', $id);
		
		$this->test_area($post['area']);
		
		$this->items[$id] = new Item_Post($post);

		$subitems = current($this->get_subitems($id));
		$meta = current(Meta::prepare_meta(array($id => $post['meta'])));

		$this->items[$id] = Transform_Item::merge($this->items[$id], $meta, $subitems);
	}

	public function main ($query) {
		$return = array();

		$perpage = Config::settings('per_page');
		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;
		$start = ($page - 1) * $perpage;
		
		$listing_condition = $this->build_listing_condition($query);
		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$items = Globals::db()->get_full_vector('post', $condition);
		
		$index = array();
		
		foreach ($items as $id => $item) {
			$this->items[$id] = new Item_Post($item);
			$index[$id] = $item['meta'];
		}
		unset ($items);

		$keys = array_keys($this->items);
		$post_subitems = $this->get_subitems($keys);
		
		$meta = Meta::prepare_meta($index);

		foreach ($this->items as $id => & $item) {
			$item = Transform_Item::merge($item, $post_subitems[$id], $meta[$id]);
		}
		
		$count = Globals::db()->get_count('post', $listing_condition);
		
		$this->items[] = new Item_Navi(array(
			'curr_page' => $page,
			'pagecount' => ceil($count / $perpage),
			'query' => $query,
			'module' => 'post',
		));
	}
	
	protected function get_subitems ($ids) {
		$ids = (array) $ids;

		$condition = Globals::db()->array_in('item_id',$ids);

		$items = Globals::db()->get_table('post_items', 'item_id,type,sort_number,data', $condition, $ids);

		$return = array();

		if (!empty($items)) {
			foreach ($items as $item) {
				$data = Crypt::unpack($item['data']);

				if (empty($data['url']) && empty($data['file'])) {
					continue;
				}

				if ($item['type'] != 'link') {
					$return[$item['item_id']][$item['type']][$item['sort_number']] = $data;
				} else {
					$crc = crc32($data['name'].'&'.$data['size'].'&'.$data['sizetype']);

					$link = & $return[$item['item_id']]['link'][$crc];

					if (!empty($link)) {
						$link['url'][$data['url']] = $data['alias'];
					} else {
						$link = $data;
						$link['url'] = array($link['url'] => $link['alias']);
					}
				}
			}
		}

		return $return;
	}
}
