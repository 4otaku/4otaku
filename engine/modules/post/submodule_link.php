<?

class Post_Submodule_Link extends Output implements Plugins
{
	protected static $broken = 0;
	protected static $unclear = 0;
	
	public function main () {		
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
			
			if (empty($this->items[$full_id])) {				
				$this->items[$full_id] = New Item_Post_Brokenlinks (array(
					'id' => $link['item_id'],
					'title' => $titles[$link['item_id']],
					'links' => array(),
				), $status);
			}			
			
			$this->items[$full_id]->add_to('links', Crypt::unpack($link['data']));
		}
		
		uksort($this->items, 'strnatcasecmp');
	
		return $return;
	}
	
	public static function description () {
		return array(
			'broken_total' => self::$broken,
			'unclear_total' => self::$unclear,
		);
	}
}
