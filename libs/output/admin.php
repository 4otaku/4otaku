<? 

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
		global $url;
		$return['display'][] = 'admin_overview';
		$return['post'] = obj::db()->sql('select id, title, comment_count from post where area="workshop" order by sortdate desc');
		$return['video'] = obj::db()->sql('select id, title, comment_count from video where area="workshop" order by sortdate desc');
		$return['art'] = obj::db()->sql('select count(id) from art where area="workshop"',2);
		$return['tags'] = obj::db()->sql('select * from misc where type="tag_type"','id');
		$return['order'] = obj::db()->sql('select id, title, comment_count from orders where area="workshop" order by sortdate desc');
		$return['comment'] = obj::db()->sql('select id, place, post_id, text, username from comment where(area != "deleted" and sortdate > '.((time()-86400)*1000).') order by sortdate desc');
		return $return;		
	}
	
	function updates($return) {
		global $url;

		if (empty($url[3])) {
			$return['display'][] = 'admin_updates_posts';
			$return['posts'] = obj::db()->sql('select id, title, update_count from post where update_count order by update_count desc, sortdate desc','id');
		} elseif (empty($url[4])) {
			$return['display'][] = 'admin_updates_single';
			$return['updates'] = obj::db()->sql('select * from updates where post_id = '.$url[3].' order by sortdate desc','id');			
			foreach ($return['updates'] as &$update) $update['link'] = unserialize($update['link']);
		} else {
			$return['display'][] = 'admin_updates_edit';
			$return['update'] = obj::db()->sql('select * from updates where id = '.$url[4],1);
			$return['update']['link'] = unserialize($return['update']['link']);
		}

		return $return;
	}	
	
	function tags($return) {
		global $url; global $sets;
		unset ($this->side_modules['sidebar']);
		if ($url[3] && $url[3] != 'search' && $url[3] != 'page' && $url[3] != 'merge') {
			$return['display'][] = 'admin_problemtags';
			if ($url[4] == 'match') {
				$tags = obj::db()->sql('select * from tag','id');	
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
				$return['tags'] = obj::db()->sql('select * from tag where post_main+post_flea_market+video_main+video_flea_market+art_main+art_flea_market=0 order by id desc','id');	
			} else {
				$return['tags'] = obj::db()->sql('select * from tag where (length(alias) - length(replace(alias,"_","")) > 2 or left(alias,1) = "_" or right(alias,1) = "_") order by id desc','id');				
			}
		} else {
			if ($url[3] == 'merge') {
				$return['display'][] = 'admin_mergetags';
				$return['tag'] = obj::db()->sql('select * from tag where alias = "'.$url[4].'"',1);
			} else {				
				$return['display'][] = 'admin_tags';
				$return['display'][] = 'navi';
				if ($url[3] == 'search') {
					$return['navi']['curr'] = max(1,$url[6]);
					$return['navi']['meta'] = $url[2].'/'.$url[3].'/'.$url[4].'/';					
					list($return['tags'], $return['navi']['last']) = self::search_tags($url[4],$return['navi']['curr'],$sets['pp']['tags_admin']);
				} else {
					$return['navi']['curr'] = max(1,$url[4]);
					$return['navi']['meta'] = $url[2].'/';
					$return['tags'] = obj::db()->sql('select * from tag order by id desc limit '.(($return['navi']['curr']-1)*$sets['pp']['tags_admin']).', '.$sets['pp']['tags_admin'],'id');
					$return['navi']['last'] = ceil(obj::db()->sql('select count(id) from tag',2)/$sets['pp']['tags_admin']);				
				}
				$return['navi']['start'] = max($return['navi']['curr']-5,2);
				$return['navi']['base'] = '/admin/';		
			}
		}
		return $return;		
	}
	
	static function search_tags($query, $current, $step) {
		$locate = redo_safety(urldecode($query));
		$tags = obj::db()->sql('select * from tag where locate("'.$locate.'",alias) or locate("'.$locate.'",variants) or locate("'.$locate.'",name) order by id desc limit '.(($current-1)*$step).', '.$step,'id');
		$page_count = ceil(obj::db()->sql('select count(id) from tag where locate("'.$locate.'",alias) or locate("'.$locate.'",variants) or locate("'.$locate.'",name)',2)/$step);
		return array($tags, $page_count);
	}
}
