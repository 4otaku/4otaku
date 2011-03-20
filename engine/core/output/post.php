<?

class Output_Post extends Output_Abstract
{
	public function single ($query) {
		$post = Globals::db()->get_row('post', $query['id']);

		$items = $this->call->get_items($post['id']);

		$post['date'] = Globals::db()->date_to_unix($post['date']);

		$meta = Meta::prepare_meta(array($post['id'] => $post['meta']));

		$return['items'] = array(
			$post['id'] => array_merge($post, current($meta), current($items))
		);

		return $return;
	}

	public function listing ($query) {
		$return = array();

		$perpage = Config::settings('post', 'Amount');

		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;

		$start = ($page - 1) * $perpage;

		$listing_condition = $this->call->build_listing_condition($query);

		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$return['items'] = Globals::db()->get_full_vector('post', $condition);

		$keys = array_keys($return['items']);
		$index = array();

		$items = $this->call->get_items($keys);

		foreach ($return['items'] as $id => & $post) {
			$post = array_merge($post, (array) $items[$id]);

			$index[$id] = $post['meta'];

			$post['date'] = Globals::db()->date_to_unix($post['date']);
		}

		$meta = Meta::prepare_meta($index);

		$return['items'] = array_replace_recursive($return['items'], $meta);

		$count = Globals::db()->get_count('post', $listing_condition);

		$return['curr_page'] = $page;
		$return['pagecount'] = ceil($count / $perpage);

		return $return;
	}
	
	public function links () {
		$return = array();
		
		$fields = array('item_id', 'data', 'status');
		
		$links = Globals::db()->get_table('post_items', $fields, "status != 'ok'");
		
		$ids = array();		
		foreach ($links as $link) {
			$ids[] = $link['item_id'];
		}
		
		$ids = array_unique($ids);		
		$condition = Globals::db()->array_in('id',$ids);		
		$titles = Globals::db()->get_vector('post', 'id,title', $condition, $ids);
		
		foreach ($links as $link) {
			$full_id = $link['status'].'-'.$link['item_id'];
			
			if (empty($return['items'][$full_id])) {
				$return['items'][$full_id] = array(
					'item_type' => 'links_'.$link['status'],
					'id' => $link['item_id'],
					'title' => $titles[$link['item_id']],
					'links' => array(),
				);
			}
			
			$return['items'][$full_id]['links'][] = Crypt::unpack($link['data']);
		}
		
		uksort($return['items'], 'strnatcasecmp');
		
		return $return;
	}

	public function get_items ($ids) {
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
