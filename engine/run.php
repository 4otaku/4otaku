<?
	include 'init.php';
	
	// Загружаем глобальные переменные

	$cookie = isset($_COOKIE[Config::main('cookie', 'name')]) ?
		$_COOKIE[Config::main('cookie', 'name')] :
		false;

/*		
	$wap_profile = !empty($_SERVER['HTTP_PROFILE']) ? $_SERVER['HTTP_PROFILE'] 
		: !empty($_SERVER['HTTP_X_WAP_PROFILE']) ? $_SERVER['HTTP_X_WAP_PROFILE']
		: null;
*/	

	$user_info = array(
		'cookie' => $cookie,
		'agent' => $_SERVER['HTTP_USER_AGENT'],
		'accept' => $_SERVER['HTTP_ACCEPT'],
//		'mobile' => $wap_profile,
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
	
	// Узнаем имя модуля с которым нам предстоит работать
	
	$module = Core::get_module(Globals::$url, Globals::$vars);
	
	// И подгружаем его конфиг
	$module_config_file = ENGINE.SL.'modules'.SL.$module.SL.'settings.ini';
	Config::load($module_config_file);

	// Унифицируем запрос
	
	list($query_input, $query_output) = Core::make_query(Globals::$url, Globals::$vars);

	if (!empty($query_input)) {
		
		$worker = Core::get_worker_name($module, $query_input, 'input');
		$worker = new $worker();
		$worker->process($query_input);
		
		if (!empty($worker->redirect_address)) {
			Http::redirect($worker->redirect_address);
		}
	}
	
	$worker = Core::get_worker_name($module, $query_output, 'output');
	if (class_exists($worker)) {
		
		$data = new $worker();
		$data->process($query_output);
		
		// Если ожидается вывод данных, создадим субзапросы согласно подгруженному конфигу
		$side_modules = Config::settings('side');

		foreach ((array) $side_modules as $side_module => $area) {
			if ($data->valid_subquery($area, $query_output)) {

				$worker = $side_module.'_Output';
				$worker = new $worker();

				$side_query = $worker->make_subquery($query_output, $module);				
				$data->add_sub_data($worker->process($side_query), $side_module);
			}
		}

		$output = new Templater();
		$output->output($data);
	}
