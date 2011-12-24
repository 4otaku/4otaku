<?

class search
{
	private $preparsed_links;
	private $morphy_ru;
	private $morphy_en;
	private $encoding;

	public $error = false;

	function init_morphy () {
		include_once(ROOT_DIR.SL.'engine'.SL.'external'.SL.'phpmorphy'.SL.'src'.SL.'common.php');

		$opts = array(
			'storage' => PHPMORPHY_STORAGE_FILE,
			'predict_by_suffix' => true,
			'predict_by_db' => true,
			'graminfo_as_text' => true

		);
		$dir = ROOT_DIR.SL.'engine'.SL.'external'.SL.'phpmorphy'.SL.'dicts';

		$lang = 'ru_RU';
		try {
			$this->morphy_ru = new phpMorphy($dir, $lang, $opts);
		} catch(phpMorphy_Exception $e) {
			$this->error = true;
		}

		$lang = 'en_EN';
		try {
			$this->morphy_en = new phpMorphy($dir, $lang, $opts);
		} catch(phpMorphy_Exception $e) {
			$this->error = true;
		}

		$this->encoding = $this->morphy_ru->getEncoding();
	}

  	function morphyphp ($words) {
		if (empty($this->morphy_ru) || empty($this->morphy_en)) {
			$this->init_morphy();
		}

		$return = array();
		if (empty($words) || $this->error) {
			return $return;
		}

		foreach ($words as $key => $word) {
			if (preg_match('/[А-ЯЁ]/u',$word)) {

				$converted = iconv('utf-8', $this->encoding, $word);

				try {
					$collection = $this->morphy_ru->findWord($converted);
					if ($collection === false) {
						$return[$key] = $word;
					} else {
						foreach($collection as $paradigm) {
							$return[$key] = iconv($this->encoding, 'utf-8', $paradigm[0]->getWord());
						}
					}
				} catch(phpMorphy_Exception $e) {
					$this->error = true;
					return $return;
				}
			}
		}

		foreach ($words as $key => $word) {
			if (!empty($return[$key])) {
				continue;
			}

			if (ctype_alnum($word{0})) {
				$converted = iconv('utf-8', $this->encoding, $word);
				try {
					$collection = $this->morphy_en->findWord($converted);
					if ($collection === false) {
						$return[$key] = $word;
					} else {
						foreach($collection as $paradigm) {
							$return[$key] = iconv($this->encoding, 'utf-8', $paradigm[0]->getWord());
						}
					}
				} catch(phpMorphy_Exception $e) {
					$this->error = true;
					return $return;
				}
			}
		}

		foreach ($words as $key => $word) {
			if (!empty($return[$key])) {
				continue;
			}
			$return[$key] = $word;
		}

		$insert = array();
		foreach ($words as $key => $word) {
			$insert[] = array($word, $return[$key]);
		}
		Database::bulk_insert('morphy_cache', $insert, array('word', 'cache'));

		ksort($return);
		return $return;
	}

	function prepare_string ($text, $lower = false) {
		$function = $lower ? 'strtolower_ru' : 'strtoupper_ru';

		$text = Transform_Text::$function(strip_tags($text));

		$replacements = array('/([^\p{L}\d\-]|　)/u','/\-(?![\p{L}\d])/','/ +/');
		$text = preg_replace($replacements, ' ', $text);

		return trim($text);
	}

	function parse_text ($text) {
		$text = explode(' ', $this->prepare_string($text));

		foreach ($text as $key => $word) {
			if ($word{0} == '-') {
				$text[$key] = substr($word,1);
				$signs[$key] = '-';
			} else {
				$signs[$key] = '+';
			}
		}

		$cache = (array) Database::get_vector('morphy_cache',
			array('word', 'cache'),
			Database::array_in('word', $text),
			$text
		);

		$delta = array_diff($text, array_keys($cache));
		if (!empty($delta)) {
			$delta = $this->morphyphp($delta);
		}

		$text = array_flip($text);
		$return = array();
		foreach ($cache as $word => $one) {
			$return[$text[$word]] = $one;
		}

		$return = $return + $delta;

		foreach ($return as $key => $word) {
			$return[$key] = $signs[$key] . $word;
		}

		ksort($return);
		return $return;
	}

