<?

class dynamic__menu
{
	public function get () {
		$return = array();

		$return['items'] = Database::get_vector('head_menu_user',
			array('id', 'url', 'name'),
			'cookie = ? order by `order`',
			query::$cookie
		);
		$return['nonempty'] = true;

		return $return;
	}

	public function add () {

		$order = Database::get_field('head_menu_user',
			'max(`order`)', 'cookie = ?', query::$cookie);

		Database::insert('head_menu_user', array(
			'cookie' => query::$cookie,
			'name' => query::$get['name'],
			'url' => query::$get['url'],
			'order' => (int) $order + 1
		));
	}

	public function edit () {

		$id = (int) query::$get['id'];

		Database::update('head_menu_user', array(
			'name' => query::$get['name'],
			'url' => query::$get['url']
		), $id);

		self::make_order(query::$cookie, $id, query::$get['order']);
	}

	public function delete () {

		$id = (int) query::$get['id'];

		Database::delete('head_menu_user', $id);
		self::make_order(query::$cookie);
	}

	protected static function make_order ($cookie, $change_id = false, $change_order = false) {

		$items = Database::get_table('head_menu_user',
			array('id', 'order'),
			'cookie = ? order by `order`',
			$cookie
		);

		if ($change_id && $change_order) {
			foreach ($items as &$item) {
				if ($item['id'] == $change_id) {
					$item['new_order'] = $change_order;
				}
			}
			unset($item);
		}

		$new_order = 1;
		foreach ($items as &$item) {
			if (!empty($item['new_order'])) {
				continue;
			}

			if ($new_order == $change_order) {
				$new_order++;
			}

			$item['new_order'] = $new_order;
			$new_order++;
		}
		unset($item);

		foreach ($items as $item) {
			if ($item['new_order'] != $item['order']) {
				Database::update('head_menu_user', array(
					'order' => $item['new_order']
				), $item['id']);
			}
		}
	}
}
