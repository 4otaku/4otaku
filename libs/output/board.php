<?
include_once('engine'.SL.'engine.php');
class output__board extends engine
{
	public $allowed_url = array(
		array(1 => '|board|', 2 => 'any', 3=> 'any', 4 => 'num', 5 => 'end'),
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('top_buttons'),
		'top' => array('add_bar'),
		'sidebar' => array('board_list','comments','quicklinks'),
		'footer' => array()
	);

	public $error_template = 'board';

	private $inner_links = array();
	private $thread = false;

	function get_data() {
		global $url;
		if (!$url[2] || $url[2] == 'page') return $this->main();
		elseif ($url[3] != 'thread') return $this->board();
		else return $this->thread();
	}

	function main() {
		global $url;
		
		$return['boards'] = obj::db()->sql('select alias, name from category where locate("|board|",area) order by id','alias');
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
			$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from board where `type` = "2"',2)/sets::pp('board'));
			$return['navi']['base'] = '/board/';
		}
		return $return;
	}

	function board() {
		global $url; global $sets; global $error;

		if (obj::db()->sql('select id from category where locate("|board|",area) and alias="'.$url[2].'"',2)) {
			$return['display'] = array('board_page', 'navi');
			$return['navi']['curr'] = max(1,$url[4]);
			$limit = 'limit '.($return['navi']['curr']-1)*$sets['pp']['board'].', '.$sets['pp']['board'];
			$condition = 'locate("|'.$url[2].'|",`boards`) and ';
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
			$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from board where locate("|'.$url[2].'|",`boards`) and `type` = "2"',2)/$sets['pp']['board']);
			$return['navi']['base'] = '/board/'.$url[2].'/';
			
			return $return;
		} else {
			$error = true;
			$this->side_modules['top'] = array('board_list');
		}
	}
	
	function get_threads($condition, $limit) {
		$return = obj::db()->sql('select * from board where '.$condition.' `type`="2" order by updated desc '.$limit,'id');

		if (is_array($return)) {
			$this->process_content($return);

			$keys = 'thread='.implode(' or thread=', array_keys($return));
			$posts = obj::db()->sql('select * from board where `type`="1" and ('.$keys.')');

			if (is_array($posts)) {
				foreach ($posts as $post) {
					$return[$post['thread']]['posts'][$post['sortdate']] = $post;
				}

				foreach ($return as $key => $thread) {
					if (!empty($thread['posts'])) {
						list($total_images, $total_video) = $this->process_content($thread['posts']);
						$total = count($thread['posts']);
						krsort($thread['posts']);
						$thread['posts'] = array_slice($thread['posts'],0,sets::pp('board_posts'));
						list($images, $video) = $this->count_content($thread['posts']);
						$return[$key]['posts'] = array_reverse($thread['posts']);
						$return[$key]['skipped'] = array(
							'images' => ($total_images - $images),
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
			$return['posts'] = obj::db()->sql('select * from board where `type`!="0" and (thread = '.$url[4].' or id = '.$url[4].') order by type, sortdate');
			list($images, $video) = $this->process_content($return['posts']);
			$return['posts'][0]['images_count'] = $images;
		}

		if (
			empty($return['posts']) || 
			$return['posts'][0]['type'] != 2 ||
			!obj::db()->sql('select id from category where locate("|board|",area) and alias="'.$url[2].'"',2)
		) {
			$error = true;
			$this->side_modules['top'] = array('board_list');
		} else {
			$this->thread = $url[4];
			$this->build_inner_links($this->inner_links, $return['posts']);
			return $return;
		}
	}
	
	function count_content($array) {
		$images_count = 0; $video_count = 0;
		if (!empty($array)) {
			foreach ($array as $key => $item) {
				if (!empty($item['content'])) {					
					if (isset($item['content']['image'])) {
						$images_count += count($item['content']['image']);
					}
					
					if (isset($item['content']['video'])) {
						$video_count++;
					}
				}
			}
		}
		return array($images_count, $video_count);
	}	

	function process_content(&$array) {
		global $url;
		$images_count = 0; $video_count = 0;
		if (!empty($array)) {
			foreach ($array as $key => $item) {
				if (!empty($item['content'])) {					
					$content = unserialize(base64_decode($item['content']));
					
					if (is_array($content['image'])) {
						foreach ($content['image'] as $image_key => $image) {
							if ($image['weight'] > 1024*1024) {
								$filesize = round($image['weight']/(1024*1024),1).' мегабайт';
							} elseif ($filesize > 1024) {
								$filesize = round($image['weight']/1024,1).' килобайт';
							} else {
								$filesize = $image['weight'].' байт';
							}							
							
							$content['image'][$image_key]['full_size_info'] = 
								$filesize . ', ' . $image['sizes'] . ' пикселей';
								
							$images_count++;
						}
					}
					
					if (isset($content['video'])) {
						$width = def::board('thumbwidth');
						$height = $width * $content['video']['aspect'];
						
						$content['video']['object'] = str_replace(
							array('%video_width%','%video_height%'),
							array($width,$height), 
							$content['video']['object']
						);
						
						$content['video']['api']['width'] = $width;
						$content['video']['api']['height'] = $height;
						
						$video_count++;
					}
					
					$array[$key]['content'] = $content;
				}
				
				if (!empty($item['boards'])) {
					$array[$key]['boards'] = array_values(array_filter(array_unique(explode('|',$item['boards']))));
				}
				$array[$key]['current_board'] = $url[2] && $url[2] != 'page' ? $url[2] : $array[$key]['boards'][array_rand($array[$key]['boards'])];
				if (!empty($item['text'])) {
					preg_match_all('/&gt;&gt;(\d+)(\s|$|<br[^>]*>)/',$item['text'],$inner_links);
					foreach ($inner_links[1] as $inner_link) {
						$this->inner_links[] = $inner_link;
					}
				}
			}
		}
		return array($images_count, $video_count);
	}

	function build_inner_links($links, &$threads) {
		$this->inner_links = obj::db()->sql('select id, thread, boards from board where `type`!="0" and (id='.implode(' or id=',$links).')','id');
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
				$data['boards'] = explode('|',trim($data['boards'],'|'));
				$data['board'] = $data['boards'][array_rand($data['boards'])];
				return '<a href="/board/'.$data['board'].'/thread/'.($data['thread'] ? $data['thread'] : $id[1]).'#board-'.$id[1].'">&gt;&gt;'.$id[1].'</a>'.$id[2];
			}
		} else {
			return $id[0];
		}
	}
}
