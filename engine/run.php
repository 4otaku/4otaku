<?	
	include 'init.php';
	
	// Загружаем глобальные переменные
Cache::set('key', 1);
	$user_info = array(
		'cookie' => $_COOKIE[Config::main('cookie', 'name')],
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
	
	if (is_array($extended_files)) {
		foreach ($extended_files as $extended_file) {
			$class_name = basename($extended_file, '.md5');
			$class_file = search_lib($class_name);
			
			$md5 = file_get_contents($extended_file);
			
			if (empty($class_file) && md5_file($class_file) !== $md5) {
				Plugin_Loader::drop_cache();
				Plugin_Loader::make_cache();
				break;
			}
		}	
	}
	
	// Определяем тип запроса, и выбираем контроллер.
	
	Objects::$controller = new Controller();
	
	// Унифицируем запрос с помощью контроллера
	
	Globals::$query = Objects::$controller->query();
	
	// Теперь мы знаем, какой модуль сегодня выполняет. Подгрузим его конфиг
	$module_config_file = ENGINE.SL.'modules'.SL.Globals::$query['module'].SL.'settings.ini';
	Config::load($module_config_file);
	
	// И создадим субзапросы согласно этому конфигу
	$submodules = Config::settings('side');
	$subqueries = array();
	foreach ((array) $submodules as $submodule => $area) {
		$subqueries[$submodule] = Objects::$controller->subquery($submodule, $area, Globals::$query);
	}
	$subqueries = array_filter($subqueries);
	
	// Ядро обрабатывает запрос
	$core = new Core();
	Globals::$data = $core->process(Globals::$query);
	
	// И субзапросы
	foreach ($subqueries as $submodule => $query) {
		Globals::$sub_data[$submodule] = $core->process($query);
	}
	
	// Полученный результат проходит пост-обработку
	Globals::$data = Objects::$wrapper->postprocess(Globals::$data);
	
	// TODO убрать этот временный хак для удобной отладки
	Globals::$data['domain'] = 'http://beta.4otaku.ru';
	
	// И результаты субзапросов, после чего они присоединются к основному результату
	foreach (Globals::$sub_data as $submodule => $data) {
		Globals::$data['sub'][$submodule] = Objects::$sub_wrapper[$submodule]->postprocess($data);
	}	

	// И выводит пользователю, используя подходящий шаблонизатор
	$template = new Templater();	
	$template->output();
