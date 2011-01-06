<?

class input__board extends input__common
{
	function add() { 
		global $check; global $def; global $sets; global $cookie; global $add_res;
		if (!$cookie) $cookie = new dynamic__cookie();		

		if (!empty(query::$post['image'])) {
			$parts = explode('#', query::$post['image']);
			if ($parts[1] == 'flash') {
				$content['flash']['full'] = $parts[0];
				$content['flash']['weight'] = $parts[2];				
			} else {
				$content['image'][0]['full'] = $parts[0];
				$content['image'][0]['thumb'] = $parts[1];
				$content['image'][0]['weight'] = $parts[2];
				$content['image'][0]['sizes'] = $parts[3];
			}
		} elseif (!empty(query::$post['video'])) {
			$content['video']['link'] = undo_safety(query::$post['video']);
			$content['video']['object'] =  obj::transform('video')->html($content['video']['link'], false);
			if (empty($content['video']['object'])) {
				$this->add_res('Извините, либо этого видеосервиса нет в нашей базе, либо с вашей ссылкой что-то не так.',true); 
				return false;
			}
			$content['video']['service_id'] = obj::transform('video')->id;
			$content['video']['aspect'] = obj::transform('video')->aspect;
		}
		
		$text = obj::transform('text')->wakaba(query::$post['text']);
		
		if (empty(query::$post['id']) + empty($content) + empty($text) <= 1) {
	
			$trip = explode('#', query::$post['user']);
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

			if (query::$post['id']) {
				$category = obj::db()->sql('select boards from board where id = '.query::$post['id'], 2);
			} else {
				$category = '|'.implode('|',query::$post['category']).'|';
			}

			$time = ceil(microtime(true)*1000);

			$insert_data = array(
				query::$post['id'] ? 1 : 2,
				query::$post['id'],
				query::$post['id'] ? 0 : $time,
				trim($user) ? trim($user) : $def['user']['name'],
				$tripcode,
				base64_encode(serialize($content)),
				undo_safety(query::$post['text']),
				$text,
				$category,
				'|',
				date('j.n.y - G:i'),
				$time,
				$cookie->get()
			);
			
			obj::db()->insert('board',$insert_data);
			
			if (!empty(query::$post['id'])) {
				obj::db()->update('board','updated',$time,query::$post['id']);
			} else {
				$this->redirect = '/board/'.query::$post['category'][array_rand(query::$post['category'])].'/thread/'.obj::db()->sql('select @@identity from board',2);
			}
		} else {
			if (empty(query::$post['id'])) {
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
