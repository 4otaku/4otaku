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
		'sidebar' => array('comments','quicklinks'),
		'footer' => array()
	);
	
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
		$return['display'] = array('board_page', 'navi');
		$return['navi']['curr'] = max(1,$url[4]);
		$limit = 'limit '.($return['navi']['curr']-1)*$sets['pp']['board'].', '.$sets['pp']['board'];
		$return['threads'] = $db->sql('select * from board where locate("|'.$url[2].'|",`boards`) and `type` = "2" order by updated '.$limit,'id');
		if (is_array($return['threads'])) {
			foreach ($return['threads'] as $key => $thread) {
				if ($thread['content']{0} == '#') {
					$return['threads'][$key]['image'] = explode('#', $thread['content']);
				} else {
					$return['threads'][$key]['video'] = str_replace(array('%video_width%','%video_height%'),array($def['board']['thumbwidth'],$def['board']['thumbheight']),$thread['content']);
				}
			}

			$keys = 'thread='.implode(' or thread=', array_keys($return['threads']));
			$posts = $db->sql('select * from board where '.$keys);
		
			if (is_array($posts)) {
				foreach ($posts as $post) {
					$return['threads'][$post['thread']]['posts'][$post['sortdate']] = $post;
				}
			
				foreach ($return['threads'] as $key => $thread) {
					krsort($thread['posts']);
					$return['threads'][$key] = array_slice($thread['posts'], 0, $sets['pp']['board_posts']);
				}
			}
		} else {
			if ($return['navi']['curr'] != 1) $error = true;
		}
		$return['navi']['start'] = max($return['navi']['curr']-5,2);
		$return['navi']['last'] = ceil($db->sql('select count(*) from board where locate("|'.$url[2].'|",`boards`) and `type` = "2"',2)/$sets['pp']['board']);
		return $return;
	}
	
	function thread() {
		global $url; global $db; global $sets; 
		$return['display'] = array('board_thread');
		$return['posts'] = $db->sql('select * from board where thread = '.$url[4].' order by sortdate','id');
		return $return;
	}
}
