<?

class Side_Sidebar extends engine
{
	static protected $search_variants = array('post', 'order', 'video', 'art',
		'news', 'comments');
	static protected $search_default = array('post', 'order', 'video');

	public function search() {
		$return = array();
		$return['checked'] = array();

		$current = query::$url[1];
		if (in_array($current, self::$search_variants)) {
			$return['checked'][] = $current;
		} elseif ($current == 'search') {
			throw new Error('Search checks for new templates not implemented yet');
		} else {
			$return['checked'] = self::$search_default;
		}

		return $return;
	}

	function comments() {
		global $sets; global $url;

		if ($url[1] == "order") {
			$area = "orders";
		} elseif ($url[1] == "search") {
			if ($url[2] == "a") $area = 'art';
			else if ($url[2] == "p") $area = 'post';
			else if ($url[2] == "v") $area = 'video';
		} else {
			$area = $url[1];
		}

		if (!($return = obj::db()->sql('select * from comment where (place="'.$area.'" and area != "deleted") order by sortdate desc limit '.$sets['pp']['latest_comments']*5,'sortdate'))) {
			$return = obj::db()->sql('select * from comment where area != "deleted" order by sortdate desc limit '.$sets['pp']['latest_comments']*5,'sortdate');
		} else {
			$link = $area == 'orders' ? 'order' : $area;
		}
		if (is_array($return)) {
			$used = array();
			foreach ($return as $key => $one) {
				if (in_array($one['place'].'-'.$one['post_id'],$used)) unset ($return[$key]);
				$used[] = $one['place'].'-'.$one['post_id'];
			}
			krsort($return);
			$return = array_slice($return,0,$sets['pp']['latest_comments'],true);
			foreach ($return as &$comment) {
				if ($comment['place'] != 'art') {
					$comment['title'] = obj::db()->sql('select title from '.$comment['place'].' where id="'.$comment['post_id'].'" limit 1',2);
				} else {
					$comment['title'] = 'Изображение №'.$comment['post_id'];
				}
				$comment['text'] = obj::transform('text')->cut_long_text(strip_tags($comment['text'],'<br><em><strong><s>'),100);
				$comment['text'] = preg_replace('/(<br(\s[^>]*)?>\n*)+/si','<br />',$comment['text']);
				$comment['text'] = obj::transform('text')->cut_long_words($comment['text']);
				$comment['href'] =  '/'.($comment['place'] == "orders" ? "order" : $comment['place']).'/'.$comment['post_id'].'/';
				$comment['username'] = mb_substr($comment['username'],0,30);
			}
			return array('data' => $return, 'link' => $link);
		}
	}

	function update() {
		$return = obj::db()->sql('select * from post_update order by sortdate desc limit 3');
		foreach ($return as & $update) {
			$update['text'] = obj::transform('text')->cut_long_text(strip_tags($update['text'],'<br>'),100);
			$update['text'] = preg_replace('/(<br(\s[^>]*)?>\n*)+/si','<br />',$update['text']);
			$update['text'] = obj::transform('text')->cut_long_words($update['text']);
			$update['author'] = mb_substr($update['username'],0,20);
			$update['post_title'] = obj::db()->sql('select title from post where id = '.$update['post_id'],2);
		}
		return $return;
	}

	function orders() {
		global $sets;
		if ($return = obj::db()->sql('select id, username, title, text, comment_count from orders where area="workshop"')) {
			shuffle($return);
			return array_slice($return, 0, $sets['pp']['random_orders']);
		}
	}

	function tags() {
		global $sets; global $def; global $url;

		if ($url['area'] != $def['area'][0] && $url['area'] != $def['area'][2]) $area = $url[1].'_'.$def['area'][0];
		else $area = $url[1].'_'.$url['area'];

		$words = array(
			$def['type'][0] => array('запись','записи','записей'),
			$def['type'][1] => array('видео','видео','видео'),
			$def['type'][2] => array('арт','арта','артов')
		);

		return $this->tag_cloud(22,8,$area,$words[$url[1]],$sets['pp']['tags']);
	}

	function art_tags() {
		global $data; global $check; global $url;

		if (in_array($url['area'], def::get('area')) && $url['area'] != 'workshop') {
			$area = $url['area'];
			$prefix = $url['area'].'/';
		} else {
			$area = def::get('area',0);
			$prefix = '';
		}

		if (is_array($data['main']['art']['thumbs'])) {
			$page_flag = true;
			foreach ($data['main']['art']['thumbs'] as $art)
				if (is_array($art['meta']['tag']))
					foreach ($art['meta']['tag'] as $alias => $tag)
						if ($tags[$alias]) $tags[$alias]['count']++;
						else $tags[$alias] = array('name' => $tag['name'], 'color' => $tag['color'], 'count' => 1, 'description' => $tag['have_description']);
		}
		elseif (is_array($data['main']['art'][0]['meta']['tag'])) {
			$page_flag = false;
			foreach ($data['main']['art'][0]['meta']['tag'] as $alias => $tag)
				if ($tags[$alias]) $tags[$alias]['count']++;
				else $tags[$alias] = array('name' => $tag['name'], 'color' => $tag['color'], 'count' => 1, 'description' => $tag['have_description']);
		}
		unset($tags['prostavte_tegi'],$tags['tagme'],$tags['deletion_request']);

		if (!empty($tags)) {
			$where = 'where alias="'.implode('" or alias="',array_keys($tags)).'"';
			$global = obj::db()->sql('select alias, art_'.$area.' from tag '.$where,'alias');
			if ($page_flag) {
				foreach ($global as $alias => $global_count) {
					$ret_key = ((int) $tags[$alias]['count'] * (int) $global_count).'.'.rand(0,10000);
					$return[$ret_key] = array('alias' => $alias, 'num' => $global_count) + $tags[$alias];
				}

				krsort($return);
				$return = array_slice($return,0,25);
				shuffle($return);
			} else {
				foreach ($global as $alias => $global_count) {
					$return[$alias] = array('alias' => $alias, 'num' => $global_count) + $tags[$alias];
				}
			}

			foreach ($return as $key => $tag)
				$return[$key]['alias'] = $prefix.'tag/'.$tag['alias'];

			uasort($return, 'transform__array::meta_sort');
			return $return;
		}
	}

	function admin_functions() {
		return true;
	}

	function quicklinks() {
		return true;
	}

	function board_list() {
		return obj::db()->sql('select alias, name from category where locate("|board|",area) order by id','alias');
	}

	function masstag() {
		return obj::db()->sql('select alias, name from category where locate("|art|",area) order by id','alias');
	}
}