	function parse_links ($links) {
		$this->preparsed_links = '';
		if (is_array($links = unserialize($links))) {
			array_walk_recursive($links, array($this, 'preparse_links'));
			return $this->parse_text($this->preparsed_links);
		}
	}

	function preparse_links ($item, $key) {
		if ($key == 'alias' || $key == 'search' || $key == 'name') {
			if (is_array($item)) {
				foreach ($item as $one) {
					$this->preparsed_links .= ' '.$one;
				}
			} else {
				$this->preparsed_links .= ' '.$item;
			}
		}
	}

	function parse_meta ($meta, $table) {
		$fields = array('alias', 'name');
		if ($table == 'tag') {
			$fields[] = 'variants';
		}

		if (preg_match('/[^a-z_\d]/iu', $table)) {
			return array();
		}

		$meta = array_filter(explode('|', $meta));
		$data = (array) Database::get_table($table,
			$fields,
			Database::array_in('alias', $meta),
			$meta
		);

		$text = '';
		foreach ($data as $one) {
			$text .= ' '.$one['alias'].' '.$one['name'].' '.$one['variants'];
		}

		return $this->parse_text($text);
	}

	function wrap ($text, $index = '|', $strength = 1) {
		if (is_array($text)) {
			foreach ($text as $word) {
				$index .= str_replace('-','_',ltrim($word,'-+')).'='.$strength.'|';
			}
		}
		return $index;
	}

	function post ($item, $id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
//		$index = $this->wrap($this->parse_links($item['link']),$index);
//		$index = $this->wrap($this->parse_links($item['info']),$index);
//		$index = $this->wrap($this->parse_links($item['file']),$index);
		$index = $this->wrap($this->parse_meta($item['tag'],'tag'),$index,2);
		$index = $this->wrap($this->parse_meta($item['author'],'author'),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);
		$index = $this->wrap($this->parse_meta($item['language'],'language'),$index,2);

		Database::insert('search',array(
			'place' => 'post',
			'item_id' => $id,
			'index' => $index,
			'area' => $item['area'],
			'sortdate' => $item['sortdate'],
			'lastupdate' => time()
		));
	}

	function video ($item, $id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
		$index = $this->wrap($this->parse_meta($item['tag'],'tag'),$index,2);
		$index = $this->wrap($this->parse_meta($item['author'],'author'),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);

		Database::insert('search',array(
			'place' => 'video',
			'item_id' => $id,
			'index' => $index,
			'area' => $item['area'],
			'sortdate' => $item['sortdate'],
			'lastupdate' => time()
		));
	}

	function art ($item, $id) {
		$index = $this->wrap($this->parse_meta($item['tag'],'tag'),'|',2);
		$index = $this->wrap($this->parse_meta($item['author'],'author'),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);

		Database::insert('search',array(
			'place' => 'art',
			'item_id' => $id,
			'index' => $index,
			'area' => $item['area'],
			'sortdate' => $item['sortdate'],
			'lastupdate' => time()
		));
	}

	function comment ($item, $id) {
		$index = $this->wrap($this->parse_text($item['text']));

		Database::insert('search',array(
			'place' => 'comment',
			'item_id' => $id,
			'index' => $index,
			'area' => $item['area'],
			'sortdate' => $item['sortdate'],
			'lastupdate' => time()
		));
	}

	function news ($item, $id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);

		Database::insert('search',array(
			'place' => 'news',
			'item_id' => $id,
			'index' => $index,
			'area' => $item['area'],
			'sortdate' => $item['sortdate'],
			'lastupdate' => time()
		));
	}

	function orders ($item, $id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);

		Database::insert('orders',array(
			'place' => 'news',
			'item_id' => $id,
			'index' => $index,
			'area' => $item['area'],
			'sortdate' => $item['sortdate'],
			'lastupdate' => time()
		));
	}
}
?>
