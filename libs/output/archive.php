<? 
include_once(SITE_FDIR.SL.'engine'.SL.'engine.php');
class output__archive extends engine
{
	function __construct() {
		global $def;
		$area = '|'.implode('|',$def['type']).'|';
		$this->allowed_url[0][2] = $area;
	}
	public $allowed_url = array(
		array(1 => '|archive|', 2 => '', 3 => '|category|author|date|', 4 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('top_buttons'),
		'top' => array(),
		'sidebar' => array('comments'),
		'footer' => array()
	);
	public $wcase = array(
		'art' => array('арт','арта','артов'),
		'video' => array('видео','видео','видео'),
		'post' => array('запись','записи','записей')
	);
	
	function get_data() {
		global $url;
		if ($url[2]) $func = trim('archive_'.$url[3],'_');
		else $func = "archive_main";
		return $this->$func();
	}
	
	function archive_main() {		
		return array('display' => array('archive_main'));
	}	

	function archive() {		
		return $this->archive_date();
	}

	function archive_date() {		
		global $db;	global $url; global $transform_text;
		if (!$transform_text) $transform_text = new transform__text();
		if ($url[2] != 'art') { $title = ' title,'; $return['display'] = array('archive_date'); }
		else $return['display'] = array('archive_artdate');
		$items = $db->sql('select pretty_date,'.$title.' id, comment_count from '.$url[2].' where area="main" order by sortdate');
		$return['count'] = count($items).' '.$transform_text->wcase(count($items),$this->wcase[$url[2]][0],$this->wcase[$url[2]][1],$this->wcase[$url[2]][2]);
		if (is_array($items)) foreach ($items as $item) {
			$item['pretty_date'] = explode(' ',str_replace(',','',$item['pretty_date']));
			if ($url[2] != 'art') $return['archives'][$item['pretty_date'][2]][$item['pretty_date'][0]][] = $item;
			else $return['archives'][$item['pretty_date'][2]][$item['pretty_date'][0]][$item['pretty_date'][1]]++;
		}
		return $return; 
	}

	function archive_category() {	
		global $db; global $url; global $transform_text;
		if (!$transform_text) $transform_text = new transform__text();
		if ($url[2] != 'art') { $title = ' title,'; $return['display'] = array('archive_body'); }
		else $return['display'] = array('archive_artbody');
		$items = $db->sql('select category,'.$title.' id, comment_count from '.$url[2].' where area="main" order by sortdate');
		$return['count'] = count($items).' '.$transform_text->wcase(count($items),$this->wcase[$url[2]][0],$this->wcase[$url[2]][1],$this->wcase[$url[2]][2]);
		if (is_array($items)) foreach ($items as $item) {
			$categories = explode('|',trim($item['category'],'|'));
			foreach ($categories as $category)
				if ($url[2] != 'art') $return['archives'][$category][] = $item;
				else $return['archives'][$category]++;
		}
		$return['name'] = $db->sql('select alias,name from category','alias');
		return $return;	
	}

	function archive_author() {	
		global $db; global $url; global $transform_text;
		if (!$transform_text) $transform_text = new transform__text();
		if ($url[2] != 'art') { $title = ' title,'; $return['display'] = array('archive_body'); }
		else $return['display'] = array('archive_artbody');	
		$items = $db->sql('select author,'.$title.' id, comment_count from '.$url[2].' where area="main" order by sortdate');
		$return['count'] = count($items).' '.$transform_text->wcase(count($items),$this->wcase[$url[2]][0],$this->wcase[$url[2]][1],$this->wcase[$url[2]][2]);
		if (is_array($items)) foreach ($items as $item) {
			$authors = explode('|',trim($item['author'],'|'));
			foreach ($authors as $author)
				if ($url[2] != 'art') $return['archives'][$author][] = $item;
				else $return['archives'][$author]++;
		}
		$return['name'] = $db->sql('select alias,name from author','alias');
		return $return;
	}
	
}
