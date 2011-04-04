<?
	
class Cookie
{	
	public static function get_preferences($cookie) {
		if (empty($cookie)) {
			self::create_cookie();
			return array();			
		}
		
		$cookie = Crypt::md5_salt($cookie, Config::main('cookie', 'salt'));
		
		$data = Objects::db()->get_vector('cookie', array('section', 'data'), '`cookie` = ?', $cookie);
		
		if (empty($data)) {
			return array();
		}
		
		foreach ($data as & $section) {
			$section = Crypt::unpack($section);
		}
		
		return $data;
	}
	
	protected static function create_cookie() {
		$cookie = Crypt::md5_salt(
			time() . Globals::$user_data['ip'], 
			strrev(Config::main('cookie', 'salt'))
		);
		
		$cookie_domain = Config::main('cookie', 'domain') ?
			Config::main('cookie', 'domain') :
			$_SERVER['SERVER_NAME'];   
		
		if ($cookie_domain == 'localhost') {
			$cookie_domain = '';
		}
		
		setcookie(
			Config::main('cookie', 'name'), 
			$cookie, 
			Objects::transform('string')->parse_time(Config::main('cookie', 'lifespan')), 
			'/', 
			$cookie_domain
		);
		
		Globals::$user_data['cookie'] = $cookie;
	}
}
