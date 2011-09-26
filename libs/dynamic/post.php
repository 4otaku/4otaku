<?

class dynamic__post
{
	public function show_updates() {

		if (Check::num(query::$get['id'])) {

			$return = (array) Database::get_full_table('updates',
				'post_id = ?', query::$get['id']);

			foreach ($return as &$update) {
				$update['link'] = unserialize($update['link']);
			}

			return $return;
		}

		return array();
	}
}
