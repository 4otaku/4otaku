<?

class input__video extends input__common
{
	function add() { 
		global $check; global $def; global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		if (query::$post['title']) {
			query::$post['link'] = undo_safety(query::$post['link']);
			if ($check->link(query::$post['link'])) {
				if (!$id = obj::db()->sql('select id from video where link="'.query::$post['link'].'"',2)) {
					if ($object = obj::transform('video')->html(query::$post['link'])) {
		
						if (query::$post['user'] != $def['user']['name']) $cookie->inner_set('user.name',query::$post['user']); else unset(query::$post['user']);
						$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse(query::$post['tags']));
						$author = obj::transform('meta')->author(obj::transform('meta')->parse(query::$post['user'],$def['user']['author']));
						$category = obj::transform('meta')->category(query::$post['category']);
						$text = obj::transform('text')->format(query::$post['description']);
			
						obj::db()->insert('video',$insert_data = array(query::$post['title'],query::$post['link'],$object,$text,undo_safety(query::$post['description']),
									$author,$category,$tags,0,0,obj::transform('text')->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
						obj::db()->insert('versions',array('video',$id = obj::db()->sql('select @@identity from video',2),
														base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));	
														
						if (isset(query::$post['transfer_to_main']) && $sets['user']['rights']) {
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
		global $check; global $def;
		query::$post['link'] = undo_safety(query::$post['link']);
		if ($check->num(query::$post['id']) && query::$post['type'] == 'video' && $check->link(query::$post['link'])) {
			$area = obj::db()->sql('select area from '.query::$post['type'].' where id='.query::$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$object = obj::transform('video')->html(query::$post['link']);
			obj::db()->update('video',array('link','object'),array(query::$post['link'],$object),query::$post['id']);
		}		
	}	
}
