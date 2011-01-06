<?

class input__admin extends engine
{
	function login() { 
		global $post; global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		if ($rights = obj::db()->sql('select rights from user where login="'.$post['login'].'" and pass="'.md5($post['pass']).'"',2)) {
			$cookie->inner_set('user.rights',$rights);
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
		global $post; global $check;
		$check->rights();
		if ($post['old_alias'] != $post['alias']) {
			obj::db()->sql('update post set tag = replace(tag,"|'.$post['old_alias'].'|","|'.$post['alias'].'|")',0);
			obj::db()->sql('update video set tag = replace(tag,"|'.$post['old_alias'].'|","|'.$post['alias'].'|")',0);
			obj::db()->sql('update art set tag = replace(tag,"|'.$post['old_alias'].'|","|'.$post['alias'].'|")',0);
		}
		$variants = array_unique(array_filter(explode(' ',str_replace(',',' ',$post['variants']))));
		if (!empty($variants)) $variants = '|'.implode('|',$variants).'|'; else $variants = '|';
		obj::db()->update('tag',array('alias','name','variants','color'),array($post['alias'],$post['name'],$variants,$post['color']),$post['id']);
	}
	
	function edit_update() {
		global $post; global $check; global $def;
		if ($check->rights()) {
			$text = obj::transform('text')->format($post['text']);	
			$links = obj::transform('link')->similar(obj::transform('link')->parse($post['link'])); 
			obj::db()->update('updates',
				array('username','text','pretty_text','link'),
				array($post['author'],$text,undo_safety($post['text']),serialize($links)),
				$post['id']
			);
		}			
	}
}
