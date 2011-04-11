<?

abstract class Module_Output implements Plugins
{
	protected function mark_item_types ($items, $type) {
		if (!empty($items) && is_array($items)) {
			foreach ($items as & $item) {
				if (empty($item['item_type'])) {
					$item['item_type'] = $type;
				}
			}
		}

		return $items;
	}

	protected function build_listing_condition ($query) {
		$condition = "area = '{$query['area']}'";

		if (!empty($query['meta']) && !empty($query['alias'])) {
			$search = array('+', $query['alias'], $query['meta']);
			$condition .= " and ".Objects::db()->make_search_condition('meta', array($search));
		}

		return $condition;
	}

	protected function test_area ($area) {
		$url = Globals::$url;
		
		if (
			empty($url[2]) || 
			empty($area) ||
			$url[2] == $area || 
			(is_numeric($url[2]) && $area == 'main')
		) {
			return;
		}
		
		$possible_areas = Config::settings('area');
		
		if (
			array_key_exists($url[2], $possible_areas) &&
			$possible_areas[$url[2]] != 'disabled'
		) {
			if ($area == 'main') {
				unset($url[2]);
			} else {
				$url[2] = $area;
			}
		} else {
			if ($area == 'main') {
				return;
			} else {
				$url = array_merge((array) array_shift($url), (array) $area, $url);
			}
		}

		Http::redirect('/'.implode('/', $url).'/');
	}
}
