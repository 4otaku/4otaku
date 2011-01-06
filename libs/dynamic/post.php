<?

class dynamic__post
{
	function show_updates() { 	
		global $check; 
		if ($check->num(query::$get['id']))
			$return = obj::db()->sql('select * from updates where post_id='.query::$get['id']);
		foreach ($return as &$update) $update['link'] = unserialize($update['link']);
		return $return;
	}
}
