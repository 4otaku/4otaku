<?
	
class Cookie
{	
	public static function get_preferences($cookie) {
		if (empty($cookie)) {
			self::create_cookie();
			return array();			
		}
		
		self::set_cookie($cookie);
		
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
	
	public static function save_preference($cookie, $section, $key, $value) {
		
		$condition = '`cookie` = ? and `section` = ?';
		
		$data = Objects::db()->get_field('cookie', 'data', $condition, array($cookie, $section));
		
		if (!empty($data)) {
			$data = Crypt::unpack($data);
		} else {
			$data = array();
		}
		
		$parts = explode('.', $key);
		$link = & $data;
		while ($part = array_shift($parts)) {
			$link = & $link[$part];
		}
		
		$link = $value;
		
		$insert = array('cookie' => $cookie, 'section' => $section, 'data' => Crypt::pack($data));
		
		Objects::db()->replace('cookie', $insert, array('cookie', 'section'));
	}
	
	protected static function set_cookie ($cookie) {		
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
	}
	
	protected static function create_cookie () {
		$cookie = Crypt::md5_salt(
			time() . Globals::$user_data['ip'], 
			strrev(Config::main('cookie', 'salt'))
		);	
		
		self::set_cookie($cookie);
		
		Globals::$user_data['cookie'] = $cookie;	
	}
}
