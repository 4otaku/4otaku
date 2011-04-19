<?

// Для вывода основных контентных разделов 

class Output_Main extends Output implements Plugins
{
	protected function build_listing_condition ($query) {
		$condition = $query['area'] ? "area = '{$query['area']}'" : "area != 'deleted'";

		if (!empty($query['meta']) && !empty($query['alias'])) {
			$search = array('+', $query['alias'], $query['meta']);
			$condition .= " and ".Database::make_search_condition('meta', array($search));
		}

		return $condition;
	}

	protected function test_area ($area) {
		$url = Globals::$url;

		if (
			empty($url[1]) || 
			empty($area) ||
			$url[1] == $area || 
			(is_numeric($url[1]) && $area == 'main')
		) {
			return;
		}
		
		$possible_areas = Config::settings('area');
		
		if (
			array_key_exists($url[1], $possible_areas) &&
			$possible_areas[$url[1]] != 'disabled'
		) {
			if ($area == 'main') {
				unset($url[1]);
			} else {
				$url[1] = $area;
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