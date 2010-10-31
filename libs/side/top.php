<?

class side__top
{
	
	function add_bar() {
		global $url; global $def; global $db;
		if ($url[2] == 'pool' && $url[1] == $def['type'][2]) {
			if (!is_numeric($url[3])) return array('type' => $url[2]);
			$data = $db->sql('select name, password from art_pool where id='.$url[3],1);
			return array('type' => $url[1], 'pool' => $data['name'], 'pass' => $data['password']);	
		}
		return array('type' => $url[1]);
	}	

	function title() {
		global $url;
		
		if ($url['area'] == 'main' || (!$url[2] && !$url['area'])) $return['link'] = $url[1];
		elseif ($url['area']) $return['link'] = $url[1].'/'.$url['area'];
		else $return['link'] = $url[1].'/'.$url[2];
		
		if ($url['area']) $return['lang'] = $url[1].'_'.$url['area'];
		else $return['lang'] = rtrim($url[1].'_'.$url[2],'_');
		
		return $return;
	}	

}
