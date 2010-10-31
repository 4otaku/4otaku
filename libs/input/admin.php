<?

include_once('engine/engine.php');
class input__admin extends engine
{
	function login() { 
		global $post; global $db; global $sets; global $cookie; global $add_res;
		if (!$cookie) $cookie = new dinamic__cookie();
		if ($rights = $db->sql('select rights from user where login="'.$post['login'].'" and pass="'.md5($post['pass']).'"',2)) {
			$cookie->inner_set('user.rights',$rights);
			$sets['user']['rights'] = $rights;
		}
		else {
			$add_res('Вы ввели неправильный логин или пароль.',true);
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
}
