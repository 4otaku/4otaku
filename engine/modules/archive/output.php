<?

class Archive_Output extends Output implements Plugins
{	
	const CACHE_PREFIX = '_archive_cache_items_';
	
	protected $count = 0;
	
	public function main () {}
	
	public function section ($query) {
		$return = array();
		
		$type = $query['section'];
		if (empty($query['subsection'])) {
			$items = Config::settings('subsections', $type, 'items');
			$group_by = current($items);
		} else {
			$group_by = $query['subsection'];
		}

		Cache::$prefix = self::CACHE_PREFIX;
		
		if (!($items = Cache::get($type.'_'.$group_by))) { 
		
			if ($group_by == 'date') {
				$items = $this->get_archive_by_date($type); 
			} else { 
				$items = $this->get_archive_by_meta($type, $group_by); 
			}
			
			Cache::set($type.'_'.$group_by, $items, DAY);
		}
		
		foreach ($items as $key => $item) {
			$this->items[$key] = $group_by == 'date' ? 
				new Item_Archive_Date ($item) :
				new Item_Archive_Meta ($item);
		}
		
		$this->flags['meta_type'] = $group_by;
		$this->flags['list_type'] = $type;
		$this->flags['count'] = Database::get_count($type, '`area` = "main"');
	}
	
	protected function get_archive_by_date ($type) {
		switch ($type) {
			case 'post': 
			case 'video':
				$fields = array('id', 'title', 'date', 'comments');
				$condition = '`area` = "main"';
				break;
			case 'art':
				$fields = 'count(*) as count, year(`date`) as year, month(`date`) as month, day(`date`) as day';
				$condition = '`area` = "main" group by year(`date`), month(`date`), day(`date`)';
				break;
			default: Error::fatal("Не умею выводить архив для $type");
		}
		
		$data = Database::get_table($type, $fields, $condition);
		$return = array();

		switch ($type) {
			case 'post': 
			case 'video':
				foreach ($data as $item) {
					list($year, $month, $day) = explode('-', preg_replace('/ .+/', '', $item['date']));
					$return[$year][$month][] = array_merge($item, array('day' => $day));
				}
				break;
			case 'art':
				foreach ($data as $item) {
					$return[$item['year']][$item['month']][$item['day']] = $item;
				}
				break;
			default: Error::fatal("Не умею выводить архив для $type");
		}
		
		return $return;
	}
	
	protected function get_archive_by_meta ($type, $meta_name) {
		$meta = Database::get_vector('meta', array('alias', 'name'), '`type` = ?', $meta_name);
		$aliases = array_unique(array_keys($meta));
		
		$count_worker = new Meta_Library();
		$data = $count_worker->get_meta_numbers($aliases, $meta_name, $type, 'main');
		$data = array_filter($data);

		$fields = array('id', 'title', 'date', 'comments');
		
		arsort($data);

		foreach ($data as $alias => & $one) {
			
			$one = array(
				'alias' => $alias,
				'count' => $one,
				'name' => $meta[$alias],
			);
			
			if ($type != 'art') {				
				$condition = '`area`= \'main\' and ';
				$search = array('+', $alias, $meta_name);
				$condition .= Database::make_search_condition('meta', array($search));
				$one['items'] = Database::get_table($type, $fields, $condition);
			}
		}
		unset($one);

		return array_values($data);
	}
	
	public static function description () {
		$types = Config::settings('sections');

		return array(
			'types' => $types,
			'column_width' => floor(100 / count($types)),
		);
	}
}
