<?

include_once 'libs'.SL.'input'.SL.'common.php';
class input__board extends input__common
{
	function add() { 
		global $post; global $check; global $def; global $sets; global $cookie; global $add_res;
		if (!$cookie) $cookie = new dinamic__cookie();
		

		if (!empty($post['image'])) {
			$content = $post['image'];
		} elseif (!empty($post['video'])) {
			$content =  obj::transform('video')->html(undo_safety($post['video']), false);
			if (empty($content)) {
				$this->add_res('Извините, либо этого видеосервиса нет в нашей базе, либо с вашей ссылкой что-то не так.',true); 
				return false;
			}
		}
		
		$text = obj::transform('text')->wakaba($post['text']);
		
		if (empty($post['id']) + empty($content) + empty($text) <= 1) {
	
			if (!trim($post['user'])) {
				$post['user'] = $def['user']['name'];
			} elseif ($post['user'] != $def['user']['name']) {
				$cookie->inner_set('user.name',$post['user']); 
			}
			
			if ($post['id']) {
				$category = obj::db()->sql('select boards from board where id = '.$post['id'], 2);
			} else {
				$category = '|'.implode('|',$post['category']).'|';
			}

			$time = ceil(microtime(true)*1000);
			$user = explode('#', $post['user']);
			$trip = $user[1] ? $this->trip($user[1]) : '';
			$trip .= $user[2] || $user[3] ? '!'.$this->trip(_crypt($user[2].$user[3])) : '';

			$insert_data = array(
				$post['id'] ? 1 : 2,
				$post['id'],
				$post['id'] ? 0 : $time,
				$user[0],
				$trip,
				$content,
				undo_safety($post['text']),
				$text,
				$category,
				'|',
				date('j.n.y - G:i'),
				$time,
				$cookie->get()
			);
			
			obj::db()->insert('board',$insert_data);
			
			if (!empty($post['id'])) {
				obj::db()->update('board','updated',$time,$post['id']);
			} else {
				$this->redirect = '/board/'.$post['category'][array_rand($post['category'])].'/thread/'.obj::db()->sql('select @@identity from board',2);
			}
		} else {
			if (empty($post['id'])) {
				$this->add_res('При создании нового треда вам надо написать текст, а также добавить картинку или видео',true); 
			} else {
				$this->add_res('Для ответа надо добавить текст, картинку или видео',true); 
			}			
		}
	}
	
	function trip($string) {
		$string = crypt($string, CRYPT_MD5);
		$string = preg_replace('/^.+\$/','',$string);
		$string = preg_replace('/[^\p{L}\d]+/','',$string);
		return strtolower(substr($string,0,6));
	}
}
