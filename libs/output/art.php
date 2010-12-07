<? 

class output__art extends engine
{
	function __construct() {
		global $cookie; global $url; global $sets;
		if (!$cookie) $cookie = new dinamic__cookie();
		$cookie->inner_set('visit.art',time(),false);
		$this->parse_area();
		if (!$url[2]) $this->error_template = 'booru_empty';		
		if ($url[2] == 'slideshow' && $url[3] && $url[4]) {
			foreach ($url as $key => $one) if ($key > 2) $url[$key-1] = $one;
			unset($url[count($url)-1]);
			$this->template = 'slideshow';
			$this->side_modules = array();
		}
	}
	public $allowed_url = array(
		array(1 => '|art|', 2 => '|page|', 3 => 'num', 4 => 'end'),
		array(1 => '|art|', 2 => '|tag|author|category|mixed|date|', 3 => 'any', 4 => '|page|', 5 => 'num', 6 => 'end'),
		array(1 => '|art|', 2 => '|pool|cg_packs|', 3 => '|page|', 4 => 'num', 5 => 'end'),
		array(1 => '|art|', 2 => '|pool|cg_packs|', 3 => 'num', 4 => '|page|sort|', 5 => 'num', 6 => 'end'),
		array(1 => '|art|', 2 => '|download|', 3 => 'any', 4 => 'end'),
		array(1 => '|art|', 2 => 'any', 3 => '|comments|', 4 => '|all|', 5 => 'end'),
		array(1 => '|art|', 2 => 'any', 3 => '|comments|', 4 => '|page|', 5 => 'num', 6 => 'end')		
	);
	public $template = 'booru';
	public $error_template = 'booru';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('top_buttons'),
		'top' => array('add_bar'),
		'sidebar' => array('masstag','art_tags','comments'),
		'footer' => array()
	);
	
	function get_data() {
		if ($this->template != 'slideshow') {
			global $db; global $url; global $error; global $sets; global $def;
			if (is_numeric($url[2])) {
				$return['display'] = array('booru_single','comments');
				$return['art'] = $this->get_art(1,'id='.$url[2].' and area != "deleted"');
				$url['area'] = $return['art'][0]['area'];
				$return['comments'] = $this->get_comments($url[1],$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
				$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
				$return['navi']['all'] = true;
				$return['navi']['name'] = "Страница комментариев";
				$return['navi']['meta'] = $url[2].'/comments/';
				$return['navi']['start'] = max($return['navi']['curr']-5,2);
				$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);				
				$this->side_modules['top'] = array();
			}
			elseif (substr($url[2],0,3) == 'cg_' && is_numeric(substr($url[2],3))) {
				if (!$sets['show']['nsfw']) $return['display'] = array('booru_w8m_nsfw','comments');
				else $return['display'] = array('booru_w8m_art','comments');
				$return['art'] = $db->base_sql('sub','select * from w8m_art where id='.substr($url[2],3),1);
				$return['gallery'] = $db->base_sql('sub','select * from w8m_galleries where id='.$return['art']['gallery_id'],1);
				$return['comments'] = $this->get_comments($url[1],$url[2],(is_numeric($url[5]) ? $url[5] : ($url[4] == 'all' ? false : 1)));
				$return['navi']['curr'] = ($url[4] == 'all' ? 'all' : max(1,$url[5]));
				$return['navi']['all'] = true;
				$return['navi']['name'] = "Страница комментариев";
				$return['navi']['meta'] = $url[2].'/comments/';
				$return['navi']['start'] = max($return['navi']['curr']-5,2);
				$return['navi']['last'] = ceil($return['comments']['number']/$sets['pp']['comment_in_post']);				
				$this->side_modules['top'] = array();				
			}
			elseif ($url[2] != 'pool' && $url[2] != 'cg_packs' && $url[2] != 'download') {
				$return['display'] = array('booru_page','navi');
				if ($url[2] == 'page' || !$url[2]) {				
					$area = 'area = "'.$url['area'].'"';
					$return['navi']['curr'] = max(1,$url[3]);
					if ($sets['artsort']['tag'] == 1) $return['art']['thumbs'] =  $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'], $area, 'order by (length(tag) - length(replace(tag,\'|\',\'\'))) desc');
					elseif ($sets['artsort']['tag'] == -1) $return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'], $area, 'order by (length(tag) - length(replace(tag,\'|\',\'\')))');
					else $return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'], $area);
				}
				elseif ($url[2] == 'date') {
					$parts = explode('-',$url[3]); global $transform_text;
					if (!$transform_text) $transform_text = new transform__text();
					if (is_numeric($parts[0].$parts[1].$parts[2]) && count($parts) == 3) {
						$area = 'area = "'.$url['area'].'" and pretty_date ="'.$transform_text->rumonth($parts[1]).' '.$parts[2].', '.$parts[0].'"';
						$return['navi']['curr'] = max(1,$url[5]);				
						$return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],$area);
						$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
					}
					else $error = true;
				}			
				elseif ($url[2] != 'mixed') {
					$this->mixed_parse($url[2].'='.$url[3]);				
					$area = 'area = "'.$url['area'].'" and locate("|'.($url['tag'] ? $url['tag'] : mysql_real_escape_string(urldecode($url[3]))).'|",art.'.$url[2].')';
					$return['navi']['curr'] = max(1,$url[5]);				
					$return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],$area);
					$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
					$return['rss'] = $this->make_rss($url[1],$url[2],$url[3]);								
				}
				else {
					$area = $this->mixed_make_sql($this->mixed_parse($url[3]));
					$return['navi']['curr'] = max(1,$url[5]);
					$return['art']['thumbs'] = $this->get_art(($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],$area);
					$return['navi']['meta'] = $url[2].'/'.$url[3].'/';					
				}
				$return['navi']['start'] = max($return['navi']['curr']-5,2);
				$return['navi']['last'] = ceil($db->sql('select count(id) from art where ('.$area.')',2)/$sets['pp']['art']);
			}
			else {
				if ($url[2] == 'pool') {
					if (is_numeric($url[3])) {
						if ($url[4] != 'sort') $return['display'] = array('booru_poolsingle','booru_page','navi');
						else $return['display'] = array('booru_poolsingle','booru_page');
						$return['pool'] = $db->sql('select * from art_pool where id='.$url[3],1);
						$pool = array_reverse(array_filter(array_unique(explode('|',$return['pool']['art']))));
						if ($url[4] != 'sort') {
							$return['navi']['curr'] = max(1,$url[5]);
							$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
							$return['navi']['start'] = max($return['navi']['curr']-5,2);
							$pool = array_slice($pool,($return['navi']['curr']-1)*$sets['pp']['art'],$sets['pp']['art']);			
							$return['navi']['last'] = ceil($return['pool']['count']/$sets['pp']['art']);
						}
						$where = 'id='.implode(' or id=',$pool);
						$pool = array_flip($pool);
						if ($art = $this->get_art(false,$where,'')) {
							foreach ($art as $one) $return['art']['thumbs'][$pool[$one['id']]] = $one;
							ksort($return['art']['thumbs']);
						}
						$return['rss'] = $this->make_rss($url[1],$url[2],$url[3]);					
					}
					else {
						$return['display'] = array('booru_poolpage','navi');
						$return['navi']['curr'] = max(1,$url[4]);
						$return['navi']['meta'] = $url[2].'/';
						$return['navi']['start'] = max($return['navi']['curr']-5,2);
						$return['navi']['last'] = ceil($db->sql('select count(id) from art_pool',2)/$sets['pp']['art_pool']);
						$return['pools'] = $db->sql('select id, name, count from art_pool order by sortdate desc limit '.($return['navi']['curr']-1)*$sets['pp']['art_pool'].', '.$sets['pp']['art_pool'],'id');
					}
				}
				elseif ($url[2] == 'cg_packs') {
					$this->side_modules['top'] = array();
					if (is_numeric($url[3])) {
						if (!$sets['show']['nsfw']) $return['display'] = array('booru_w8m_nsfw','navi');
						else $return['display'] = array('booru_w8m_single','navi');
						$return['navi']['curr'] = max(1,$url[5]);
						$return['navi']['meta'] = $url[2].'/'.$url[3].'/';
						$return['navi']['start'] = max($return['navi']['curr']-5,2);
						$return['navi']['last'] = ceil($db->base_sql('sub','select count(id) from w8m_art where gallery_id='.$url[3],2)/$sets['pp']['art']);
						$return['pool'] = $db->base_sql('sub','select * from w8m_galleries where id='.$url[3],1);
						$return['thumbs'] = $db->base_sql('sub','select * from w8m_art where gallery_id='.$url[3].' order by folder, filename desc limit '.($return['navi']['curr']-1)*$sets['pp']['art'].', '.$sets['pp']['art'],'id');
					}
					else {
						if (!$sets['show']['nsfw']) $return['display'] = array('booru_w8m_nsfw','navi');
						else $return['display'] = array('booru_w8m_pools','navi');
						$return['navi']['curr'] = max(1,$url[4]);
						$return['navi']['meta'] = $url[2].'/';
						$return['navi']['start'] = max($return['navi']['curr']-5,2);
						$return['navi']['last'] = ceil($db->base_sql('sub','select count(id) from w8m_galleries where sort_order > 0',2)/$sets['pp']['art_cg_pool']);
						$return['pools'] = $db->base_sql('sub','select * from w8m_galleries where sort_order > 0 order by sort_order desc limit '.($return['navi']['curr']-1)*$sets['pp']['art_cg_pool'].', '.$sets['pp']['art_cg_pool'],'id');
					}
				}
				else {						
					if (!preg_match('/\.(png|jpg|jpeg|gif)/iu',$url[3])) {
						$string = bin2hex(_base64_decode($url[3]));
						if ($name = $db->base_sql('sub','select name from w8m_galleries where md5="'.$string.'"',2)) {
							$this->template = 'download';
							$this->side_modules = array();
							return array('file' => '/var/www/nameless/data/www/w8m.4otaku.ru/image/'.$string.'.zip', 'name' => str_replace(array(' ',';'),'_',$name).'.zip');
						} 							
						elseif ($data = $db->base_sql('sub','select filename, gallery_id, ext from w8m_art where md5="'.$string.'"',1)) {
							$gallery = $db->base_sql('sub','select md5 from w8m_galleries where id='.$data['gallery_id'],2);
							$this->template = 'download';
							$this->side_modules = array();
							if ($data['ext'] == 'jpg') $type = 'jpeg'; else $type = $data['ext'];
							return array('file' => '/var/www/nameless/data/www/w8m.4otaku.ru/image/'.$gallery.'/full/'.$string.'.'.$data['ext'], 'name' => str_replace(array(' ',';'),'_',$data['filename']), 'type' => $type);
						} 
						else $error = true;
					}
					else {
						$this->template = 'download';
						$this->side_modules = array();
						if (pathinfo($url[3],PATHINFO_EXTENSION) == 'jpg') $type = 'jpeg'; else $type = pathinfo($url[3],PATHINFO_EXTENSION);
						return array('file' => ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$url[3], 'name' => $url[3], 'type' => $type);							
					}
				}
			}
			$return['navi']['base'] = '/art'.($url['area'] != $def['area'][0] ? '/'.$url['area'] : '').'/';		
			return $return;
		}
	}
	
	function get_art($limit, $area, $order = 'order by sortdate desc') {
		global $error; global $db; global $url; global $check; global $def; global $sets;
		if ($limit) $limit = 'limit '.$limit;
		$return = $db->sql('select * from art where ('.$area.') '.$order.' '.$limit);
		if (is_array($return)) {
			if ($limit == 'limit 1') {
				$return[0]['translations']['full'] = unserialize(base64_decode($db->sql('select data from art_translation where (art_id='.$return[0]['id'].' and active = 1)',2)));
				if (is_array($return[0]['translations']['full'])) foreach ($return[0]['translations']['full'] as $key => $one) $return[0]['translations']['full'][$key]['text'] = str_replace('"','&quot;',$one['text']); 
				if ($return[0]['resized'] && is_array($return[0]['translations']['full'])) {
					$size = explode('x',$return[0]['resized']);
					$coeff = $size[0] / $def['booru']['resizewidth'];
					foreach ($return[0]['translations']['full'] as $one) {
						foreach ($one as $key => $param) if ($key != 'text') $one[$key] = round($param / $coeff);
						$return[0]['translations']['resized'][] = $one;
					}
				}
				if ($pools = trim($return[0]['pool'],'|')) {
					$return[0]['pool'] = $db->sql('select id, name, art from art_pool where id='.str_replace('|',' or id=',$pools),'id');
					foreach ($return[0]['pool'] as &$pool) {
						$pool['art'] = array_reverse(explode('|',trim($pool['art'],'|')));
						$pool['left'] = $pool['art'][array_search($return[0]['id'],$pool['art'])-1];
						$pool['right'] = $pool['art'][array_search($return[0]['id'],$pool['art'])+1];
					}
					unset($pool);
				}
			}
			foreach ($return as &$art) 
				if ($check->link($art['source'])) $art['source'] = '<a href="'.$art['source'].'" target="_blank">'.$art['source'].'</a>';
			$meta = $this->get_meta($return,array('category','author','tag'));
			foreach ($meta as $key => $type) 
				if (is_array($type))
					foreach ($return as &$art) 
						 foreach ($type as $alias => $name) 
							if (stristr($art[$key],'|'.$alias.'|')) 
								$art['meta'][$key][$alias] = $name;
			return $return;
		}
		else $error = true;
	}
}
