<?

class output__search extends engine
{
	private $areas = array('p' => 'post', 'v' => 'video', 'a' => 'art', 'n' => 'news', 'c' => 'comment', 'o' => 'orders');
	private $comment_titles = array(
		'post' => 'записи',
		'video' => 'видео',
		'orders' => 'заказу'
	);

	private $cyrillic_stoplist = array('А', 'И', 'О', 'У', 'С', 'НО');

	private $minus_words = array('no', 'without', 'not', 'не', 'без');

	public $allowed_url = array(
		array(1 => '|search|', 2 => 'any', 3 => '|rel|date|rdate|art|', 4 => 'any', 5 => '|page|', 6 => 'num', 7 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	private $weights = array();

	function get_data() {
		global $url; global $error; global $search; global $sets;
		$return['display'] = array('search_info','search_content','navi');
		if (!in_array($url[2],$this->areas)) {
			$area = str_split($url[2]);
			foreach ($area as &$one) $one = $this->areas[$one];
			$area = array_filter($area);
			unset($one);
		} else {
			$area = array($url[2]);
		}

		if ($url[2] != 'a') {
			$pp = $sets['pp']['search'];
		} else {
			$pp = $sets['pp']['art'];
			$this->template = 'booru';
			$this->error_template = 'booru';
			$this->side_modules['sidebar'] = array('masstag','art_tags','comments');
			$query = $this->check_art_queries(urldecode($url[4]));
			if ($error) return false;
		}

		$left_join = '';
		if ($url[3] != 'rel') {
			$main = 'and s.area="main"';
			$lim = (max(1,$url[6])-1)*$pp.', '.$pp;

			if ($url[3] == 'art') {
				$left_join = ' left join art as a on a.id = s.item_id ';
				$order_sets = explode('-', $sets['art']['sort']);
				$limit = 'order by '.
					($order_sets[0] == 'rating' ? 'rating' : 'sortdate').' '.
					($order_sets[1] == 'asc' ? 'asc' : 'desc').' '.
					($order_sets[0] == 'rating' ? ', sortdate desc' : '').
					' limit '.$lim;
			} elseif ($url[3] == 'date') {
				$limit = ' order by s.sortdate desc limit '.$lim;
			} elseif ($url[3] == 'rdate') {
				$limit = ' order by s.sortdate limit '.$lim;
			}
		}

		if (empty($area)) $return['display'] = array('search_info','search_error');
		else {
			if (!$search) $search = new search();
			$request = urldecode($url[4]);
//			$request = preg_replace('/(\s|^)('.implode('|',$this->minus_words).')\s+(?=[^\s])/uis', ' -', $request);
			$request = preg_replace('/(\s|^)('.implode('|',$this->cyrillic_stoplist).')\s*/uis', ' ', $request);
			$terms = $search->parse_text($request);

			if ($search->error == true) {
				$error = true;
				return;
			}

			$pretty_query = $query ? $request : $search->prepare_string(urldecode($url[4]),true);

			if (empty($terms)) $return['display'] = array('search_info','search_error');
			else {
				foreach ($terms as $term) {
					if (mb_strlen($term, 'UTF-8') > 3) $longterms[] = str_replace('-','_',$term);
					else $shortterms[] = $term;
				}

				if (!empty($longterms)) {
					$longquery = ' and match (s.`index`) against ("'.implode(' ',$longterms).'" in boolean mode)';
				}
				if (!empty($shortterms)) {
					$shortquery = '';
					foreach ($shortterms as $shortterm) {
						$not = $shortterm{0} == '-';
						$shortquery .= 'and s.`index` '.($not ? 'not ' : '').'like "%|'.substr($shortterm,1).'=%"';
					}
				}

				if (empty($query)) {
					$query = '(s.place="'.implode('" or s.place="',$area).'") '.$main.$shortquery.$longquery.$limit;
					$navi_query = '(s.place="'.implode('" or s.place="',$area).'") '.$main.$shortquery.$longquery;
				}
				$data = obj::db()->sql('select s.place, s.item_id, s.`index`, s.area, s.sortdate from search as s'.$left_join.' where ' . $query);

				if (empty($data)) {
					foreach ($area as $one) $zero[] = 0;
					obj::db()->update('search_queries',$area,$zero,$pretty_query,'query');
					$return['variants'] = $this->get_variants($pretty_query, $area);
					$return['display'] = array('search_info','search_error');
				} else {
					if (!$limit) {
						$return['navi']['last'] = ceil(count($data)/$pp);

						$this->weights = obj::db()->sql('select place, weight from search_weights','place');
						if (count($terms) > 1) $data = $this->relevance($data,$terms,$pp,max(1,$url[6]));
						else $data = $this->relevance_simple($data,$terms,$pp,max(1,$url[6]));
					} else {
						$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from search as s where '. $navi_query,2)/$pp);
					}

					if ($url[2] != 'a') foreach ($data as $one) {
						$function = 'fetch_'.$one['place'];
						$return['data'][] = $this->$function($one['item_id']);
						$found[$one['place']] = true;
					} else {
						$return['art']['thumbs'] = $this->process_art($data);
						$return['display'] = array('booru_page','navi');
						$found['art'] = true;
					}

					foreach ($area as $one) {
						$update .= ($found[$one] ? ", ".$one."=".$one."+1" : '');
						$insert .= ", ".($found[$one] ? 1 : 0);
					}
					if (!strpos($pretty_query, 'md5:'))
					{
						obj::db()->sql("insert into search_queries (`id` ,`query` ,`length` , `".implode("` ,`",$area)."`) values('','".$pretty_query."',".mb_strlen($pretty_query).$insert.") on duplicate key update ".substr($update,1).";",0);
					}

					$return['navi']['curr'] = max(1,$url[6]);
					$return['navi']['start'] = max($return['navi']['curr']-5,2);
					$return['navi']['base'] = '/search/'.$url[2].'/'.$url[3].'/'.$url[4].'/';
				}
			}
		}
		if ($url[2] == 'a' && strpos($return['display'][0],'info')) unset ($return['display'][0]);
		return $return;
	}

	private function relevance($items,$terms,$per_page,$current_page) {
		global $def;
		foreach ($items as $key => $item) {
			$index[$key] = explode('|',trim($item['index'],'|'));
			foreach ($index[$key] as $pos => &$one) {
				$strength[$key][$pos] = substr($one,strpos($one,'=')+1);
				$word_count[$key] = $word_count[$key] + $strength[$key][$pos];
				$one = substr($one,0,strpos($one,'='));
			}
		}

		foreach ($index as $index_key => $item) {
			unset($matches,$distance,$relevance); $range = 1;
			foreach ($terms as $term) {
				$matches[] = array_keys($item,$term);
			}

			foreach ($matches[0] as $match)
				foreach ($matches as $key => $matched) if ($key)
					foreach ($matched as $check)
						if ($distance[$key]) $distance[$key] = min($distance[$key],abs($match + $key - $check));
						else $distance[$key] = abs($match + $key - $check);
			if (is_array($distance)) foreach ($distance as $one) $range = $range * pow(2,$one);

			foreach ($matches as $matched) foreach ($matched as $match) $relevance = $relevance + $strength[$index_key][$match];
			$relevance = (($relevance + $items[$index_key]['post_id']/100000) / $range) / sqrt($word_count[$index_key] / max($this->weights[$items[$index_key]['place']],1));
			if ($items[$index_key]['area'] != $def['area'][0] || $items[$index_key]['place'] == 'comment') $relevance = $relevance / 2;
			$items[$index_key]['relevance'] = $relevance;
		}
		usort($items,array($this,'relevance_sort'));
		return array_slice($items,($current_page-1)*$per_page,$per_page);
	}

	private function relevance_simple($items,$terms,$per_page,$current_page) {
		global $def;
		$term = current($terms);
		foreach ($items as $key => &$item) {
			$index = explode('|',trim($item['index'],'|'));
			$word_count = 0;
			foreach ($index as $pos => &$one) {
				$word_count = $word_count + substr($one,strpos($one,'=')+1);
				if (substr($one,0,strpos($one,'=')) == $term)
					$item['relevance'] = $item['relevance'] + intval(substr($one,strpos($one,'=')+1));
			}
			$item['relevance'] = ($item['relevance'] + $item['post_id']/100000) / sqrt($word_count / max($this->weights[$item['place']],1));
			if ($item['area'] != $def['area'][0] || $item['place'] == 'comment') $item['relevance'] = $item['relevance'] / 2;
		}
		usort($items,array($this,'relevance_sort'));
		return array_slice($items,($current_page-1)*$per_page,$per_page);
	}

	private function relevance_sort($a,$b) {
		return ($a['relevance'] > $b['relevance']) ? -1 : 1;
	}

	private function count_sort($a,$b) {
		return ($a['count'] > $b['count']) ? -1 : 1;
	}

	private function get_variants($query, $area, $levenshtein_range = 0) {
		global $def; global $check;
		$levenshtein_range = $check->num($levenshtein_range, 5, $def['search']['levenstein'], 1);
		$length = mb_strlen($query);

		$variants = obj::db()->sql('select query, '.implode('+',$area).' as count from search_queries where (length >= '.($length - $levenshtein_range).' and length <= '.($length + $levenshtein_range).' and ('.implode('>0 or ', $area).'>0))');
		if (is_array($variants)) {
			usort($variants,array($this,'count_sort'));

			$return = array();
			while (count($return) < $def['search']['variants'] && $variant = current($variants)) {

				if ($this->levenshtein($variant['query'],$query) <= $levenshtein_range) {
					$return[] = $variant['query'];
				}

				next($variants);
			}
			return $return;
		}

		return false;
	}

	private function check_art_queries($query) {
		global $error;
		$parts = explode(':',$query);
		switch ($parts[0]) {
			case 'md5':
				if ($id = obj::db()->sql('select id from art where md5="'.$parts[1].'"',2)) {
					return 'place="art" and item_id='.$id;
				} else {
					$error = true;
					return false;
				}
			default: return false;
		}
	}

	function process_art($data) {
		include_once('libs/output/art.php');
		foreach ($data as $one) $where .= ' or id='.$one['item_id'];
		return output__art::get_art(false,substr($where,4));
	}

	function fetch_post($id) {
		$worker = new Read_Post();
		$worker->get_item($id);
		$post = $worker->get_data('items');
		$post = current($post);

		$post['template'] = 'post';
		$post['navi'] = '/post/';
		return $post;
	}

	function fetch_video($id) {
		$worker = new Read_Video();
		$worker->get_item($id);
		$video = $worker->get_data('items');
		$video = current($video);
		$video->set_display_object('thumb');

		$video['template'] = 'video';
		$video['navi'] = '/video/';
		return $video;
	}

	function fetch_art($id) {
		global $check;
		$art = obj::db()->sql('select * from art where id='.$id,1);
		if ($check->link($art['source'])) $art['source'] = '<a href="'.$art['source'].'" target="_blank">'.$art['source'].'</a>';
		$meta = $this->get_meta(array($art),array('category','author','tag'));
		foreach ($meta as $key => $type)
			if (is_array($type))
				 foreach ($type as $alias => $name)
					$art['meta'][$key][$alias] = $name;
		$art['template'] = 'art'; $art['navi'] = '/art/';
		return $art;
	}

	function fetch_news($id) {
		$worker = new Read_News();
		$worker->get_item($id);
		$news = $worker->get_data('items');
		$news = current($news);

		$news['template'] = 'news';
		$news['navi'] = '/news/';
		return $news;
	}

	function fetch_orders($id) {
		$orders = obj::db()->sql('select * from orders where id='.$id.' limit 1',1);
		$orders['category'] = obj::db()->sql('select name, alias from category where alias="'.implode('" or alias="',array_unique(array_filter(explode('|',$orders['category'])))).'"','alias');
		$orders['template'] = 'orders'; $orders['navi'] = '/orders/';
		return $orders;
	}

	function fetch_comment($id) {
		$item = obj::db()->sql('select * from comment where id='.$id.' limit 1',1);

		if ($item['place'] == 'news') {
			$item['title'] = 'Комментарий к новости "'.
				obj::db()->sql('select title from '.$item['place'].' where url="'.$item['post_id'].'"',2).
				'"';
		} elseif ($item['place'] == 'art') {
			$item['title'] = 'Комментарий к изображению';
			$item['preview_picture'] = obj::db()->sql('select thumb from art where id='.$item['post_id'],2);
		} else {
			$item['title'] = 'Комментарий к '.
				$this->comment_titles[$item['place']].' "'.
				obj::db()->sql('select title from '.$item['place'].' where id='.$item['post_id'],2).
				'"';
		}

		if ($item['place'] == 'orders') {
			$item['place'] = 'order';
		}
		$item['template'] = 'comment';
		return $item;
	}

	function levenshtein($str1, $str2) {
		$len1 = mb_strlen($str1);
		$len2 = mb_strlen($str2);
		$i = 0;

		do {
			if(mb_substr($str1, $i, 1) != mb_substr($str2, $i, 1)) break;
			$i++; $len1--; $len2--;
		} while ($len1 > 0 && $len2 > 0);

		if($i > 0) {
			$str1 = mb_substr($str1, $i);
			$str2 = mb_substr($str2, $i);
		}

		$i = 0;
		do {
			if(mb_substr($str1, $len1-1, 1) != mb_substr($str2, $len2-1, 1)) break;
			$i++; $len1--; $len2--;
		} while($len1 > 0 && $len2 > 0);

		if($i > 0) {
			$str1 = mb_substr($str1, 0, $len1);
			$str2 = mb_substr($str2, 0, $len2);
		}

		if ($len1 == 0) return $len2;
		if ($len2 == 0) return $len1;

		$v0 = range(0, $len1); $v1 = array();

		for ($i = 1; $i <= $len2; $i++) {
			$v1[0] = $i; $str2j = mb_substr($str2, $i - 1, 1);

			for ($j = 1; $j <= $len1; $j++) {
				$cost = (mb_substr($str1, $j - 1, 1) == $str2j) ? 0 : 1;

				$m_min = $v0[$j] + 1; $b = $v1[$j - 1] + 1; $c = $v0[$j - 1] + $cost;
				if ($b < $m_min) $m_min = $b;
				if ($c < $m_min) $m_min = $c;
				$v1[$j] = $m_min;
			}

			$vTmp = $v0; $v0 = $v1; $v1 = $vTmp;
		}

		return $v0[$len1];
	}

}
