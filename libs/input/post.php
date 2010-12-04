<?

include_once 'libs'.SL.'input'.SL.'common.php';
class input__post extends input__common 
{
	function add() { 
		global $post; global $db; global $check; global $def; global $transform_meta; global $sets;
		global $transform_text; global $transform_link; global $cookie;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if (!$transform_text) $transform_text = new transform__text();
		if (!$transform_link) $transform_link = new transform__link();
		if (!$cookie) $cookie = new dinamic__cookie();
		
		$post['link'] = $check->link_array($post['link']);		
		if ($post['title'] && $post['link']) {
		
			if ($post['author'] != $def['user']['name'] && $post['author']) $cookie->inner_set('user.name',$post['author']);
			
			$tags = $transform_meta->add_tags($transform_meta->parse($post['tags']));
			$author = $transform_meta->author($transform_meta->parse($post['author'],$def['user']['author']));
			$category = $transform_meta->category($post['category']);
			$language = $transform_meta->language($post['language']);

			$text = $transform_text->format($post['text']);			
			if (is_array($post['images'])) $images = implode($post['images'],'|');

			$post['bonus_link'] = $check->link_array($post['bonus_link']);
			$links = $transform_link->similar($transform_link->parse($post['link']));
			if (is_array($post['bonus_link'])) $bonus_links = $transform_link->parse($post['bonus_link']);

			$db->insert('post',$insert_data = array($post['title'],$text,undo_safety($post['text']),$images,serialize($links),serialize($bonus_links),serialize($post['file']),
						$author,$category,$language,$tags,0,0,0,$transform_text->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
			$db->insert('versions',array('post',$id = $db->sql('select @@identity from post',2),
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
		global $post; global $db; global $check; global $def;
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			if (is_array($post['images'])) $images = implode($post['images'],'|');
			$db->update($post['type'],'image',$images,$post['id']);
		}		
	}
	
	function edit_post_links() {
		global $post; global $db; global $check; global $def; global $transform_link;
		if (!$transform_link) $transform_link = new transform__link();
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$links = $check->link_array($post['link']);
			if (is_array($links)) $links = $transform_link->similar($transform_link->parse($links)); 
			$db->update($post['type'],'link',serialize($links),$post['id']);
		}		
	}	
	
	function edit_post_bonus_links() {
		global $post; global $db; global $check; global $def; global $transform_link;
		if (!$transform_link) $transform_link = new transform__link();
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$links = $check->link_array($post['link']);
			if (is_array($links)) $links = $transform_link->parse($links); 
			$db->update($post['type'],'info',serialize($links),$post['id']);
		}		
	}
	
	function edit_post_files() {
		global $post; global $db; global $check; global $def;
		if ($check->num($post['id']) && $post['type'] == 'post') {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$db->update($post['type'],'file',serialize($post['file']),$post['id']);
		}		
	}		
	
	function update() {
		global $post; global $db; global $check; global $def; global $transform_text; global $transform_link;
		global $cookie;
		if (!$transform_text) $transform_text = new transform__text();
		if (!$transform_link) $transform_link = new transform__link();
		if (!$cookie) $cookie = new dinamic__cookie();
		if ($check->rights()) {
			$text = $transform_text->format($post['text']);	
			$links = $transform_link->similar($transform_link->parse($post['link'])); 
			$db->insert('updates',array($post['id'],$post['author'],$text,undo_safety($post['text']),serialize($links),$transform_text->rudate(),ceil(microtime(true)*1000),$def['area'][0]));
			$db->sql('update post set update_count = update_count + 1 where id='.$post['id'],0);
			$db->update('misc',array('data1','data2','data3','data4'),array($post['id'],$db->sql('select title from post where id='.$post['id'],2),$post['author'],$text),'latest_update','type');
		}		
	}		
}
