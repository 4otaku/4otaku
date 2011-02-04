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
				default:
					break;
			}
		}
	}
	
	private function delete_art($id, $move_to) {
		global $def;
		
		$data = obj::db()->sql('select area, tag from art where id='.$id,1);
		$tags = array_unique(array_filter(explode('|',$data['tag'])));
		if ($data['area'] == $def['area'][0] || $data['area'] == $def['area'][2])
			obj::transform('meta')->erase_tags($tags,'art_'.$data['area']);
		
		$this->move_art_comments($id, $move_to);
		
		obj::db()->sql('update art set area="deleted" where id='.$id,0);
		obj::db()->sql('delete from art_similar where id='.$id,0);
		obj::db()->sql('update art_similar set similar=replace(similar,"'.$id.'|","") where id='.$move_to,0);
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
	
	private function make_similar($erase, $update) {
		$this->move_art_meta($erase, $update);
		
		$image = obj::db()->sql('select thumb, md5, extension from art where id='.$erase,1);
		obj::db()->sql('update art set variation = concat(variation, "'.implode('.',$image).'|") where id='.$update,0);
		
		$this->delete_art($erase, $update);		
	}
}
