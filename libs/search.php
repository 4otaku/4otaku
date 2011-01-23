<?

class search
{	
	private $preparsed_links;
	private $morphy_ru;
	private $morphy_en;
	
	function init_morphy() {
		include_once(ROOT_DIR.SL.'engine'.SL.'external'.SL.'phpmorphy'.SL.'src'.SL.'common.php');
		$opts = array( 'storage' => PHPMORPHY_STORAGE_FILE, 'predict_by_suffix' => true, 'predict_by_db' => true, 'graminfo_as_text' => true);
		$dir = ROOT_DIR.SL.'engine'.SL.'external'.SL.'phpmorphy'.SL.'dicts';
		$lang = 'ru_RU'; 
		try { $this->morphy_ru = new phpMorphy($dir, $lang, $opts); } 
		catch(phpMorphy_Exception $e) { 
			die('Критическая ошибка морфологического анализа, известите пожалуйста админа. admin@4otaku.ru'); 
		}
		$lang = 'en_EN'; 
		try { $this->morphy_en = new phpMorphy($dir, $lang, $opts); } 
		catch(phpMorphy_Exception $e) { 
			die('Критическая ошибка морфологического анализа, известите пожалуйста админа. admin@4otaku.ru'); 
		}
	}
	
  	function morphyphp($words) {
		if (empty($this->morphy_ru) || empty($this->morphy_en)) $this->init_morphy();		
	
		if (!empty($words)) {
			foreach ($words as $key => $word) {
				if (preg_match('/[А-ЯЁ]/u',$word)) {
					$word2 = iconv('utf-8', $this->morphy_ru->getEncoding(), $word); 
					try { 
						$collection = $this->morphy_ru->findWord($word2);
						if ($collection === false) $return[$key] = $word;  
						else foreach($collection as $paradigm)
							$return[$key] = iconv($this->morphy_ru->getEncoding(), 'utf-8', $paradigm[0]->getWord()); 
					} catch(phpMorphy_Exception $e) {die('Критическая ошибка морфологического анализа, известите пожалуйста админа. admin@4otaku.ru');}
				}
			}
			
			foreach ($words as $key => $word) if (!$return[$key]) {
				if (ctype_alnum($word{0})) {				
					$word2 = iconv('utf-8', $this->morphy_en->getEncoding(), $word); 
					try { 
						$collection = $this->morphy_en->findWord($word2);
						if ($collection === false) $return[$key] = $word;  
						else foreach($collection as $paradigm)
							$return[$key] = iconv($this->morphy_en->getEncoding(), 'utf-8', $paradigm[0]->getWord());
					} catch(phpMorphy_Exception $e) {die('Критическая ошибка морфологического анализа, известите пожалуйста админа. admin@4otaku.ru');}
				}
			}
			
			foreach ($words as $key => $word) if (!$return[$key]) $return[$key] = $word;
			
			$query = 'insert into morphy_cache (`word`, `cache`) values ';
			foreach ($words as $key => $word) $query .= '("'.$word.'","'.$return[$key].'"),';
			obj::db()->sql(rtrim($query,',').';',0);
			
			ksort($return);
			return $return;
		}
	}
	
	function prepare_string($text,$lower = false) {
		if (!$lower) $function = 'strtoupper_ru'; else $function = 'strtolower_ru';
		$text = trim(preg_replace(array('/([^\p{L}\d\-]|　)/u','/\-(?![\p{L}\d])/','/ +/'),' ',obj::transform('text')->$function(strip_tags($text))));
		return $text;
	}	
	
