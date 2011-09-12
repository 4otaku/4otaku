<?

class input__post extends input__common 
{
	function add() { 
		global $check; global $def; global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		query::$post['link'] = $check->link_array(query::$post['link']);		
		if (query::$post['title'] && query::$post['link']) {
		
			if (query::$post['author'] != $def['user']['name'] && query::$post['author']) $cookie->inner_set('user.name',query::$post['author']);
			
			$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse(query::$post['tags']));
			$author = obj::transform('meta')->author(obj::transform('meta')->parse(query::$post['author'],$def['user']['author']));
			$category = obj::transform('meta')->category(query::$post['category']);
			$language = obj::transform('meta')->language(query::$post['language']);

			$text = obj::transform('text')->format(trim(query::$post['text']));
			if (is_array(query::$post['images'])) $images = implode(query::$post['images'],'|');

			query::$post['bonus_link'] = $check->link_array(query::$post['bonus_link']);
			$links = obj::transform('link')->similar(obj::transform('link')->parse(query::$post['link']));
			if (is_array(query::$post['bonus_link'])) $bonus_links = obj::transform('link')->parse(query::$post['bonus_link']);

			obj::db()->insert('post',$insert_data = array(query::$post['title'],$text,undo_safety(query::$post['text']),$images,serialize($links),serialize($bonus_links),serialize(query::$post['file']),
						$author,$category,$language,$tags,0,0,0,obj::transform('text')->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
			obj::db()->insert('versions',array('post',$id = obj::db()->sql('select @@identity from post',2),
											base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));	
			if (isset(query::$post['transfer_to_main']) && $sets['user']['rights']) {
				include_once('libs/input/common.php');						
				$_post = array('id' => $id, 'sure' => 1, 'do' => array('post','transfer'), 'where' => 'main');							
				input__common::transfer($_post);
			} else {											
				$this->add_res('Ваша запись успешно добавлена, и доступна по адресу <a href="/post/'.$id.'/">http://4otaku.ru/post/'.$id.'/</a> или в <a href="/post/'.$def['area'][1].'/">мастерской</a>.');
			}
		}
		else $this->add_res('Не все обязательные поля заполнены',true);
	}
	
	function edit_post_images() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			if (is_array(query::$post['images'])) $images = implode(query::$post['images'],'|');
			obj::db()->update(query::$post['type'],'image',$images,query::$post['id']);
		}		
	}
	
	function edit_post_links() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$links = $check->link_array(query::$post['link']);
			if (is_array($links)) $links = obj::transform('link')->similar(obj::transform('link')->parse($links)); 
			obj::db()->update(query::$post['type'],'link',serialize($links),query::$post['id']);
		}		
	}	
	
	function edit_post_bonus_links() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$links = $check->link_array(query::$post['link']);
			if (is_array($links)) $links = obj::transform('link')->parse($links); 
			obj::db()->update(query::$post['type'],'info',serialize($links),query::$post['id']);
		}		
	}
	
	function edit_post_files() {
		global $check; global $def;
		if ($check->num(query::$post['id']) && query::$post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			obj::db()->update(query::$post['type'],'file',serialize(query::$post['file']),query::$post['id']);
		}		
	}		
	
	function update() {
		global $check; global $def; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		if (!$check->rights(true)) {
			$this->add_res('Недостаточно прав чтобы обновить запись', true);
			return;
		}
		
		$author = trim(strip_tags(query::$post['author']));
		if (empty($author)) {
			$this->add_res('Вы забыли указать автора обновления', true);
			return;			
		}
		
		$text = obj::transform('text')->format(query::$post['text']);
		if (!trim(strip_tags($text))) {
			$this->add_res('Вы забыли добавить описание обновления', true);
			return;			
		}		
		
		$links = obj::transform('link')->similar(obj::transform('link')->parse(query::$post['link']));
		foreach ($links as $link) {
			if (count(array_filter($link['url'])) > 0) {
				$links_found = true;
				break;
			}
		}
		if (empty($links_found)) {
			$this->add_res('Проверьте ссылки, с ними была какая-то проблема', true);
			return;			
		}
		
		obj::db()->insert('updates',array(query::$post['id'],$author,$text,undo_safety(query::$post['text']),serialize($links),obj::transform('text')->rudate(),ceil(microtime(true)*1000),$def['area'][0]));
		obj::db()->sql('update post set update_count = update_count + 1 where id='.query::$post['id'],0);
		obj::db()->update('misc',array('data1','data2','data3','data4'),array(query::$post['id'],obj::db()->sql('select title from post where id='.query::$post['id'],2),query::$post['author'],$text),'latest_update','type');
		
		$this->add_res('Запись успешно обновлена');
	}		
}
