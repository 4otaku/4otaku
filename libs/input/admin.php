<?

class input__admin extends engine
{
	function login() {
		global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		if ($rights = obj::db()->sql('select rights from user where login="'.query::$post['login'].'" and pass="'.md5(query::$post['pass']).'"',2)) {
			$cookie->inner_set('user.rights',$rights);
			$cookie->inner_set('user.admin_login',query::$post['login']);
			$sets['user']['rights'] = $rights;
		}
		else {
			$this->add_res('Вы ввели неправильный логин или пароль.',true);
		}
	}

	function logout() {
		global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		$cookie->inner_set('user.rights',0);
		$sets['user']['rights'] = 0;
	}

	function edittag() {

		Check::rights();

		if (query::$post['old_alias'] != query::$post['alias']) {
			$params = array(
				'|'.query::$post['old_alias'].'|',
				'|'.query::$post['alias'].'|',
			);

			Database::sql('update post set tag = replace(tag,?,?)', $params);
			Database::sql('update video set tag = replace(tag,?,?)', $params);
			Database::sql('update art set tag = replace(tag,?,?)', $params);
		}

		$variants = array_unique(array_filter(explode(' ',str_replace(',', ' ', query::$post['variants']))));
		if (!empty($variants)) {
			$variants = '|'.implode('|',$variants).'|';
		} else {
			$variants = '|';
		}

		Database::update('tag', array(
			'alias' => query::$post['alias'],
			'name' => query::$post['name'],
			'variants' => $variants,
			'color' => query::$post['color']
		), query::$post['id']);
	}

	function similar() {
		global $check;

		if ($check->rights()) {
			$action = explode('|', query::$post['action']);
			$from = query::$post[$action[1]{0}];
			$to = query::$post[$action[1]{1}];
			switch ($action[0]) {
				case 'delete':
					$this->delete_art($from, $to);
					break;
				case 'move_meta':
					$this->move_art_meta($from, $to);
					break;
				case 'make_similar':
					$this->make_similar($from, $to);
					break;
				case 'nondublicates':
					$this->nondublicates($from, $to);
					break;
				default:
					break;
			}
		}
	}

	private function delete_art($id, $move_to, $hard = false) {
		global $def;

		$data = obj::db()->sql('select area, tag from art where id='.$id,1);
		$tags = array_unique(array_filter(explode('|',$data['tag'])));
		if ($data['area'] == $def['area'][0] || $data['area'] == $def['area'][2])
			obj::transform('meta')->erase_tags($tags,'art_'.$data['area']);

		$this->move_art_comments($id, $move_to);

		if ($hard) {
			obj::db()->sql('delete from art where id='.$id,0);
		} else {
			obj::db()->sql('update art set area="deleted" where id='.$id,0);
		}
		obj::db()->sql('delete from art_similar where id='.$id,0);
		obj::db()->sql('update art_similar set similar=replace(similar,"|'.$id.'|","|") where id='.$move_to,0);
	}

	private function move_art_meta($from, $to) {
		$tags = obj::db()->sql('select tag from art where id='.$from,2);
		$categories = obj::db()->sql('select tag from category where id='.$from,2);
		$categories = array_filter(explode('|', $categories));

		dynamic__art::add_tag(str_replace('|', ' ', $tags), $to);
		foreach ($categories as $category) {
			dynamic__art::add_category($category, $to);
		}

		obj::db()->sql('update search set lastupdate=0 where place="art" and item_id="'.$to.'"',0);
	}

	private function move_art_comments($from, $to) {
		obj::db()->sql('update comment set post_id = '.$to.' where post_id='.$from,0);
		obj::db()->sql('update art set comment_count = (select count(*) from comment where area!="deleted" and post_id='.$to.' and place="art") where id='.$to,0);
	}

	public function make_similar($erase, $update) {
		$this->move_art_meta($erase, $update);

		$image = obj::db()->sql('select * from art where id='.$erase,1);
		$current_order = obj::db()->sql('select max(`order`) from art_variation where art_id='.$update,2);
		$next_order = (!is_numeric($current_order)) ? 0 : $current_order + 1;
		$insert = array(
			$update, $image['md5'], $image['thumb'],
			$image['extension'], $image['resized'],
			$next_order, $image['animated']
		);

		obj::db()->insert('art_variation', $insert);

		$this->delete_art($erase, $update, true);
	}

