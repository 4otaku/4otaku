<?

class engine
{
	public $error_template = 'main';
	
	function parse_area() {
		global $url; global $def;
		if (in_array($url[2],$def['area'])) {
			$area = $url[2];
			foreach ($url as $key => $one) if ($key > 2) $url[$key-1] = $one;
			$url = array_slice($url,0,-1,true); $url['area'] = $area;
		}
		else $url['area'] = $def['area'][0];
		if ($url[2] == 'tag') $url['tag'] = obj::db()->sql('select alias from tag where locate("|'.mysql_real_escape_string(urldecode($url[3])).'|",variants)',2);
	}	

	function check_404($ways) {
		global $url; global $error;
		$error = true; 
		foreach ($ways as $conditions) {
			$error = false; 
			foreach ($conditions as $key => $condition) {
				if (!empty($url[$key])) {
					if (preg_match("/[^a-zа-яё\d_\-\+%&\.,=]/iu",$url[$key])) $error = true;					 
					elseif ($condition == 'end') $error = true;
					elseif ($condition == 'num') { if (!is_numeric($url[$key]) && $url[$key]) $error = true; }
					elseif (!strpos(' '.$condition,'|'.$url[$key].'|') && $url[$key] && $condition != 'any') $error = true;
				}
				if ($error) break;
			}
			if (!$error) break;
		}
	}
	
	function make_404($type) {
		global $sape_links; 
		$this->template = '404__'.$type;
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("HTTP/1.x 404 Not Found");
	}

	function get_side_data($input) {
		$return = array();
		foreach ($input as $part => $modules) {
			$class = 'side__'.$part;
			$$part = new $class();
			foreach ($modules as $module) $return[$part][$module] = $$part->$module();
		}
		return $return;
	}
	
	function get_meta($rows,$tables) {
		foreach ($rows as $row) 
			foreach ($tables as $table)
				$alias[$table] .= $row[$table];
		foreach ($tables as $table) {
			if ($table != 'tag') $return[$table] = obj::db()->sql('select alias, name from '.$table.' where alias="'.implode('" or alias="',array_unique(array_filter(explode('|',$alias[$table])))).'"','alias');
			else {
				$return[$table] = obj::db()->sql('select alias, name, color, variants from '.$table.' where alias="'.implode('" or alias="',array_unique(array_filter(explode('|',$alias[$table])))).'"','alias');
				if ($return[$table]) foreach ($return[$table] as &$one) $one['variants'] = array_filter(explode('|',trim($one['variants'],'|')));
			}
		}
		return $return;	
	}
	
	function get_comments($place, $id, $pos = false) {
		global $sets;
		$return['number'] = obj::db()->sql('select count(id) from comment where (place="'.$place.'" and post_id="'.$id.'" and rootparent=0 and area != "deleted")',2,'count(id)');
		if ($return['number']) { 
			if ($pos === false) $limit='';
				else $limit = ' limit '.($sets['pp']['comment_in_post']*($pos - 1)).', '.$sets['pp']['comment_in_post'];
			if ($sets['dir']['comments_tree']) $order=' desc'; 
				else $order = '';
			$parents = obj::db()->sql('select * from comment where (place="'.$place.'" and post_id="'.$id.'" and rootparent=0 and area != "deleted") order by sortdate'.$order.$limit);
			foreach ($parents as $parent) $condition .= ' or rootparent='.$parent['id'];	
			$temp_children = obj::db()->sql('select * from comment where (('.substr($condition,4).') and area != "deleted") order by sortdate'.$order);
			if (is_array($temp_children)) foreach ($temp_children as $child) $children[$child['rootparent']][$child['id']] = $child;
			foreach ($parents as $parent) {
				 if (is_array($children[$parent['id']]))
					$return['comments'][] = $this->get_comments_tree($parent,$children[$parent['id']],array());
				else 
					$return['comments'][] = array_merge($parent,array('position' => 0));
			}
			return $return;
		}		
	}
	 
	function get_comments_tree($root,$children,$return,$position = 0) {
		$return = array_merge($root,array('position' => $position));
		if (is_array($children)) foreach ($children as $child) 
			if ($child['parent'] == $root['id']) $return['children'][] = $this->get_comments_tree($child,$children,$return,($position+1));
		return $return;
	}
	
