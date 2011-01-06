<? 

class output__search extends engine
{
	private $areas = array('p' => 'post', 'v' => 'video', 'a' => 'art', 'n' => 'news', 'c' => 'comment', 'o' => 'orders');
	
	public $allowed_url = array(
		array(1 => '|search|', 2 => 'any', 3 => '|rel|date|rdate|', 4 => 'any', 5 => '|page|', 6 => 'num', 7 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('top_buttons'),
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
		
		if ($url[3] == 'date') { 
			$main = 'and area="main"';
			$limit = ' order by sortdate desc limit '.(max(1,$url[6])-1)*$pp.', '.$pp;
		} elseif ($url[3] == 'rdate') {
			$main = 'and area="main"';			
			$limit = ' order by sortdate limit '.(max(1,$url[6])-1)*$pp.', '.$pp;
		}
		
		if (empty($area)) $return['display'] = array('search_info','search_error');
		else {
			if (!$search) $search = new search();
			$terms = $search->parse_text(urldecode($url[4]));
			$pretty_query = $query ? urldecode($url[4]) : $search->prepare_string(urldecode($url[4]),true);

			if (empty($terms)) $return['display'] = array('search_info','search_error');
			else {
				foreach ($terms as $term) {
					if (mb_strlen($term, 'UTF-8') > 2) $longterms[] = $term;
					else $shortterms[] = $term;
				}
				
				if (!empty($longterms)) {
					$longquery = ' and match (`index`) against ("+'.implode(' +',$longterms).'" in boolean mode)';
				}
				if (!empty($shortterms)) {
					$shortquery = ' and `index` like "%|'.implode('=%" and `index` like "%|',$shortterms).'=%"';
				}
				
				if (empty($query)) {
					$query = '(place="'.implode('" or place="',$area).'") '.$main.$shortquery.$longquery.$limit;
					$navi_query = '(place="'.implode('" or place="',$area).'") '.$main.$shortquery.$longquery;
				}
				$data = obj::db()->sql('select place, item_id, `index`, area, sortdate from search where ' . $query); 
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
						$return['navi']['last'] = ceil(obj::db()->sql('select count(*) from search where '. $navi_query,2)/$pp);
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
		query::$post = obj::db()->sql('select * from post where id='.$id,1);
		if (trim(query::$post['image'])) query::$post['image'] = explode('|',query::$post['image']);
		query::$post['links'] = unserialize(query::$post['link']);
		query::$post['files'] = unserialize(query::$post['file']);
		query::$post['info'] = unserialize(query::$post['info']);		
		query::$post['text'] = preg_replace(array(
			'/(<\/a><\/div><div class="text hidden">)(\s*<br[^>]*>)+/s',
			'/(<br[^>]*>\s*)+(<\/div><\/div>)/s'
			),array('$1','$2'),query::$post['text']);
		$meta = $this->get_meta(array(query::$post),array('category','author','language','tag'));
		foreach ($meta as $key => $type) 
			if (is_array($type))
				foreach ($type as $alias => $name) 
					query::$post['meta'][$key][$alias] = $name;
		query::$post['template'] = 'post'; query::$post['navi'] = '/post/';
		return query::$post;
	}

	function fetch_video($id) {
		global $sets;
		$sizes = explode('x',$sets['video']['thumb']);
		$video = obj::db()->sql('select * from video where id='.$id,1);
		$video['object'] = str_replace(array('%video_width%','%video_height%'),$sizes,$video['object']);
		$video['text'] = trim($video['text']);
		$meta = $this->get_meta(array($video),array('category','author','tag'));
		foreach ($meta as $key => $type) 
			if (is_array($type))
				 foreach ($type as $alias => $name) 
					$video['meta'][$key][$alias] = $name;
		$video['template'] = 'video'; $video['navi'] = '/video/';					
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
		$news = obj::db()->sql('select * from news where id='.$id,1);
		$news['template'] = 'news'; $news['navi'] = '/news/';							
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
		if ($item['place'] != 'news') $item['title'] = 'Комментарий к записи '.obj::db()->sql('select title from '.$item['place'].' where id='.$item['post_id'],2);
		else $item['title'] = 'Комментарий к записи '.obj::db()->sql('select title from '.$item['place'].' where url="'.$item['post_id'].'"',2);
		if ($item['place'] == 'orders') $item['place'] = 'order';
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
