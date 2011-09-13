<?

class engine
{
	public $error_template = 'main';

	public function parse_area () {
		global $url;

		if (in_array($url[2], def::get('area'))) {

			$area = $url[2];
			foreach ($url as $key => $one) {
				if ($key > 2) {
					$url[$key-1] = $one;
				}
			}
			$url = array_slice($url, 0, -1, true);
			$url['area'] = $area;
		} else {
			$url['area'] = def::get('area', 0);
		}

		if ($url[2] == 'tag') {
			$locate = "|$url[3]|";
			$url['tag'] = Database::get_field('tag', 'alias', 'locate(?,variants)', $locate);
		}
	}

	public function check_404 ($ways) {
		global $url; global $error;

		$error = true;
		foreach ($ways as $conditions) {

			$error = false;
			foreach ($conditions as $key => $condition) {

				if (!empty($url[$key])) {
					if (preg_match("/[^a-zа-яё\d_\-\+%&\.,=:]/iu", $url[$key])) {
						$error = true;
					} elseif ($condition == 'end') {
						$error = true;
					} elseif ($condition == 'num') {
						if (!is_numeric($url[$key]) && $url[$key]) {
							$error = true;
						}
					} elseif (
						!strpos(' '.$condition,'|'.$url[$key].'|') &&
						$url[$key] && $condition != 'any'
					) {
						$error = true;
					}
				}
				if ($error) {
					break;
				}
			}
			if (!$error) {
				break;
			}
		}
	}

	public function make_404 ($type = false) {
		if (!empty($type)) {
			$this->template = '404__'.$type;
		}
		self::error_headers();
	}

	public static function error_headers () {
		header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Pragma: no-cache");
		header("HTTP/1.x 404 Not Found");
	}

	public function get_side_data ($input) {
		$return = array();

		foreach ($input as $part => $modules) {
			$class = 'side__'.$part;
			$worker = new $class();
			foreach ($modules as $module) {
				$return[$part][$module] = $worker->$module();
			}
		}
		return $return;
	}

	public function get_meta ($rows, $tables) {

		$alias = array_fill_keys($tables, '');
		foreach ($rows as $row) {
			foreach ($tables as $table) {
				$alias[$table] .= $row[$table];
			}
		}

		$return = array_fill_keys($tables, 0);
		foreach ($tables as $table) {
			$aliases = array_unique(array_filter(explode('|', $alias[$table])));
			if ($table != 'tag') {

				$return[$table] = Database::get_vector($table,
					array('alias', 'name'),
					Database::array_in('alias', $aliases),
					$aliases
				);
			} else {

				$return[$table] = Database::get_vector($table,
					array('alias', 'name', 'color', 'variants', 'have_description'),
					Database::array_in('alias', $aliases),
					$aliases
				);
				if (!empty($return[$table])) {
					foreach ($return[$table] as &$one) {
						$one['variants'] = array_filter(explode('|',trim($one['variants'],'|')));
					}
				}
			}
		}
		return $return;
	}

	public static function have_tag_variants($tags) {
		if (is_array($tags)) {
			foreach ($tags as $tag) {
				if (!empty($tag['variants'])) {
					return true;
				}
			}
		}

		return false;
	}

	public function get_comments ($place, $id, $pos = false) {
		$return = array();

		if ($pos === false) {
			$limit = '';
		} else {
			$limit = ' limit '.(sets::pp('comment_in_post')*($pos - 1)).
				', '.sets::pp('comment_in_post');
		}

		if (sets::dir('comments_tree')) {
			$order = ' desc';
		} else {
			$order = '';
		}

		$condition = 'place = ? and post_id= ? and rootparent = ? '.
			'and area != ? order by sortdate'.$order.$limit;
		$params = array($place, $id, 0, "deleted");

		$parents = Database::set_counter()->get_full_vector('comment', $condition, $params);
		$children = array();

		$return['number'] = Database::get_counter();
		if (!empty($return['number'])) {

			if (!empty($parents)) {

				$params = array_keys($parents);
				$condition = Database::array_in('rootparent', $params).
					' and area != ? order by sortdate'.$order;
				array_push($params, "deleted");
				$temp_children = Database::get_full_table('comment', $condition, $params);

				if (!empty($temp_children)) {
					foreach ($temp_children as $child) {
						$children[$child['rootparent']][$child['id']] = $child;
					}
				}

				foreach ($parents as $id => $parent) {
					$parent['id'] = $id;
					if (!empty($children[$id])) {
						$return['comments'][] = $this->get_comments_tree($parent, $children[$id], array());
					} else {
						$return['comments'][] = array_merge($parent, array('position' => 0));
					}
				}
			}
			return $return;
		}
	}

	public function get_comments_tree ($root, $children, $return, $position = 0) {
		$return = array_merge($root, array('position' => $position));

		if (is_array($children)) {
			foreach ($children as $child) {
				if ($child['parent'] == $root['id']) {
					$return['children'][] = $this->get_comments_tree($child,$children,$return,($position+1));
				}
			}
		}

		return $return;
	}

	public static function add_res($text, $error = false, $force_cookie = false) {
		global $add_res;

		$add_res = array('text' => $text, 'error' => $error);

		if (!empty(query::$post['do']) || $force_cookie) {

			$cookie = obj::get('dynamic__cookie');

			$cookie->inner_set('add_res.text', $text);
			$cookie->inner_set('add_res.error', $error);
		}
	}

	public static function mixed_parse($string) {
		global $error; global $mixed;

		$temp_params = explode('&', str_replace(' ', '+', $string));

		$params = array();
		foreach ($temp_params as $param) {
			$param_part = explode('=', $param);
			$params[$param_part[0]] = $param_part[1];
		}

		$singular = array(0 => 'category', 1 => 'tag', 2 => 'language', 3 => 'author');
		$plural = array(0 => 'categories', 1 => 'tags', 2 => 'languages', 3 => 'authors');

		$mixed = array();
		foreach ($params as $key => &$param) {
			if (in_array($key, $singular) || in_array($key, $plural)) {

				$value = ''; $sign = "+";
				for ($i = 0; $i <= strlen($param); $i++) {
					if ($param{$i} == "+" || $param{$i} == "-" || $i == strlen($param)) {

						if (!empty($value)) {
							$mixed[$key][] = array(
								'data' => explode(',', urldecode($value)),
								'sign' => $sign
							);
						}
						$sign = $param{$i}; $value = '';
					} else {
						$value .= $param{$i};
					}
				}
			} elseif (!empty($key)) {
				$error = true;
			}
		}

		return $mixed;
	}

	public static function mixed_make_sql($mixed) {
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

	public static function mixed_make_url($mixed) {
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

	public function mixed_add($new,$type,$sign = '+') {
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

	public function tag_cloud ($maxsize,$minsize,$area,$words,$limit = false) {
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

	public function make_rss($area,$type,$value) {
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

	public function get_navigation($parts) {
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

	public static function redirect($url, $permanent = false) {
		$permanent ? header("HTTP/1.x 301 Moved Permanently") : header("HTTP/1.x 302 Moved Temporarily");
		header("Location: $url");
		exit();
	}
}
