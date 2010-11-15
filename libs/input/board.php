<?

include_once 'libs'.SL.'input'.SL.'common.php';
class input__board extends input__common
{
	function add() { 
		global $post; global $db; global $check; global $def; global $sets;
		global $transform_text; global $transform_video; global $cookie; global $add_res;
		if (!$transform_text) $transform_text = new transform__text();
		if (!$transform_video) $transform_video = new transform__video();
		if (!$cookie) $cookie = new dinamic__cookie();
		
		if ($post['image']) {
			$content = $post['image'];
		} else {
			$content = $transform_video->html(undo_safety($post['video']), false);
		}
		
		if ($content || !$post['video']) {
	
			if ($post['user'] != $def['user']['name']) {
				$cookie->inner_set('user.name',$post['user']); 
			}
			
			if ($post['id']) {
				$category = $db->sql('select boards from board where id = '.$post['id'], 2);
			} else {
				$category = '|'.implode('|',$post['category']).'|';
			}

			$text = $transform_text->format($post['text']);
//			$text = $transform_text->board($text);

			if ($content || trim(strip_tags($text))) {
				
				$time = ceil(microtime(true)*1000);
				$user = explode('#', $post['user']);
				$trip = $user[1] ? '!'.crypt($user[1]) : '';
				$trip .= $user[2] || $user[3] ? '!!'.crypt(_crypt($user[2].$user[3])) : '';

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
					$transform_text->rudate(),
					$time,
					$cookie->get()
				);
				
				$db->insert('board',$insert_data);
			} else {
			$add_res = array('error' => true, 'text' => 'Надо добавить текст, картинку или видео'); 
			}
		} else {
			$add_res = array('error' => true, 'text' => 'Извините, либо этого видеосервиса нет в нашей базе, либо с вашей ссылкой что-то не так.'); 
		}
	}
}
