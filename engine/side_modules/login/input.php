<?

class Login_Input extends Input implements Plugins
{
	const	INCORRECT_PASSWORD = 'profile_incorrect_password',
			NO_SUCH_USER = 'profile_no_such_user',
			PASSWORDS_DONT_MATCH = 'profile_passwords_dont_match',
			PASSWORD_TOO_SHORT = 'profile_password_too_short',
			LOGIN_TOO_SHORT = 'profile_no_such_user',
			USER_ALREADY_EXISTS = 'profile_no_such_user';
	
	public function login ($query) {
		$params = array($query['login']);
		$params[] = $this->encode_password($query['pass']);

		$cookie = Database::get_field('user', 'cookie', '`username` = ? and `password` = ?', $params);
		
		if (!empty($cookie)) {
			Cookie::set_cookie($cookie);
			$this->redirect_address = '';
			return;
		} 
		
		if (Database::get_field('user', 'cookie', '`username` = ?', $query['login'])) {
			$this->status_message = self::INCORRECT_PASSWORD;
		} else {
			$this->status_message = self::NO_SUCH_USER;
		}
	}
	
	public function register ($query) {
		if (count($query['pass']) != 2 || count(array_unique($query['pass'])) != 1) {
			$this->status_message = self::PASSWORDS_DONT_MATCH;
			return;
		}
		
		$password = current($query['pass']);
		
		if (mb_strlen($password) < Config::settings('min_length', 'password')) {
			$this->status_message = self::PASSWORD_TOO_SHORT;
			return;
		}
		
		if (mb_strlen($query['login']) < Config::settings('min_length', 'login')) {
			$this->status_message = self::LOGIN_TOO_SHORT;
			return;	
		}
		
		if (Database::get_field('user', 'cookie', '`username` = ?', $query['login'])) {
			$this->status_message = self::USER_ALREADY_EXISTS;
			return;
		}
		
		$insert = array(
			'cookie' => Globals::$user_data['cookie'],
			'username' => $query['login'],
			'password' => $this->encode_password($password),
			'email'=> $query['mail'],
		);
		
		Database::insert('user', $insert);
		$this->redirect_address = '';
	}
	
	public function remember_password ($query) {
		$this->redirect_address = '';
	}
	
	protected function encode_password ($password) {
		return Crypt::md5_salt($password, Config::main('salt'));
	}
}
