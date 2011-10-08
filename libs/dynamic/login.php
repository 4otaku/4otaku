<?

class Dynamic_Login extends Engine
{
	const	INCORRECT_PASSWORD = 'Пароль введен неверно',
			NO_SUCH_USER = 'Такого пользователя не существует',
			PASSWORDS_DONT_MATCH = 'Введенные вами пароли не совпадают',
			PASSWORD_TOO_SHORT = 'Вы задали слишком короткий пароль',
			LOGIN_TOO_SHORT = 'Вы задали слишком короткий логин',
			USER_ALREADY_EXISTS = 'Такой пользователь уже существует',
			REGISTER_SUCCESS = 'Вы успешно зарегистрировались',
			LOGIN_SUCCESS = 'Вы успешно вошли';

	public function login () {
		$query = query::$post;

		$params = array($query['login']);
		$params[] = $this->encode_password($query['pass']);

		$cookie = Database::get_field('user', 'cookie', '`username` = ? and `password` = ?', $params);

		if (!empty($cookie)) {
			Cookie::set_cookie($cookie);
			exit(self::LOGIN_SUCCESS);
		}

		if (Database::get_field('user', 'cookie', '`username` = ?', $query['login'])) {
			exit(self::INCORRECT_PASSWORD);
		} else {
			exit(self::NO_SUCH_USER);
		}
	}

	public function register ($query) {
		if (count($query['pass']) != 2 || count(array_unique($query['pass'])) != 1) {
			exit(self::PASSWORDS_DONT_MATCH);
		}

		$password = current($query['pass']);

		if (mb_strlen($password) < Config::settings('min_length', 'password')) {
			exit(self::PASSWORD_TOO_SHORT);
		}

		if (mb_strlen($query['login']) < Config::settings('min_length', 'login')) {
			exit(self::LOGIN_TOO_SHORT);
		}

		if (Database::get_field('user', 'cookie', '`username` = ?', $query['login'])) {
			exit(self::USER_ALREADY_EXISTS);
		}

		if (empty($query['mail'])) {
			$query['mail'] = md5(rand());
		}

		$insert = array(
			'cookie' => Globals::$user_data['cookie'],
			'username' => $query['login'],
			'password' => $this->encode_password($password),
			'email'=> $query['mail'],
		);

		Database::insert('user', $insert);

		Meta::add('author', $query['login']);

		exit(self::REGISTER_SUCCESS);
	}

	public function remember_password ($query) {
		$this->redirect_address = '';
	}

	protected function encode_password ($password) {
		return Crypt::md5_salt($password, Config::main('salt'));
	}
}
