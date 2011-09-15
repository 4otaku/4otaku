<?

class dynamic__menu
{
	function add () {

		$order = Database::get_field('head_menu_user',
			'max(`order`)', 'cookie = ?', query::$cookie);

		Database::insert('head_menu_user', array(
			'cookie' => query::$cookie,
			'name' => query::$get['name'],
			'url' => query::$get['url'],
			'order' => (int) $order + 1
		));
	}
}
