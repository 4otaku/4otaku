<?

class Fetch_Tag implements Plugins
{
	const CACHE_COUNT_PREFIX = '_tag_count_';
	
	public function get_data_by_alias($aliases) {
		
		$condition = Objects::db()->array_in('alias', $aliases);
		$full_condition = "type='tag' and ".$condition;

		$select = array('alias','name','color');

		$tags = Objects::db()->get_vector('meta', $select, $full_condition, $aliases, false);
		
		if (empty($tags)) {
			return array();
		}

		$variants = Objects::db()->get_full_table('tag_variants', $condition, $aliases);

		foreach ($variants as $variant) {
			$tags[$variant['alias']]['variants'][] = $variant['variant'];
		}
		
		$numbers = $this->get_tag_numbers($aliases, Globals::$query['module'], Globals::$query['area']);
		
		foreach ($numbers as $alias => $number) {
			$tags[$alias]['count'] = $number;
		}		

		return $tags;
	}
	
	public function get_tag_numbers($aliases, $module, $area) {
		
		Cache::$prefix = self::get_tag_count_prefix($module, $area);
		$cached = Cache::get_array($aliases);

		$aliases = array_diff($aliases, array_keys($cached));		
		$return = array();
		
		if (!empty($aliases)) {
			foreach ($aliases as $alias) {
				$return[$alias] = self::count_tag($alias, $module, $area);
			}
			
			Cache::set_array(array_keys($return), array_values($return), WEEK);
		}
		
		return array_merge($return, $cached);
	}
	
	public static function count_tag($alias, $module, $area) {
		$condition = '`area`= ? and ';
		$search = array('+', $alias, 'tag');
		$condition .= Objects::db()->make_search_condition('meta', array($search));
		return Objects::db()->get_count($module, $condition, $area);		
	}
	
	public static function get_tag_count_prefix($module, $area) {
		return self::CACHE_COUNT_PREFIX.(string) $module.'_'.(string) $area.'_';		
	}
}