	private function nondublicates($first, $second) {
		obj::db()->sql('update art_similar set similar=replace(similar,"|'.$second.'|","|") where id='.$first,0);
		obj::db()->sql('update art_similar set similar=replace(similar,"|'.$first.'|","|") where id='.$second,0);
	}

	/* Раздел для CG-паков */

	public function pack_delete () {
		global $check;
		if ($check->rights() && query::$post['sure'] == 'on') {
			$id = query::$post['id'];

			obj::db()->sql('delete from art_pack where id='.$id,0);
			$ids = obj::db()->sql('select art_id from art_in_pack where pack_id='.$id);
			obj::db()->sql('delete from art_in_pack where pack_id='.$id,0);

			$condition = '';
			foreach ($ids as $art_id) {
				$art_id = current($art_id);
				if (!obj::db()->sql('select art_id from art_in_pack where art_id='.$art_id,2)) {
					obj::db()->sql('update art set area="deleted" where area="cg" and id='.$art_id,0);
				}
			}
		}
	}

	public function pack_edit () {
		global $check;
		if ($check->rights()) {
			$id = query::$post['id'];

			$text = obj::transform('text')->format(trim(query::$post['text']));
			$pretty_text = trim(query::$post['text']);

			$update = array(
				'title' => query::$post['name'],
				'text' => $text,
				'pretty_text' => $pretty_text,
			);

			obj::db()->update('art_pack',array_keys($update),array_values($update), $id);
		}
	}

	public function pack_join () {

		Check::rights();

		$parent = (int) query::$post['parent'];
		$child = (int) query::$post['child'];

		Database::update('art_pack', array('weight' => 0), $parent);

		$child_arts = Database::get_full_table('art_in_pack', 'pack_id = ?', $child);
		$parent_order = Database::get_field('art_in_pack', 'max(`order`)', 'pack_id = ?', $parent);

		foreach ($child_arts as $child_art) {
			Database::update('art_in_pack',
				array(
					'order' => $child_art['order'] + $parent_order + 1,
					'pack_id' => $parent
				),
				'pack_id = ? and art_id = ?',
				array($child_art['pack_id'], $child_art['art_id'])
			);
		}

		Database::delete('art_pack', $child);
	}

	public function pack_sort () {
		global $check;
		if ($check->rights()) {
			$pack_id = (int) query::$post['id'];

			if (!empty(query::$post['order'])) {
				foreach (query::$post['order'] as $id => $one) {
					$one = (int) $one;
					if ($one > 0) {
						obj::db()->sql('update art_in_pack set `order`='.$one.' where art_id='.$id.' and pack_id='.$pack_id,0);
					}

				}
			}

			if (!empty(query::$post['delete'])) {
				foreach (query::$post['delete'] as $id) {
					$id = (int) $id;
					if ($id > 0) {
						obj::db()->sql('delete from art_in_pack where art_id='.$id.' and pack_id='.$pack_id,0);
						if (!obj::db()->sql('select art_id from art_in_pack where art_id='.$id,2)) {
							obj::db()->sql('update art set area="deleted" where area="cg" and id='.$id,0);
						}
					}
				}
				obj::db()->update('art_pack','weight',0,$pack_id);
			}

			obj::db()->update('art_pack','cover',query::$post['chosen'],$pack_id);
		}
	}

	/* Раздел для редактирования верхнего меню */

	public function add_menu_item () {
		global $check;
		if ($check->rights() && trim(query::$post['name']) && trim(query::$post['url'])) {
			$insert = array(
				query::$post['name'],
				query::$post['url'],
				(int) query::$post['parent'],
				obj::db()->sql('select `order` from head_menu order by `order` desc limit 1',2) + 1,
			);
			obj::db()->insert('head_menu', $insert);
		}
	}

	public function edit_menu_item () {
		global $check;
		if (
			$check->rights() && trim(query::$post['name']) &&
			trim(query::$post['url']) && is_numeric(query::$post['id'])
		) {
			$update = array(
				'name' => query::$post['name'],
				'url' => query::$post['url'],
				'parent' => (int) query::$post['parent'],
				'order' => (int) query::$post['order'],
			);
			obj::db()->update(
				'head_menu',
				array_keys($update),
				array_values($update),
				query::$post['id']
			);
		}
	}

	public function delete_menu_item () {
		global $check;
		if (
			$check->rights() && isset(query::$post['sure']) &&
			is_numeric(query::$post['id'])
		) {
			obj::db()->sql('delete from head_menu where id = '.query::$post['id'],0);
			obj::db()->sql('update head_menu set parent = 0 where parent = '.query::$post['id'],0);
		}
	}
}
