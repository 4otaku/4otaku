<?

class input__video extends input__common
{
	function add() { 
		global $post; global $check; global $def; global $sets; global $cookie;
		if (!$cookie) $cookie = new dinamic__cookie();
		
		if ($post['title']) {
			$post['link'] = undo_safety($post['link']);
			if ($check->link($post['link'])) {
				if (!$id = obj::db()->sql('select id from video where link="'.$post['link'].'"',2)) {
					if ($object = obj::transform('video')->html($post['link'])) {
		
						if ($post['user'] != $def['user']['name']) $cookie->inner_set('user.name',$post['user']); else unset($post['user']);
						$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse($post['tags']));
						$author = obj::transform('meta')->author(obj::transform('meta')->parse($post['user'],$def['user']['author']));
						$category = obj::transform('meta')->category($post['category']);
						$text = obj::transform('text')->format($post['description']);
			
						obj::db()->insert('video',$insert_data = array($post['title'],$post['link'],$object,$text,undo_safety($post['description']),
									$author,$category,$tags,0,0,obj::transform('text')->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
						obj::db()->insert('versions',array('video',$id = obj::db()->sql('select @@identity from video',2),
														base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));	
														
						if (isset($post['transfer_to_main']) && $sets['user']['rights']) {
							include_once('libs/input/common.php');						
							$_post = array('id' => $id, 'sure' => 1, 'do' => array('video','transfer'), 'where' => 'main');							
							input__common::transfer($_post);
						} else {
							$this->add_res('Ваше видео успешно добавлено, и доступно по адресу <a href="/video/'.$id.'/">http://4otaku.ru/video/'.$id.'/</a> или в <a href="/video/'.$def['area'][1].'/">очереди на премодерацию</a>.');
						}
					}
					else $this->add_res('Извините, либо этого видеосервиса нет в нашей базе, либо с вашей ссылкой что-то не так.', true); 
				}
				else $this->add_res('Это видео уже у нас есть, оно находится по адресу <a href="/video/'.$id.'/">http://4otaku.ru/video/'.$id.'/</a>.', true);
			}
			else $this->add_res('Вы не предоставили ссылки, или же ссылка почему-то битая.', true);
		}
		else $this->add_res('Вы забыли указать заголовок для видео.', true);
	}
	
	function edit_video_link() {
		global $post; global $check; global $def;
		$post['link'] = undo_safety($post['link']);
		if ($check->num($post['id']) && $post['type'] == 'video' && $check->link($post['link'])) {
			$area = obj::db()->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$object = obj::transform('video')->html($post['link']);
			obj::db()->update('video',array('link','object'),array($post['link'],$object),$post['id']);
		}		
	}	
}
