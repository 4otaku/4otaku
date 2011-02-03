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
		$posts = obj::db()->sql('select id, link, title from post','id');
		$errors = obj::db()->sql('select * from gouf_links where status = "error"');
		
		if (is_array($errors) && is_array($posts)) {
			foreach ($errors as $error) {
				$posts[$error['post_id']]['error'][$error['id']] = $error;
				$posts[$error['post_id']]['errorlinks'][] = $error['link'];
			}
			
			foreach ($posts as $key => &$post) 
				if (is_array($post['error'])) {
					$post['link'] = unserialize($post['link']);
					foreach ($post['link'] as $key2 => $link) {
						$number = count($link['url']);
						$post['linkcount'] = $post['linkcount'] + $number;
						foreach ($post['error'] as $error) if (in_array($error['link'],$link['url'])) $number--;
						
						foreach ($link['url'] as $key3 => $url) {
							if (in_array($url,$post['errorlinks'])) { 
								$post['errors'][$key.'-'.$key2.'-'.$key3] = $link['name'].': <a href="'.
									$url.'" target="_blank">'.obj::transform('text')->cut_long_words($url,100).'</a> (~'.
									$link['size'].' '.$link['sizetype'].')'; 
								$post['severity'] = $post['severity'] + 100; 
							}
						}
						if ($number < 2) {
							foreach ($link['url'] as $key3 => $url) {
								if (!in_array($url,$post['errorlinks'])) { 
									$post['warnings'][$key.'-'.$key2.'-'.$key3] = $link['name'].': <a href="'.
										$url.'" target="_blank">'.obj::transform('text')->cut_long_words($url,100).'</a> (~'.
										$link['size'].' '.$link['sizetype'].')'; 
									$post['severity'] = $post['severity'] + 1; 
								}
							}
						}
						if ($number < 1) {
							foreach ($link['url'] as $key3 => $url) {
								if (in_array($url,$post['errorlinks'])) { 
									$post['critical_errors'][$key.'-'.$key2.'-'.$key3] = $link['name'].': <a href="'.
										$url.'" target="_blank">'.obj::transform('text')->cut_long_words($url,100).
										'</a> (~'.$link['size'].' '.$link['sizetype'].')'; 
									$post['severity'] = $post['severity'] + 9900; 
									unset($post['errors'][$key.'-'.$key2.'-'.$key3]);
								}
							}
						}
					}		
				}
			foreach ($posts as $key => $post) if (!$post['severity']) unset ($posts[$key]);

			uasort($posts, array($this, 'compare_severity'));
			
			$return['posts'] = $posts;
			$return['total'] = obj::db()->sql('select count(id) from gouf_links where status = "error"',2);
			return $return;
		}
		return array('total' => 0, 'display' => array('gouf'));
	}
	
	function compare_severity($a, $b) { 
		if ($a['severity']/$a['linkcount'] > $b['severity']/$b['linkcount']) return -1;
		else return 1;
	}	
}
