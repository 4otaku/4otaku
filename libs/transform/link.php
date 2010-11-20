<?

class transform__link
{
	function parse($links) {
		global $db;
		$alias = $db->sql('select data1, data2 from misc where type="site_alias"','data1');
		foreach ($links as &$link) {
			$type = 'alias';
			$parts = explode('&gt;',$link['link']);
			$link['url'] = undo_safety(end($parts));
			if (count($parts) == 2) {
				$link['alias'] = str_replace('&lt;','',$parts[0]);
				$type = 'search';
			}			
			$domain = parse_url($link['link'],PHP_URL_HOST);
			if (substr($domain,0,4) == 'www.') $domain = substr($domain,4);
			if ($alias[$domain]) $link[$type] = $alias[$domain];
			else $link[$type] = $domain;
		}
		return $links;
	}
	
	function similar($links){
		$newlinks = array();		
		foreach ($links as $link) {
			$found = false;
			foreach ($newlinks as &$newlink)
				if ($newlink['name'] == $link['name'] && $newlink['size'] == $link['size'] && $newlink['sizetype'] == $link['sizetype']) {
					$newlink['url'][] = $link['url']; $newlink['alias'][] = $link['alias']; $found = true;
				}
			if (!$found) {
				$link['url'] = array($link['url']);
				$link['alias'] = array($link['alias']);
				$newlinks[] = $link;
			}
		}
		return $newlinks;
	}	
}
