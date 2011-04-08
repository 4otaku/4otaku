<?

class Links_Output extends Module_Output implements Plugins
{
	protected static $broken = 0;
	protected static $unclear = 0;
	
	public function main () {
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
		
		$return['items'] = array();
		
		foreach ($links as $link) {
			$status = $link['status'];			
			$full_id = $status.'-'.$link['item_id'];
	
			if ($status == 'broken') {
				self::$broken++;
			} elseif ($status == 'unclear') {
				self::$unclear++;
			}
			
			if (empty($return['items'][$full_id])) {
				$return['items'][$full_id] = array(
					'item_type' => 'links_'.$status,
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
	
	public static function description () {
		return array(
			'broken_total' => self::$broken,
			'unclear_total' => self::$unclear,
		);
	}
}
