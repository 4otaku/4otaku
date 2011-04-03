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
	
	public function get_tag_numbers($aliases, $module, $area = false) {
		
		Cache::$prefix = self::CACHE_COUNT_PREFIX.(string) $module.'_'.(string) $area.'_';		
		$cached = Cache::get_array($aliases);

		$aliases = array_diff($aliases, array_keys($cached));		
		$return = array();
		
		if (!empty($aliases)) {
			foreach ($aliases as $alias) {
				$condition = '`area`= ? and ';
				$search = array('+', $alias, 'tag');
				$condition .= Objects::db()->make_search_condition('meta', array($search));
				$return[$alias] = Objects::db()->get_count($module, $condition, $area);
			}
			
			Cache::set_array(array_keys($return), array_values($return), WEEK);
		}
		
		return array_merge($return, $cached);
	}
}
