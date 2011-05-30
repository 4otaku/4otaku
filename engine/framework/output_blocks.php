<?

abstract class Output_Blocks extends Output implements Plugins
{
	protected $query = array();
	
	public function main ($query, $config = 'settings') {	
				
		$this->query = $query;		
		
		$parts = Globals::user($config, 'blocks');

		uksort($parts, 'strnatcmp');
		
		foreach ($parts as $settings) {

			$function = $settings['type'];

			if (!empty($settings['enabled']) && method_exists($this, $function)) {
			
				$this->items[$function] = new Item_Block(array(
					'items' => $this->$function($settings), 
				), $settings);
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
	
	protected function posts ($settings) {

		return $this->content($settings, 'post');
	}
	
	protected function video ($settings) {

		return $this->content($settings, 'video');
	}
	
	protected function art ($settings) {

		return $this->content($settings, 'art');
	}		
	
	protected function content ($settings, $type) {

		$return = array();
		
		if (empty($type)) {
			return $return;
		}
		
		$worker = 'Output_'.ucfirst($type);
		$worker = new $worker();
		
		return $return;
	}	
}
