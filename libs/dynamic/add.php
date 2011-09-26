<?
class dynamic__add
{
	protected function get_categories($area) {
		return Database::get_vector('category',
			'alias, name',
			'locate(?, area) order by id',
			'|'.$area.'|');
	}

	protected function get_languages() {
		return Database::get_vector('language',
			'alias, name',
			'1 order by id');
	}

	public function art() {
		return array('category' => $this->get_categories('art'));
	}

	public function video() {
		return array('category' => $this->get_categories('video'));
	}

	public function post() {
		return array(
			'category' => $this->get_categories('post'),
			'language' => $this->get_languages()
		);
	}

	public function board() {
		return array('category' => $this->get_categories('board'));
	}

	public function order() {
		return array('category' => $this->get_categories('post'));
	}

	public function replay() {
		return true;
	}

	public function soku() {
		return true;
	}

	public function comment() {
		return true;
	}

	public function pool() {
		return true;
	}

	public function update() {

		if (!Check::num(query::$get['id'])) {
			return array();
		}

		$links = Database::get_field('post', 'link', query::$get['id']);
		return unserialize($links);
	}

	public function checkpassword() {
		if (Check::num(query::$get['id'])) {

			$pass = Database::get_field('art_pool', 'password', query::$get['id']);
			if ($pass == md5(query::$get['val'])) {

				echo 'success';
				return;
			}
		}

		echo 'fail';
	}
}
