<?

class dynamic__edit extends engine
{
	public $template;
	public $postparse;
	public $textarea = false;

	public function show () {

		$url = preg_replace('/\?[^\/]+$/','', query::$get['path']);
		query::$url = array_filter(explode('/', $url));
		unset(query::$url[0]);
		if (!query::$url[1]) {
			query::$url[1] = 'index';
		}

		$output = 'output__'.query::$get['type'];
		$output = new $output();

		$data = array('main' => array());

		switch (query::$get['type']) {
			case 'video':
				if (query::$get['num']) {
					$size = sets::video('full');
				} else {
					$size = sets::video('thumb');
				}

				$data['main']['video'] =
					$output->get_video(1, 'id='.query::$get['id'], $size);
				$this->template = 'templates/main/video.php';
				break;
			case 'order':
				$data['main'] =
					$output->order_single(query::$get['id']);
				$this->template = 'templates/main/order/single.php';
				break;
			case 'art':
				$data['main']['art'] =
					$output->get_art(1, 'id='.query::$get['id']);
				$data['main']['art'][0]['rating'] =
					$output->get_rating(query::$get['id']);
				$data['main']['art'][0]['packs'] =
					$output->get_packs(query::$get['id']);
				$data['main']['art'][0]['pool'] =
					$output->get_pools(query::$get['id']);
				$this->template = 'templates/main/booru/single.php';
				break;
			default: die;
		}
		$data['main']['navi']['base'] = '/'.query::$get['type'].'/';

		$this->postparse = '/<div[^>]*class="innerwrap[^"]*"[^>]*>.*<\/div><!-- wrapend -->/uis';
		if (query::$get['num']) {
			$data['main']['display']['comments'] = true;
		} else {
			$data['main']['display'] = array();
		}

		return $data;
	}

	public function save () {

		if (
			!ctype_alnum(query::$post['type']) ||
			!Check::num(query::$post['id'])
		) {
			return;
		}

		$input = 'input__'.query::$post['type'];
		$func = 'edit_'.query::$post['part'];

		$input = new $input();

		if (query::$post['type'] == 'order') {
			query::$post['type'] = 'orders';
		}

		$old_data = Database::get_full_row(query::$post['type'], query::$post['id']);
		$input->$func();
		$new_data = Database::get_full_row(query::$post['type'], query::$post['id']);

		if ($old_data != $new_data) {

			unset($new_data['id']);
			Database::update('search', array('lastupdate' => 0),
				'place = ? and item_id = ?',
				array(query::$post['type'], query::$post['id']));

			if (query::$post['type'] == 'orders') {
				query::$post['type'] = 'order';
			}

			Database::insert('versions',array(
				'type' => query::$post['type'],
				'item_id' => query::$post['id'],
				'data' => base64_encode(serialize($new_data)),
				'time' => ceil(microtime(true)*1000),
				'author' => sets::user('name'),
				'ip' => $_SERVER['REMOTE_ADDR']));
		}
	}

	public function remove_from_pool () {

		if (
			!Check::num(query::$get['id']) ||
			!Check::num(query::$get['val']) ||
			!Database::get_count('art_pool',
				'id = ? and (password = "" or password = ?)',
				array(query::$get['id'], md5(query::$get['password'])))
		) {
			return;
		}

		Database::delete('art_in_pool', 'art_id = ? and pool_id = ?',
			array(query::$get['val'], query::$get['id']));
	}

	public function sort_pool () {

		if (!Check::num(query::$get['id'])) {
			return;
		}

		$arts = array_values(array_unique(query::$post['art']));
		$id = query::$get['id'];

		if (
			count($arts) !=
			Database::get_count('art_in_pool', 'pool_id = ?', $id)
		) {
			return;
		}

		foreach ($arts as $order => $art) {
			Database::update('art_in_pool', array('order' => $order),
				'art_id = ? and pool_id = ?', array($art, $id));
		}
	}

	public function title () {
		global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type']))
			return array('value' => obj::db()->sql('select title from '.query::$get['type'].' where id='.query::$get['id'],2));
	}

	public function text () {
		global $check;
		$this->textarea = true;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type']))
			return array('value' => obj::db()->sql('select pretty_text from '.query::$get['type'].' where id='.query::$get['id'],2));
	}

	public function category () {
		global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type']))
			return array('value' => array_filter(explode('|',obj::db()->sql('select category from '.query::$get['type'].' where id='.query::$get['id'],2))),
						'categories' => obj::db()->sql('select name, alias from category'.(query::$get['type'] != 'orders' ? ' where locate("|'.query::$get['type'].'|",area)' : '').' order by id','alias'));
	}

	public function language () {
		global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type']))
			return array('value' => array_filter(explode('|',obj::db()->sql('select language from '.query::$get['type'].' where id='.query::$get['id'],2))),
						'languages' => obj::db()->sql('select name, alias from language order by id','alias'));
	}

	public function tag () {
		global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) {
			$return['value'] = array_unique(array_filter(explode('|',obj::db()->sql('select tag from '.query::$get['type'].' where id='.query::$get['id'],2))));
			$return['colors'] = array();

			$meta = obj::db()->sql('select alias, name, color from tag where alias="'.implode('" or alias="',$return['value']).'"','alias');
			foreach ($return['value'] as &$one) {
				if (!empty($meta[$one])) {
					$one = $meta[$one]['name'];
					$return['colors'][$meta[$one]['name']] = $meta[$one]['color'];
				}
			}
		}
		uasort($return['value'], 'transform__array::meta_sort');
		$return['area'] = query::$get['type'];
		return $return;
	}

	public function author () {
		 global $check;
		if ($check->num(query::$get['id']) && $check->lat(query::$get['type'])) {
			$return['value'] = array_unique(array_filter(explode('|',obj::db()->sql('select author from '.query::$get['type'].' where id='.query::$get['id'],2))));
			$meta = obj::db()->sql('select alias, name from author where alias="'.implode('" or alias="',$return['value']).'"','alias');
			foreach ($return['value'] as &$one) if ($meta[$one]) $one = $meta[$one];
		}
		return $return;
	}

	public function orders_username () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'orders')
			return array('value' => obj::db()->sql('select username from orders where id='.query::$get['id'],2));
	}

	public function orders_mail () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'orders')
			return array('value' => obj::db()->sql('select email, spam from orders where id='.query::$get['id'],1));
	}

	public function art_source () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art')
			return array('value' => obj::db()->sql('select source from art where id='.query::$get['id'],2));
	}

	public function art_image () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art')
			return true;
	}

	public function art_groups () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art') {
			$return = obj::db()->sql('select id, name from art_pool','id');
			asort($return);
			return $return;
		}
	}

	public function art_variation () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art')
			return true;
	}

	public function art_variation_list () {
		if (!Check::num(query::$get['id']) || query::$get['type'] != 'art') {
			return false();
		}

		$return = (array) Database::order('order', 'ASC')
			->get_full_table('art_variation', 'art_id = ?', query::$get['id']);

		$image = Database::get_full_row('art', query::$get['id']);

		array_unshift($return, $image);

		return $return;
	}

	public function art_translations () {
		global $check;
		if ($check->num(query::$get['id']) && query::$get['type'] == 'art')
			return true;
	}

	public function comment () {
		global $check;
		if (!$check->num(query::$get['id'])) {
			return false;
		}

		$return = obj::db()->sql('select pretty_text,username,cookie from comment where id='.query::$get['id'],1);

		if (empty($return)) {
			return false;
		}

		if (query::$cookie != $return['cookie'] && !$check->rights()) {
			return false;
		}

		return $return;
	}
}