	function make_tip($text) {
		$text = strip_tags($text,'<br>');
		$pos = mb_strpos($text,'<br');
		if ($pos > 20) {
			$return = mb_substr($text,0,$pos);
			return strlen($return) != strlen($text) ? $return.' ... ' : $return;
		}
		elseif ($pos) {
			$pos = mb_strpos($text,'<br',20);
			$return = mb_substr($text,0,$pos);
			return strlen($return) != strlen($text) ? $return.' ... ' : $return;
		}
		return $text;
	}
	
	static function add_res($text, $error = false, $force_cookie = false) {
		global $add_res; global $post; global $cookie;
		$add_res = array('text' => $text, 'error' => $error);
		if (!empty($post['do']) || $force_cookie) {
			if (empty($cookie)) $cookie = new dinamic__cookie();
			$cookie->inner_set('add_res.text',$text);
			$cookie->inner_set('add_res.error',$error);
		}
	}
	
	static function mixed_parse($string) {
		global $error; global $mixed;
		$temp_params = explode('&',str_replace(' ','+',$string));
		foreach ($temp_params as $param) {
			$param_part=explode ('=',$param);
			$params[$param_part[0]] = $param_part[1];
		}
		
		$singular = array(0 => 'category',1 => 'tag',2 => 'language',3 => 'author');
		$plural = array(0 => 'categories',1 => 'tags',2 => 'languages',3 => 'authors');

		foreach ($params as $key => &$param) {
			if (in_array($key,$singular) || in_array($key,$plural)) {
				$i=0; $value=''; $sign="+";
				while (!($i > strlen($param))) {
					if ($param{$i} == "+" || $param{$i} == "-" || $i == strlen($param)) {
						if ($value) {
							$return[$key][] = array('data' => explode(',',urldecode($value)), 'sign' => $sign);
						}
						$sign = $param{$i}; $value='';
					}
					else $value .= $param{$i};
					$i++;
				}
			}
			elseif ($key) $error = true;
		}
		return $mixed = $return;
	}

	static function mixed_make_sql($mixed) {
		global $url;	
		$return = 'area="'.$url['area'].'"';
		$plusing = array('categories' => 'category','tags' => 'tag','languages' => 'language','authors' => 'author');

		foreach ($mixed as $key => &$param) {
			if (array_key_exists($key,$plusing)) $key = $plusing[$key];
			foreach ($param as $one) {
				if (count($one['data']) == 1) $return .= ' and '.($one['sign'] == "+" ? '' : '!').'locate("|'.current($one['data']).'|",'.$key.')';
				else {
					$return .= ' and '.($one['sign'] == "+" ? '' : '!').'( ';
					foreach ($one['data'] as $or => $part) $return .= ($or ? 'or' : '').' locate("|'.$part.'|",'.$key.') ';
					$return .= ')';
				}			
			}
		}
		return $return;
	}	
	
	static function mixed_make_url($mixed) {
		global $url; global $def;
		
		if ($url['area'] != $def['area'][0]) $base = '/'.$url[1].'/'.$url['area'].'/';
		else $base = '/'.$url[1].'/';

		$plusing = array('categories' => 'category','tags' => 'tag','languages' => 'language','authors' => 'author');
		
		if (count($mixed) == 0) return $base;

		if (count($mixed) == 1) {
			$key = key($mixed); $value = current($mixed);
			if (count($value) == 1) {
				$value = current($value);
				if ($value['sign'] == '+' && count($value['data']) == 1)
					return $base.(array_key_exists($key,$plusing) ? $plusing[$key] : $key).'/'.current($value['data']).'/';
			}
		}

		foreach ($mixed as $key => &$param) {
			$return .= $key.'='; $first = true;
			foreach ($param as $one) {
				if ($one['sign'] == '+' && $first) $one['sign'] = '';
				$return .= $one['sign'].implode(',',$one['data']);
				$first = false;				
			}
			$return .= '&';
		}
		return $base.'mixed/'.substr($return,0,-1).'/';
	}	

