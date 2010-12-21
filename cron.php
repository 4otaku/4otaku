<?

include_once 'inc.common.php';

$cron = new cron();

if ($key = key($_GET)) {
	$cron->$key();
	var_dump(memory_get_peak_usage(true));
} else {
	$time = time();
	$tasks = obj::db()->sql('select * from cron where time < '.$time);
	if (is_array($tasks)) foreach ($tasks as $task) if (method_exists($cron,$task['function'])) {
		$func = $task['function'];
		$cron->$func(); $nexttime = $time + $task['period'] - 15;
		obj::db()->update('cron','time',$nexttime,$task['id']);
	}
}
