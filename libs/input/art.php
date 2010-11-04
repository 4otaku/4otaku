<?
include_once 'libs'.LS.'input'.LS.'common.php';
class input__art extends input__common
{
	function add() { 
		global $post; global $db; global $check; global $def; global $url; global $sets; 
		global $transform_text; global $transform_meta; global $cookie; global $add_res;
		if (!$transform_meta) $transform_meta = new transform__meta();
		if (!$transform_text) $transform_text = new transform__text();
		if (!$cookie) $cookie = new dinamic__cookie();
		
		if (is_array($post['images'])) {
			if ($url[2] == 'pool' && is_numeric($url[3])) $data = $db->sql('select concat(id,"|") as pool, password from art_pool where id='.$url[3],1);
			if (!$data['password'] || $data['password'] == md5($post['password'])) {
				$tags = $transform_meta->add_tags($transform_meta->parse($post['tags']));
				$category = $transform_meta->category($post['category']);
				$author = $transform_meta->author($transform_meta->parse($post['author'],$def['user']['author']));
				$post['images'] = array_reverse($post['images']);
				foreach ($post['images'] as $image) {
					$name = explode('#',$image);
					$name[0] = $check->hash($name[0]); $name[1] = $check->hash($name[1]); 
					if ($name[0] && $name[1] && $name[2] && !$db->base_sql('sub','select id from w8m_art where md5="'.$name[0].'"',2)) {
						$db->insert('art',$insert_data = array($name[0],$name[1],$name[2],$name[3],$author,$category,$tags,"|".$data['pool'],"",
												$post['source'],0,0,$transform_text->rudate(),$time = ceil(microtime(true)*1000),$def['area'][1]));
						$db->insert('versions',array('art',$id = $db->sql('select @@identity from art',2),
														base64_encode(serialize($insert_data)),$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));					
						$i++;
					}
				}
				
				if (isset($post['transfer_to_main']) && $sets['user']['rights']) {
					$_post = array('sure' => 1, 'do' => array('art','transfer'), 'where' => 'main');
					include_once('libs/input/common.php');						
					if (!$id) $id = $db->sql('select @@identity from art',2);
					$j = $i;
					while ($j > 0) {
						$_post['id'] = ($id - --$j);
						input__common::transfer($_post);
					}		
				} else {
					if ($i > 1) $add_res['text'] = 'Ваши изображения успешно добавлены, и доступны в <a href="/art/'.$def['area'][1].'/">очереди на премодерацию</a>.';
					else $add_res['text'] = 'Ваше изображение успешно добавлено, и доступно по адресу <a href="/art/'.$id.'/">http://4otaku.ru/art/'.$id.'/</a> или в <a href="/art/'.$def['area'][1].'/">очереди на премодерацию</a>.';
				}
				
				if ($data) {
					if (!$id) $id = $db->sql('select @@identity from art',2);
					$j = 0;
					while ($j < $i) $newart .= '|'.($id - $j++);
					$db->sql('update art_pool set count = count + '.$i.', art = concat("'.$newart.'",art) where id='.$url[3],0);
				}
			}
			else $add_res = array('error' => true, 'text' => 'Неправильный пароль от группы.');
		}
		else $add_res = array('error' => true, 'text' => 'Не все обязательные поля заполнены.');
	}
	
	function addpool() {
		global $post; global $db; global $check; global $def; global $transform_text; global $add_res;
		if (!$transform_text) $transform_text = new transform__text();
		if ($post['name'] && $text = $transform_text->format($post['text'])) {
			$post['email'] = $check->email($post['email'],'');
			$db->insert('art_pool',array($post['name'],$text,$post['text'],0,"|",md5($post['password']),$post['email'],microtime(true)*1000));
			$id = $db->sql('select @@identity from art_pool',2);
			$add_res['text'] = 'Новая группа успешно добавлена, и доступна по адресу <a href="/art/pool/'.$id.'/">http://4otaku.ru/art/pool/'.$id.'/</a>.';
		}
		else $add_res = array('error' => true, 'text' => 'Не все обязательные поля заполнены.');
	}
	
	function edit_art_image() {
		global $post; global $db; global $check;
		if ($check->num($post['id']) && $post['type'] == 'art') {
			$name = explode('#',end($post['images']));
			$name[0] = $check->hash($name[0]); $name[1] = $check->hash($name[1]); if ($name[3] != 1) $name[3] ='';
			$db->update('art',array('md5','thumb','extension','resized'),$name,$post['id']);
		}		
	}
		
	function edit_art_source() {
		global $post; global $db; global $check;
		if ($check->num($post['id']) && $post['type'] == 'art') {
			$db->update('art','source',$post['source'],$post['id']);
		}		
	}
	
	function edit_art_groups() {
		global $post; global $db; global $check;
		if ($check->num($post['id']) && $post['type'] == 'art' && is_array($post['group'])) {
			$pools = $db->sql('select pool from art where id='.$post['id'],2);
			$post['group'] = array_filter(array_unique($post['group']));
			foreach ($post['group'] as $key => $group)
				if ($db->dsql('select id from art_pool where (id='.$group.' and (locate("|'.$post['id'].'|",art) or (password != "" and password != "'.md5($post['password']).'")))',2))
					unset($post['group'][$key]);
			if (count($post['group'])) {
				foreach ($post['group'] as $group) $pools .= $group.'|';
				$where = 'id='.implode(' or id=',$post['group']);
				$db->update('art','pool',$pools,$post['id']);
				$db->sql('update art_pool set count = count + 1, art = concat("|'.$post['id'].'",art) where ('.$where.')',0);
			}
		}		
	}	
	
	function edit_art_translations() {
		global $post; global $db; global $def; global $check; global $transform_text;
		if (!$transform_text) $transform_text = new transform__text();
		if ($check->num($post['id']) && $post['type'] == 'art') {
			$time = microtime(true)*1000;
			$date = $transform_text->rudate();
			$db->update('art_translation','active',0,$post['id'],'art_id');
			if ($post['size'] == 'resized') {
				$size = explode('x',$db->sql('select resized from art where id='.$post['id'],2));
				$coeff = $size[0] / $def['booru']['resizewidth'];
			}
			else $coeff = 1;
			var_dump($post['trans']);
			foreach ($post['trans'] as $key => $translation) {
				if (!$text = $transform_text->format($translation['text']))
					unset ($post['trans'][$key]);
				else {
					foreach ($translation as $key2 => $one) if ($key2 != 'text') $post['trans'][$key][$key2] = round(intval($one) * $coeff);
					$post['trans'][$key]['pretty_text'] = $translation['text']; $post['trans'][$key]['text'] = $text;
				}
			}
			var_dump($post['trans']);
			$db->insert('art_translation',array($post['id'],base64_encode(serialize($post['trans'])),$post['author'],$date,$time,1));
			$db->sql('update art set translator="'.$post['author'].'" where id='.$post['id'].' and translator=""',0);			
		}		
	}	

}
