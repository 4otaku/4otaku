<?

class input__art extends input__common
{
	function addpool() {
		global $check; global $def; global $add_res;
		if (query::$post['name'] && $text = obj::transform('text')->format(query::$post['text'])) {
			query::$post['email'] = $check->email(query::$post['email'],'');
			obj::db()->insert('art_pool',array(query::$post['name'],$text,query::$post['text'],md5(query::$post['password']),query::$post['email'],microtime(true)*1000));
			$id = obj::db()->sql('select @@identity from art_pool',2);
			$add_res['text'] = 'Новая группа успешно добавлена, и доступна по адресу <a href="/art/pool/'.$id.'/">http://4otaku.ru/art/pool/'.$id.'/</a>.';
		}
		else $add_res = array('error' => true, 'text' => 'Не все обязательные поля заполнены.');
	}

	function edit_art_source() {
		global $check;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'art') {
			obj::db()->update('art','source',query::$post['source'],query::$post['id']);
		}
	}

	function edit_art_groups() {
		global $check;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'art' && is_array(query::$post['group'])) {

			$qroups = array_filter(array_unique(query::$post['group']));

			foreach ($qroups as $key => $group) {
				if (Database::get_count('art_pool',
					'id = ? and password != "" and password != ?',
					array($group, md5(query::$post['password'])))
				) {
					unset($qroups[$key]);
				}
			}

			foreach ($qroups as $group) {
				$order = Database::order('order')->
					get_field('art_in_pool', 'order', 'pool_id = ?', $group);

				Database::insert('art_in_pool', array(
					'art_id' => query::$post['id'],
					'pool_id' => $group,
					'order' => $order + 1
				));
			}
		}
	}

	function edit_art_translations() {
		global $def; global $check;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'art') {
			$time = microtime(true)*1000;
			$date = obj::transform('text')->rudate();
			obj::db()->update('art_translation','active',0,query::$post['id'],'art_id');
			if (query::$post['size'] == 'resized') {
				$info = obj::db()->sql('select resized, md5 from art where id='.query::$post['id'],1);
				$full_size = explode('x',$info['resized']);
				$small_size = getimagesize(ROOT_DIR.SL.'images/booru/resized/'.$info['md5'].'.jpg');
				$coeff = $full_size[0] / $small_size[0];
			} else {
				$coeff = 1;
			}
			foreach (query::$post['trans'] as $key => $translation) {
				if (!$text = obj::transform('text')->format($translation['text']))
					unset (query::$post['trans'][$key]);
				else {
					foreach ($translation as $key2 => $one) if ($key2 != 'text') query::$post['trans'][$key][$key2] = round(intval($one) * $coeff);
					query::$post['trans'][$key]['pretty_text'] = $translation['text']; query::$post['trans'][$key]['text'] = $text;
				}
			}
			obj::db()->insert('art_translation',array(query::$post['id'],base64_encode(serialize(query::$post['trans'])),query::$post['author'],$date,$time,1));
			obj::db()->sql('update art set translator="'.query::$post['author'].'" where id='.query::$post['id'].' and translator=""',0);
		}
	}

	function edit_art_variation() {
		global $def; global $check;

		if ($check->rights()) {
			preg_match('/(?:^|\/)(\d+)(?:\/|#|$)/', query::$post['from'], $from);

			$from = (int) $from[1];
			$to = (int) query::$post['to'];

			if ($from && $to) {
				$admin = new input__admin();
				$admin->make_similar($from, $to);
			}
		}
	}
}
