<?

class input__post extends input__common 
{
	function add() { 
		global $post; global $check; global $def; global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		$post['link'] = $check->link_array($post['link']);		
		if ($post['title'] && $post['link']) {
		
			if ($post['author'] != $def['user']['name'] && $post['author']) $cookie->inner_set('user.name',$post['author']);
			
			$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse($post['tags']));
			$author = obj::transform('meta')->author(obj::transform('meta')->parse($post['author'],$def['user']['author']));
			$category = obj::transform('meta')->category($post['category']);
			$language = obj::transform('meta')->language($post['language']);

			$text = obj::transform('text')->format($post['text']);			
			if (is_array($post['images'])) $images = implode($post['images'],'|');

			$post['bonus_link'] = $check->link_array($post['bonus_link']);
			$links = obj::transform('link')->similar(obj::transform('link')->parse($post['link']));
			if (is_array($post['bonus_link'])) $bonus_links = obj::transform('link')->parse($post['bonus_link']);

			obj::db()->insert('post',$insert_data = array($post['title'],$text,undo_safety($post['text']),$images,serialize($links),serialize($bonus_links),serialize($post['file']),
						$author,$category,$language,$tags,0,0,0,obj::transform('text')->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
			obj::db()->insert('versions',array('post',$id = obj::db()->sql('select @@identity from post',2),
											base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));	
			if (isset($post['transfer_to_main']) && $sets['user']['rights']) {
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
		global $post; global $check; global $def;
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			if (is_array($post['images'])) $images = implode($post['images'],'|');
			obj::db()->update($post['type'],'image',$images,$post['id']);
		}		
	}
	
	function edit_post_links() {
		global $post; global $check; global $def;
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$links = $check->link_array($post['link']);
			if (is_array($links)) $links = obj::transform('link')->similar(obj::transform('link')->parse($links)); 
			obj::db()->update($post['type'],'link',serialize($links),$post['id']);
		}		
	}	
	
	function edit_post_bonus_links() {
		global $post; global $check; global $def;
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$links = $check->link_array($post['link']);
			if (is_array($links)) $links = obj::transform('link')->parse($links); 
			obj::db()->update($post['type'],'info',serialize($links),$post['id']);
		}		
	}
	
	function edit_post_files() {
		global $post; global $check; global $def;
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = obj::db()->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			obj::db()->update($post['type'],'file',serialize($post['file']),$post['id']);
		}		
	}		
	
	function update() {
		global $post; global $check; global $def; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		if ($check->rights()) {
			$text = obj::transform('text')->format($post['text']);	
			$links = obj::transform('link')->similar(obj::transform('link')->parse($post['link'])); 
			obj::db()->insert('updates',array($post['id'],$post['author'],$text,undo_safety($post['text']),serialize($links),obj::transform('text')->rudate(),ceil(microtime(true)*1000),$def['area'][0]));
			obj::db()->sql('update post set update_count = update_count + 1 where id='.$post['id'],0);
			obj::db()->update('misc',array('data1','data2','data3','data4'),array($post['id'],obj::db()->sql('select title from post where id='.$post['id'],2),$post['author'],$text),'latest_update','type');
		}		
	}		
}
