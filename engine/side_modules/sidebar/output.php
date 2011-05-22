<?

class Sidebar_Output extends Output implements Plugins
{
	protected $query = array();
	
	public function main ($query) {	
		$this->query = $query;
		
		Config::load(__DIR__.SL.'settings.ini', true);
		
		$parts = Globals::user('sidebar', 'parts');

		uksort($parts, 'strnatcmp');
		
		foreach ($parts as $settings) {

			$function = $settings['type'];

			if ((bool) $settings['enabled'] && method_exists($this, $function)) {
			
				$this->items[$function] = new Item_Sidebar_Block(array(
					'items' => $this->$function($settings), 
					'header' => !empty($settings['header']) ? 
						$settings['header'] : '',
				));
			}
		}
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
	
	protected function responses ($settings) {}
	
	protected function tags ($settings) {

		if (Config::settings('meta', 'tag') == 'enabled') {
			$module = $this->query['module'];
		} else {
			$module = $settings['default']['module'];
		}
		
		if (!empty($this->query['area'])) {
			$area = $this->query['area'];
		} else {
			$area = $settings['default']['area'];
		}
		
		$tags = Tags_Output::get_partial_tag_cloud(
			$module, 
			$area, 
			$settings['count'], 
			$settings['size']['minimum'], 
			$settings['size']['maximum']
		);
		
		foreach ($tags as & $tag) {
			$tag['flag'] = array(
				'type' => $module,
				'area' => $area
			);
		}
		
		return $tags;
	}
	
	public function make_subquery ($query, $module) {
		$query['module'] = $module;
		unset($query['function']);
		
		return $query;
	}	
}
