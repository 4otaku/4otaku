<?

class Dynamic_Login extends Dynamic_Abstract
{
	const	INCORRECT_PASSWORD = 'Пароль введен неверно',
			NO_SUCH_USER = 'Такого пользователя не существует',
			PASSWORDS_DONT_MATCH = 'Введенные вами пароли не совпадают',
			PASSWORD_TOO_SHORT = 'Вы задали слишком короткий пароль',
			LOGIN_TOO_SHORT = 'Вы задали слишком короткий логин',
			USER_ALREADY_EXISTS = 'Такой пользователь уже существует',
			EMAIL_ALREADY_EXISTS = 'Пользователь с таким е-мейлом уже существует',
			EMAIL_INCORRECT = 'Е-мейл введен неверно',
			REGISTER_SUCCESS = 'Вы успешно зарегистрировались',
			LOGIN_SUCCESS = 'Вы успешно вошли',
			CHANGE_SUCCESS = 'Вы успешно сменили пароль';

	public function login () {
		$query = query::$post;

		$params = array($query['login']);
		$params[] = $this->encode_password($query['pass']);

		$cookie = Database::get_field('user', 'cookie',
			'`login` = ? and `pass` = ?', $params);

		if ($cookie === '') {
			Database::update('user', array('cookie' => query::$cookie),
				'`login` = ? and `pass` = ?', $params);
			$this->reply(self::LOGIN_SUCCESS);
		}

		if (!empty($cookie)) {
			$this->set_cookie($cookie);
			$this->reply(self::LOGIN_SUCCESS);
		}

		$test = Database::get_field('user', 'cookie',
			'`login` = ?', $query['login']);

		if ($test !== false) {
			$this->reply(self::INCORRECT_PASSWORD, false);
		} else {
			$this->reply(self::NO_SUCH_USER, false);
		}
	}

	public function register () {
		$query = query::$post;

		if ($query['pass'] != $query['pass2']) {
			$this->reply(self::PASSWORDS_DONT_MATCH, false);
		}

		$password = $query['pass'];

		if (mb_strlen($password) < 6) {
			$this->reply(self::PASSWORD_TOO_SHORT, false);
		}

		if (mb_strlen($query['login']) < 6) {
			$this->reply(self::LOGIN_TOO_SHORT, false);
		}

		if (Database::get_count('user', '`login` = ?', $query['login'])) {
			$this->reply(self::USER_ALREADY_EXISTS, false);
		}

		if (
			!empty($query['email']) &&
			!Check::email($query['email'])
		) {
			$this->reply(self::EMAIL_INCORRECT, false);
		}

		if (
			!empty($query['email']) &&
			Database::get_count('user', '`email` = ?', $query['email'])
		) {
			$this->reply(self::EMAIL_ALREADY_EXISTS, false);
		}

		$insert = array(
			'cookie' => query::$cookie,
			'login' => $query['login'],
			'pass' => $this->encode_password($password),
			'email'=> $query['email'],
		);

		Database::insert('user', $insert);

		$this->reply(self::REGISTER_SUCCESS);
	}

	public function change_pass () {
		$query = query::$post;

		$params = array(query::$cookie);
		$params[] = $this->encode_password($query['old_pass']);

		if (
			!Database::get_count('user',
				'`cookie` = ? and `pass` = ?', $params)
		) {
			Database::debug();
			$this->reply(self::INCORRECT_PASSWORD, false);
		}

		if ($query['pass'] != $query['pass2']) {
			$this->reply(self::PASSWORDS_DONT_MATCH, false);
		}

		$password = $query['pass'];

		if (mb_strlen($password) < 6) {
			$this->reply(self::PASSWORD_TOO_SHORT, false);
		}

		$update = array(
			'pass' => $this->encode_password($password)
		);

		Database::update('user', $update,
			'`cookie` = ? and `pass` = ?', $params);

		$this->reply(self::CHANGE_SUCCESS);
	}

	protected function encode_password ($password) {
		return md5($password);
	}

	protected function set_cookie ($cookie) {

		$cookie_domain = def::site('domain') != 'localhost' ?
			def::site('domain') :
			'';
		$cookie_domain .= SITE_DIR;

		setcookie('settings', $cookie,
			time()+3600*24*60, '/', $cookie_domain);
	}
}

class dynamic__login extends Dynamic_Login {}
