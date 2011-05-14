<?

class Sidebar_Output extends Output implements Plugins
{
	public function main ($query) {	
		Config::load(__DIR__.SL.'settings.ini', true);
		
		$parts = Globals::user('sidebar', 'parts');
		
		uksort($parts, array($this, 'position_sort'));
		
		foreach ($parts as $function => $settings) {

			if ((bool) $settings['enabled']) {
			
				$this->items[$function] = new Item_Sidebar_Block(array(
					'items' => $this->$function($settings), 
					'header' => !empty($settings['header']) ? 
						$settings['header'] : '',
				));
			}
		}
	}
	
	protected function position_sort ($a, $b) {	
		return ($a['position'] < $b['position']) ? 1 : -1;
	}
	
	protected function comments ($settings) {
		$limit = $settings['count'];
		
		$params = array('deleted');
		$condition = "area != ? group by place, item_id order by max(date) desc limit $limit";

		$comments = Database::get_full_table('comment', $condition, $params);
		
		$return = array();
		
		foreach ($comments as $comment) {
			$return[] = new Item_Comment_Block(array(
				'items' => array(new Item_Comment($comment)),
				'place' => $comment['place'],
				'id' => $comment['item_id'],				
			));
		}
		
		return $return;
	}
	
	protected function search ($settings) {}
}
