<?

class engine
{
	public $error_template = 'main';

	protected static $meta_plural_singular = array(
		'categories' => 'category',
		'tags' => 'tag',
		'languages' => 'language',
		'authors' => 'author'
	);

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
					if (preg_match("/[^a-zа-яё\d_\-\+%&\.,=;:\s]/iu", $url[$key])) {
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
		$date = gmdate("D, d M Y H:i:s");

		header("Expires: $date GMT");
		header("Last-Modified: $date GMT");
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
					$return['children'][] =
						$this->get_comments_tree($child, $children, $return, ($position+1));
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

		$singular = array_values(self::$meta_plural_singular);
		$plural = array_keys(self::$meta_plural_singular);

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
		$plusing = self::$meta_plural_singular;

		foreach ($mixed as $key => &$param) {
			if (array_key_exists($key, $plusing)) {
				$key = $plusing[$key];
			}

			foreach ($param as $one) {
				$sign = $one['sign'] == "+" ? '' : '!';

				if (count($one['data']) == 1) {

					$return .= ' and '.$sign.'locate("|'.reset($one['data']).'|",'.$key.')';
				} else {

					$return .= ' and '.$sign.'( ';
					foreach ($one['data'] as $or => $part) {
						$return .= ($or ? 'or' : '').' locate("|'.$part.'|",'.$key.') ';
					}
					$return .= ')';
				}
			}
		}
		return $return;
	}

	public static function mixed_make_url ($mixed) {
		global $url;

		if ($url['area'] != def::area(0)) {
			$base = '/'.$url[1].'/'.$url['area'].'/';
		} else {
			$base = '/'.$url[1].'/';
		}

		$plusing = self::$meta_plural_singular;

		if (count($mixed) == 0) {
			return $base;
		}

		if (count($mixed) == 1) {
			$key = key($mixed);
			$value = current($mixed);

			if (count($value) == 1) {
				$value = current($value);
				if ($value['sign'] == '+' && count($value['data']) == 1) {

					if (array_key_exists($key, $plusing)) {
						$key = $plusing[$key];
					}
					return $base.$key.'/'.current($value['data']).'/';
				}
			}
		}

		foreach ($mixed as $key => &$param) {
			$return .= $key.'=';

			foreach ($param as $id => $one) {
				if ($one['sign'] == '+' && !$id) {
					$one['sign'] = '';
				}
				$return .= $one['sign'].implode(',', $one['data']);
			}

			$return .= '&';
		}
		return $base.'mixed/'.substr($return, 0, -1).'/';
	}

	public function mixed_add ($new, $type, $sign = '+') {
		global $mixed;

		if (!isset($mixed)) {
			$mixed = array();
		}

		$plusing = self::$meta_plural_singular;
		$singplu = array_flip($plusing);

		$temp = $mixed;

		foreach ($temp as $key => $part) {
			if ($key == $type || $plusing[$key] == $type || $singplu[$key] == $type) {

				foreach ($part as $key2 => $param) {
					$key3 = array_search($new, $param['data']);

					if ($key3 !== false) {
						if ($param['sign'] == $sign) {
							return $this->mixed_make_url($mixed);
						} else {

							unset ($temp[$key][$key2]['data'][$key3]);
							if (empty($temp[$key][$key2]['data'])) {
								unset ($temp[$key][$key2]);
							}
							if (empty($temp[$key])) {
								unset ($temp[$key]);
							}
							return $this->mixed_make_url($temp);
						}
					}
				}
				$temp[$key][] = array('data' => array($new), 'sign' => $sign);
				return $this->mixed_make_url($temp);
			}
		}

		$temp[$type][] = array('data' => array($new), 'sign' => $sign);
		return $this->mixed_make_url($temp);
	}

	public function tag_cloud ($maxsize, $minsize, $area, $words, $limit = false) {
		$tags = array('ru' => array(), 'nonru' => array());

		if (preg_match('/[^a-z\d_]/is', $area) || preg_match('/[^\s\d,]/is', $limit)) {
			return $tags;
		}
		if (!empty($limit)) {
			$limit =  ' limit '.$limit;
		}

		$temp_tags = Database::get_table('tag',
			array('alias', 'name', $area),
			$area.' > 0 and alias != ? order by '.$area.' desc'.$limit,
			'prostavte_tegi');

		if (!empty($temp_tags)) {
			$max = $temp_tags[0][$area];
			$min = end(end($temp_tags));

			if ($max - $min < 4) {
				return true;
			}

			foreach ($temp_tags as $tag) {
				$name = Transform_Text::strtolower_ru($tag['name']);

				$calc = round(($maxsize - $minsize)*($tag[$area]-$min)/($max-$min) + $minsize);
				$word = Transform_Text::wcase($tag[$area], $words);

				$name = str_replace('_','_<wbr />',$name);
				$add_tag = array(
					'alias' => $tag['alias'],
					'count' => $tag[$area],
					'size' => $calc,
					'word' => $word
				);
				if (preg_match('/^[а-яё]/ui', $tag['name'])) {
					$tags['ru'][$name] = $add_tag;
				} else {
					$tags['nonru'][$name] = $add_tag;
				}
			}

			ksort($tags['ru'], SORT_LOCALE_STRING);
			ksort($tags['nonru'], SORT_LOCALE_STRING);
			return array_merge($tags['ru'], $tags['nonru']);
		}
	}

	public function make_rss($area, $type, $value) {

		if (preg_match('/[^a-z\d_]/is', $type)) {
			return array();
		}

		$name = array(
			'tag' => 'тега',
			'author' => 'автора',
			'language' => 'языка',
			'category' => 'категории',
			'pool' => 'группы'
		);

		if ($type == 'pool') {
			$metaname = Database::get_field('art_pool', 'name', (int) $value);
		} else {
			$metaname = Database::get_field($type, 'name', 'alias = ?', $value);
		}

		return array(
			'type-name' => $name[$type],
			'meta-name' => $metaname,
			'area' => $area,
			'type' => $type,
			'value' => $value
		);
	}

	public function get_navigation($parts) {
		global $url;

		$return = array();
		if (preg_match('/[^a-z\d_]/is', $url[1])) {
			return $return;
		}

		foreach ($parts as $part) {
			if (preg_match('/[^a-z\d_]/is', $part)) {
				continue;
			}

			if ($part == 'tag') {

				if ($url['area'] != def::area(2)) {
					$area = $url[1].'_'.def::area(0);
				} else {
					$area = $url[1].'_'.def::area(2);
				}

				$return[$part] = Database::get_vector(
					$part,
					array('alias', 'name'),
					'1 order by '.$area.' desc limit 70'
				);

				if (!empty($return[$part])) {
					foreach($return[$part] as &$tag) {
						$tag = str_replace('_', ' ', $tag);
					}
				}
			} else {
				$condition = $part == 'category' ? 'locate("|'.$url[1].'|",area)' : '1';

				$return[$part] = Database::get_vector(
					$part,
					array('alias', 'name'),
					$condition.' order by id'
				);
			}
		}

		return $return;
	}

	public static function redirect($url, $permanent = false, $exit = true) {

		if (!empty($permanent)) {
			header("HTTP/1.x 301 Moved Permanently");
		} else {
			header("HTTP/1.x 302 Moved Temporarily");
		}

		header("Location: $url");

		if (!empty($exit)) {
			exit();
		}
	}
}
