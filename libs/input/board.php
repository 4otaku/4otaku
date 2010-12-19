<?

include_once 'libs'.SL.'input'.SL.'common.php';
class input__board extends input__common
{
	function add() { 
		global $post; global $check; global $def; global $sets; global $cookie; global $add_res;
		if (!$cookie) $cookie = new dinamic__cookie();		

		if (!empty($post['image'])) {
			$parts = explode('#', $post['image']);
			$content['image'][0]['full'] = $parts[0];
			$content['image'][0]['thumb'] = $parts[1];
			$content['image'][0]['weight'] = $parts[2];
			$content['image'][0]['sizes'] = $parts[3];
		} elseif (!empty($post['video'])) {
			$content['video']['link'] = undo_safety($post['video']);
			$content['video']['object'] =  obj::transform('video')->html($content['video']['link'], false);
			if (empty($content['video']['object'])) {
				$this->add_res('Извините, либо этого видеосервиса нет в нашей базе, либо с вашей ссылкой что-то не так.',true); 
				return false;
			}
			$content['video']['is_api'] = obj::transform('video')->api;
			$content['video']['api']['id'] = obj::transform('video')->id;
			$content['video']['aspect'] = obj::transform('video')->aspect;
		}
		
		$text = obj::transform('text')->wakaba($post['text']);
		
		if (empty($post['id']) + empty($content) + empty($text) <= 1) {
	
			$trip = explode('#', $post['user']);
			$user = array_shift($trip);
			$user = preg_replace('/#.*$/','',$user);
			$trip = array_slice($trip,0,3);

			$tripcode = $trip[0] ? $this->trip($trip[0]) : '';
			$tripcode .= $trip[1] || $trip[2] ? '!'.$this->trip(_crypt($trip[1].$trip[2])) : '';

			if (trim($user) && $user != $def['user']['name']) {
				$cookie->inner_set('user.name',$user); 
			}			
			if (implode('#',$trip)) {
				$cookie->inner_set('user.trip',implode('#',$trip)); 
			}			

			if ($post['id']) {
				$category = obj::db()->sql('select boards from board where id = '.$post['id'], 2);
			} else {
				$category = '|'.implode('|',$post['category']).'|';
			}

			$time = ceil(microtime(true)*1000);

			$insert_data = array(
				$post['id'] ? 1 : 2,
				$post['id'],
				$post['id'] ? 0 : $time,
				trim($user) ? trim($user) : $def['user']['name'],
				$tripcode,
				base64_encode(serialize($content)),
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
