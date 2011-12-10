<?

class transform__link
{
	public static function parse($links) {
		$alias = Database::get_vector('misc', array('data1', 'data2'), 'type = ?', 'site_alias');
		foreach ($links as $key => $link) {
			$type = 'alias';
			$parts = explode('&gt;',$link['link']);
			$links[$key]['url'] = undo_safety(end($parts));
			if (count($parts) == 2) {
				$links[$key]['alias'] = str_replace('&lt;','',$parts[0]);
				$type = 'search';
			}			
			$domain = parse_url($link['link'],PHP_URL_HOST);
			if (substr($domain,0,4) == 'www.') $domain = substr($domain,4);
			if ($alias[$domain]) $links[$key][$type] = $alias[$domain];
			else $links[$key][$type] = $domain;
		}
		return $links;
	}
}

// Новое имя. Заменить им старое, после завершения всех замен.
class Transform_Link extends transform__link {}
