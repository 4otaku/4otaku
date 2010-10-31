<?

class dinamic__post
{

	function show_updates() { 	
		global $db; global $check; global $get;
		if ($check->num($get['id']))
			$return = $db->sql('select * from updates where post_id='.$get['id']);
		foreach ($return as &$update) $update['link'] = unserialize($update['link']);
		return $return;
	}
}
