<? 
include_once('engine'.SL.'engine.php');
class output__admin extends engine
{
	public $allowed_url = array(
		array(1 => '|admin|', 2 => 'any', 3 => 'any', 4 => 'any', 5 => 'any', 6 => 'any', 7 => 'any', 8 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),	
		'header' => array('top_buttons'),
		'top' => array(),
		'sidebar' => array('admin_functions'),		
		'footer' => array()
	);
	
	function get_data() {
		global $url; global $sets;
		$return['display'][] = 'admin_header';
		if (!$sets['user']['rights']) $return['display'][] = 'admin_loginform';
		else {
			if (method_exists(__CLASS__,$url[2])) {
				$function = $url[2];
				$return = $this->$function($return);
			}
			elseif ($url[2]) {
				$return['display'][] = 'admin_wrongurl';
			}
		}
		return $return;
	}
	
	function overview($return) {
		global $url; global $db;
		$return['display'][] = 'admin_overview';
		$return['post'] = $db->sql('select id, title, comment_count from post where area="workshop" order by sortdate desc');
		$return['video'] = $db->sql('select id, title, comment_count from video where area="workshop" order by sortdate desc');
		$return['art'] = $db->sql('select count(id) from art where area="workshop"',2);
		$return['tags'] = $db->sql('select * from misc where type="tag_type"','id');
		$return['order'] = $db->sql('select id, title, comment_count from orders where area="workshop" order by sortdate desc');
		$return['comment'] = $db->sql('select id, place, post_id, text, username from comment where(area != "deleted" and sortdate > '.((time()-86400)*1000).') order by sortdate desc');
		return $return;		
	}
	
	function tags($return) {
		global $url; global $db; global $sets;
		unset ($this->side_modules['sidebar']);
		if ($url[3] && $url[3] != 'search' && $url[3] != 'page') {
			$return['display'][] = 'admin_problemtags';
			if ($url[4] == 'match') {
				$tags = $db->sql('select * from tag','id');	
				$compare_tags = $tags;
				foreach ($tags as $key => $tag) {
					unset ($compare_tags[$key]);					
					foreach ($compare_tags as $match) {
						if ($tag['alias']) if ($tag['alias'] == $match['alias'] || $tag['alias'] == $match['name'] || ($match['variants'] && strpos($match['variants'],'|'.$tag['alias'].'|'))) { $return['tags'][] = array($tag, $match); continue; }
						if ($tag['name']) if ($tag['name'] == $match['alias'] || $tag['name'] == $match['name'] || ($match['variants'] && strpos($match['variants'],'|'.$tag['name'].'|'))) { $return['tags'][] = array($tag, $match); continue; }
						$tag_vars = array_filter(explode('|',trim($tag['variants'],'|'))); $match_vars = array_filter(explode('|',trim($match['variants'],'|')));
						if (!empty($tag_vars) && !empty($match_vars) && array_intersect($tag_vars,$match_vars)) { $return['tags'][] = array($tag, $match); continue; }
					}
				}
			} elseif ($url[4] == 'empty') {
				$return['tags'] = $db->sql('select * from tag where post_main+post_flea_market+video_main+video_flea_market+art_main+art_flea_market=0 order by id desc','id');	
			} else {
				$return['tags'] = $db->sql('select * from tag where (length(alias) - length(replace(alias,"_","")) > 2 or left(alias,1) = "_" or right(alias,1) = "_") order by id desc','id');				
			}
		} else {
			$return['display'][] = 'admin_tags';
			$return['display'][] = 'navi';
			if ($url[3] == 'search') {
				$return['navi']['curr'] = max(1,$url[6]);
				$return['navi']['meta'] = $url[2].'/'.$url[3].'/'.$url[4].'/';
				$locate = redo_safety(urldecode($url[4]));
				$return['tags'] = $db->sql('select * from tag where locate("'.$locate.'",alias) or locate("'.$locate.'",variants) or locate("'.$locate.'",name) order by id desc limit '.(($return['navi']['curr']-1)*$sets['pp']['tags_admin']).', '.$sets['pp']['tags_admin'],'id');
				$return['navi']['last'] = ceil($db->sql('select count(id) from tag where locate("'.urldecode($url[4]).'",alias) or locate("'.urldecode($url[4]).'",variants) or locate("'.urldecode($url[4]).'",name)',2)/$sets['pp']['tags_admin']);
			} else {
				$return['navi']['curr'] = max(1,$url[4]);
				$return['navi']['meta'] = $url[2].'/';
				$return['tags'] = $db->sql('select * from tag order by id desc limit '.(($return['navi']['curr']-1)*$sets['pp']['tags_admin']).', '.$sets['pp']['tags_admin'],'id');
				$return['navi']['last'] = ceil($db->sql('select count(id) from tag',2)/$sets['pp']['tags_admin']);				
			}
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['base'] = '/admin/';		
		}
		return $return;		
	}
}
