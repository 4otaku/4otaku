<?

class input__admin extends engine
{
	function login() { 
		global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		if ($rights = obj::db()->sql('select rights from user where login="'.query::$post['login'].'" and pass="'.md5(query::$post['pass']).'"',2)) {
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
		global $check;
		$check->rights();
		if (query::$post['old_alias'] != query::$post['alias']) {
			obj::db()->sql('update post set tag = replace(tag,"|'.query::$post['old_alias'].'|","|'.query::$post['alias'].'|")',0);
			obj::db()->sql('update video set tag = replace(tag,"|'.query::$post['old_alias'].'|","|'.query::$post['alias'].'|")',0);
			obj::db()->sql('update art set tag = replace(tag,"|'.query::$post['old_alias'].'|","|'.query::$post['alias'].'|")',0);
		}
		$variants = array_unique(array_filter(explode(' ',str_replace(',',' ',query::$post['variants']))));
		if (!empty($variants)) $variants = '|'.implode('|',$variants).'|'; else $variants = '|';
		obj::db()->update('tag',array('alias','name','variants','color'),array(query::$post['alias'],query::$post['name'],$variants,query::$post['color']),query::$post['id']);
	}
	
	function edit_update() {
		global $check; global $def;
		if ($check->rights()) {
			$text = obj::transform('text')->format(query::$post['text']);	
			$links = obj::transform('link')->similar(obj::transform('link')->parse(query::$post['link'])); 
			obj::db()->update('updates',
				array('username','text','pretty_text','link'),
				array(query::$post['author'],$text,undo_safety(query::$post['text']),serialize($links)),
				query::$post['id']
			);
		}			
	}
	
	function similar() {
		global $check;
		
		if ($check->rights()) {
			$action = explode('|', query::$post['action']);
			switch ($action[0]) {
				case 'delete':
					$this->delete_art(query::$post[$action[1]]);
					break;
				case 'move_tags':
					$from = query::$post[$action[1]{0}];
					$to = query::$post[$action[1]{1}];
					$this->move_art_tags($from, $to);
					break;
				case 'make_similar':
					break;
				default:
					break;
			}
		}
	}
	
	private function delete_art($id) {
		global $def;
		
		$data = obj::db()->sql('select area, tag from art where id='.$id,1);
		$tags = array_unique(array_filter(explode('|',$data['tag'])));
		if ($data['area'] == $def['area'][0] || $data['area'] == $def['area'][2])
			obj::transform('meta')->erase_tags($tags,'art_'.$data['area']);
		
		obj::db()->sql('update comment set area="deleted" where place="art" and post_id='.$id,0);
		obj::db()->sql('update art set area="deleted" where id='.$id,0);
		obj::db()->sql('delete from art_similar where id='.$id,0);
	}
	
	private function move_art_tags($from, $to) {
		$tags = obj::db()->sql('select tag from art where id='.$from,2);
		dynamic__art::add_tag(str_replace('|', ' ', $tags), $to);
	}
}
