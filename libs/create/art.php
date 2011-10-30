<?php

class Create_Art extends Create_Abstract
{
	public static function main() {

		global $check; global $def; global $url; global $sets; global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		
		if (is_array(query::$post['images'])) {
			if ($url[2] == 'pool' && is_numeric($url[3])) {
				$pool_password = Database::get_field('art_pool', 'password', $url[3]);
			}
			if (empty($pool_password) || $pool_password == md5(query::$post['password'])) {
				$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse_array(query::$post['tags']));
				$category = obj::transform('meta')->category(query::$post['category']);
				$author = obj::transform('meta')->author(obj::transform('meta')->parse(query::$post['author'],$def['user']['author']));

				$similar = array();

				if (!empty(query::$post['dublicates'])) {
					foreach (query::$post['images'] as $image_key => $image) {
						if (query::$post['dublicates'] != $image_key + 1) {
							unset(query::$post['images'][$image_key]);
							$similar[] = $image;
						}
					}
				}

				$add_to_groups = array();
				$groups = array();
				$art_ids = array();
				foreach (query::$post['images'] as $image) {					
					$thumb = $image['thumb'];
					$md5 = $image['md5'];
					$extension = $image['extension'];
					$resized = $image['resized'];
					$animated = $image['animated'];
					
					$thumb = $check->hash($thumb); 
					$md5 = $check->hash($md5);
					if (
						$md5 && $thumb && $extension &&
						!Database::get_count('art', 'md5 = ?', $md5)
					) {
						if (!empty($image['id_in_group']) && $image['id_in_group'] > 1) {
							
							if (!isset($add_to_groups[$image['id_group']])) {
								$add_to_groups[$image['id_group']] = array();
							}
							
							$add_to_groups[$image['id_group']][$image['id_in_group']] = $image;
							continue;
						}
						
						$local_tags = empty($image['tags']) ? $tags :
							obj::transform('meta')->add_tags(obj::transform('meta')->parse_array($image['tags']));
					
						$insert_data = array(
							'md5' => $md5,
							'thumb' => $thumb,
							'extension' => $extension,
							'resized' => $resized,
							'animated' => (int) $animated,
							'author' => $author,
							'category' => $category,
							'tag' => $local_tags,
							'source' => query::$post['source'],
							'pretty_date' => obj::transform('text')->rudate(),
							'sortdate' => $time = ceil(microtime(true)*1000),
							'area' => $def['area'][1]
						);	

						Database::insert('art', $insert_data);
						obj::db()->insert('versions',array('art',$id = Database::last_id(),
											base64_encode(serialize($insert_data)),
											$time,$sets['user']['name'],$_SERVER['REMOTE_ADDR']));

						if (function_exists('puzzle_fill_cvec_from_file') && function_exists('puzzle_compress_cvec')) {
							$imagelink = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.'large_'.$name[1].'.jpg';
							$vector = puzzle_fill_cvec_from_file($imagelink);
							$vector = base64_encode(puzzle_compress_cvec($vector));

							obj::db()->insert('art_similar',array($id, $vector, 0, '|'),false);
						}
						
						if (!empty($image['id_group'])) {
							
							$groups[$image['id_group']] = $id;
						}						

						$art_ids[] = $id;
						$i++;
					}
				}

				if (!empty($add_to_groups)) {
					foreach ($add_to_groups as $id_group => $add) {

						if (empty($groups[$id_group])) {
							continue;
						}
						$insert_id = $groups[$id_group];
												
						ksort($add);
						$order_next = 0;
						
						foreach ($add as $variant) {
							$insert = array($insert_id, $variant['md5'],
								$variant['thumb'], $variant['extension'],
								!empty($variant['resized']), $order_next++, $variant['animated']);

							obj::db()->insert('art_variation', $insert);
						}
					}
				}

				if (!empty($similar)) {
					if (!$id) {
						$id = obj::db()->sql('select @@identity from art',2);
					}
					$order_next = 0;
					foreach ($similar as $variant) {
						$insert = array($id, $variant['md5'],
							$variant['thumb'], $variant['extension'],
							!empty($variant['resized']), $order_next++, $variant['animated']);

						obj::db()->insert('art_variation', $insert);
					}
				}

				if (!empty(query::$post['transfer_to']) && $sets['user']['rights']) {
					$_post = array('sure' => 1, 
						'do' => array('art','transfer'), 
						'where' => query::$post['transfer_to']);

					foreach ($art_ids as $art_id) {
						var_dump($art_id);
						$_post['id'] = $art_id;
						input__common::transfer($_post);
					}
				} else {
					if ($i > 1) $this->add_res('Ваши изображения успешно добавлены, и доступны в <a href="/art/'.$def['area'][1].'/">очереди на премодерацию</a>.');
					else $this->add_res('Ваше изображение успешно добавлено, и доступно по адресу <a href="/art/'.$id.'/">http://4otaku.ru/art/'.$id.'/</a> или в <a href="/art/'.$def['area'][1].'/">очереди на премодерацию</a>.');
				}

				if (isset($pool_password)) {
					if (!$id) {
						$id = obj::db()->sql('select @@identity from art',2);
					}
					$j = 0;
					$order = Database::set_order('order')->
						get_field('art_in_pool', 'order', 'pool_id = ?', $url[3]);
					while ($j < $i) {
						Database::insert('art_in_pool', array(
							'art_id' => $id - $j++,
							'pool_id' => $url[3],
							'order' => ++$order
						));
					}
				}
			} else {
				$this->add_res('Неправильный пароль от группы.', true);
			}
		} else {
			$this->add_res('Не все обязательные поля заполнены.', true);
		}
	}
}
