<? 
include_once('engine/engine.php');
class dinamic__art extends engine
{
	function slideshow() {
		global $db; global $get; global $check; global $def; global $sets; global $url;
		$types = array ('tag','author','category','mixed','date','pool');
		if (in_array($get['type'],$types) && $check->num($get['id'])) {
			$limit = ' order by sortdate desc limit '.($get['id'] - 1).', 5'; $area_prefix = 'area="'.$def['area'][0].'" and ';
			switch ($get['type']) {
				case "mixed": 
					include_once ('engine/engine.php'); 
					$engine = new engine();
					$url['area'] = 'main';  $area_prefix = '';
					$area = "(".$engine->mixed_make_sql($engine->mixed_parse(html_entity_decode(urldecode($get['area'])))).")"; 
					break;
				case "date": 
					$transform_text = new transform__text(); 
					$parts = explode('-',$get['area']);
					if (is_numeric($parts[0].$parts[1].$parts[2]) && count($parts) == 3)
						$area = '(pretty_date ="'.$transform_text->rumonth($parts[1]).' '.$parts[2].', '.$parts[0].'")';
					break;
				case "pool":
					$pool = $db->sql('select art from art_pool where id='.$get['area'],2);
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
			$return = $db->sql('select id, md5, extension, resized from art where ('.$area_prefix.$area.')'.$limit,'id');
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
					$translations = $db->sql('select art_id, data from art_translation where ('.$where.' and active = 1)','art_id');
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
		global $db; global $get; global $check; global $sets;
		if (is_numeric($get['id'])) {
			if ($check->lat($function = $get['sign']) && $check->rights()) $this->$function(urldecode($get['data']),$get['id']);
			$return = $db->sql('select id, category, tag, author, thumb from art where id='.$get['id'].' limit 1',1);
			$return['meta'] = $this->get_meta(array($return),array('category','author','tag'));
			return $return;
		}
	}
	
	function add_tag($tags,$id) {
		global $db; global $def; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();		
		$info = $db->sql('select area, tag from art where id='.$id,1);	
		if ($info['area'] != $def['area'][1]) {
			$area = 'art_'.$info['area'];
			$transform_meta->erase_tags(array_unique(array_filter(explode('|',$info['tag']))),$area);
		}
		$tags = $transform_meta->add_tags($transform_meta->parse(str_replace('|',' ',$info['tag']).' '.$tags),$area);
		$db->update('art','tag',$tags,$id);	
	}
	
	function danbooru($temp, $did)
	{
		global $db;
		$dmd5 = $db->sql('select md5 from art where id='.$did,2);
						
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

		$this->add_tag($dtag, $did);
	}	
	
	function substract_tag($tags,$id) {
		global $db; global $def; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();		
		
		$info = $db->sql('select area, tag from art where id='.$id,1);
		$old_tags = array_unique(array_filter(explode('|',$info['tag'])));
		if ($data['area'] != $def['area'][1]) {
			$area = 'art_'.$info['area'];
			$transform_meta->erase_tags($old_tags,$area);
		}
				
		$tags = $transform_meta->parse($tags);
		foreach ($tags as &$tag) 
			$tag = $db->sql('select alias from tag where name = "'.$tag.'" or locate("|'.$tag.'|",variants) or alias="'.$tag.'"',2);
		
		$tags = array_diff($old_tags,$tags);
		$tags = $transform_meta->add_tags($tags,$area);
		$db->update('art','tag',$tags,$id);		
	}
	
	function add_category($category,$id) {
		global $db; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();	
		$categories = explode('|',trim($db->sql('select category from art where id='.$id,2),'|'));
		$categories[] = $category;
		$category = $transform_meta->category($categories);
		$db->update('art','category',$category,$id);		
	}
	
	function substract_category($category,$id) {
		global $db; global $transform_meta;
		if (!$transform_meta) $transform_meta = new transform__meta();	
		$categories = explode('|',trim($db->sql('select category from art where id='.$id,2),'|'));
		$categories = array_diff($categories,array($category));
		if (empty($categories)) $categories = array('none');
		$category = $transform_meta->category($categories);
		$db->update('art','category',$category,$id);	
	}	
	
	function transfer($area,$id) {
		$post = array('id' => $id, 'sure' => 1, 'do' => array('art','transfer'), 'where' => $area);
		include_once('libs/input/common.php');
		input__common::transfer($post);
	}		
}
