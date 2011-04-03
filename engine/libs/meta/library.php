<?

class Meta_Library implements Plugins
{	
	public static function parse_mixed_url($url) {
		
		return array();
	}
		
	public function get_meta_numbers($aliases, $type, $module, $area) {
		
		$condition = '`type` = ? and `module` = ? and `area` = ? and ';
		$condition .= Objects::db()->array_in('alias', $aliases);
		$params = array($type, $module, $area);
		$params = array_merge($params, $aliases);
		$found = Objects::db()->get_vector('meta_count', array('alias', 'count'), $condition, $params);

		$aliases = array_diff($aliases, array_keys($found));		
		$return = array();
		
		if (!empty($aliases)) {
			foreach ($aliases as $alias) {
				$return[$alias] = self::count_meta($alias, $type, $module, $area);
			}
		}
		
		return array_merge($return, $found);
	}
	
	public static function count_meta($alias, $type, $module, $area) {
		$condition = '`area`= ? and ';
		$search = array('+', $alias, $type);
		$condition .= Objects::db()->make_search_condition('meta', array($search));
		$count = Objects::db()->get_count($module, $condition, $area);
		
		$insert = array(
			'type' => $type,
			'alias' => $alias,
			'module' => $module,
			'area' => $area,
			'count' => $count,
			'expires' => Objects::db()->unix_to_date(time() + WEEK),
		);
		
		$dont_update = array('type', 'alias', 'module', 'area');
		
		Objects::db()->replace('meta_count', $insert, $dont_update);
	}
	
	// Алиасы для более удобного вызова самой частоиспользуемой вариации
		
	public function get_tag_numbers($aliases, $module, $area) {		
		return $this->get_meta_numbers($aliases, 'tag', $module, $area);		
	}
	
	public static function count_tag($alias, $module, $area) {
		return self::count_meta($alias, 'tag', $module, $area);		
	}
}
