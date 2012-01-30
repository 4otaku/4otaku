<?

class dynamic__box
{
	function rss () {
		return true;
	}

	function faq () {
		return true;
	}

	function settings () {
		return true;
	}

	function wakaba () {
		return true;
	}

	function add_personal_menu () {
		return true;
	}

	function edit_personal_menu () {
		$id = (int) query::$get['id'];

		$return = Database::get_full_row('head_menu_user', $id);

		if (!empty($return)) {
			$return['count'] = Database::get_count('head_menu_user',
				'cookie = ?',
				query::$cookie
			);

			if ($return['url']{0} == '/') {
				$return['url'] = 'http://'.def::site('domain').$return['url'];
			}
		}

		return $return;
	}

	function login () {
		return true;
	}

	function change_pass () {
		return true;
	}

	function remind_pass () {
		return true;
	}

	function register () {
		return true;
	}

	function plugins () {

		$plugins = Database::get_full_vector('plugin');

		$used = sets::array_get('plugins');

		foreach ($plugins as $id => &$plugin) {
			$plugin['on'] = !empty($used[$id]);
		}

		return $plugins;
	}
}
