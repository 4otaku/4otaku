<?

class Tags_Output extends Output implements Plugins
{	
	const CACHE_PREFIX = '_tag_cache_items_';
	
	protected $count = 0;
	
	public function main () {
		$sections = Config::settings('sections');
		
		$query['section'] = current($sections);
		
		return $this->section($query);
	}
	
	public function section ($query) {
		$return = array();
		
		$type = $query['section'];
		if (empty($query['subsection'])) {
			$items = Config::settings('sections', $type, 'items');
			$area = current($items);
		} else {
			$area = $query['subsection'];
		}

		Cache::$prefix = self::CACHE_PREFIX;
		
		if (!($items = Cache::get($type.'_'.$area))) { 
		
			$items = $this->get_full_tag_cloud($type, $area);
			
			Cache::set($type.'_'.$area, $items, DAY);
		}
		
		$this->flags['area'] = $area;
		$this->flags['type'] = $type;
	}
	
	protected function get_full_tag_cloud ($type, $area) {
		$meta = Database::get_vector('meta', array('alias', 'name', 'color'), '`type` = tag');
		$aliases = array_unique(array_keys($meta));
		
		$count_worker = new Meta_Library();
		$data = $count_worker->get_meta_numbers($aliases, 'tag', $type, $area);
		$data = array_filter($data);

//		arsort($data);

		foreach ($data as $alias => & $one) {
			
			$one = array(
				'alias' => $alias,
				'count' => $one,
				'color' => $meta[$alias]['color'],
				'name' => $meta[$alias]['name'],
				'item_type' => 'tag',
			);
		}
		unset($one);

		return array_values($data);
	}
}
