<? 
	
function __autoload($class_name) {
    include_once 'libs/' . str_replace('__','/',$class_name) . '.php';
}

mb_internal_encoding('UTF-8');
include_once 'engine/config.php';
$db = new mysql();
$cron = new cron();

if ($key = key($_GET)) {
	$cron->$key();
	var_dump(memory_get_peak_usage(true));	
}
else {
	$time = time();
	$tasks = $db->sql('select * from cron where time < '.$time);
	if (is_array($tasks)) foreach ($tasks as $task) if (method_exists($cron,$task['function'])) {
		$func = $task['function']; 
		$cron->$func(); $nexttime = $time + $task['period'] - 15;
		$db->update('cron','time',$nexttime,$task['id']);
	}
}
