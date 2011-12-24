<?

class Side_Top
{
	function __construct() {
		global $sets; global $cookie; global $add_res;
		if (!empty($sets['add_res']['text'])) {
			$add_res = $sets['add_res'];
			if (empty($cookie)) $cookie = new dynamic__cookie();
			$cookie->inner_set('add_res.text','');
			$cookie->inner_set('add_res.error',false);
		}
	}

	function add_bar() {
		global $url; global $def;
		if ($url[2] == 'pool' && $url[1] == $def['type'][2]) {
			if (!is_numeric($url[3])) return array('type' => $url[2], 'name' => $url[2]);
			$data = obj::db()->sql('select name, password from art_pool where id='.$url[3],1);
			return array('type' => $url[1], 'pool' => $data['name'], 'pass' => $data['password'], 'name' => $url[1]);
		}
		if ($url[3] == 'thread') return array('type' => $url[1], 'name' => $url[3], 'info' => $url[4]);
		if ($url[1] == 'board') return array('type' => $url[1], 'info' => $url[2], 'name' => $url[1]);
		return array('type' => $url[1], 'name' => $url[1]);
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

	function board_list() {
		return obj::db()->sql('select id, alias, name from category where locate("|board|",area) order by id','id');
	}

}
