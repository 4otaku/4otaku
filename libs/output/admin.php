<?

class output__admin extends engine
{
	public $allowed_url = array(
		array(1 => '|admin|', 2 => 'any', 3 => 'any', 4 => 'any', 5 => 'any', 6 => 'any', 7 => 'any', 8 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('admin_functions'),
		'footer' => array()
	);

	function get_data() {
		global $url; global $sets;
		$return['display'][] = 'admin_header';
		if (!$sets['user']['rights']) {
			$return['display'][] = 'admin_loginform';
		} else {
			if (method_exists($this,$url[2])) {
				$function = $url[2];
				$return = $this->$function($return);
			} elseif ($url[2]) {
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

	function subscribe($return) {
		$return['display'][] = 'admin_subscribe';
		$return['author'] = obj::db()->sql('select alias, name from author','alias');
		$return['category'] = obj::db()->sql('select alias, name from category','alias');
		$return['language'] = obj::db()->sql('select alias, name from language','alias');
		return $return;
	}

	function duplicates($return) {
		$return['display'][] = 'admin_duplicateslist';
		$duplicates = obj::db()->sql('select id, similar from art_similar where similar != "|" and similar != ""','id');

		if(is_array($duplicates))
		{
			$art_ids = array();
			$pairings = array();

			foreach ($duplicates as $id => $similar) {
				$similar = explode('|', trim($similar, '|'));
				foreach ($similar as $item) {
					$art_ids[] = $item;
					$pairings[] = min($id,$item).'-'.max($id,$item);
				}
				$art_ids[] = $id;
			}

			$art_ids = array_unique($art_ids);
			$return['doubles'] = array_unique($pairings);

			$return['arts'] = obj::db()->sql('select * from art where id in ("'.implode('","', $art_ids).'")','id');

			$translations = obj::db()->sql('select art_id from art_translation where art_id in ("'.implode('","', $art_ids).'")');

			foreach ($return['arts'] as $id => $art) {
				$image = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$art['md5'].'.'.$art['extension'];
				$fileinfo = getimagesize($image);
				$return['arts'][$id]['width'] = $fileinfo[0];
				$return['arts'][$id]['height'] = $fileinfo[1];
				$return['arts'][$id]['size'] = obj::transform('file')->weight(filesize($image));
				$return['arts'][$id]['translation'] = in_array($id, (array) $translations);
			}
			unset ($art);

			$meta = $this->get_meta($return['arts'],array('tag'));
			foreach ($return['arts'] as &$art)
				foreach ($meta['tag'] as $alias => $name)
					if (stristr($art['tag'],'|'.$alias.'|'))
						$art['meta']['tag'][$alias] = $name['name'];

			unset ($art);
		}

		return $return;
	}

	function menu($return) {

		$return['display'][] = 'admin_menu';
		$return['select'] = (array) obj::db()->sql('select id, name from head_menu where parent = 0 order by `order`', 'id');

		return $return;
	}

	function cg_packs($return) {
		global $url;

		$return['display'][] = 'admin_packs_menu';
		if ($url[3] == 'upload') {
			$return['display'][] = 'admin_packs_upload';
		} elseif ($url[3] == 'manage') {
			$return['display'][] = 'admin_packs_manage';
			$return['display'][] = 'navi';
			$return['navi']['curr'] = max(1,$url[5]);
			$return['navi']['meta'] = '/';
			$return['navi']['start'] = max($return['navi']['curr']-5,2);
			$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from art_pack where cover != ""',2)/50);
			$return['navi']['base'] = '/admin/cg_packs/manage';

			$return['packs'] = obj::db()->sql('select id, title from art_pack where cover != "" order by id limit '.(($return['navi']['curr']-1)*50).', 50', 'id');

			if ($return['navi']['curr'] == 1) {
				$return['new'] = obj::db()->sql('select id, title from art_pack where cover = ""');
				if (!empty($return['new'])) {
					foreach ($return['new'] as &$pack) {
						$pack['status'] = obj::db()->sql('select data1 from misc where type="pack_status" and data2 = '.$pack['id'],2);
						$pack['count'] = obj::db()->sql('select count(*) from misc where type="pack_art" and data1 = '.$pack['id'],2);
					}
				}
			}
		} elseif ($url[3] == 'sort' && !empty($url[4])) {
			$return['display'][] = 'admin_packs_sort';

			$return['gal'] = obj::db()->sql('select * from art_pack where id = '.$url[4],1);
			$return['art'] = obj::db()->sql('select * from art_in_pack as p left join art as a on p.art_id = a.id where p.pack_id = '.$url[4].' order by `order`');
		} elseif ($url[3] == 'edit' && !empty($url[4])) {
			$return['display'][] = 'admin_packs_edit';
			$return['gal'] = obj::db()->sql('select * from art_pack where id = '.$url[4],1);
		} elseif ($url[3] == 'join') {
			$return['display'][] = 'admin_packs_join';
			$return['list'] = obj::db()->sql('select id, title from art_pack where cover != "" order by id', 'id');
		} elseif ($url[3] == 'cleanup') {
			$return['display'][] = 'admin_packs_cleanup';
			if ($url[4] == 'sure') {
				exec('rm -rf "'.ROOT_DIR.SL.'files'.SL.'pack_uploaded'.SL.'*"');
				obj::db()->sql('delete from misc where type="pack_art"',0);
				obj::db()->sql('delete from misc where type="pack_status"',0);
			}
		}

		return $return;
	}

	function tags($return) {
		global $url; global $sets;
		if ($url[3] && $url[3] != 'search' && $url[3] != 'page' && $url[3] != 'merge') {
			unset ($this->side_modules['sidebar']);
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
				$return['tag'] = obj::db()->sql('select * from tag where alias = "'.urlencode($url[4]).'"',1);
			} else {
				unset ($this->side_modules['sidebar']);
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
