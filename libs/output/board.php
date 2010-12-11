<?
include_once('engine'.SL.'engine.php');
class output__board extends engine
{
	public $allowed_url = array(
		array(1 => '|board|', 2 => 'any', 3=> '|page|thread|', 4 => 'num', 5 => 'end'),
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

	function get_data() {
		global $url;
		if (!$url[2]) return $this->main();
		elseif ($url[3] != 'thread') return $this->board();
		else return $this->thread();
	}

	function main() {
		$return['display'] = array('board_main');
		return $return;
	}

	function board() {
		global $url; global $db; global $sets; global $error;
		
		if (obj::db()->sql('select id from category where locate("|board|",area) and alias="'.$url[2].'"',2)) {		
			$return['display'] = array('board_page', 'navi');
			$return['navi']['curr'] = max(1,$url[4]);
			$limit = 'limit '.($return['navi']['curr']-1)*$sets['pp']['board'].', '.$sets['pp']['board'];
			$return['threads'] = $db->sql('select * from board where locate("|'.$url[2].'|",`boards`) and `type`="2" order by updated desc '.$limit,'id');
			if (is_array($return['threads'])) {
				$this->process_content($return['threads']);

				$keys = 'thread='.implode(' or thread=', array_keys($return['threads']));
				$posts = $db->sql('select * from board where `type`="1" and ('.$keys.')');

				if (is_array($posts)) {				
					foreach ($posts as $post) {
						$return['threads'][$post['thread']]['posts'][$post['sortdate']] = $post;
					}

					foreach ($return['threads'] as $key => $thread) {
						if (!empty($thread['posts'])) {
							list($total_images, $total_video) = $this->process_content($thread['posts']);
							$total = count($thread['posts']);
							krsort($thread['posts']);
							$thread['posts'] = array_slice($thread['posts'], 0, $sets['pp']['board_posts']);
							list($images, $video) = $this->process_content($thread['posts']);
							$return['threads'][$key]['posts'] = array_reverse($thread['posts']);
							$return['threads'][$key]['skipped'] = array(
								'images' => ($total_images - $images),
								'video' => ($total_video - $video),
								'posts' => ($total - count($thread['posts'])),
							);
						}
					}
				}
			} else {
				$error = true;
				if ($return['navi']['curr'] == 1) {
					$this->error_template = 'board_empty';
				}
			}
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil($db->sql('select count(*) from board where locate("|'.$url[2].'|",`boards`) and `type` = "2"',2)/$sets['pp']['board']);
			return $return;
		} else {
			$error = true;
			$this->side_modules['top'] = array('board_list');			
		}
	}

	function thread() {
		global $url; global $db; global $sets; global $error;

		if (intval($url[4])) {
			$return['display'] = array('board_thread');
			$return['posts'] = $db->sql('select * from board where `type`!="0" and (thread = '.$url[4].' or id = '.$url[4].') order by type, sortdate');
			$this->process_content($return['posts']);
		}

		if (empty($return['posts']) || $return['posts'][0]['type'] != 2) {
			$error = true;
			$this->side_modules['top'] = array('board_list');
		} else {
			return $return;
		}
	}

	function process_content(&$array) {
		$images = 0; $video = 0;
		if (!empty($array)) {
			foreach ($array as $key => $item) {
				if (!empty($item['content'])) {
					if ($item['content']{0} == '#') {
						$array[$key]['image'] = explode('#', $item['content']);
						$images++;
					} else {
						$array[$key]['video'] = str_replace(array('%video_width%','%video_height%'),array(def::get('board','thumbwidth'),def::get('board','thumbheight')),$item['content']);
						$video++;
					}
				}
			}
		}
		return array($images, $video);
	}
}
