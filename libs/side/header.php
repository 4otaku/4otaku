<?

class Side_Header
{
	function menu () {

		$menu = obj::db()->sql('select * from head_menu order by `order`', 'id');
		$return = array();
		if (!empty($menu)) {
			foreach ($menu as $key => $element) {
				if ($element['parent'] == 0) {
					$return[$key] = $element;
					$return[$key]['items'] = array();
					unset($menu[$key]);
				}
			}

			foreach ($menu as $key => $element) {
				if (array_key_exists($element['parent'], $return)) {
					$return[$element['parent']]['items'][$key] = $element;
				}
			}
		}

		return $return;
	}

	function personal () {

		return Database::get_vector('head_menu_user',
			array('id', 'url', 'name'),
			'cookie = ? order by `order`',
			query::$cookie
		);
	}
}
