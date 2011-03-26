<?

class Module_Web_Library implements Plugins
{
	public static function get_download (& $url) {

		if (isset($url[0]) && $url[0] == 'download') {
			array_shift($url);

			return array('download' => true);
		}
	}

	public static function get_area (& $url) {
		if (isset($url[0])) {
			if (
				$url[0] == 'workshop' ||
				$url[0] == 'flea' ||
				$url[0] == 'sprites'
			) {
				$area = array_shift($url);

				return array('area' => $area);
			}
		}

		return array('area' => 'main');
	}

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
