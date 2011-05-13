<?

class Query_Library implements Plugins
{
	public static function download (& $url) {

		if (isset($url[0]) && $url[0] == 'download') {
			array_shift($url);

			return array('download' => true);
		}
	}

	public static function part (& $url) {
		if (isset($url[0]) && !empty($url[0])) {
			$section = array_shift($url);

			return array('part' => $section);
		}
	}

	public static function area (& $url) {
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

	public static function section (& $url) {
		$possible_sections = Config::settings('sections');

		if (empty($possible_sections)) {
			return;
		}
		
		if (isset($url[0]) && array_key_exists($url[0], $possible_sections)) {
			$section = array_shift($url);
			$return = array('section' => $section, 'function' => 'section');
			
			if (
				isset($url[0]) && 
				isset($possible_sections[$section]['items']) && 
				array_key_exists($url[0], $possible_sections[$section]['items'])
			) {
				$subsection = array_shift($url);
				$return['subsection'] = $subsection;
			}
			
			return $return;
		}		
	}

	public static function date (& $url) {
		$return = array(
			'year' => date('Y'),
			'month' => date('n'),
			'day' => date('j'),
		);
		
		if (isset($url[0]) && is_numeric($url[0])) {
			$return['year'] = array_shift($url);
		}
		
		if (isset($url[0]) && is_numeric($url[0])) {
			$return['month'] = array_shift($url);
		}
		
		if (isset($url[0]) && is_numeric($url[0])) {
			$return['day'] = array_shift($url);
		}
		
		return $return;
	}
/*	
	public static function get_tag_cloud (& $url) {
		if (isset($url[0]) && $url[0] == 'tag_cloud') {

			array_shift($url);

			return array('function' => 'tag_cloud');
		}
	}
*/
	public static function mixed (& $url) {

		if (isset($url[0]) && $url[0] == 'mixed' && isset($url[1])) {
			
			$mixed = Meta::parse_mixed_url($url[1]);
			array_splice($url, 0, 2);

			if (array_key_exists('mixed', Globals::$preferences)) {
				$mixed = array_replace_recursive(Globals::$preferences['mixed'], $mixed);
			}			

			return array('mixed' => $mixed, 'function' => 'main');
		}
	}

	public static function meta (& $url) {
		$meta_types = array('tag', 'category', 'language', 'author');

		if (isset($url[0]) && in_array($url[0], $meta_types) && isset($url[1])) {

			$meta = array_splice($url, 0, 2);

			return array('meta' => $meta[0], 'alias' => $meta[1], 'function' => 'main');
		}
	}

	public static function pool (& $url) {
		if (isset($url[0]) && $url[0] == 'pool') {
			if (isset($url[1]) && is_numeric($url[1])) {
				
				$meta = array_splice($url, 0, 2);				
				return array(
					'meta' => $meta[0], 
					'alias' => $meta[1], 
					'submodule' => 'pool', 
					'area' => false,
					'function' => 'group',
				);
			} else {
				
				array_shift($url);
				return array(
					'submodule' => 'pool',
					'function' => 'index'
				);
			}
		}
	}

	public static function pack (& $url) {
		if (isset($url[0]) && $url[0] == 'cg_pack') {
			if (isset($url[1]) && is_numeric($url[1])) {
				
				$meta = array_splice($url, 0, 2);				
				return array(
					'meta' => $meta[0], 
					'alias' => $meta[1], 
					'submodule' => 'pack', 
					'area' => false,
					'function' => 'group',
				);
			} else {
				
				array_shift($url);
				return array(
					'submodule' => 'pack', 
					'function' => 'index',
				);
			}
		}
	}

	public static function links (& $url) {
		if (isset($url[0]) && $url[0] == 'links') {
				
			array_shift($url);
			return array('submodule' => 'link');
		}
	}

	public static function page (& $url) {
		if (isset($url[0]) && $url[0] == 'page' && isset($url[1]) && is_numeric($url[1])) {

			$page = array_splice($url, 0, 2);

			return array('page' => end($page));
		}
	}

	public static function comment_page (& $url) {
		if (
			isset($url[0]) && $url[0] == 'comments' && 
			isset($url[1]) && $url[1] == 'all'
		) {

			array_splice($url, 0, 2);

			return array('comment_page' => 'all');
		}
		
		if (
			isset($url[0]) && $url[0] == 'comments' && 
			isset($url[1]) && $url[1] == 'page' &&
			isset($url[2]) && is_numeric($url[2])
		) {

			$page = array_splice($url, 0, 3);

			return array('comment_page' => end($page));
		}		
	}

	public static function id (& $url) {

		if (isset($url[0]) && is_numeric($url[0])) {

			$id = array_shift($url);

			return array('id' => $id, 'function' => 'single');
		}
	}

	public static function name (& $url) {

		if (isset($url[0]) && !is_numeric($url[0])) {

			$name = array_shift($url);

			return array('name' => $name, 'function' => 'single');
		}
	}
	
	public static function board (& $url) {
		if (isset($url[0])) {

			$board = array_shift($url);

			return array('meta' => 'category', 'alias' => $board);
		}		
	}
		
	public static function thread (& $url) {
		if (isset($url[0]) && $url[0] == 'thread' && isset($url[1]) && is_numeric($url[1])) {
			
			$thread = array_splice($url, 0, 2);
			
			return array('id' => end($thread), 'function' => 'single');
		}
	}
			
	public static function boards_new (& $url) {
		if (isset($url[0]) && $url[0] == 'new' && isset($url[1])) {

			$date = array_splice($url, 0, 2);

			return array('type' => 'new', 'date' => end($date));
		}				
	}
				
	public static function boards_updated (& $url) {
		if (isset($url[0]) && $url[0] == 'updated' && isset($url[1])) {

			$date = array_splice($url, 0, 2);

			return array('type' => 'updated', 'date' => end($date));
		}			
	}
}