	function parse_text($text) {
		$text = explode(' ',$this->prepare_string($text));
		
		foreach ($text as $key => $word) {
			if ($word{0} == '-') {
				$text[$key] = substr($word,1);
				$signs[$key] = '-';
			} else {
				$signs[$key] = '+';
			}
		}

		$cache = obj::db()->sql('select * from morphy_cache where word="'.implode('" or word="',$text).'"','word');
		if (empty($cache)) $cache = array();
		
		$delta = array_diff($text,array_keys($cache));		
		if (!empty($delta)) $delta = $this->morphyphp($delta);
		
		$text = array_flip($text);
		foreach ($cache as $word => $one) $return[$text[$word]] = $one;
		if (empty($return)) $return = array();
		
		$return = $return + $delta;
		
		foreach ($return as $key => $word) {
			$return[$key] = $signs[$key].$word;
		}		
		
		ksort($return);
		return $return;
	}
	
	function parse_links($links) {
		$this->preparsed_links = '';
		if (is_array($links = unserialize($links))) {
			array_walk_recursive($links, array($this, 'preparse_links'));
			return $this->parse_text($this->preparsed_links);
		}
	}
	
	function preparse_links($item, $key) {
		if ($key == 'alias' || $key == 'search' || $key == 'name')
			if (is_array($item))
				foreach ($item as $one) $this->preparsed_links .= ' '.$one;
			else $this->preparsed_links .= ' '.$item;
	}
	
	function parse_meta($meta,$table) {		
		$fields = 'alias, name';
		if ($table == 'tag') $fields .= ', variants';
		$data = obj::db()->sql('select '.$fields.' from '.$table.' where alias = "'.str_replace('|','" or alias = "',$meta).'"');
		if (is_array($data)) foreach ($data as $one)
			$text .= ' '.$one['alias'].' '.$one['name'].' '.$one['variants'];
		return $this->parse_text($text);
	}	
	
	function wrap($text,$index = '|',$strength = 1) {
		if (is_array($text)) {
			foreach ($text as $word) {
				$word = explode('-',$word);
				foreach ($word as $add)
					$index .= ltrim($add,'-+').'='.$strength.'|';
			}
		}
		return $index;
	}
	
	function post($item,$id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
		$index = $this->wrap($this->parse_links($item['link']),$index);
		$index = $this->wrap($this->parse_links($item['info']),$index);
		$index = $this->wrap($this->parse_links($item['file']),$index);
		$index = $this->wrap($this->parse_meta($item['tag'],'tag'),$index,2);
		$index = $this->wrap($this->parse_meta($item['author'],'author'),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);
		$index = $this->wrap($this->parse_meta($item['language'],'language'),$index,2);
		obj::db()->insert('search',array('post',$id,$index,$item['area'],$item['sortdate'],time()));
		obj::db()->debug();
	}
	
	function video($item,$id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
		$index = $this->wrap($this->parse_meta($item['tag'],'tag'),$index,2);
		$index = $this->wrap($this->parse_meta($item['author'],'author'),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);		
		obj::db()->insert('search',array('video',$id,$index,$item['area'],$item['sortdate'],time()));
		obj::db()->debug();
	}	
	
	function art($item,$id) {
		$index = $this->wrap($this->parse_meta($item['tag'],'tag'),'|',2);
		$index = $this->wrap($this->parse_meta($item['author'],'author'),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);	
		obj::db()->insert('search',array('art',$id,$index,$item['area'],$item['sortdate'],time()));
		obj::db()->debug();
	}	
	
	function comment($item,$id) {
		$index = $this->wrap($this->parse_text($item['text']));
		obj::db()->insert('search',array('comment',$id,$index,$item['area'],$item['sortdate'],time()));
		obj::db()->debug();
	}
	
	function news($item,$id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
		obj::db()->insert('search',array('news',$id,$index,$item['area'],$item['sortdate'],time()));
		obj::db()->debug();
	}	
	
	function orders($item,$id) {
		$index = $this->wrap($this->parse_text($item['title']),'|',3);
		$index = $this->wrap($this->parse_text($item['text']),$index);
		$index = $this->wrap($this->parse_meta($item['category'],'category'),$index,2);		
		obj::db()->insert('search',array('orders',$id,$index,$item['area'],$item['sortdate'],time()));
		obj::db()->debug();
	}	
}
?>
