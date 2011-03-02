<?
	
	include 'init.php';
	
	// Загрузим найденные плагины
	
	$plugin_files = glob(ROOT.SL.'plugins'.SL.'*.php');
	
	foreach ($plugin_files as $plugin_file) {		
		Plugins::load($plugin_file);
	}	
	
	// Определяем тип запроса, и выбираем контроллер.
	
	Objects::$controller = new Controller();
	
	// Унифицируем запрос с помощью контроллера
	
	Globals::$query = Objects::$controller->build();
	
	// Ядро обрабатывает запрос
	$core = new Core(Globals::$query);
	Globals::$data = $core->call->process();
	
	// Полученный результат подхватывает менеджер представлений
	$view = new Manager(Globals::$data);
	$view->call->postprocess();
	
	// И выводит пользователю, используя подходящий шаблонизатор
	$view->call->output();
	
	
