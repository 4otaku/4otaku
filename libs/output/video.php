<? 
include_once('engine/engine.php');
class output__video extends engine
{
	function __construct() {
		global $cookie; global $url;
		if (!$cookie) $cookie = new dinamic__cookie();
		$cookie->inner_set('visit.video',time(),false);	
		$this->parse_area();
		if (!$url[2]) $this->error_template = 'empty';		
	}
	public $allowed_url = array(
		array(1 => '|video|', 2 => '|tag|category|author|mixed|', 3=> 'any', 4 => '|page|', 5 => 'num', 6 => 'end'),
		array(1 => '|video|', 2 => '|page|', 3 => 'num', 4 => 'end'),
		array(1 => '|video|', 2 => 'num', 3=> '|comments|', 4 => '|all|', 5 => 'end'),
		array(1 => '|video|', 2 => 'num', 3=> '|comments|', 4 => '|page|', 5 => 'num', 6 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),	
		'header' => array('top_buttons'),
		'top' => array('add_bar'),
		'sidebar' => array('comments','quicklinks','orders','tags'),
		'footer' => array()
	);
	
	function get_data() {
		global $url; global $db; global $def; global $sets; 
		$return['navigation'] = $this->get_navigation(array('tag','category'));		
		if (is_numeric($url[2]) && $url[2]>0) {
			$return['display'] = array('video','comments');		
			$return['video'] = $this->get_video(1,'id='.$url[2].' and area != "deleted"',$sets['video']['full']);
			$url['area'] = $return['video'][0]['area'];
			$return['comments'] = $this->get_comments($url[1],$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
			$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
			$return['navi']['all'] = true;
			$return['navi']['name'] = "Страница комментариев";
			$return['navi']['meta'] = $url[2].'/comments/';
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);			
			$this->side_modules['top'] = array();
		}
		else {
			$return['display'] = array('video','navi');		
			if ($url[2] == 'page' || !$url[2]) {
				$area = 'area = "'.$url['area'].'"';
				$return['navi']['curr'] = max(1,$url[3]);			
				$return['video'] = $this->get_video(($return['navi']['curr']-1)*$sets['pp']['video'].', '.$sets['pp']['video'],$area,$sets['video']['thumb']);
			}
			elseif ($url[2] == 'tag' || $url[2] == 'category' || $url[2] == 'author' || $url[2] == 'language') {
				$this->mixed_parse($url[2].'='.$url[3]);
				$area = 'area = "'.$url['area'].'" and locate("|'.($url['tag'] ? $url['tag'] : mysql_real_escape_string(urldecode($url[3]))).'|",video.'.$url[2].')';
				$return['navi']['curr'] = max(1,$url[5]);				
				$return['video'] = $this->get_video(($return['navi']['curr']-1)*$sets['pp']['video'].', '.$sets['pp']['video'],$area,$sets['video']['thumb']);
				$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
				$return['rss'] = $this->make_rss($url[1],$url[2],$url[3]);
			}
			elseif ($url[2] == 'mixed') {
				$area = $this->mixed_make_sql($this->mixed_parse($url[3]));
				$return['navi']['curr'] = max(1,$url[5]);				
				$return['video'] = $this->get_video(($return['navi']['curr']-1)*$sets['pp']['video'].', '.$sets['pp']['video'],$area,$sets['video']['thumb']);
				$return['navi']['meta'] = $url[2].'/'.$url[3].'/';			
			}
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil($db->sql('select count(id) from video where ('.$area.')',2,'count(id)')/$sets['pp']['video']);		
		}
		$return['navi']['base'] = '/video'.($url['area'] != $def['area'][0] ? '/'.$url['area'] : '').'/';		
		return $return;
	}
	
	function get_video($limit, $area, $sizes) {
		global $error; global $db;
		$sizes = explode('x',$sizes);
		$return = $db->sql('select * from video where ('.$area.') order by sortdate desc limit '.$limit);
		if (is_array($return)) {
			foreach ($return as &$video) {
				$video['object'] = str_replace(array('%video_width%','%video_height%'),$sizes,$video['object']);
				$video['text'] = trim($video['text']);
			}
			$meta = $this->get_meta($return,array('category','author','tag'));
			foreach ($meta as $key => $type) 
				if (is_array($type))
					foreach ($return as &$video) 
						 foreach ($type as $alias => $name) 
							if (stristr($video[$key],'|'.$alias.'|')) 
								$video['meta'][$key][$alias] = $name;
			return $return;
		}
		else $error = true;
	}		
}
