<?

class Archive_Output extends Module_Output implements Plugins
{	
	const CACHE_PREFIX = '_archive_cache_items_';
	
	public function main () {
		$return = array();
	
		return $return;
	}
	
	public function section () {
		$return = array();
		
		$type = Globals::$query['section'];
		if (empty(Globals::$query['subsection'])) {
			$items = Config::settings('subsections', $type, 'items');
			$group_by = current($items);
		} else {
			$group_by = Globals::$query['subsection'];
		}

		Cache::$prefix = self::CACHE_PREFIX;
		
		if (!($return['items'] = Cache::get($type.'_'.$group_by))) { 
		
			switch ($group_by) {
				case 'date': 
					$return['items'] = $this->get_archive_by_date($type); 
					break;
				case 'author': 
				case 'category': 
					$return['items'] = $this->get_archive_by_meta($type, $group_by); 
					break;
				default: Error::fatal("Неправильный конфиг архива");
			}
			
			Cache::set($type.'_'.$group_by, $return['items'], DAY);
		}
		
		$return['meta_type'] = $group_by;
		return $return;
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
		
		$data = Objects::db()->get_table($type, $fields, $condition);
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
		
		$return = array('years' => $return);
		$return = $this->mark_item_types(array($return), 'archive_date');
		
		return $return;
	}
	
	protected function get_archive_by_meta ($type, $meta_name) {
		$meta = Objects::db()->get_vector('meta', array('alias', 'name'), '`type` = ?', $meta_name);
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
				$condition .= Objects::db()->make_search_condition('meta', array($search));
				$one['items'] = Objects::db()->get_table($type,	$fields, $condition);
			}
		}
		unset($one);

		$return = array('data' => array_values($data));
		$return = $this->mark_item_types(array($return), 'archive_meta');
		
		return $return;			
	}
	
	public static function description () {
		$types = Config::settings('sections');
		
		return array(
			'types' => $types,
			'column_width' => floor(100 / count($types)),
		);
	}
}
