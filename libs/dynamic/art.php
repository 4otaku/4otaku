<?

class dynamic__art extends engine
{
	function slideshow() {
		global $check; global $def; global $sets; global $url;
		$types = array ('tag','author','category','mixed','date','pool');
		if (in_array(query::$get['type'],$types) && $check->num(query::$get['id'])) {
			$limit = ' order by sortdate desc limit '.(query::$get['id'] - 1).', 5'; $area_prefix = 'area="'.$def['area'][0].'" and ';
			switch (query::$get['type']) {
				case "mixed":
					$url['area'] = 'main';  $area_prefix = '';
					$area = "(".engine::mixed_make_sql(engine::mixed_parse(html_entity_decode(urldecode(query::$get['area'])))).")";
					break;
				case "date":
					$parts = explode('-',query::$get['area']);
					if (is_numeric($parts[0].$parts[1].$parts[2]) && count($parts) == 3)
						$area = '(pretty_date ="'.obj::transform('text')->rumonth($parts[1]).' '.$parts[2].', '.$parts[0].'")';
					break;
				case "pool":
					$pool = Database::order('order', 'asc')
						->limit(5, query::$get['id'] - 1)
						->get_vector('art_in_pool', 'art_id', 'pool_id = ?', query::$get['area']);

					$area = "(id=".implode(' or id=',$pool).")";
					$limit = ''; $area_prefix = '';

					break;
				default:
					$area = '(locate("|'.urldecode(query::$get['area']).'|",art.'.query::$get['type'].'))';
			}
			if (!$sets['show']['nsfw'] && query::$get['type'] != 'pool')
				$area .= ' and !locate("|nsfw|",art.category)';
			elseif ($sets['show']['nsfw'] && query::$get['type'] != 'pool') {
				if (!$sets['show']['yaoi']) $area .= ' and !(locate("|yaoi|",art.tag) and locate("|nsfw|",art.category))';
				if (!$sets['show']['furry']) $area .= ' and !(locate("|furry|",art.tag) and locate("|nsfw|",art.category))';
				if (!$sets['show']['guro']) $area .= ' and !(locate("|guro|",art.tag) and locate("|nsfw|",art.category))';
			}
			$return = obj::db()->sql('select id, md5, extension, resized from art where ('.$area_prefix.$area.')'.$limit,'id');
			if (query::$get['type'] == 'pool') {
				$_return = $return; unset($return);
				foreach ($pool as $one) $return[$one] = $_return[$one];
			}
			if (is_array($return)) {
				foreach ($return as &$art) {
					if (!$art['resized']) $sizes = getimagesize('images/booru/full/'.$art['md5'].'.'.$art['extension']);
					else $sizes = getimagesize('images/booru/resized/'.$art['md5'].'.jpg');
					$art['height'] = $sizes[1];
				}
				if ($sets['show']['translation']) {
					$where = 'art_id='.implode(' or art_id=',array_keys($return));
					$translations = obj::db()->sql('select art_id, data from art_translation where ('.$where.' and active = 1)','art_id');
					if (is_array($translations)) foreach ($translations as $key => $translation) {
						$return[$key]['translations']['full'] = unserialize(base64_decode($translation));
						if ($return[$key]['resized'] && is_array($return[$key]['translations']['full'])) {
							$size = explode('x',$return[$key]['resized']);
							$coeff = $size[0] / $def['booru']['resizewidth'];
							foreach ($return[$key]['translations']['full'] as $one) {
								foreach ($one as $field => &$param) if ($field != 'text') $param = round($param / $coeff);
								$return[$key]['translations']['resized'][] = $one;
							}
						}
					}
				}
				return $return;
			}
			else die ("finish");
		}
	}

	function set_vote () {
		$rating = (int) query::$get['rating'];

		if ($rating > 1) {
			$rating = 1;
		}

		if ($rating < -1) {
			$rating = -1;
		}

		$insert = array(
			(int) query::$get['id'],
			query::$cookie,
			ip2long($_SERVER['REMOTE_ADDR']),
			$rating
		);

		obj::db()->insert('art_rating', $insert);
	}

