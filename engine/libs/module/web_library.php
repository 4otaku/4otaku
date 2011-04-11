<?

class Module_Web_Library implements Plugins
{
	public static function get_download (& $url) {

		if (isset($url[0]) && $url[0] == 'download') {
			array_shift($url);

			return array('download' => true);
		}
	}

	public static function get_part (& $url) {
		if (isset($url[0]) && !empty($url[0])) {
			$section = array_shift($url);

			return array('part' => $section);
		}
	}

	public static function get_area (& $url) {
		$possible_areas = Config::settings('area');

		if (empty($possible_areas)) {
			return;
		}
		
		if (
			isset($url[0]) && 
			array_key_exists($url[0], $possible_areas) &&
			$possible_areas[$url[0]] != 'disabled'
		) {
			$area = array_shift($url);
			return array('area' => $area);
		}

		return array('area' => 'main');
	}

	public static function get_section (& $url) {
		$possible_sections = Config::settings('sections');

		if (empty($possible_sections)) {
			return;
		}
		
		if (isset($url[0]) && array_key_exists($url[0], $possible_sections)) {
			$section = array_shift($url);
			$return = array('section' => $section, 'function' => 'section');
			
			if (isset($url[0]) && array_key_exists($url[0], $possible_sections[$section]['items'])) {
				$subsection = array_shift($url);
				$return['subsection'] = $subsection;
			}
			
			return $return;
		}		
	}
/*	
	public static function get_tag_cloud (& $url) {
		if (isset($url[0]) && $url[0] == 'tag_cloud') {

			array_shift($url);

			return array('function' => 'tag_cloud');
		}
	}
*/
	public static function get_mixed (& $url) {

		if (isset($url[0]) && $url[0] == 'mixed' && isset($url[1])) {
			
			$mixed = Meta::parse_mixed_url($url[1]);
			array_splice($url, 0, 2);

			if (array_key_exists('mixed', Globals::$preferences)) {
				$mixed = array_replace_recursive(Globals::$preferences['mixed'], $mixed);
			}			

			return array('mixed' => $mixed, 'function' => 'main');
		}
	}

	public static function get_meta (& $url) {
		$meta_types = array('tag', 'category', 'language', 'author');

		if (isset($url[0]) && in_array($url[0], $meta_types) && isset($url[1])) {

			$meta = array_splice($url, 0, 2);

			return array('meta' => $meta[0], 'alias' => $meta[1], 'function' => 'main');
		}
	}

	public static function get_pool (& $url) {
		if (isset($url[0]) && $url[0] == 'pool' && isset($url[1])) {

			$meta = array_splice($url, 0, 2);

			return array('meta' => $meta[0], 'alias' => $meta[1], 'function' => 'pool', 'area' => false);
		}
	}

	public static function get_pack (& $url) {
		if (isset($url[0]) && $url[0] == 'cg_pack' && isset($url[1])) {

			$meta = array_splice($url, 0, 2);

			return array('meta' => $meta[0], 'alias' => $meta[1], 'function' => 'pack', 'area' => false);
		}
	}

	public static function get_page (& $url) {
		if (isset($url[0]) && $url[0] == 'page' && isset($url[1]) && is_numeric($url[1])) {

			$page = array_splice($url, 0, 2);

			return array('page' => end($page), 'function' => 'main');
		}
	}

	public static function get_id (& $url) {

		if (isset($url[0]) && is_numeric($url[0])) {

			$id = array_shift($url);

			return array('id' => $id, 'function' => 'single');
		}
	}
}
