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
	
	// Проверяем кеш расширенных плагинами библиотек
	
	$extended_files = glob(ROOT.SL.'cache'.SL.'extended'.SL.'*.md5');
	
	foreach ((array) $extended_files as $extended_file) {
		$class_name = basename($extended_file, '.md5');
		$class_file = search_lib($class_name);
		
		$md5 = file_get_contents($extended_file);
		
		if (empty($class_file) && md5_file($class_file) !== $md5) {
			Plugin_Loader::drop_cache();
			Plugin_Loader::make_cache();
			break;
		}
	}	
	
	// Определяем тип запроса, и выбираем контроллер.
	
	Objects::$controller = new Controller();
	
	// Унифицируем запрос с помощью контроллера
	
	Globals::$query = Objects::$controller->build();
	
	// Ядро обрабатывает запрос
	$core = new Core();
	Globals::$data = $core->process(Globals::$query);
	
	// Полученный результат подхватывает менеджер представлений
	$view = new Manager(Globals::$data);
	$view->postprocess();
	
	// И выводит пользователю, используя подходящий шаблонизатор
	$view->output();
	
	
