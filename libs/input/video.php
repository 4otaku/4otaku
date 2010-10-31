<?

include_once 'libs/input/common.php';
class input__video extends input__common
{
	function add() { 
		global $post; global $db; global $check; global $def; global $transform_meta; global $sets;
		global $transform_text; global $transform_video; global $cookie; global $add_res;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if (!$transform_text) $transform_text = new transform__text();
		if (!$transform_video) $transform_video = new transform__video();
		if (!$cookie) $cookie = new dinamic__cookie();
		
		if ($post['title']) {
			$post['link'] = undo_safety($post['link']);
			if ($check->link($post['link'])) {
				if (!$id = $db->sql('select id from video where link="'.$post['link'].'"',2)) {
					if ($object = $transform_video->html($post['link'])) {
		
						if ($post['user'] != $def['user']['name']) $cookie->inner_set('user.name',$post['user']); else unset($post['user']);
						$tags = $transform_meta->add_tags($transform_meta->parse($post['tags']));
						$author = $transform_meta->author($transform_meta->parse($post['user'],$def['user']['author']));
						$category = $transform_meta->category($post['category']);
						$text = $transform_text->format($post['description']);
			
						$db->insert('video',$insert_data = array($post['title'],$post['link'],$object,$text,undo_safety($post['description']),
									$author,$category,$tags,0,0,$transform_text->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
						$db->insert('versions',array('video',$id = $db->sql('select @@identity from video',2),
														base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));	
														
						if (isset($post['transfer_to_main']) && $sets['user']['rights']) {
							include_once('libs/input/common.php');						
							$_post = array('id' => $id, 'sure' => 1, 'do' => array('video','transfer'), 'where' => 'main');							
							input__common::transfer($_post);
						} else {
							$add_res['text'] = 'Ваше видео успешно добавлено, и доступно по адресу <a href="/video/'.$id.'/">http://4otaku.ru/video/'.$id.'/</a> или в <a href="/video/'.$def['area'][1].'/">очереди на премодерацию</a>.';
						}
					}
					else $add_res = array('error' => true, 'text' => 'Извините, либо этого видеосервиса нет в нашей базе, либо с вашей ссылкой что-то не так.'); 
				}
				else $add_res = array('error' => true, 'text' => 'Это видео уже у нас есть, оно находится по адресу <a href="/video/'.$id.'/">http://4otaku.ru/video/'.$id.'/</a>.');
			}
			else $add_res = array('error' => true, 'text' => 'Вы не предоставили ссылки, или же ссылка почему-то битая.');
		}
		else $add_res = array('error' => true, 'text' => 'Вы забыли указать заголовок для видео.');
	}
	
	function edit_video_link() {
		global $post; global $db; global $check; global $def; global $transform_video;
		if (!$transform_video) $transform_video = new transform__video();
		$post['link'] = undo_safety($post['link']);
		if ($check->num($post['id']) && $post['type'] == 'video' && $check->link($post['link'])) {
			$area = $db->sql('select area from '.$post['type'].' where id='.$post['id'],2);
			if ($area != $def['area'][1]) $check->rights();
			$object = $transform_video->html($post['link']);
			$db->update('video',array('link','object'),array($post['link'],$object),$post['id']);
		}		
	}	
}
