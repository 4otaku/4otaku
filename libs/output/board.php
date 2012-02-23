<?

class output__board extends engine
{
	function __construct() {
		global $cookie;
		if (!$cookie) $cookie = new dynamic__cookie();
		$cookie->inner_set('visit.board',time()+1,false);
	}

	public $allowed_url = array(
		array(1 => '|board|', 2 => 'any', 3=> 'any', 4 => 'any', 5 => 'num', 6 => 'end'),
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('board_list','comments','quicklinks'),
		'footer' => array()
	);

	public $error_template = 'board';

	private $inner_links = array();
	private $thread = false;
	private $board_categories = array();

	function get_data() {
		global $url;

		$this->board_categories = Database::get_vector('category',
			array('id', 'alias', 'name'),
			'locate("|board|",area) ORDER BY id');

		if (!$url[2] || $url[2] == 'page') {
			return $this->main();
		} elseif ($url[2] == 'new' || $url[2] == 'updated') {
			return $this->updated();
		} elseif ($url[3] != 'thread') {
			return $this->board();
		} else {
			return $this->thread();
		}
	}

	function main() {
		global $url;

		$return['boards'] = $this->board_categories;

		if (!sets::get('board','allthreads')) {
			$return['display'] = array('board_switcher','board_main');
		} else {
			$return['display'] = array('board_menu','board_page','navi','board_menu','board_switcher');
			$return['navi']['curr'] = max(1,$url[3]);
			$limit = 'limit '.($return['navi']['curr']-1)*sets::pp('board').', '.sets::pp('board');
			$condition = '';
			$return['threads'] = $this->get_threads($condition, $limit);
			if (empty($return['threads'])) {
				$error = true;
				$this->side_modules['top'] = array('board_list');
			}

			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('
				SELECT count(distinct(board.id))
				FROM board LEFT JOIN board_category ON board.id=board_category.thread_id
				WHERE board.`type` = "thread" AND board_category.actual = 1
			',2)/sets::pp('board'));
			$return['navi']['base'] = '/board/';
		}
		return $return;
	}

	function updated() {
		global $url; global $error;

		$time = current(unpack('i*',_base64_decode($url[3])))*1000;

		$return['boards'] = $this->board_categories;

		$return['display'] = array('board_page','navi','board_menu');
		$return['navi']['curr'] = max(1,$url[5]);
		$limit = 'limit '.($return['navi']['curr']-1)*sets::pp('board').', '.sets::pp('board');
		$condition = $url[2] == 'new' ?
			'board.sortdate > '.$time.' AND ' :
			'board.updated > '.$time.' AND board.sortdate < '.$time.' AND ';

		$return['threads'] = $this->get_threads($condition, $limit);
		if (empty($return['threads'])) {
			$error = true;
			$this->side_modules['top'] = array('board_list');
		}

		$return['navi']['start'] = max($return['navi']['curr']-5,2);
		$return['navi']['last'] = ceil(obj::db()->sql('
			SELECT count(distinct(board.id))
			FROM board LEFT JOIN board_category ON board.id=board_category.thread_id
			WHERE '.$condition.' board.`type` = "thread" AND board_category.actual = 1
		',2)/sets::pp('board'));
		$return['navi']['base'] = '/board/'.$url[2].'/'.$url[3].'/';

		return $return;
	}

	function board() {
		global $url; global $sets; global $error;

		$category_sql = 'select id from category where locate("|board|",area) and alias="'.$url[2].'"';
		if ($category_id = obj::db()->sql($category_sql,2)) {
			$return['display'] = array('board_page', 'navi');
			$return['navi']['curr'] = max(1,$url[4]);
			$limit = 'limit '.($return['navi']['curr']-1)*$sets['pp']['board'].', '.$sets['pp']['board'];
			$condition = 'board_category.category_id = '.$category_id.' AND ';
			$return['threads'] = $this->get_threads($condition, $limit);
			if (empty($return['threads'])) {
				$error = true;
				if ($return['navi']['curr'] == 1) {
					$this->error_template = 'board_empty';
				} else {
					$this->side_modules['top'] = array('board_list');
				}
			}
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('
				SELECT count(distinct(board.id))
				FROM board LEFT JOIN board_category ON board.id=board_category.thread_id
				WHERE '.$condition.'
					board.`type` = "thread" AND
					board_category.actual = 1
			',2)/$sets['pp']['board']);
			$return['navi']['base'] = '/board/'.$url[2].'/';

			return $return;
		} else {
			$error = true;
			$this->side_modules['top'] = array('board_list');
		}
	}

	function get_threads($condition, $limit) {
		$return = obj::db()->sql('
			SELECT board.*, board_category.category_id
			FROM board LEFT JOIN board_category ON board.id=board_category.thread_id
			WHERE '.$condition.'
				board.`type` = "thread" AND
				board_category.actual = 1
			GROUP BY board.id
			ORDER BY board.updated DESC '.$limit
		,'id');

		if (is_array($return)) {
			$categories = obj::db()->sql('
				SELECT * FROM board_category
				WHERE thread_id in ('.implode(',',array_keys($return)).')
			');

			foreach ($categories as $category) {
				$return[$category['thread_id']]['categories'][] = array_merge(
					$this->board_categories[$category['category_id']],
					array('actual' => $category['actual'])
				);
			}

			$this->process_content($return);

			$keys = 'thread='.implode(' or thread=', array_keys($return));
			$posts = obj::db()->sql('select * from board where `type`="post" and ('.$keys.')');

			if (is_array($posts)) {
				foreach ($posts as $post) {
					$return[$post['thread']]['posts'][$post['id']] = $post;
				}

				foreach ($return as $key => $thread) {

					if (!empty($thread['posts'])) {
						list($total_images, $total_flash, $total_video) = $this->process_content($thread['posts']);
						$total = count($thread['posts']);
						krsort($thread['posts']);
						$thread['posts'] = array_slice($thread['posts'],0,sets::pp('board_posts'));
						list($images, $flash, $video) = $this->count_content($thread['posts']);
						$return[$key]['posts'] = array_reverse($thread['posts']);
						$return[$key]['skipped'] = array(
							'images' => ($total_images - $images),
							'flash' => ($total_flash - $flash),
							'video' => ($total_video - $video),
							'posts' => ($total - count($thread['posts'])),
						);
					}
				}
			}
		} else {
			return false;
		}
		$this->build_inner_links($this->inner_links, $return);
		return $return;
	}

	function thread() {
		global $url; global $sets; global $error;

		if (intval($url[4])) {
			$return['display'] = array('board_thread');
			$op_post = obj::db()->sql('select * from board where `type`="thread" and id = '.$url[4], 1);

			$categories = obj::db()->sql('SELECT * FROM board_category WHERE thread_id ='.$url[4]);

			foreach ($categories as $category) {
				$op_post['categories'][] = array_merge(
					$this->board_categories[$category['category_id']],
					array('actual' => $category['actual'])
				);
			}


			$return['posts'] = array($op_post['id'] => $op_post);
			$op_post = & $return['posts'][$op_post['id']];

			$replies = (array) obj::db()->sql('select * from board where `type`="post" and thread = '.$url[4].' order by sortdate');
			foreach ($replies as $reply) {
				$return['posts'][$reply['id']] = $reply;
			}

			list($images, $video) = $this->process_content($return['posts']);
			$op_post['images_count'] = $images;
		}

		$wrong_board = !obj::db()->sql('select id from category where locate("|board|",area) and alias="'.$url[2].'"',2);

		if ($url[2] == 'download') {
			$this->template = 'main__board__download';
			$wrong_board = false;
		}

		if (
			empty($return['posts']) ||
			$op_post['type'] != 'thread' ||
			$wrong_board
		) {
			$error = true;
			$this->side_modules['top'] = array('board_list');
		} else {
			$this->thread = $url[4];

			$this->build_inner_links($this->inner_links, $return['posts']);
			$op_post = $this->check_downloads($op_post);
			return $return;
		}
	}

	function check_downloads($op_post) {
		$op_post['downloads']['pdf'] = true;
		if (
			$op_post['images_count'] > 1 &&
			class_exists('ZipArchive')
		) {
			$op_post['downloads']['zip'] = true;
		}
		return $op_post;
	}

	function count_content($array) {
		$images_count = 0; $video_count = 0; $flash_count = 0;
		if (!empty($array)) {
			foreach ($array as $key => $item) {
				if (!empty($item['content'])) {
					if (!empty($item['content']['image'])) {
						$images_count += count($item['content']['image']);
					}
					
					if (!empty($item['content']['random'])) {
						$images_count += count($item['content']['random']);
					}

					if (!empty($item['content']['flash'])) {
						$flash_count += count($item['content']['flash']);
					}

					if (!empty($item['content']['video'])) {
						$video_count += count($item['content']['video']);
					}
				}
			}
		}
		return array($images_count, $flash_count, $video_count);
	}

	function process_content(&$array) {
		global $url;
		$images_count = 0; $video_count = 0; $flash_count = 0;
		if (!empty($array)) {
			$ids = array_keys($array);
			$content_array = (array) obj::db()->sql('
				SELECT * FROM board_attachment
				WHERE post_id in ('.implode(',',$ids).')
				ORDER BY `order`
			');

			foreach ($array as $key => $item) {
				$array[$key]['content'] = array(
					'image' => array(),
					'flash' => array(),
					'video' => array(),
				);
			}


			foreach ($content_array as $content) {
				$array[$content['post_id']]['content'][$content['type']][] =
					unserialize(base64_decode($content['data']));
			}

			foreach ($array as $key => $item) {

				if (!empty($item['content'])) {
					$content = $item['content'];
					$current_count = 0;

					if (!empty($content['image'])) {
						foreach ($content['image'] as $image_key => $image) {
							$content['image'][$image_key]['full_size_info'] =
								obj::transform('file')->weight($image['weight']) .
								', ' . $image['sizes'] . ' пикселей';

							$images_count++;
							$current_count++;
						}
					}
					
					if (!empty($content['random'])) {
						foreach ($content['random'] as $random_key => $image) {
							$content['random'][$random_key]['full_size_info'] =
								obj::transform('file')->weight($image['size']) .
								', ' . $image['width'] . 'x' . $image['height'] . ' пикселей';							
							
							$images_count++;
							$current_count++;
						}
					}					

					if (!empty($content['flash'])) {
						foreach ($content['flash'] as $flash_key => $flash) {
							$content['flash'][$flash_key]['full_size_info'] =
								obj::transform('file')->weight($flash['weight']);

							$flash_count++;
							$current_count++;
						}
					}

					if (!empty($content['video'])) {
						$width = def::board('thumbwidth');

						foreach ($content['video'] as $video_key => $video) {
							$height = $width * $video['aspect'];

							$content['video'][$video_key]['object'] = str_replace(
								array('%video_width%','%video_height%'),
								array($width,$height),
								$video['object']
							);

							$content['video'][$video_key]['height'] = $height;

							$video_count++;
							$current_count++;
						}
					}

					$array[$key]['content'] = $content;
				}

				if (!empty($item['boards'])) {
					$array[$key]['boards'] = array_values(array_filter(array_unique(explode('|',$item['boards']))));
				}
				if ($url[2] && strlen($url[2]) < 3) {
					$array[$key]['current_board'] = $url[2];
				} elseif (!empty($array[$key]['categories'])) {
					$c_key = array_rand($array[$key]['categories']);
					$array[$key]['current_board'] = $array[$key]['categories'][$c_key]['alias'];
				}

				if (!empty($item['text'])) {
					preg_match_all('/&gt;&gt;(\d+)(\s|$|<br[^>]*>)/',$item['text'],$inner_links);
					foreach ($inner_links[1] as $inner_link) {
						$this->inner_links[] = $inner_link;
					}
				}

				if ($current_count > 1) {
					$array[$key]['multi_content'] = true;
				}
			}
		}
		return array($images_count, $flash_count, $video_count);
	}

	function build_inner_links($links, &$threads) {
		$inner_links = (array) obj::db()->sql('
			select board.id, board.thread, board_category.category_id
			FROM board LEFT JOIN board_category ON
				board.thread=board_category.thread_id OR
				board.id=board_category.thread_id
			WHERE
				board.`type`!="deleted" and
				board.`type`!="old" and
				(board.id='.implode(' or board.id=',$links).') and
				board_category.actual = 1
		');

		$this->inner_links = array();
		foreach ($inner_links as $link) {
			if (empty($this->inner_links[$link['id']])) {
				$this->inner_links[$link['id']] = array(
					'thread' => $link['thread'],
					'boards' => array()
				);
			}

			$this->inner_links[$link['id']]['boards'][] =
				$this->board_categories[$link['category_id']]['alias'];
		}

		if (is_array($threads)) {
			foreach ($threads as $key => $thread) {
				$threads[$key]['text'] = $this->set_inner_links($thread['text']);
				if (is_array($thread['posts'])) {
					foreach ($thread['posts'] as $post_key => $post) {
						$threads[$key]['posts'][$post_key]['text'] = $this->set_inner_links($post['text']);
					}
				}
			}
		}
	}

	function set_inner_links($text) {
		return preg_replace_callback('/&gt;&gt;(\d+)(\s|$|<br[^>]*>)/s',array(&$this,'set_link'),$text);
	}

	function set_link($id) {
		if (isset($this->inner_links[$id[1]])) {
			$data = $this->inner_links[$id[1]];
			if ($data['thread'] === $this->thread) {
				return '<a href="#board-'.$id[1].'">&gt;&gt;'.$id[1].'</a>'.$id[2];
			} else {
				$data['board'] = $data['boards'][array_rand($data['boards'])];
				return '<a href="/board/'.$data['board'].'/thread/'.($data['thread'] ? $data['thread'] : $id[1]).'#board-'.$id[1].'">&gt;&gt;'.$id[1].'</a>'.$id[2];
			}
		} else {
			return $id[0];
		}
	}
}