	function mixed_add($new,$type,$sign = '+') {
		global $mixed;
		if (!isset($mixed)) $mixed = array();

		$plusing = array('categories' => 'category','tags' => 'tag','languages' => 'language','authors' => 'author');
		$singplu = array_flip($plusing);
		
		$temp = $mixed;
		foreach ($temp as $key => $part) {
			if ($key == $type || $plusing[$key] == $type || $singplu[$key] == $type) {
				foreach ($part as $key2 => $param) {
					$key3 = array_search($new,$param['data']);
					if ($key3 !== false)
						if ($param['sign'] == $sign) return $this->mixed_make_url($mixed);
						else {
							unset ($temp[$key][$key2]['data'][$key3]);
							if (empty($temp[$key][$key2]['data'])) unset ($temp[$key][$key2]);
							if (empty($temp[$key])) unset ($temp[$key]);
							return $this->mixed_make_url($temp);
						}
				}
				$temp[$key][] = array('data' => array($new), 'sign' => $sign);
				return $this->mixed_make_url($temp);
			}
		}
		$temp[$type][] = array('data' => array($new), 'sign' => $sign);
		return $this->mixed_make_url($temp);		
	}	
	
	function tag_cloud ($maxsize,$minsize,$area,$words,$limit = false) {
		if ($limit) $limit =  ' limit '.$limit; 
		$tags = array('ru' => array(), 'nonru' => array());

		$temp_tags = obj::db()->sql('select alias, name, '.$area.' from tag where ('.$area.' > 0 and alias != "prostavte_tegi") order by '.$area.' desc'.$limit);
		if ($temp_tags) {
			$max = $temp_tags[0][$area];
			$min = end(end($temp_tags));

			if ($max - $min < 4) return true;

			foreach ($temp_tags as $tag) {
				$name = obj::transform('text')->strtolower_ru($tag['name']);
				$calc = round(($maxsize - $minsize)*($tag[$area]-$min)/($max-$min) + $minsize);
				$word = obj::transform('text')->wcase($tag[$area],$words[0],$words[1],$words[2]);
				if (preg_match('/[а-яА-Я]/u', mb_substr($tag['name'],0,1)))
					$tags['ru'][str_replace('_','_<wbr />',$name)]= array('alias' => $tag['alias'], 'count' => $tag[$area], 'size' => $calc, 'word' => $word);
				else 
					$tags['nonru'][str_replace('_','_<wbr />',$name)]= array('alias' => $tag['alias'], 'count' => $tag[$area], 'size' => $calc, 'word' => $word);
			}

			ksort($tags['ru'],SORT_LOCALE_STRING);ksort($tags['nonru'],SORT_LOCALE_STRING);
			return array_merge($tags['ru'],$tags['nonru']);
		}
	}
	
	function make_rss($area,$type,$value) {
		$name = array('tag' => 'тега', 'author' => 'автора', 'language' => 'языка', 'category' => 'Категории', 'pool' => 'Группы');
		if ($type == 'pool') $metaname = obj::db()->sql('select name from art_pool where id='.$value,2);
		else $metaname = obj::db()->sql('select name from '.$type.' where alias="'.$value.'"',2);
		return array(
			'type-name' => $name[$type],
			'meta-name' => $metaname,
			'area' => $area,
			'type' => $type,
			'value' => $value										
		);
	}
	
	function get_navigation($parts) {
		global $url; global $def;
		foreach ($parts as $part) {
			if ($part == 'tag') { 
				if ($url['area'] != $def['area'][2]) $area = $url[1].'_'.$def['area'][0];
				else $area = $url[1].'_'.$def['area'][2];
				if ($return[$part] = obj::db()->sql('select alias, name from '.$part.' order by '.$area.' desc limit 70','alias')) {
					foreach($return[$part] as &$tag) $tag = str_replace('_',' ',$tag);
				}
			}
			else {
				$return[$part] = obj::db()->sql('select alias, name from '.$part.($part == 'category' ? ' where locate("|'.$url[1].'|",area)' : '').' order by id','alias');
			}
		}
		return $return;
	}
	
	static function redirect($url, $permanent = false) {
		$permanent ? header("HTTP/1.x 301 Moved Permanently") : header("HTTP/1.x 302 Moved Temporarily");
		header("Location: $url");
		exit();
	}
}
