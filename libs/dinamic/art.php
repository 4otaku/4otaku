<? 
include_once('engine/engine.php');
class dinamic__art extends engine
{
	function slideshow() {
		global $get; global $check; global $def; global $sets; global $url;
		$types = array ('tag','author','category','mixed','date','pool');
		if (in_array($get['type'],$types) && $check->num($get['id'])) {
			$limit = ' order by sortdate desc limit '.($get['id'] - 1).', 5'; $area_prefix = 'area="'.$def['area'][0].'" and ';
			switch ($get['type']) {
				case "mixed": 
					$url['area'] = 'main';  $area_prefix = '';
					$area = "(".engine::mixed_make_sql(engine::mixed_parse(html_entity_decode(urldecode($get['area'])))).")"; 
					break;
				case "date": 
					$parts = explode('-',$get['area']);
					if (is_numeric($parts[0].$parts[1].$parts[2]) && count($parts) == 3)
						$area = '(pretty_date ="'.obj::transform('text')->rumonth($parts[1]).' '.$parts[2].', '.$parts[0].'")';
					break;
				case "pool":
					$pool = obj::db()->sql('select art from art_pool where id='.$get['area'],2);
					$pool = array_slice(array_reverse(array_filter(array_unique(explode('|',$pool)))),$get['id'] - 1,5);
					$area = "(id=".implode(' or id=',$pool).")";
					$limit = ''; $area_prefix = '';
					break;
				default:
					$area = '(locate("|'.urldecode($get['area']).'|",art.'.$get['type'].'))';
			}
			if (!$sets['show']['nsfw'] && $get['type'] != 'pool')
				$area .= ' and !locate("|nsfw|",art.category)';
			elseif ($sets['show']['nsfw'] && $get['type'] != 'pool') {
				if (!$sets['show']['yaoi']) $area .= ' and !(locate("|yaoi|",art.tag) and locate("|nsfw|",art.category))';
				if (!$sets['show']['furry']) $area .= ' and !(locate("|furry|",art.tag) and locate("|nsfw|",art.category))';
				if (!$sets['show']['guro']) $area .= ' and !(locate("|guro|",art.tag) and locate("|nsfw|",art.category))';
			}
			$return = obj::db()->sql('select id, md5, extension, resized from art where ('.$area_prefix.$area.')'.$limit,'id');
			if ($get['type'] == 'pool') {
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
	
	function masstag() {
		global $get; global $check; global $sets;
		if (is_numeric($get['id'])) {
			if ($check->lat($function = $get['sign']) && $check->rights()) $this->$function(urldecode($get['data']),$get['id']);
			$return = obj::db()->sql('select * from art where id='.$get['id'].' limit 1',1);
			obj::db()->insert('versions',array('art',$get['id'],base64_encode(serialize($return)),ceil(microtime(true)*1000),$sets['user']['name'],$_SERVER['REMOTE_ADDR']));
			$return['meta'] = $this->get_meta(array($return),array('category','author','tag'));
			obj::db()->sql('update search set lastupdate=0 where place="art" and item_id="'.$get['id'].'"',0);
			return $return;
		}
	}
	
	function add_tag($tags,$id) {
		global $def;		
		$info = obj::db()->sql('select area, tag from art where id='.$id,1);	
		if ($info['area'] != $def['area'][1]) {
			$area = 'art_'.$info['area'];
			obj::transform('meta')->erase_tags(array_unique(array_filter(explode('|',$info['tag']))),$area);
		}
		$tags = obj::transform('meta')->add_tags(obj::transform('meta')->parse(str_replace('|',' ',$info['tag']).' '.$tags),$area);
		obj::db()->update('art','tag',$tags,$id);	
	}
	
	function danbooru($section, $did)
	{
		if ($section == 'danbtag')
		{
			$dmd5 = obj::db()->sql('select md5 from art where id='.$did,2);
		
			$domdoc = new DOMDocument();	
			$domdoc->load('http://danbooru.donmai.us/post/index.xml?tags=md5:'.$dmd5);
			
			$elements = $domdoc->getElementsByTagName('post');
			foreach ($elements as $node) 
			{
				$dtagstr[] = $node->getAttribute('tags');
			}
			
			$dtags[] = explode(" ", $dtagstr[0]);
			
			foreach ($dtags[0] as $key => &$tag)
			{
				if (strpos($tag, '_(artist)') > 0) 				{ $tag = '<artist>' . str_replace('_(artist)', '', $tag); }	
				else if (strpos($tag, '_(copyright)') > 0) 		{ $tag = '<copyright>' . str_replace('_(copyright)', '', $tag); }	
				else if (strpos($tag, '_(character)') > 0)		{ $tag = '<character>' . $tag; }	
				
				if (strpos($tag, 'hard_translated') === (int)0) { }	
				else if (strpos($tag, 'translated') === (int)0)	{ $tag = 'translation_request' . str_replace('translated', '', $tag); }	
	
				if (strpos($tag, 'bad_id') === (int)0) 			{ $tag = str_replace('bad_id', '',$tag); }
			}
			
			$dtag = implode(", ", $dtags[0]); 
			
			$this->add_tag($dtag, $did);														/* Another function call */
		}
		else if ($section == 'iqdb')
		{
			$mass = obj::db()->sql('select md5,extension from art where id='.$did,1);
		
			if (isset($mass)) 
			{
				include(ROOT_DIR.SL.'libs'.SL.'simple_html_dom.php');
				
				$html = file_get_html('http://iqdb.hanyuu.net/?url=http://4otaku.ru/images/booru/full/'.$mass['md5'].'.'.$mass['extension']);
				$tables= $html->find(table);

				foreach($tables as $table)
				{	
					if($table->children(4) != NULL)												/* First table doesn't have this field */
					{
						$a = $table->children(1)->children(0)->find('a');						/* Needed couse simple_html_dom syntax */
						if(isset($a) && (strpos($a[0]->href,'http://danbooru.donmai.us/post/show/') === (int)0) && (preg_match('/(?P<digit>\d+)% (?P<name>\w+)/', $table->children(4)->children(0), $matches)))
						{
							if($matches['digit'] >= 95)
							{
								$temp = $table->children(1)->children(0)->find('img');			/* Needed couse simple_html_dom syntax */
								$dtags[] = explode(" ", substr($temp[0]->alt,strpos($temp[0]->alt,'Tags: ')+6));
								
								foreach ($dtags[0] as $key => &$tag)
								{
									if (strpos($tag, '_(artist)') > 0) 				{ $tag = '<artist>' . str_replace('_(artist)', '', $tag); }	
									else if (strpos($tag, '_(copyright)') > 0) 		{ $tag = '<copyright>' . str_replace('_(copyright)', '', $tag); }	
									else if (strpos($tag, '_(character)') > 0)		{ $tag = '<character>' . $tag; }	
									
									if (strpos($tag, 'hard_translated') === (int)0) { }	
									else if (strpos($tag, 'translated') === (int)0)	{ $tag = 'translation_request' . str_replace('translated', '', $tag); }	
						
									if (strpos($tag, 'bad_id') === (int)0) 			{ $tag = str_replace('bad_id', '',$tag); }
								}
								
								$dtag = implode(", ", $dtags[0]);
							
								$this->add_tag($dtag, $did);									/* Another function call */
							}
						}
					}
				}
			}
		}
		else
		{
			echo 'Please, select option first.';
		}
	}	
	
	function substract_tag($tags,$id) {
		global $def;	
		
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
		$categories = explode('|',trim(obj::db()->sql('select category from art where id='.$id,2),'|'));
		$categories = array_diff($categories,array($category));
		if (empty($categories)) $categories = array('none');
		$category = obj::transform('meta')->category($categories);
		obj::db()->update('art','category',$category,$id);	
	}	
	
	function transfer($area,$id) {
		$post = array('id' => $id, 'sure' => 1, 'do' => array('art','transfer'), 'where' => $area);
		include_once('libs/input/common.php');
		input__common::transfer($post);
	}		
}
