<?

class input__admin extends engine
{
	function login() { 
		global $post; global $db; global $sets; global $cookie;
		if (!$cookie) $cookie = new dinamic__cookie();
		if ($rights = $db->sql('select rights from user where login="'.$post['login'].'" and pass="'.md5($post['pass']).'"',2)) {
			$cookie->inner_set('user.rights',$rights);
			$sets['user']['rights'] = $rights;
		}
		else {
			$this->add_res('Вы ввели неправильный логин или пароль.',true);
		}
	}	

	function logout() { 
		global $db; global $sets; global $cookie;
		if (!$cookie) $cookie = new dinamic__cookie();
		$cookie->inner_set('user.rights',0);
		$sets['user']['rights'] = 0;
	}
	
	function edittag() {
		global $post; global $db; global $check;
		$check->rights();
		if ($post['old_alias'] != $post['alias']) {
			$db->sql('update post set tag = replace(tag,"|'.$post['old_alias'].'|","|'.$post['alias'].'|")',0);
			$db->sql('update video set tag = replace(tag,"|'.$post['old_alias'].'|","|'.$post['alias'].'|")',0);
			$db->sql('update art set tag = replace(tag,"|'.$post['old_alias'].'|","|'.$post['alias'].'|")',0);
		}
		$variants = array_unique(array_filter(explode(' ',str_replace(',',' ',$post['variants']))));
		if (!empty($variants)) $variants = '|'.implode('|',$variants).'|'; else $variants = '|';
		$db->update('tag',array('alias','name','variants','color'),array($post['alias'],$post['name'],$variants,$post['color']),$post['id']);
	}
	
	function edit_update() {
		global $post; global $db; global $check; global $def; global $transform_text; global $transform_link;
		if (!$transform_text) $transform_text = new transform__text();
		if (!$transform_link) $transform_link = new transform__link();		
		if ($check->rights()) {
			$text = $transform_text->format($post['text']);	
			$links = $transform_link->similar($transform_link->parse($post['link'])); 
			$db->update('updates',
				array('username','text','pretty_text','link'),
				array($post['author'],$text,undo_safety($post['text']),serialize($links)),
				$post['id']
			);
		}			
	}
}
