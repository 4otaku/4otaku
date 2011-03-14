<?
	
	include 'init.php';
	
	// Загружаем глобальные переменные
	
	$user_info = array(
		'cookie' => $_COOKIE[Config::main('cookie', 'Name')],
		'agent' => $_SERVER['HTTP_USER_AGENT'],
		'accept' => $_SERVER['HTTP_ACCEPT'],
		'mobile' => !empty($_SERVER['HTTP_PROFILE']) ? $_SERVER['HTTP_PROFILE'] 
			: !empty($_SERVER['HTTP_X_WAP_PROFILE']) ? $_SERVER['HTTP_X_WAP_PROFILE'] 
			: null,
		'ip' => $_SERVER['REMOTE_ADDR'],
	);
	
	Globals::get_vars($_GET);
	Globals::get_vars($_POST);	
	Globals::get_url($_SERVER['REQUEST_URI']);
	Globals::get_user($user_info);	
	
	// Загружаем найденные плагины
	
	$plugin_files = glob(ROOT.SL.'plugins'.SL.'*.php');
	
	foreach ($plugin_files as $plugin_file) {		
		Plugins::load($plugin_file);
	}	
	
	// Определяем тип запроса, и выбираем контроллер.
	
	Objects::$controller = new Controller();
	
	// Унифицируем запрос с помощью контроллера
	
	Globals::$query = Objects::$controller->build();
	
	// Ядро обрабатывает запрос
	$core = new Core();
	Globals::$data = $core->call->process(Globals::$query);
	
	// Полученный результат подхватывает менеджер представлений
	$view = new Manager(Globals::$data);
	$view->call->postprocess();
	
	// И выводит пользователю, используя подходящий шаблонизатор
	$view->call->output();
	
	
