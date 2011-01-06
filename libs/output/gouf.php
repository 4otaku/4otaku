<? 

class output__gouf extends engine
{
	public $allowed_url = array(
		array(1 => '|gouf|', 2 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),	
		'header' => array('top_buttons'),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);
	
	function get_data() {
		$return['display'] = array('gouf');		
		query::$posts = obj::db()->sql('select id, link, title from post','id');
		$errors = obj::db()->sql('select * from gouf_links where status = "error"');
		
		if (is_array($errors) && is_array(query::$posts)) {
			foreach ($errors as $error) {
				query::$posts[$error['post_id']]['error'][$error['id']] = $error;
				query::$posts[$error['post_id']]['errorlinks'][] = $error['link'];
			}
			
			foreach (query::$posts as $key => &query::$post) 
				if (is_array(query::$post['error'])) {
					query::$post['link'] = unserialize(query::$post['link']);
					foreach (query::$post['link'] as $key2 => $link) {
						$number = count($link['url']);
						query::$post['linkcount'] = query::$post['linkcount'] + $number;
						foreach (query::$post['error'] as $error) if (in_array($error['link'],$link['url'])) $number--;
						foreach ($link['url'] as $key3 => $url) if (in_array($url,query::$post['errorlinks'])) { query::$post['errors'][$key.'-'.$key2.'-'.$key3] = $link['name'].': <a href="'.$url.'" target="_blank">'.$url.'</a> (~'.$link['size'].' '.$link['sizetype'].')'; query::$post['severity'] = query::$post['severity'] + 100; }
						if ($number < 2) foreach ($link['url'] as $key3 => $url) if (!in_array($url,query::$post['errorlinks'])) { query::$post['warnings'][$key.'-'.$key2.'-'.$key3] = $link['name'].': <a href="'.$url.'" target="_blank">'.$url.'</a> (~'.$link['size'].' '.$link['sizetype'].')'; query::$post['severity'] = query::$post['severity'] + 1; }
						if ($number < 1) foreach ($link['url'] as $key3 => $url) if (in_array($url,query::$post['errorlinks'])) { query::$post['critical_errors'][$key.'-'.$key2.'-'.$key3] = $link['name'].': <a href="'.$url.'" target="_blank">'.$url.'</a> (~'.$link['size'].' '.$link['sizetype'].')'; query::$post['severity'] = query::$post['severity'] + 9900; unset(query::$post['errors'][$key.'-'.$key2.'-'.$key3]);}
					}		
				}
			foreach (query::$posts as $key => query::$post) if (!query::$post['severity']) unset (query::$posts[$key]);

			uasort(query::$posts, array($this, 'compare_severity'));
			
			$return['posts'] = query::$posts;
			$return['total'] = obj::db()->sql('select count(id) from gouf_links where status = "error"',2);
			return $return;
		}
		return array('total' => 0);
	}
	
	function compare_severity($a, $b) { 
		if ($a['severity']/$a['linkcount'] > $b['severity']/$b['linkcount']) return -1;
		else return 1;
	}	
}
