<?

class input__board extends input__common
{
	protected $storing_boards = array(
		'd', 'sto'
	);

	protected $random_codes = array(
		'random_main' => 'main',
		'random_flea' => 'flea_market',
		'random_cg' => 'cg',
		'random_sprite' => 'sprites',
	);
	
	protected $max_threads_in_board = 20;

	function add() {
		global $check; global $def; global $sets; global $cookie; global $add_res;
		if (!$cookie) $cookie = new dynamic__cookie();

		$content = array();
		$count_content = 0;

		if (!empty(query::$post['image']) && is_array(query::$post['image'])) {

			foreach (query::$post['image'] as $add_image) {
				if ($count_content >= def::board('maxcontent')) {
					continue;
				}
				
				$count_content++;

				if (array_key_exists($add_image, $this->random_codes)) {
					$random_art = $this->get_random_art($this->random_codes[$add_image]); 
					
					if ($random_art) {
						$name = $random_art['md5'] . '.' . $random_art['extension'];
						$path = IMAGES . SL . 'booru' . SL . 'full' . SL . $name;

						try {
							$resizer = new Transform_Upload_Board_Image($path, $name);						
							$result = $resizer->process_file();
						} catch (Error_Upload $e) {
							continue;
						}
						
						if (empty($result['success'])) {
							continue;
						}
						
						$content['random'][] = array(
							'id' => $random_art['id'],
							'full' => $result['full'],
							'thumb' => $result['thumb'],
							'size' => $result['size'],
							'width' => $result['width'],
							'height' => $result['height'],
						);
					}
					
					continue;
				}

				$parts = explode('#', $add_image);
				if ($parts[1] == 'flash') {
					$content['flash'][] = array(
						'full' => $parts[0],
						'weight' => $parts[2],
					);
				} else {
					$content['image'][] = array(
						'full' => $parts[0],
						'thumb' => $parts[1],
						'weight' => $parts[2],
						'sizes' => $parts[3],
					);
				}
			}
		}

		if (!empty(query::$post['video']) && is_array(query::$post['video'])) {
			query::$post['video'] = array_unique(query::$post['video']);

			foreach (query::$post['video'] as $add_video) {
				if (empty($add_video) || $count_content >= def::board('maxcontent')) {
					continue;
				}
				$video_link = undo_safety($add_video);
				$video_worker = new Transform_Video($video_link);
				$video_object = $video_worker->disable_nico()->get_html();

				if (empty($video_object)) {
					$this->add_res(
						'Извините, видеосервиса для ссылки '.
						'('.$add_video.') нет в нашей базе, '.
						'либо с вашей ссылкой что-то не так.'
					,true);
					continue;
				}
				$content['video'][] = array(
					'link' => $video_link,
					'object' => $video_object,
					'service_id' => $video_worker->get_id(),
					'aspect' => $video_worker->get_aspect(),
				);
				$count_content++;
			}
		}

		$text = obj::transform('text')->wakaba(query::$post['text']);

		$is_thread = (bool) empty(query::$post['id']);

		if ($is_thread + empty($content) + empty($text) <= 1) {

			$trip = preg_split('/(?<!&)#/', query::$post['user']);
			$user = array_shift($trip);
//			$user = preg_replace('/#.*$/','',$user);
			$trip = array_slice($trip,0,3);

			$tripcode = $trip[0] ? $this->trip($trip[0]) : '';
			$tripcode .= $trip[1] || $trip[2] ? '!'.$this->trip(_crypt($trip[1].$trip[2])) : '';

			if (trim($user) && $user != $def['user']['name']) {
				$cookie->inner_set('user.name',$user);
			}
			if (!empty($trip)) {
				$cookie->inner_set('user.trip',implode('#',$trip));
			} else {
				$cookie->inner_set('user.trip','');
			}

			if ($is_thread) {
				$categories = (array) query::$post['category'];

				$board_categories = obj::db()->sql('
					SELECT id, alias from category
					WHERE locate("|board|",area)
					ORDER BY id
				', 'alias');
				$insert_categories = array();

				$limit = $this->max_threads_in_board - 1;
				$to_flush = array();
				foreach ($categories as $category) {
					if (!array_key_exists($category, $board_categories)) {
						continue;
					}

					if (!in_array($category, $this->storing_boards)) {
						$to_flush = array_merge(
							$to_flush,
							(array) obj::db()->sql('
								SELECT board.id, board_category.category_id
								FROM board LEFT JOIN board_category
								ON board.id=board_category.thread_id
								WHERE
									board.type="thread" AND
									board_category.actual = 1 AND
									board_category.category_id = '.$board_categories[$category].'
								ORDER by board.updated DESC
								LIMIT '.$limit.', 1000
							')
						);
					}

					$insert_categories[] = $board_categories[$category];
				}

				if (!empty($to_flush)) {
					$condition = ''; $check_ids = array();
					foreach ($to_flush as $one) {
						$condition .= ' or
							(thread_id = '.$one['id'].' and category_id = '.$one['category_id'].')';
						$check_ids[] = $one['id'];
					}
					$condition = substr($condition, 4);
					obj::db()->sql('update `board_category` set actual = 0 where'.$condition,0);

					$still_living = array_keys((array) obj::db()->sql('
						SELECT board.id
						FROM board LEFT JOIN board_category
						ON board.id=board_category.thread_id
						WHERE
							board.id in ('.implode(',',$check_ids).') AND
							board_category.actual = 1
					','id'));

					$old_threads = array_diff($check_ids, $still_living);

					obj::db()->sql('update `board` set type = "old" where id in ('.implode(',',$old_threads).')',0);
				}
			}

			$time = ceil(microtime(true)*1000);

			$insert_data = array(
				$is_thread ? 'thread' : 'post',
				query::$post['id'],
				$is_thread ? $time : 0,
				trim($user) ? trim($user) : $def['user']['name'],
				$tripcode,
				undo_safety(query::$post['text']),
				$text,
				date('j.n.y - G:i'),
				$time,
				$cookie->get(),
				$_SERVER['REMOTE_ADDR']
			);

			obj::db()->insert('board',$insert_data);
			$id = obj::db()->sql('select @@identity from board',2);
			foreach ($insert_categories as $category) {
				obj::db()->insert('board_category',array($id,$category,1),false);
			}
			$this->add_content($content, $id);

			if (!empty(query::$post['id'])) {
				obj::db()->update('board','updated',$time,query::$post['id']);
			} else {
				$this->redirect = '/board/'.query::$post['category'][array_rand(query::$post['category'])].'/thread/'.$id;
			}
		} else {
			if (empty(query::$post['id'])) {
				$this->add_res('При создании нового треда вам надо написать текст, а также добавить картинку или видео',true);
			} else {
				$this->add_res('Для ответа надо добавить текст, картинку или видео',true);
			}
		}
	}

	protected function add_content($content, $id) {
		$i = 0;
		foreach ($content as $type => $items) {
			foreach ($items as $item) {
				obj::db()->insert('board_attachment',
					array($id,$type,base64_encode(serialize($item)),$i++)
				);
			}
		}
	}
	
	protected function get_random_art($type) {
		$data = false;
		$attempts = 0;
		while (!$data && ++$attempts < 5) {
			$count = Database::get_count('art', 'area = ?', $type);
			if (!$count) {
				continue;
			}
			$position = mt_rand(0, $count - 1);
			$data = Database::limit(1, $position)->get_full_row('art', 'area = ?', $type);
		}
		
		return $data;
	}

	function trip($string) {
		$string = crypt($string, CRYPT_MD5);
		$string = preg_replace('/^.+\$/','',$string);
		$string = preg_replace('/[^\p{L}\d]+/','',$string);
		return strtolower(substr($string,0,6));
	}
}
