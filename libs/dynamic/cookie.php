<?

class dynamic__cookie
{
	function set() { 	
		global $check; 
		$fields = explode(',',query::$get['field']);
		if (is_array($fields) && $check->hash($_COOKIE['settings']))
			foreach ($fields as $field)
				if (preg_match('/^[A-Za-z0-9_.]+$/',$field)) {
					if (substr(query::$get['val'],0,1) == '{' && substr(query::$get['val'],-1) == '}') switch (query::$get['val']) {
						case '{time}': $value = microtime(true)*1000; break;
						case '{false}': $value = 0; break;
						case '{true}': $value = 1; break;								
						default: $value = trim(query::$get['val'],'{}');
					}
					else $value = query::$get['val'];
					$this->inner_set($field,$value);
				}
	}
	
	function inner_set($field,$value,$allow_empty = true) {
		$parts = explode('.',$field);
		$settings = obj::db()->sql('select data from settings where cookie = "'.$_COOKIE['settings'].'"',2);
		$settings = unserialize(base64_decode($settings));
		if ($allow_empty || !empty($settings)) {
			$settings[$parts[0]][$parts[1]] = $value;
			obj::db()->update('settings',array('data'),array(base64_encode(serialize($settings))),$_COOKIE['settings'],'cookie');
		}
	}
	
	function get() {
		global $check;
		if ($check->hash($_COOKIE['settings']))
			return $_COOKIE['settings'];
	}
}
