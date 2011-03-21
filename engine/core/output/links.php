<?

class Output_Links extends Output_Abstract
{
	public function listing () {
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
}
