<?

class Art_Output extends Module_Output implements Plugins
{
	public function single ($query) {
		$art = Globals::db()->get_row('art', $query['id']);
		$this->test_area($art['area']);	
		
		$art['date'] = Globals::db()->date_to_unix($art['date']);
		
		$meta = Meta::prepare_meta(array($art['id'] => $art['meta']));

		$return['items'] = array(
			$art['id'] => array_merge(
				$art, 
				current($meta),
				array('translations' => current($this->get_translations($art['id']))),
				array('groups' => current($this->get_groups($art['id']))),
				array('variations' => current($this->get_variations($art['id'])))
			),
		);
		
		$return['items'] = $this->mark_item_types($return['items'], 'art');		

		return $return;
	}

	public function main ($query) {
		$return = array();

		$perpage = Config::settings('per_page');

		$page = isset($query['page']) && $query['page'] > 0 ? $query['page'] : 1;

		$start = ($page - 1) * $perpage;

		$listing_condition = $this->build_listing_condition($query);

		$condition = $listing_condition . " order by date desc limit $start, $perpage";

		$return['items'] = Globals::db()->get_full_vector('art', $condition);

		$keys = array_keys($return['items']);
		$index = array();

		foreach ($return['items'] as $id => & $art) {
			$index[$id] = $art['meta'];
			unset($art['date']);
		}

		$meta = Meta::prepare_meta($index);

		$return['items'] = array_replace_recursive($return['items'], $meta);
		$return['items'] = $this->mark_item_types($return['items'], 'art_in_list');

		$count = Globals::db()->get_count('art', $listing_condition);

		$return['curr_page'] = $page;
		$return['pagecount'] = ceil($count / $perpage);

		return $return;
	}
	
	protected function get_translations ($ids) {
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
