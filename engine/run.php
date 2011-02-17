<?
	
	include 'init.php';
	
	// Загрузим найденные плагины
	
	$plugin_files = glob(ROOT.SL.'plugins'.SL.'*.php');
	
	foreach ($plugin_files as $plugin_file) {		
		Plugins::load($plugin_file);
	}	
	
	// Контроллер формирует запрос общего вида в ядро.
	$query = new Query();
	$query->call->get_controller();
	$query->controller->call->build();
	$query->call->make_clean();
	
	// Ядро обрабатывает запрос
	$core = new Core($query);
	$data = $core->call->process();
	
	// Полученный результат подхватывает менеджер представлений
	$view = new Manager($data);
	$view->call->postprocess();
	
	// И выводит пользователю, используя подходящий шаблонизатор
	$view->call->output();
	
	
