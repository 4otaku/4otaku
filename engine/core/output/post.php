<?

class Output_Post extends Output_Abstract
{	
	public function single($query) {
		$post = Globals::db()->get_row('post',$query['id']);
		
		$items = $this->call->get_items($post['id']);
		
		$return['posts'] = array(
			$post['id'] => array_merge($post, current($items))
		);

		return $return;
	}	
	
	public function listing($query) {
		$return = array();
		
		$query['start'] = 1;
		$query['end'] = 5;
		
		$start = $query['start'] - 1;
		$number = $query['end'] - $query['start'] + 1;
		
		$condition = "area = 'main' order by date desc limit $start, $number";
		
		$return['posts'] = Globals::db()->get_vector('post',$condition);
		
		$keys = array_keys($return['posts']);
		
		$items = $this->call->get_items($keys);
		
		foreach ($return['posts'] as $id => & $post) {
			$post = array_merge($post, (array) $items[$id]);
		}

		return $return;
	}
	
	public function get_items($ids) {
		$ids = (array) $ids;
		
		$condition = "item_id in (".str_repeat('?,',count($ids)-1)."?)";
		
		$items = Globals::db()->get_table('post_items',$condition,'item_id,type,sort_number,data',$ids);
		
		$return = array();
		
		if (!empty($items)) {
			foreach ($items as $item) {
				if ($item['type'] != 'link') {
					$return[$item['item_id']][$item['type']][$item['sort_number']]
						 = Crypt::unpack_array($item['data']);
				} else {
					$data = Crypt::unpack_array($item['data']);
					
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