	function masstag() {
		global $check; global $sets;
		if (is_numeric(query::$get['id'])) {
			if ($check->lat($function = query::$get['sign'])) {
				$this->$function(urldecode(query::$get['data']),query::$get['id']);
			}
			$return = obj::db()->sql('select * from art where id='.query::$get['id'].' limit 1',1);
			obj::db()->insert('versions',array('art',query::$get['id'],base64_encode(serialize($return)),ceil(microtime(true)*1000),$sets['user']['name'],$_SERVER['REMOTE_ADDR']));
			$return['meta'] = $this->get_meta(array($return),array('category','author','tag'));
			obj::db()->sql('update search set lastupdate=0 where place="art" and item_id="'.query::$get['id'].'"',0);
			return $return;
		}
	}

	static function add_tag($tags,$id) {
		global $def;
		$info = obj::db()->sql('select area, tag from art where id='.$id,1);
		if ($info['area'] != $def['area'][1]) {
			$area = 'art_'.$info['area'];
			obj::transform('meta')->erase_tags(array_unique(array_filter(explode('|',$info['tag']))),$area);
		}
		$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse(str_replace('|',' ',$info['tag']).' '.$tags),$area);
		obj::db()->update('art','tag',$tags,$id);
	}

	function danbooru($section, $id)
	{
		Check::rights();

		if ($section == 'danbtag')
		{
			$dmd5 = obj::db()->sql('select md5 from art where id='.$id,2);

			$domdoc = new DOMDocument();
			$domdoc->load('http://danbooru.donmai.us/post/index.xml?tags=md5:'.$dmd5);

			if (isset($domdoc))
			{
				$elements = $domdoc->getElementsByTagName('post');
				foreach ($elements as $node)
				{
					$dtagstr[] = $node->getAttribute('tags');
				}

				$request = array('artist' => true,
								'series' => true,
								'character' => true);

				$dtags[] = explode(" ", $dtagstr[0]);
				$dtags[0] = $this->filter_external_tags($dtags[0], $request);
				$dtag = implode(", ", $dtags[0]);

				if(!empty($dtag))
				{
					$this->substract_tag('tagme', $id);
					foreach ($request as $key => $value)
					{
						if(!$value)
							$this->substract_tag($key . '_request', $id);
					}
					$this->add_tag($dtag, $id);
				}
			}
		}
		else if ($section == 'iqdb')
		{
			$mass = obj::db()->sql('select md5,extension from art where id='.$id,1);

			if (isset($mass))
			{
				include(ROOT_DIR.SL.'engine'.SL.'external'.SL.'simple_html_dom.php');

				$html = file_get_html('http://iqdb.hanyuu.net/?url=http://4otaku.ru/images/booru/full/'.$mass['md5'].'.'.$mass['extension']);
				$tables = $html->find(table);

				foreach($tables as $table)
				{
					if($table->children(4) != NULL)												/* First table doesn't have this field */
					{
						$a = $table->children(1)->children(0)->find('a');						/* Needed couse simple_html_dom syntax */
						if(isset($a) && (preg_match('/(?P<digit>\d+)% (?P<name>\w+)/', $table->children(4)->children(0), $matches)))
						{
							if($matches['digit'] >= 90)
							{
								$temp = $table->children(1)->children(0)->find('img');			/* Needed couse simple_html_dom syntax */

								if(strpos($table->children(2), 'Zerochan')) 					// Zerochan uses spaces instead of _
								{
									$dtags[] = str_replace(' ', '_', explode(", ", substr($temp[0]->alt,strpos($temp[0]->alt,'Tags: ')+6)));
								}
								else
								{
									$dtags[] = explode(" ", substr($temp[0]->alt,strpos($temp[0]->alt,'Tags: ')+6));
								}

								if ($dtags[sizeof($dtags)-1][0] !== "" || isset($dtags[sizeof($dtags)-1][1])) $diff_arr[sizeof($dtags[sizeof($dtags)-1])] = $dtags[sizeof($dtags)-1];										// My Little Magic

								$category = substr($temp[0]->alt,strpos($temp[0]->alt,'Rating: ')+8,1);
								if ($category == 'e') $explicit = true;
							}
						}
					}
				}

				if (isset($diff_arr))
				{
					krsort($diff_arr);															/* Может быть оно уже отсортировано iqdb */
					$request = array('artist' => true,
									'series' => true,
									'character' => true);
					$diff_arr = $this->filter_external_tags(reset($diff_arr), $request);
					$dtag = html_entity_decode(implode(", ", $diff_arr));

					if ($explicit)
					{
						$this->add_category('nsfw',$id);
						$this->substract_category('none',$id);
					}
					$this->substract_tag('tagme', $id);
					foreach ($request as $key => $value)
					{
						if(!$value)
							$this->substract_tag($key . '_request', $id);
					}
					$this->add_tag($dtag, $id);
				}
				else
				{
					/*echo "Sorry, can't found any tags >_<";								   	/* TODO: Придумать, как обработать неудачи масстега */
				}
			}

			$html->clear();
			unset($html);
			unset($tables);
			unset($temp);
		}
		else
		{
			echo 'Please, select option first.';
		}
	}

	function filter_external_tags($tags, &$request)
	{
		foreach ($tags as $key => &$tag)
		{
			if (strpos($tag, '_(artist)') > 0) 				{ $tag = '<artist>' . str_replace('_(artist)', '', $tag); $request['artist'] = false; }
			else if (strpos($tag, '_(copyright)') > 0) 		{ $tag = '<copyright>' . str_replace('_(copyright)', '', $tag); $request['series'] = false; }
			else if (strpos($tag, '_(character)') > 0)		{ $tag = '<character>' . $tag; $request['character'] = false; }

			if (strpos($tag, 'hard_translated') === (int)0) { }
			else if (strpos($tag, 'translated') === (int)0)	{ $tag = 'translation_request' . str_replace('translated', '', $tag); }

			if (strpos($tag, 'bad_id') === (int)0) 			{ $tag = str_replace('bad_id', '',$tag); }
		}

		return $tags;
	}

	static function substract_tag($tags,$id) {
		global $def;

		Check::rights();

		$info = obj::db()->sql('select area, tag from art where id='.$id,1);
		$old_tags = array_unique(array_filter(explode('|',$info['tag'])));
		if ($data['area'] != $def['area'][1]) {
			$area = 'art_'.$info['area'];
			obj::transform('meta')->erase_tags($old_tags,$area);
		}

		$tags = obj::transform('meta')->parse($tags);
		foreach ($tags as &$tag)
			$tag = obj::db()->sql('select alias from tag where name = "'.$tag.'" or locate("|'.$tag.'|",variants) or alias="'.$tag.'"',2);

		$tags = array_diff($old_tags,$tags);
		$tags = obj::transform('meta')->add_tags($tags,$area);
		obj::db()->update('art','tag',$tags,$id);
	}

	function add_category($category,$id) {
		$categories = explode('|',trim(obj::db()->sql('select category from art where id='.$id,2),'|'));
		$categories[] = $category;
		$category = obj::transform('meta')->category($categories);
		obj::db()->update('art','category',$category,$id);
	}

	function substract_category($category,$id) {

		Check::rights();

		$categories = explode('|',trim(obj::db()->sql('select category from art where id='.$id,2),'|'));
		$categories = array_diff($categories,array($category));
		if (empty($categories)) $categories = array('none');
		$category = obj::transform('meta')->category($categories);
		obj::db()->update('art','category',$category,$id);
	}

	function transfer($area,$id) {
		global $add_res;

		Check::rights();

		query::$post = array('id' => $id, 'sure' => 1, 'do' => array('art','transfer'), 'where' => $area);
		include_once('libs/input/common.php');
		$result = input__common::transfer(query::$post, false);
		if (!empty($result)) {
			$add_res['meta_error'] = $result;
		}
	}

	function is_dublicates() {
		$arts = explode(',', query::$get['data']);

		if (
			count($arts) < 2 ||
			!function_exists('puzzle_fill_cvec_from_file') ||
			!function_exists('puzzle_vector_normalized_distance')
		) {
			return false;
		}

		foreach ($arts as $key => $art) {
			$file = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.'large_'.$art.'.jpg';
			$arts[$key] = puzzle_fill_cvec_from_file($file);
		}

		foreach ($arts as $key => $art) {
			foreach ($arts as $key2 => $compare_art) {
				if (
					$key != $key2 &&
					puzzle_vector_normalized_distance($art, $compare_zart) > 0.3
				) {
					return false;
				}
			}
		}

		return count($arts);
	}
}
